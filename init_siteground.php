<?php
/**
 * TaskFlow - Inizializzazione cartelle per SiteGround
 * Esegui questo file una sola volta dopo il deploy
 */

$directories = [
    'assets/uploads',
    'assets/uploads/clienti',
    'assets/uploads/progetti',
    'assets/uploads/avatars',
    'assets/uploads/preventivi',
    'assets/uploads/temp',
    'assets/uploads/task_files',
    'assets/uploads/loghi',
    'logs'
];

echo "<h1>TaskFlow - Inizializzazione</h1>";
echo "<pre>";

foreach ($directories as $dir) {
    $path = __DIR__ . '/' . $dir;
    if (!is_dir($path)) {
        if (mkdir($path, 0755, true)) {
            echo "✓ Creata cartella: $dir\n";
        } else {
            echo "✗ Errore creazione: $dir\n";
        }
    } else {
        echo "→ Già esistente: $dir\n";
    }
    
    // Verifica permessi
    if (is_dir($path)) {
        chmod($path, 0755);
        echo "  Permessi: 755\n";
    }
}

echo "\n✅ Inizializzazione completata!";
echo "\n\n<i>Ora puoi eliminare questo file per sicurezza.</i>";
echo "</pre>";
