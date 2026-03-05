-- =====================================================
-- TaskFlow - Sezione Report e Analytics (SAFE VERSION)
-- =====================================================

-- -----------------------------------------------------
-- 1. Tabella per cache report giornalieri
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS report_cache (
    id INT AUTO_INCREMENT PRIMARY KEY,
    report_type VARCHAR(50) NOT NULL COMMENT 'Tipo report',
    cache_date DATE NOT NULL,
    data JSON NOT NULL COMMENT 'Dati JSON',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_type_date (report_type, cache_date),
    UNIQUE KEY unique_type_date (report_type, cache_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- 2. Tabella per log attività utente
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS user_activity_log (
    id VARCHAR(20) PRIMARY KEY,
    user_id VARCHAR(20) NOT NULL,
    action_type VARCHAR(50) NOT NULL,
    entity_type VARCHAR(50) NOT NULL,
    entity_id VARCHAR(20) NULL,
    details JSON NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_action_type (action_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- 3. Tabella per statistiche mensili
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS monthly_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    year_month VARCHAR(7) NOT NULL COMMENT 'Formato YYYY-MM',
    stat_type VARCHAR(50) NOT NULL,
    stat_data JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_month_type (year_month, stat_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- 4. Colonne per progetti (usa queste singolarmente se danno errore)
-- -----------------------------------------------------
-- Esegui queste solo se le colonne NON esistono già:
-- ALTER TABLE progetti ADD COLUMN view_count INT DEFAULT 0;
-- ALTER TABLE progetti ADD COLUMN last_viewed_at DATETIME NULL;

-- -----------------------------------------------------
-- 5. Colonne per task (usa queste singolarmente se danno errore)
-- -----------------------------------------------------
-- Esegui queste solo se le colonne NON esistono già:
-- ALTER TABLE task ADD COLUMN started_at DATETIME NULL;
-- ALTER TABLE task ADD COLUMN completed_at DATETIME NULL;

-- -----------------------------------------------------
-- Messaggio
-- -----------------------------------------------------
SELECT 'Tabelle report create! Se le colonne danno errore duplicato, ignora.' as message;
