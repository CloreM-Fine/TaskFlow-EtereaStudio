<?php
/**
 * TaskFlow - API User Profile
 * Gestisce dati utente inclusa paga oraria
 */

require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Verifica autenticazione
requireAuth();

$userId = $_SESSION['user_id'] ?? '';
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'get_profile':
        getUserProfile($userId);
        break;
        
    case 'update_paga_oraria':
        updatePagaOraria($userId);
        break;
        
    default:
        jsonResponse(false, null, 'Azione non valida');
}

/**
 * Recupera profilo utente
 */
function getUserProfile(string $userId): void {
    global $pdo;
    
    if (empty($userId)) {
        jsonResponse(false, null, 'Utente non autenticato');
        return;
    }
    
    try {
        $stmt = $pdo->prepare("
            SELECT id, nome, email, colore, paga_oraria, created_at 
            FROM utenti 
            WHERE id = ?
        ");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        if ($user) {
            jsonResponse(true, [
                'id' => $user['id'],
                'nome' => $user['nome'],
                'email' => $user['email'],
                'colore' => $user['colore'],
                'paga_oraria' => (float)$user['paga_oraria'],
                'created_at' => $user['created_at']
            ]);
        } else {
            jsonResponse(false, null, 'Utente non trovato');
        }
    } catch (PDOException $e) {
        error_log("Errore get_user_profile: " . $e->getMessage());
        jsonResponse(false, null, 'Errore recupero profilo');
    }
}

/**
 * Aggiorna paga oraria utente
 */
function updatePagaOraria(string $userId): void {
    global $pdo;
    
    if (empty($userId)) {
        jsonResponse(false, null, 'Utente non autenticato');
        return;
    }
    
    $pagaOraria = $_POST['paga_oraria'] ?? 0;
    
    // Validazione
    $pagaOraria = floatval($pagaOraria);
    if ($pagaOraria < 0) {
        $pagaOraria = 0;
    }
    
    try {
        $stmt = $pdo->prepare("
            UPDATE utenti 
            SET paga_oraria = ? 
            WHERE id = ?
        ");
        
        if ($stmt->execute([$pagaOraria, $userId])) {
            jsonResponse(true, ['paga_oraria' => $pagaOraria], 'Tariffa oraria aggiornata');
        } else {
            jsonResponse(false, null, 'Errore aggiornamento');
        }
    } catch (PDOException $e) {
        error_log("Errore update_paga_oraria: " . $e->getMessage());
        jsonResponse(false, null, 'Errore database');
    }
}
