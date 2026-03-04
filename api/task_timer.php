<?php
/**
 * TaskFlow - API Timer Task
 * Gestisce avvio, pausa, stop e stato del timer per le task
 */

require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Verifica autenticazione
requireAuth();

$userId = $_SESSION['user_id'] ?? '';
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'start':
        startTimer($userId);
        break;
        
    case 'pause':
        pauseTimer($userId);
        break;
        
    case 'resume':
        resumeTimer($userId);
        break;
        
    case 'stop':
        stopTimer($userId);
        break;
        
    case 'status':
        getTimerStatus($userId);
        break;
        
    case 'task_times':
        getTaskTimes($userId);
        break;
        
    default:
        jsonResponse(false, null, 'Azione non valida');
}

/**
 * Avvia un nuovo timer per una task
 */
function startTimer(string $userId): void {
    global $pdo;
    
    $taskId = $_POST['task_id'] ?? '';
    
    if (empty($taskId)) {
        jsonResponse(false, null, 'Task ID richiesto');
        return;
    }
    
    // Verifica che la task esista
    $stmt = $pdo->prepare("SELECT id FROM task WHERE id = ?");
    $stmt->execute([$taskId]);
    if (!$stmt->fetch()) {
        jsonResponse(false, null, 'Task non trovata');
        return;
    }
    
    // Ferma eventuali timer attivi precedenti per questo utente
    $stmt = $pdo->prepare("
        UPDATE task_timer_sessions 
        SET status = 'completed', stopped_at = NOW() 
        WHERE user_id = ? AND status = 'running'
    ");
    $stmt->execute([$userId]);
    
    // Crea nuova sessione timer
    $sessionId = generateEntityId('tmt');
    $stmt = $pdo->prepare("
        INSERT INTO task_timer_sessions (id, task_id, user_id, started_at, status) 
        VALUES (?, ?, ?, NOW(), 'running')
    ");
    
    if ($stmt->execute([$sessionId, $taskId, $userId])) {
        jsonResponse(true, [
            'session_id' => $sessionId,
            'task_id' => $taskId,
            'started_at' => date('Y-m-d H:i:s'),
            'status' => 'running'
        ], 'Timer avviato');
    } else {
        jsonResponse(false, null, 'Errore avvio timer');
    }
}

/**
 * Mette in pausa il timer
 */
function pauseTimer(string $userId): void {
    global $pdo;
    
    $sessionId = $_POST['session_id'] ?? '';
    
    if (empty($sessionId)) {
        jsonResponse(false, null, 'Session ID richiesto');
        return;
    }
    
    // Recupera sessione e calcola tempo trascorso
    $stmt = $pdo->prepare("
        SELECT id, started_at, total_seconds 
        FROM task_timer_sessions 
        WHERE id = ? AND user_id = ? AND status = 'running'
    ");
    $stmt->execute([$sessionId, $userId]);
    $session = $stmt->fetch();
    
    if (!$session) {
        jsonResponse(false, null, 'Sessione non trovata o non attiva');
        return;
    }
    
    // Calcola secondi trascorsi
    $startedAt = strtotime($session['started_at']);
    $elapsedSeconds = time() - $startedAt;
    $totalSeconds = $session['total_seconds'] + $elapsedSeconds;
    
    // Aggiorna sessione
    $stmt = $pdo->prepare("
        UPDATE task_timer_sessions 
        SET status = 'paused', paused_at = NOW(), total_seconds = ? 
        WHERE id = ?
    ");
    
    if ($stmt->execute([$totalSeconds, $sessionId])) {
        jsonResponse(true, [
            'session_id' => $sessionId,
            'status' => 'paused',
            'total_seconds' => $totalSeconds,
            'formatted_time' => formatDuration($totalSeconds)
        ], 'Timer in pausa');
    } else {
        jsonResponse(false, null, 'Errore pausa timer');
    }
}

/**
 * Riprende il timer dalla pausa
 */
function resumeTimer(string $userId): void {
    global $pdo;
    
    $sessionId = $_POST['session_id'] ?? '';
    
    if (empty($sessionId)) {
        jsonResponse(false, null, 'Session ID richiesto');
        return;
    }
    
    $stmt = $pdo->prepare("
        UPDATE task_timer_sessions 
        SET status = 'running', resumed_at = NOW() 
        WHERE id = ? AND user_id = ? AND status = 'paused'
    ");
    
    if ($stmt->execute([$sessionId, $userId])) {
        if ($stmt->rowCount() > 0) {
            jsonResponse(true, [
                'session_id' => $sessionId,
                'status' => 'running',
                'resumed_at' => date('Y-m-d H:i:s')
            ], 'Timer ripreso');
        } else {
            jsonResponse(false, null, 'Sessione non trovata o non in pausa');
        }
    } else {
        jsonResponse(false, null, 'Errore ripresa timer');
    }
}

/**
 * Ferma definitivamente il timer
 */
function stopTimer(string $userId): void {
    global $pdo;
    
    $sessionId = $_POST['session_id'] ?? '';
    
    if (empty($sessionId)) {
        jsonResponse(false, null, 'Session ID richiesto');
        return;
    }
    
    // Recupera sessione
    $stmt = $pdo->prepare("
        SELECT tts.*, u.paga_oraria 
        FROM task_timer_sessions tts
        JOIN utenti u ON tts.user_id = u.id
        WHERE tts.id = ? AND tts.user_id = ? 
        AND (tts.status = 'running' OR tts.status = 'paused')
    ");
    $stmt->execute([$sessionId, $userId]);
    $session = $stmt->fetch();
    
    if (!$session) {
        jsonResponse(false, null, 'Sessione non trovata');
        return;
    }
    
    // Calcola tempo totale
    $totalSeconds = (int)$session['total_seconds'];
    if ($session['status'] === 'running') {
        $startedAt = strtotime($session['started_at']);
        $elapsedSeconds = time() - $startedAt;
        $totalSeconds += $elapsedSeconds;
    }
    
    $hourlyRate = (float)($session['paga_oraria'] ?? 0);
    $hours = $totalSeconds / 3600;
    $calculatedCost = round($hours * $hourlyRate, 2);
    
    // Aggiorna sessione
    $stmt = $pdo->prepare("
        UPDATE task_timer_sessions 
        SET status = 'completed', stopped_at = NOW(), total_seconds = ? 
        WHERE id = ?
    ");
    
    if ($stmt->execute([$totalSeconds, $sessionId])) {
        // Salva log time tracking
        $logId = generateEntityId('ttl');
        $stmt = $pdo->prepare("
            INSERT INTO task_time_logs 
            (id, task_id, user_id, log_date, start_time, end_time, duration_seconds, hourly_rate, calculated_cost) 
            VALUES (?, ?, ?, CURDATE(), ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $logId, 
            $session['task_id'], 
            $userId,
            $session['started_at'],
            date('Y-m-d H:i:s'),
            $totalSeconds,
            $hourlyRate,
            $calculatedCost
        ]);
        
        // Aggiorna totale nella task
        $stmt = $pdo->prepare("
            UPDATE task 
            SET tempo_totale_secondi = tempo_totale_secondi + ?,
                costo_stimato = costo_stimato + ?
            WHERE id = ?
        ");
        $stmt->execute([$totalSeconds, $calculatedCost, $session['task_id']]);
        
        jsonResponse(true, [
            'session_id' => $sessionId,
            'task_id' => $session['task_id'],
            'total_seconds' => $totalSeconds,
            'formatted_time' => formatDuration($totalSeconds),
            'hourly_rate' => $hourlyRate,
            'calculated_cost' => $calculatedCost
        ], 'Timer completato');
    } else {
        jsonResponse(false, null, 'Errore stop timer');
    }
}

/**
 * Recupera stato timer attivo
 */
function getTimerStatus(string $userId): void {
    global $pdo;
    
    $taskId = $_GET['task_id'] ?? '';
    
    if (!empty($taskId)) {
        // Stato specifico per task
        $stmt = $pdo->prepare("
            SELECT * FROM task_timer_sessions 
            WHERE task_id = ? AND user_id = ? AND (status = 'running' OR status = 'paused')
            ORDER BY created_at DESC LIMIT 1
        ");
        $stmt->execute([$taskId, $userId]);
    } else {
        // Timer attivo per utente
        $stmt = $pdo->prepare("
            SELECT tts.*, t.titolo as task_titolo 
            FROM task_timer_sessions tts
            JOIN task t ON tts.task_id = t.id
            WHERE tts.user_id = ? AND (tts.status = 'running' OR tts.status = 'paused')
            ORDER BY tts.created_at DESC LIMIT 1
        ");
        $stmt->execute([$userId]);
    }
    
    $session = $stmt->fetch();
    
    if ($session) {
        $totalSeconds = (int)$session['total_seconds'];
        
        if ($session['status'] === 'running') {
            $startedAt = strtotime($session['started_at']);
            $elapsedSeconds = time() - $startedAt;
            $totalSeconds += $elapsedSeconds;
        }
        
        jsonResponse(true, [
            'session_id' => $session['id'],
            'task_id' => $session['task_id'],
            'task_titolo' => $session['task_titolo'] ?? null,
            'status' => $session['status'],
            'started_at' => $session['started_at'],
            'total_seconds' => $totalSeconds,
            'formatted_time' => formatDuration($totalSeconds)
        ]);
    } else {
        jsonResponse(true, ['status' => 'no_active'], 'Nessun timer attivo');
    }
}

/**
 * Recupera tutti i time log per una task
 */
function getTaskTimes(string $userId): void {
    global $pdo;
    
    $taskId = $_GET['task_id'] ?? '';
    
    if (empty($taskId)) {
        jsonResponse(false, null, 'Task ID richiesto');
        return;
    }
    
    // Totale dalla task
    $stmt = $pdo->prepare("
        SELECT tempo_totale_secondi, costo_stimato 
        FROM task WHERE id = ?
    ");
    $stmt->execute([$taskId]);
    $taskTotals = $stmt->fetch();
    
    // Dettaglio sessioni
    $stmt = $pdo->prepare("
        SELECT * FROM task_timer_sessions 
        WHERE task_id = ? AND status = 'completed'
        ORDER BY stopped_at DESC
    ");
    $stmt->execute([$taskId]);
    $sessions = $stmt->fetchAll();
    
    // Dettaglio logs
    $stmt = $pdo->prepare("
        SELECT * FROM task_time_logs 
        WHERE task_id = ? 
        ORDER BY created_at DESC
    ");
    $stmt->execute([$taskId]);
    $logs = $stmt->fetchAll();
    
    jsonResponse(true, [
        'total_seconds' => (int)($taskTotals['tempo_totale_secondi'] ?? 0),
        'total_cost' => (float)($taskTotals['costo_stimato'] ?? 0),
        'formatted_time' => formatDuration($taskTotals['tempo_totale_secondi'] ?? 0),
        'sessions' => $sessions,
        'logs' => $logs
    ]);
}

/**
 * Formatta durata in secondi in formato leggibile
 */
function formatDuration(int $seconds): string {
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    $secs = $seconds % 60;
    
    if ($hours > 0) {
        return sprintf('%dh %02dm %02ds', $hours, $minutes, $secs);
    } elseif ($minutes > 0) {
        return sprintf('%dm %02ds', $minutes, $secs);
    } else {
        return sprintf('%ds', $secs);
    }
}
