<?php
/**
 * TaskFlow - API Guida Interattiva
 * 
 * Endpoint per gestire lo stato della guida dashboard
 */

require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Verifica autenticazione
requireAuth();

$userId = $_SESSION['user_id'] ?? '';
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'mark_guida':
        markGuidaAsSeen($userId);
        break;
        
    case 'reset_guida':
        resetGuida($userId);
        break;
        
    case 'check_guida':
        checkGuidaStatus($userId);
        break;
        
    default:
        jsonResponse(false, null, 'Azione non valida');
}

/**
 * Marca la guida come vista per l'utente
 */
function markGuidaAsSeen(string $userId): void {
    global $pdo;
    
    if (empty($userId)) {
        jsonResponse(false, null, 'Utente non autenticato');
        return;
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE utenti SET guidavista = 1 WHERE id = ?");
        $stmt->execute([$userId]);
        
        jsonResponse(true, ['guidavista' => 1], 'Guida marcata come vista');
    } catch (PDOException $e) {
        error_log("Errore mark_guida: " . $e->getMessage());
        jsonResponse(false, null, 'Errore durante il salvataggio');
    }
}

/**
 * Resetta lo stato della guida (per riutilizzarla)
 */
function resetGuida(string $userId): void {
    global $pdo;
    
    if (empty($userId)) {
        jsonResponse(false, null, 'Utente non autenticato');
        return;
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE utenti SET guidavista = 0 WHERE id = ?");
        $stmt->execute([$userId]);
        
        jsonResponse(true, ['guidavista' => 0], 'Guida resettata');
    } catch (PDOException $e) {
        error_log("Errore reset_guida: " . $e->getMessage());
        jsonResponse(false, null, 'Errore durante il reset');
    }
}

/**
 * Verifica lo stato della guida
 */
function checkGuidaStatus(string $userId): void {
    global $pdo;
    
    if (empty($userId)) {
        jsonResponse(false, null, 'Utente non autenticato');
        return;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT guidavista FROM utenti WHERE id = ?");
        $stmt->execute([$userId]);
        $guidavista = $stmt->fetchColumn();
        
        jsonResponse(true, [
            'guidavista' => (int)$guidavista,
            'da_vedere' => ($guidavista === false || $guidavista === null || (int)$guidavista === 0)
        ]);
    } catch (PDOException $e) {
        error_log("Errore check_guida: " . $e->getMessage());
        jsonResponse(false, null, 'Errore durante la verifica');
    }
}
