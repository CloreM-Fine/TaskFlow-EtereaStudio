<?php
/**
 * TaskFlow
 * API Gestione Guida Introductiva
 */

// Abilita error reporting per debug
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Header JSON sempre
header('Content-Type: application/json');

// Configurazione sessione PRIMA di qualsiasi output
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Lax');
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
           (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
ini_set('session.cookie_secure', $isHttps ? 1 : 0);

// AVVIA SESSIONE SUBITO
session_start();

try {
    require_once __DIR__ . '/../includes/functions.php';
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Errore caricamento: ' . $e->getMessage()]);
    exit;
}

// Verifica autenticazione
requireAuth();

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$userId = currentUserId();

try {
    switch ($action) {
        case 'check_guida':
            checkGuidaStatus($userId);
            break;
            
        case 'mark_guida':
            markGuidaVista($userId);
            break;
            
        case 'reset_guida':
            resetGuida($userId);
            break;
            
        default:
            jsonResponse(false, null, 'Azione non valida');
    }
} catch (Throwable $e) {
    error_log("Guida API Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Errore server: ' . $e->getMessage()]);
}

/**
 * Controlla lo stato della guida per l'utente corrente
 */
function checkGuidaStatus(string $userId): void {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT guidavista FROM utenti WHERE id = ?");
        $stmt->execute([$userId]);
        $guidavista = $stmt->fetchColumn();
        
        // Se il campo non esiste o è NULL, assumiamo 0 (guida non vista)
        $guidavista = ($guidavista === null || $guidavista === false) ? 0 : (int)$guidavista;
        
        jsonResponse(true, [
            'guidavista' => $guidavista,
            'mostra_guida' => $guidavista === 0
        ]);
    } catch (PDOException $e) {
        error_log("Errore check guida: " . $e->getMessage());
        // In caso di errore (es. colonna non esiste), restituiamo 0
        jsonResponse(true, [
            'guidavista' => 0,
            'mostra_guida' => false, // Non mostriamo la guida se c'è un errore DB
            'error' => 'Campo guidavista non disponibile'
        ]);
    }
}

/**
 * Segna la guida come vista per l'utente corrente
 */
function markGuidaVista(string $userId): void {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("UPDATE utenti SET guidavista = 1 WHERE id = ?");
        $stmt->execute([$userId]);
        
        jsonResponse(true, ['guidavista' => 1], 'Guida segnata come vista');
    } catch (PDOException $e) {
        error_log("Errore mark guida: " . $e->getMessage());
        jsonResponse(false, null, 'Errore durante l\'aggiornamento');
    }
}

/**
 * Resetta lo stato della guida (per mostrarla di nuovo)
 */
function resetGuida(string $userId): void {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("UPDATE utenti SET guidavista = 0 WHERE id = ?");
        $stmt->execute([$userId]);
        
        jsonResponse(true, ['guidavista' => 0], 'Guida resettata. Verrà mostrata al prossimo accesso alla dashboard.');
    } catch (PDOException $e) {
        error_log("Errore reset guida: " . $e->getMessage());
        jsonResponse(false, null, 'Errore durante il reset');
    }
}
