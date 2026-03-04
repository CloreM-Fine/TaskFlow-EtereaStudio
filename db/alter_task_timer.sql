-- =====================================================
-- TaskFlow - Time Tracking per Task
-- Aggiunge tabelle e colonne per il tracciamento tempo
-- =====================================================

-- -----------------------------------------------------
-- 1. Tabella per le sessioni timer delle task
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS task_timer_sessions (
    id VARCHAR(20) PRIMARY KEY,
    task_id VARCHAR(20) NOT NULL,
    user_id VARCHAR(20) NOT NULL,
    started_at DATETIME NOT NULL,
    paused_at DATETIME NULL,
    resumed_at DATETIME NULL,
    stopped_at DATETIME NULL,
    total_seconds INT DEFAULT 0 COMMENT 'Tempo totale in secondi',
    status ENUM('running', 'paused', 'completed') DEFAULT 'running',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_task_id (task_id),
    INDEX idx_user_id (user_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- 2. Colonna paga oraria nella tabella utenti
-- -----------------------------------------------------
ALTER TABLE utenti 
ADD COLUMN paga_oraria DECIMAL(10,2) DEFAULT 0.00 COMMENT 'Tariffa oraria in euro';

-- -----------------------------------------------------
-- 3. Colonna per tracciare tempo totale nella tabella task
-- (opzionale, per performance - calcolato da timer_sessions)
-- -----------------------------------------------------
ALTER TABLE task 
ADD COLUMN tempo_totale_secondi INT DEFAULT 0 COMMENT 'Tempo totale tracciato in secondi',
ADD COLUMN costo_stimato DECIMAL(10,2) DEFAULT 0.00 COMMENT 'Costo calcolato in base al tempo';

-- -----------------------------------------------------
-- 4. Tabella per storico time tracking (riepilogo giornaliero)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS task_time_logs (
    id VARCHAR(20) PRIMARY KEY,
    task_id VARCHAR(20) NOT NULL,
    user_id VARCHAR(20) NOT NULL,
    log_date DATE NOT NULL,
    start_time TIME NULL,
    end_time TIME NULL,
    duration_seconds INT DEFAULT 0,
    hourly_rate DECIMAL(10,2) DEFAULT 0.00,
    calculated_cost DECIMAL(10,2) DEFAULT 0.00,
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_task_id (task_id),
    INDEX idx_user_id (user_id),
    INDEX idx_log_date (log_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- Messaggio di conferma
-- -----------------------------------------------------
SELECT 'Tabelle time tracking create con successo!' as message;
