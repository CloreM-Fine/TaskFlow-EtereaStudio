<?php
/**
 * TaskFlow - API Report e Analytics
 * Fornisce dati aggregati per dashboard report
 */

require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Verifica autenticazione
requireAuth();

$userId = $_SESSION['user_id'] ?? '';
$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'overview':
        getOverviewReport($userId);
        break;
        
    case 'progetti':
        getProgettiReport($userId);
        break;
        
    case 'finanze':
        getFinanzeReport($userId);
        break;
        
    case 'task':
        getTaskReport($userId);
        break;
        
    case 'clienti':
        getClientiReport($userId);
        break;
        
    case 'tempi':
        getTempiReport($userId);
        break;
        
    case 'trend':
        getTrendData($userId);
        break;
        
    default:
        jsonResponse(false, null, 'Azione non valida');
}

/**
 * Report overview generale
 */
function getOverviewReport(string $userId): void {
    global $pdo;
    
    try {
        // Progetti
        $stmt = $pdo->query("SELECT 
            COUNT(*) as totali,
            SUM(CASE WHEN stato = 'in_corso' THEN 1 ELSE 0 END) as in_corso,
            SUM(CASE WHEN stato = 'completato' THEN 1 ELSE 0 END) as completati,
            SUM(CASE WHEN stato = 'archiviato' THEN 1 ELSE 0 END) as archiviati
            FROM progetti");
        $progetti = $stmt->fetch();
        
        // Task
        $stmt = $pdo->query("SELECT 
            COUNT(*) as totali,
            SUM(CASE WHEN stato = 'completato' THEN 1 ELSE 0 END) as completate,
            SUM(CASE WHEN stato = 'da_fare' THEN 1 ELSE 0 END) as da_fare
            FROM task");
        $task = $stmt->fetch();
        
        // Finanze - totale fatturato
        $stmt = $pdo->query("SELECT 
            COALESCE(SUM(valore), 0) as fatturato_totale,
            COALESCE(SUM(CASE WHEN stato = 'pagamento_completato' THEN valore ELSE 0 END), 0) as pagato,
            COALESCE(SUM(CASE WHEN stato IN ('da_pagare', 'da_saldare') THEN valore ELSE 0 END), 0) as da_incassare
            FROM progetti");
        $finanze = $stmt->fetch();
        
        // Clienti
        $stmt = $pdo->query("SELECT COUNT(*) as totali FROM clienti");
        $clienti = $stmt->fetchColumn();
        
        // Progetti recenti
        $stmt = $pdo->query("SELECT id, titolo, stato, valore, created_at 
            FROM progetti 
            ORDER BY created_at DESC 
            LIMIT 5");
        $progettiRecenti = $stmt->fetchAll();
        
        jsonResponse(true, [
            'progetti' => [
                'totali' => (int)$progetti['totali'],
                'in_corso' => (int)$progetti['in_corso'],
                'completati' => (int)$progetti['completati'],
                'archiviati' => (int)$progetti['archiviati']
            ],
            'task' => [
                'totali' => (int)$task['totali'],
                'completate' => (int)$task['completate'],
                'da_fare' => (int)$task['da_fare'],
                'percentuale_completamento' => $task['totali'] > 0 ? round(($task['completate'] / $task['totali']) * 100) : 0
            ],
            'finanze' => [
                'fatturato_totale' => (float)$finanze['fatturato_totale'],
                'pagato' => (float)$finanze['pagato'],
                'da_incassare' => (float)$finanze['da_incassare']
            ],
            'clienti' => [
                'totali' => (int)$clienti
            ],
            'progetti_recenti' => $progettiRecenti
        ]);
    } catch (PDOException $e) {
        error_log("Errore report overview: " . $e->getMessage());
        jsonResponse(false, null, 'Errore generazione report');
    }
}

/**
 * Report progetti dettagliato
 */
function getProgettiReport(string $userId): void {
    global $pdo;
    
    try {
        // Per tipologia
        $stmt = $pdo->query("SELECT 
            tipologia,
            COUNT(*) as count,
            COALESCE(SUM(valore), 0) as valore_totale
            FROM progetti
            WHERE tipologia IS NOT NULL
            GROUP BY tipologia
            ORDER BY count DESC");
        $perTipologia = $stmt->fetchAll();
        
        // Per stato
        $stmt = $pdo->query("SELECT 
            stato,
            COUNT(*) as count,
            COALESCE(SUM(valore), 0) as valore_totale
            FROM progetti
            GROUP BY stato");
        $perStato = $stmt->fetchAll();
        
        // Per mese (ultimi 12 mesi)
        $stmt = $pdo->query("SELECT 
            DATE_FORMAT(created_at, '%Y-%m') as mese,
            COUNT(*) as count,
            COALESCE(SUM(valore), 0) as valore
            FROM progetti
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY mese");
        $perMese = $stmt->fetchAll();
        
        // Top 5 progetti per valore
        $stmt = $pdo->query("SELECT id, titolo, valore, stato, cliente_id
            FROM progetti
            ORDER BY valore DESC
            LIMIT 5");
        $topProgetti = $stmt->fetchAll();
        
        // Progetti con task in ritardo
        $stmt = $pdo->query("SELECT p.id, p.titolo, COUNT(t.id) as task_scadute
            FROM progetti p
            JOIN task t ON t.progetto_id = p.id
            WHERE t.scadenza < NOW() 
            AND t.stato != 'completato'
            GROUP BY p.id
            ORDER BY task_scadute DESC
            LIMIT 5");
        $progettiRitardo = $stmt->fetchAll();
        
        jsonResponse(true, [
            'per_tipologia' => $perTipologia,
            'per_stato' => $perStato,
            'per_mese' => $perMese,
            'top_progetti' => $topProgetti,
            'progetti_in_ritardo' => $progettiRitardo
        ]);
    } catch (PDOException $e) {
        error_log("Errore report progetti: " . $e->getMessage());
        jsonResponse(false, null, 'Errore generazione report');
    }
}

/**
 * Report finanze
 */
function getFinanzeReport(string $userId): void {
    global $pdo;
    
    try {
        // Cassa corrente
        $stmt = $pdo->query("SELECT 
            COALESCE(SUM(CASE WHEN tipo = 'entrata' THEN importo ELSE 0 END), 0) as entrate,
            COALESCE(SUM(CASE WHEN tipo = 'uscita' THEN importo ELSE 0 END), 0) as uscite,
            COALESCE(SUM(CASE WHEN tipo = 'entrata' THEN importo ELSE -importo END), 0) as saldo
            FROM transazioni_economiche
            WHERE conto = 'cassa'");
        $cassa = $stmt->fetch();
        
        // Wallet
        $stmt = $pdo->query("SELECT 
            u.nome,
            u.wallet_saldo as saldo
            FROM utenti u
            ORDER BY u.wallet_saldo DESC");
        $wallet = $stmt->fetchAll();
        
        // Andamento mensile cassa
        $stmt = $pdo->query("SELECT 
            DATE_FORMAT(data, '%Y-%m') as mese,
            COALESCE(SUM(CASE WHEN tipo = 'entrata' THEN importo ELSE 0 END), 0) as entrate,
            COALESCE(SUM(CASE WHEN tipo = 'uscita' THEN importo ELSE 0 END), 0) as uscite
            FROM transazioni_economiche
            WHERE data >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(data, '%Y-%m')
            ORDER BY mese");
        $andamentoMensile = $stmt->fetchAll();
        
        // Stato pagamenti progetti
        $stmt = $pdo->query("SELECT 
            stato_pagamento,
            COUNT(*) as count,
            COALESCE(SUM(valore), 0) as totale
            FROM progetti
            GROUP BY stato_pagamento");
        $statoPagamenti = $stmt->fetchAll();
        
        jsonResponse(true, [
            'cassa' => [
                'entrate' => (float)$cassa['entrate'],
                'uscite' => (float)$cassa['uscite'],
                'saldo' => (float)$cassa['saldo']
            ],
            'wallet' => $wallet,
            'andamento_mensile' => $andamentoMensile,
            'stato_pagamenti' => $statoPagamenti
        ]);
    } catch (PDOException $e) {
        error_log("Errore report finanze: " . $e->getMessage());
        jsonResponse(false, null, 'Errore generazione report');
    }
}

/**
 * Report task
 */
function getTaskReport(string $userId): void {
    global $pdo;
    
    try {
        // Per priorita
        $stmt = $pdo->query("SELECT 
            priorita,
            COUNT(*) as count
            FROM task
            GROUP BY priorita
            ORDER BY FIELD(priorita, 'alta', 'media', 'bassa')");
        $perPriorita = $stmt->fetchAll();
        
        // Per stato
        $stmt = $pdo->query("SELECT 
            stato,
            COUNT(*) as count
            FROM task
            GROUP BY stato");
        $perStato = $stmt->fetchAll();
        
        // Task completate per mese
        $stmt = $pdo->query("SELECT 
            DATE_FORMAT(completato_il, '%Y-%m') as mese,
            COUNT(*) as count
            FROM task
            WHERE completato_il IS NOT NULL
            AND completato_il >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(completato_il, '%Y-%m')
            ORDER BY mese");
        $completatePerMese = $stmt->fetchAll();
        
        // Task in scadenza (prossimi 7 giorni)
        $stmt = $pdo->query("SELECT t.*, p.titolo as progetto_titolo
            FROM task t
            JOIN progetti p ON t.progetto_id = p.id
            WHERE t.scadenza BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)
            AND t.stato != 'completato'
            ORDER BY t.scadenza
            LIMIT 10");
        $inScadenza = $stmt->fetchAll();
        
        // Tempo medio completamento task
        $stmt = $pdo->query("SELECT 
            AVG(TIMESTAMPDIFF(HOUR, created_at, completato_il)) as ore_medie
            FROM task
            WHERE stato = 'completato'
            AND completato_il IS NOT NULL");
        $tempoMedio = $stmt->fetchColumn();
        
        jsonResponse(true, [
            'per_priorita' => $perPriorita,
            'per_stato' => $perStato,
            'completate_per_mese' => $completatePerMese,
            'in_scadenza' => $inScadenza,
            'tempo_medio_completamento' => round($tempoMedio, 1)
        ]);
    } catch (PDOException $e) {
        error_log("Errore report task: " . $e->getMessage());
        jsonResponse(false, null, 'Errore generazione report');
    }
}

/**
 * Report clienti
 */
function getClientiReport(string $userId): void {
    global $pdo;
    
    try {
        // Top clienti per fatturato
        $stmt = $pdo->query("SELECT 
            c.id,
            c.ragione_sociale,
            COUNT(p.id) as num_progetti,
            COALESCE(SUM(p.valore), 0) as fatturato
            FROM clienti c
            LEFT JOIN progetti p ON p.cliente_id = c.id
            GROUP BY c.id
            ORDER BY fatturato DESC
            LIMIT 10");
        $topClienti = $stmt->fetchAll();
        
        // Clienti per numero progetti
        $stmt = $pdo->query("SELECT 
            c.id,
            c.ragione_sociale,
            COUNT(p.id) as num_progetti
            FROM clienti c
            LEFT JOIN progetti p ON p.cliente_id = c.id
            GROUP BY c.id
            ORDER BY num_progetti DESC
            LIMIT 10");
        $clientiProgetti = $stmt->fetchAll();
        
        // Nuovi clienti per mese
        $stmt = $pdo->query("SELECT 
            DATE_FORMAT(created_at, '%Y-%m') as mese,
            COUNT(*) as count
            FROM clienti
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY mese");
        $nuoviClienti = $stmt->fetchAll();
        
        jsonResponse(true, [
            'top_clienti' => $topClienti,
            'clienti_per_progetti' => $clientiProgetti,
            'nuovi_clienti' => $nuoviClienti
        ]);
    } catch (PDOException $e) {
        error_log("Errore report clienti: " . $e->getMessage());
        jsonResponse(false, null, 'Errore generazione report');
    }
}

/**
 * Report tempi e produttività
 */
function getTempiReport(string $userId): void {
    global $pdo;
    
    try {
        // Tempo totale tracciato
        $stmt = $pdo->query("SELECT 
            COALESCE(SUM(total_seconds), 0) as tempo_totale,
            COUNT(*) as sessioni
            FROM task_timer_sessions
            WHERE status = 'completed'");
        $tempoTotale = $stmt->fetch();
        
        // Tempo per progetto
        $stmt = $pdo->query("SELECT 
            p.id,
            p.titolo,
            COALESCE(SUM(tts.total_seconds), 0) as tempo_totale
            FROM progetti p
            JOIN task t ON t.progetto_id = p.id
            JOIN task_timer_sessions tts ON tts.task_id = t.id
            WHERE tts.status = 'completed'
            GROUP BY p.id
            ORDER BY tempo_totale DESC
            LIMIT 10");
        $tempoPerProgetto = $stmt->fetchAll();
        
        // Costo stimato vs fatturato
        $stmt = $pdo->query("SELECT 
            COALESCE(SUM(costo_stimato), 0) as costi_totali,
            COALESCE(SUM(valore), 0) as fatturato
            FROM progetti");
        $margine = $stmt->fetch();
        
        jsonResponse(true, [
            'tempo_totale' => [
                'secondi' => (int)$tempoTotale['tempo_totale'],
                'ore' => round($tempoTotale['tempo_totale'] / 3600, 2),
                'sessioni' => (int)$tempoTotale['sessioni']
            ],
            'tempo_per_progetto' => $tempoPerProgetto,
            'margine' => [
                'costi' => (float)$margine['costi_totali'],
                'fatturato' => (float)$margine['fatturato'],
                'margine' => (float)$margine['fatturato'] - (float)$margine['costi_totali']
            ]
        ]);
    } catch (PDOException $e) {
        error_log("Errore report tempi: " . $e->getMessage());
        jsonResponse(false, null, 'Errore generazione report');
    }
}

/**
 * Dati trend per grafici
 */
function getTrendData(string $userId): void {
    global $pdo;
    
    $tipo = $_GET['tipo'] ?? 'progetti'; // progetti, finanze, task
    $mesi = intval($_GET['mesi'] ?? 12);
    
    try {
        $labels = [];
        $data = [];
        
        if ($tipo === 'progetti') {
            $stmt = $pdo->query("SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as mese,
                COUNT(*) as count
                FROM progetti
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL $mesi MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY mese");
            $result = $stmt->fetchAll();
            
            foreach ($result as $r) {
                $labels[] = $r['mese'];
                $data[] = (int)$r['count'];
            }
        } elseif ($tipo === 'fatturato') {
            $stmt = $pdo->query("SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as mese,
                COALESCE(SUM(valore), 0) as totale
                FROM progetti
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL $mesi MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY mese");
            $result = $stmt->fetchAll();
            
            foreach ($result as $r) {
                $labels[] = $r['mese'];
                $data[] = (float)$r['totale'];
            }
        }
        
        jsonResponse(true, [
            'labels' => $labels,
            'data' => $data
        ]);
    } catch (PDOException $e) {
        error_log("Errore trend data: " . $e->getMessage());
        jsonResponse(false, null, 'Errore');
    }
}
