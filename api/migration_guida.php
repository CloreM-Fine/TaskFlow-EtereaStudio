<?php
/**
 * TaskFlow
 * Migration: Aggiunta campo guidavista alla tabella utenti
 * 
 * Questo file aggiunge il campo necessario per tracciare se l'utente ha visto la guida introduttiva.
 * Eseguire via browser o CLI.
 */

require_once __DIR__ . '/../includes/functions.php';
header('Content-Type: application/json');

try {
    // Verifica se la colonna guidavista esiste
    $stmt = $pdo->query("SHOW COLUMNS FROM utenti LIKE 'guidavista'");
    
    if ($stmt->rowCount() === 0) {
        // Aggiunge la colonna guidavista
        $pdo->exec("ALTER TABLE utenti ADD COLUMN guidavista TINYINT(1) NOT NULL DEFAULT 0");
        
        echo json_encode([
            'success' => true, 
            'message' => 'Campo guidavista aggiunto con successo alla tabella utenti',
            'executed' => true
        ]);
    } else {
        echo json_encode([
            'success' => true, 
            'message' => 'Campo guidavista già esistente',
            'executed' => false
        ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'error' => $e->getMessage()
    ]);
}
