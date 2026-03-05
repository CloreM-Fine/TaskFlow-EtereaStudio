-- =====================================================
-- TaskFlow - Sezione Report e Analytics
-- =====================================================

-- -----------------------------------------------------
-- 1. Tabella per cache report giornalieri (performance)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS report_cache (
    id INT AUTO_INCREMENT PRIMARY KEY,
    report_type VARCHAR(50) NOT NULL COMMENT 'Tipo report: overview, progetti, finanze, etc',
    cache_date DATE NOT NULL,
    data JSON NOT NULL COMMENT 'Dati del report in JSON',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_type_date (report_type, cache_date),
    UNIQUE KEY unique_type_date (report_type, cache_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- 2. Tabella per log attività utente (audit trail)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS user_activity_log (
    id VARCHAR(20) PRIMARY KEY,
    user_id VARCHAR(20) NOT NULL,
    action_type VARCHAR(50) NOT NULL COMMENT 'create, update, delete, view, complete',
    entity_type VARCHAR(50) NOT NULL COMMENT 'progetto, task, cliente, etc',
    entity_id VARCHAR(20) NULL,
    details JSON NULL COMMENT 'Dettagli aggiuntivi',
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_action_type (action_type),
    INDEX idx_entity (entity_type, entity_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- 3. Tabella per statistiche mensili (pre-calcolate)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS monthly_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    `year_month` VARCHAR(7) NOT NULL COMMENT 'Formato: YYYY-MM',
    `stat_type` VARCHAR(50) NOT NULL COMMENT 'progetti, finanze, task, etc',
    `stat_data` JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_month_type (`year_month`, `stat_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- 4. Colonna per tracciare visualizzazioni progetti
-- -----------------------------------------------------
ALTER TABLE progetti 
ADD COLUMN view_count INT DEFAULT 0 COMMENT 'Numero visualizzazioni',
ADD COLUMN last_viewed_at DATETIME NULL COMMENT 'Ultima visualizzazione';

-- -----------------------------------------------------
-- 5. Colonna per tracciare tempo lavorato su task
-- -----------------------------------------------------
ALTER TABLE task 
ADD COLUMN started_at DATETIME NULL COMMENT 'Quando il task è stato iniziato',
ADD COLUMN completed_at DATETIME NULL COMMENT 'Quando il task è stato completato';

-- -----------------------------------------------------
-- Messaggio di conferma
-- -----------------------------------------------------
SELECT 'Tabelle report create con successo!' as message;
