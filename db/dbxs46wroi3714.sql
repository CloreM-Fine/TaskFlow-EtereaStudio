-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Mar 03, 2026 alle 20:22
-- Versione del server: 8.4.5-5
-- Versione PHP: 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbxs46wroi3714`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `appuntamenti`
--

CREATE TABLE `appuntamenti` (
  `id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `titolo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` enum('appuntamento','appuntamento_online','shooting_cliente','scadenza_task','scadenza_progetto','promemoria') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'appuntamento',
  `data_inizio` datetime NOT NULL,
  `data_fine` datetime DEFAULT NULL,
  `progetto_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `task_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utente_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `partecipanti` json DEFAULT NULL,
  `google_event_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_sync_status` enum('pending','synced','error') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `google_sync_error` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `briefing_conversations`
--

CREATE TABLE `briefing_conversations` (
  `id` int NOT NULL,
  `user_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `titolo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `cliente_nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `messages` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `clienti`
--

CREATE TABLE `clienti` (
  `id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ragione_sociale` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` enum('Azienda','Privato','Partita IVA') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Azienda',
  `piva_cf` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `indirizzo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `citta` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cap` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provincia` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cellulare` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pec` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sito_web` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `logo_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `codici_ateco`
--

CREATE TABLE `codici_ateco` (
  `id` int NOT NULL,
  `codice` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descrizione` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coefficiente_redditivita` decimal(5,2) NOT NULL DEFAULT '0.00',
  `tassazione` decimal(5,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `contabilita_mensile`
--

CREATE TABLE `contabilita_mensile` (
  `id` int NOT NULL,
  `mese` int NOT NULL,
  `anno` int NOT NULL,
  `saldo_iniziale` decimal(12,2) NOT NULL DEFAULT '0.00',
  `totale_entrate` decimal(12,2) NOT NULL DEFAULT '0.00',
  `saldo_finale` decimal(12,2) NOT NULL DEFAULT '0.00',
  `numero_progetti` int NOT NULL DEFAULT '0',
  `note` text COLLATE utf8mb4_unicode_ci,
  `creato_il` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `aggiornato_il` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `cronologia_calcoli_tasse`
--

CREATE TABLE `cronologia_calcoli_tasse` (
  `id` int NOT NULL,
  `user_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fatturato` decimal(12,2) NOT NULL,
  `codice_ateco` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descrizione_ateco` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coefficiente` decimal(5,2) NOT NULL,
  `reddito_imponibile` decimal(12,2) NOT NULL,
  `aliquota_irpef` decimal(5,2) NOT NULL,
  `imposta_irpef` decimal(12,2) NOT NULL,
  `inps_percentuale` decimal(5,2) NOT NULL,
  `contributi_inps` decimal(12,2) NOT NULL,
  `acconto_percentuale` decimal(5,2) NOT NULL,
  `acconti` decimal(12,2) NOT NULL,
  `totale_tasse` decimal(12,2) NOT NULL,
  `netto` decimal(12,2) NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `impostazioni`
--

CREATE TABLE `impostazioni` (
  `id` int NOT NULL,
  `chiave` varchar(100) NOT NULL,
  `valore` text,
  `tipo` varchar(50) DEFAULT 'string',
  `descrizione` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `listini`
--

CREATE TABLE `listini` (
  `id` varchar(20) NOT NULL,
  `titolo` varchar(255) NOT NULL,
  `descrizione` text,
  `colore` varchar(7) DEFAULT '#FFFFFF',
  `immagine` varchar(255) DEFAULT NULL,
  `attivo` tinyint(1) DEFAULT '1',
  `created_by` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `listini_servizi`
--

CREATE TABLE `listini_servizi` (
  `id` int NOT NULL,
  `listino_id` varchar(20) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descrizione` text,
  `prezzo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `durata_minuti` int DEFAULT NULL,
  `ordine` int DEFAULT '0',
  `attivo` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `listino_categorie`
--

CREATE TABLE `listino_categorie` (
  `id` int NOT NULL,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordine` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `listino_voci`
--

CREATE TABLE `listino_voci` (
  `id` int NOT NULL,
  `categoria_id` int NOT NULL,
  `tipo_servizio` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descrizione` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `prezzo` decimal(10,2) NOT NULL,
  `sconto_percentuale` int DEFAULT '0',
  `ordine` int DEFAULT '0',
  `attivo` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `frequenza` int DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `notifiche`
--

CREATE TABLE `notifiche` (
  `id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `titolo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `messaggio` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `entita_tipo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entita_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `progetto_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utente_destinatario` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creato_da` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `letta` tinyint(1) DEFAULT '0',
  `data_creazione` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_lettura` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `preventivi_salvati`
--

CREATE TABLE `preventivi_salvati` (
  `id` int NOT NULL,
  `numero` varchar(50) NOT NULL,
  `cliente_id` varchar(20) DEFAULT NULL,
  `progetto_id` varchar(20) DEFAULT NULL,
  `cliente_nome` varchar(255) NOT NULL,
  `data_validita` date DEFAULT NULL,
  `sconto_globale` decimal(5,2) DEFAULT '0.00',
  `note` text,
  `servizi_json` json NOT NULL,
  `subtotale` decimal(10,2) DEFAULT '0.00',
  `totale` decimal(10,2) DEFAULT '0.00',
  `file_path` varchar(500) DEFAULT NULL,
  `created_by` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `frequenza` int DEFAULT '1',
  `frequenza_testo` varchar(50) DEFAULT 'Una tantum',
  `mostra_burocrazia` tinyint(1) DEFAULT '1',
  `tempi_consegna` varchar(500) DEFAULT NULL,
  `non_include` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `preventivo_template_burocrazia`
--

CREATE TABLE `preventivo_template_burocrazia` (
  `id` int NOT NULL,
  `nome` varchar(100) NOT NULL,
  `tipo` varchar(50) NOT NULL DEFAULT 'generale',
  `contenuto` text NOT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  `created_by` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `preventivo_template_condizioni`
--

CREATE TABLE `preventivo_template_condizioni` (
  `id` int NOT NULL,
  `nome` varchar(100) NOT NULL,
  `contenuto` text NOT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  `created_by` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `progetti`
--

CREATE TABLE `progetti` (
  `id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `titolo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cliente_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descrizione` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tipologie` json DEFAULT NULL,
  `prezzo_totale` decimal(10,2) DEFAULT '0.00',
  `stato_progetto` enum('da_iniziare','in_corso','completato','consegnato','archiviato') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'da_iniziare',
  `stato_pagamento` enum('da_pagare','da_pagare_acconto','acconto_pagato','da_saldare','cat','pagamento_completato') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'da_pagare',
  `acconto_importo` decimal(10,2) DEFAULT '0.00',
  `acconto_percentuale` int DEFAULT '0',
  `saldo_importo` decimal(10,2) DEFAULT '0.00',
  `partecipanti` json DEFAULT NULL,
  `data_inizio` date DEFAULT NULL,
  `data_consegna_prevista` date DEFAULT NULL,
  `data_consegna_effettiva` date DEFAULT NULL,
  `data_pagamento` date DEFAULT NULL,
  `distribuzione_effettuata` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `colore_tag` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#FFFFFF'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `progetti_checklist`
--

CREATE TABLE `progetti_checklist` (
  `id` int NOT NULL,
  `progetto_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipologia` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `checklist_data` json NOT NULL,
  `linguaggio_sito` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ultimo_salvataggio` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `progetto_documenti`
--

CREATE TABLE `progetto_documenti` (
  `id` int NOT NULL,
  `progetto_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nome_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` int DEFAULT NULL,
  `mime_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tipo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'generico',
  `uploaded_by` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `uploaded_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `scadenze`
--

CREATE TABLE `scadenze` (
  `id` int NOT NULL,
  `titolo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_scadenza` date NOT NULL,
  `tipologia_id` int DEFAULT NULL,
  `descrizione` text COLLATE utf8mb4_unicode_ci,
  `user_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cliente_id` int DEFAULT NULL,
  `link` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stato` enum('aperta','completata','scaduta') COLLATE utf8mb4_unicode_ci DEFAULT 'aperta',
  `creato_il` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `aggiornato_il` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `scadenze_tipologie`
--

CREATE TABLE `scadenze_tipologie` (
  `id` int NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `colore` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT '#64748b',
  `creato_il` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `task`
--

CREATE TABLE `task` (
  `id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `progetto_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `titolo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descrizione` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `immagine` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `colore` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assegnato_a` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assegnati` json DEFAULT NULL,
  `scadenza` datetime DEFAULT NULL,
  `stato` enum('da_fare','in_corso','completato') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'da_fare',
  `priorita` enum('bassa','media','alta') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'media',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `task_allegati`
--

CREATE TABLE `task_allegati` (
  `id` int NOT NULL,
  `task_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` int DEFAULT NULL,
  `uploaded_by` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `task_commenti`
--

CREATE TABLE `task_commenti` (
  `id` int NOT NULL,
  `task_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `utente_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `commento` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `task_visualizzazioni`
--

CREATE TABLE `task_visualizzazioni` (
  `id` int NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `progetto_id` varchar(50) NOT NULL,
  `last_viewed` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `timeline`
--

CREATE TABLE `timeline` (
  `id` int NOT NULL,
  `utente_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `azione` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entita_tipo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entita_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dettagli` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `auto_delete_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `transazioni_economiche`
--

CREATE TABLE `transazioni_economiche` (
  `id` int NOT NULL,
  `progetto_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` enum('cassa','wallet') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `utente_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `importo` decimal(10,2) DEFAULT NULL,
  `percentuale` int DEFAULT NULL,
  `data` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `descrizione` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wallet_saldo` decimal(10,2) DEFAULT '0.00',
  `colore` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#3B82F6',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_access_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `google_refresh_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `google_token_expires` datetime DEFAULT NULL,
  `google_calendar_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_connected` tinyint(1) DEFAULT '0',
  `last_login` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `guida_vista` tinyint(1) DEFAULT '0',
  `guida_data` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id`, `nome`, `password`, `email`, `wallet_saldo`, `colore`, `avatar`, `google_access_token`, `google_refresh_token`, `google_token_expires`, `google_calendar_id`, `google_connected`, `last_login`, `is_active`, `created_at`, `guida_vista`, `guida_data`) VALUES
('uxs46wroi3714', 'Lorenzo Ferrarini', 'Lorenzo2026!', NULL, 0.00, '#0891B2', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, CURRENT_TIMESTAMP, 0, NULL);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `vista_progetti_riepilogo`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `vista_progetti_riepilogo` (
`id` varchar(20)
,`titolo` varchar(255)
,`cliente_id` varchar(20)
,`descrizione` text
,`tipologie` json
,`prezzo_totale` decimal(10,2)
,`stato_progetto` enum('da_iniziare','in_corso','completato','consegnato','archiviato')
,`stato_pagamento` enum('da_pagare','da_pagare_acconto','acconto_pagato','da_saldare','cat','pagamento_completato')
,`acconto_importo` decimal(10,2)
,`saldo_importo` decimal(10,2)
,`partecipanti` json
,`data_inizio` date
,`data_consegna_prevista` date
,`data_consegna_effettiva` date
,`data_pagamento` date
,`distribuzione_effettuata` tinyint(1)
,`created_at` timestamp
,`updated_at` timestamp
,`created_by` varchar(20)
,`cliente_nome` varchar(255)
,`num_task_totali` bigint
,`num_task_completati` decimal(23,0)
);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `vista_task_dettagli`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `vista_task_dettagli` (
`id` varchar(20)
,`progetto_id` varchar(20)
,`titolo` varchar(255)
,`descrizione` text
,`assegnato_a` varchar(20)
,`scadenza` datetime
,`stato` enum('da_fare','in_corso','completato')
,`priorita` enum('bassa','media','alta')
,`created_at` timestamp
,`completed_at` timestamp
,`created_by` varchar(20)
,`assegnato_nome` varchar(255)
,`assegnato_colore` varchar(7)
,`progetto_titolo` varchar(255)
);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `appuntamenti`
--
ALTER TABLE `appuntamenti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `progetto_id` (`progetto_id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_appuntamenti_data` (`data_inizio`),
  ADD KEY `idx_appuntamenti_utente` (`utente_id`),
  ADD KEY `idx_google_event_id` (`google_event_id`),
  ADD KEY `idx_google_sync_status` (`google_sync_status`);

--
-- Indici per le tabelle `briefing_conversations`
--
ALTER TABLE `briefing_conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indici per le tabelle `clienti`
--
ALTER TABLE `clienti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indici per le tabelle `codici_ateco`
--
ALTER TABLE `codici_ateco`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_codice` (`codice`);

--
-- Indici per le tabelle `contabilita_mensile`
--
ALTER TABLE `contabilita_mensile`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_mese_anno` (`mese`,`anno`),
  ADD KEY `idx_anno_mese` (`anno`,`mese`);

--
-- Indici per le tabelle `cronologia_calcoli_tasse`
--
ALTER TABLE `cronologia_calcoli_tasse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indici per le tabelle `impostazioni`
--
ALTER TABLE `impostazioni`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chiave` (`chiave`);

--
-- Indici per le tabelle `listini`
--
ALTER TABLE `listini`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `listini_servizi`
--
ALTER TABLE `listini_servizi`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `listino_categorie`
--
ALTER TABLE `listino_categorie`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `listino_voci`
--
ALTER TABLE `listino_voci`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_listino_categoria` (`categoria_id`),
  ADD KEY `idx_listino_attivo` (`attivo`);

--
-- Indici per le tabelle `notifiche`
--
ALTER TABLE `notifiche`
  ADD PRIMARY KEY (`id`),
  ADD KEY `creato_da` (`creato_da`),
  ADD KEY `idx_notifiche_utente` (`utente_destinatario`,`letta`),
  ADD KEY `idx_notifiche_data` (`data_creazione` DESC),
  ADD KEY `idx_notifiche_entita` (`entita_tipo`,`entita_id`),
  ADD KEY `idx_notifiche_progetto` (`progetto_id`);

--
-- Indici per le tabelle `preventivi_salvati`
--
ALTER TABLE `preventivi_salvati`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `preventivo_template_burocrazia`
--
ALTER TABLE `preventivo_template_burocrazia`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `preventivo_template_condizioni`
--
ALTER TABLE `preventivo_template_condizioni`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `progetti`
--
ALTER TABLE `progetti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_progetti_cliente` (`cliente_id`),
  ADD KEY `idx_progetti_stato` (`stato_progetto`);

--
-- Indici per le tabelle `progetti_checklist`
--
ALTER TABLE `progetti_checklist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_progetto_tipologia` (`progetto_id`,`tipologia`);

--
-- Indici per le tabelle `progetto_documenti`
--
ALTER TABLE `progetto_documenti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `progetto_id` (`progetto_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indici per le tabelle `scadenze`
--
ALTER TABLE `scadenze`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_data_scadenza` (`data_scadenza`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_stato` (`stato`);

--
-- Indici per le tabelle `scadenze_tipologie`
--
ALTER TABLE `scadenze_tipologie`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_nome` (`nome`);

--
-- Indici per le tabelle `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_task_progetto` (`progetto_id`),
  ADD KEY `idx_task_assegnato` (`assegnato_a`),
  ADD KEY `idx_task_scadenza` (`scadenza`);

--
-- Indici per le tabelle `task_allegati`
--
ALTER TABLE `task_allegati`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indici per le tabelle `task_commenti`
--
ALTER TABLE `task_commenti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_commenti_task` (`task_id`),
  ADD KEY `idx_commenti_utente` (`utente_id`);

--
-- Indici per le tabelle `task_visualizzazioni`
--
ALTER TABLE `task_visualizzazioni`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_view` (`user_id`,`progetto_id`),
  ADD KEY `idx_progetto` (`progetto_id`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indici per le tabelle `timeline`
--
ALTER TABLE `timeline`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_timeline_utente` (`utente_id`),
  ADD KEY `idx_timeline_auto_delete` (`auto_delete_date`);

--
-- Indici per le tabelle `transazioni_economiche`
--
ALTER TABLE `transazioni_economiche`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_transazioni_progetto` (`progetto_id`),
  ADD KEY `idx_transazioni_utente` (`utente_id`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `briefing_conversations`
--
ALTER TABLE `briefing_conversations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `codici_ateco`
--
ALTER TABLE `codici_ateco`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `contabilita_mensile`
--
ALTER TABLE `contabilita_mensile`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `cronologia_calcoli_tasse`
--
ALTER TABLE `cronologia_calcoli_tasse`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `impostazioni`
--
ALTER TABLE `impostazioni`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `listini_servizi`
--
ALTER TABLE `listini_servizi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `listino_categorie`
--
ALTER TABLE `listino_categorie`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `listino_voci`
--
ALTER TABLE `listino_voci`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `preventivi_salvati`
--
ALTER TABLE `preventivi_salvati`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `preventivo_template_burocrazia`
--
ALTER TABLE `preventivo_template_burocrazia`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `preventivo_template_condizioni`
--
ALTER TABLE `preventivo_template_condizioni`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `progetti_checklist`
--
ALTER TABLE `progetti_checklist`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `progetto_documenti`
--
ALTER TABLE `progetto_documenti`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `scadenze`
--
ALTER TABLE `scadenze`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `scadenze_tipologie`
--
ALTER TABLE `scadenze_tipologie`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `task_allegati`
--
ALTER TABLE `task_allegati`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `task_commenti`
--
ALTER TABLE `task_commenti`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `task_visualizzazioni`
--
ALTER TABLE `task_visualizzazioni`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `timeline`
--
ALTER TABLE `timeline`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `transazioni_economiche`
--
ALTER TABLE `transazioni_economiche`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

--
-- Struttura per vista `vista_progetti_riepilogo`
--
DROP TABLE IF EXISTS `vista_progetti_riepilogo`;

CREATE VIEW `vista_progetti_riepilogo`  AS SELECT `p`.`id` AS `id`, `p`.`titolo` AS `titolo`, `p`.`cliente_id` AS `cliente_id`, `p`.`descrizione` AS `descrizione`, `p`.`tipologie` AS `tipologie`, `p`.`prezzo_totale` AS `prezzo_totale`, `p`.`stato_progetto` AS `stato_progetto`, `p`.`stato_pagamento` AS `stato_pagamento`, `p`.`acconto_importo` AS `acconto_importo`, `p`.`saldo_importo` AS `saldo_importo`, `p`.`partecipanti` AS `partecipanti`, `p`.`data_inizio` AS `data_inizio`, `p`.`data_consegna_prevista` AS `data_consegna_prevista`, `p`.`data_consegna_effettiva` AS `data_consegna_effettiva`, `p`.`data_pagamento` AS `data_pagamento`, `p`.`distribuzione_effettuata` AS `distribuzione_effettuata`, `p`.`created_at` AS `created_at`, `p`.`updated_at` AS `updated_at`, `p`.`created_by` AS `created_by`, `c`.`ragione_sociale` AS `cliente_nome`, count(`t`.`id`) AS `num_task_totali`, sum((case when (`t`.`stato` = 'completato') then 1 else 0 end)) AS `num_task_completati` FROM ((`progetti` `p` left join `clienti` `c` on((`p`.`cliente_id` = `c`.`id`))) left join `task` `t` on((`p`.`id` = `t`.`progetto_id`))) GROUP BY `p`.`id` ;

-- --------------------------------------------------------

--
-- Struttura per vista `vista_task_dettagli`
--
DROP TABLE IF EXISTS `vista_task_dettagli`;

CREATE VIEW `vista_task_dettagli`  AS SELECT `t`.`id` AS `id`, `t`.`progetto_id` AS `progetto_id`, `t`.`titolo` AS `titolo`, `t`.`descrizione` AS `descrizione`, `t`.`assegnato_a` AS `assegnato_a`, `t`.`scadenza` AS `scadenza`, `t`.`stato` AS `stato`, `t`.`priorita` AS `priorita`, `t`.`created_at` AS `created_at`, `t`.`completed_at` AS `completed_at`, `t`.`created_by` AS `created_by`, `u`.`nome` AS `assegnato_nome`, `u`.`colore` AS `assegnato_colore`, `p`.`titolo` AS `progetto_titolo` FROM ((`task` `t` left join `utenti` `u` on((`t`.`assegnato_a` = `u`.`id`))) left join `progetti` `p` on((`t`.`progetto_id` = `p`.`id`))) ;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `appuntamenti`
--
ALTER TABLE `appuntamenti`
  ADD CONSTRAINT `appuntamenti_ibfk_1` FOREIGN KEY (`progetto_id`) REFERENCES `progetti` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appuntamenti_ibfk_2` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appuntamenti_ibfk_3` FOREIGN KEY (`utente_id`) REFERENCES `utenti` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `appuntamenti_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `utenti` (`id`) ON DELETE SET NULL;

--
-- Limiti per la tabella `clienti`
--
ALTER TABLE `clienti`
  ADD CONSTRAINT `clienti_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `utenti` (`id`) ON DELETE SET NULL;

--
-- Limiti per la tabella `listino_voci`
--
ALTER TABLE `listino_voci`
  ADD CONSTRAINT `listino_voci_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `listino_categorie` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `notifiche`
--
ALTER TABLE `notifiche`
  ADD CONSTRAINT `notifiche_ibfk_1` FOREIGN KEY (`utente_destinatario`) REFERENCES `utenti` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifiche_ibfk_2` FOREIGN KEY (`creato_da`) REFERENCES `utenti` (`id`) ON DELETE SET NULL;

--
-- Limiti per la tabella `progetti`
--
ALTER TABLE `progetti`
  ADD CONSTRAINT `progetti_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clienti` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `progetti_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `utenti` (`id`) ON DELETE SET NULL;

--
-- Limiti per la tabella `progetti_checklist`
--
ALTER TABLE `progetti_checklist`
  ADD CONSTRAINT `progetti_checklist_ibfk_1` FOREIGN KEY (`progetto_id`) REFERENCES `progetti` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `progetto_documenti`
--
ALTER TABLE `progetto_documenti`
  ADD CONSTRAINT `progetto_documenti_ibfk_1` FOREIGN KEY (`progetto_id`) REFERENCES `progetti` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `progetto_documenti_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `utenti` (`id`) ON DELETE SET NULL;

--
-- Limiti per la tabella `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `task_ibfk_1` FOREIGN KEY (`progetto_id`) REFERENCES `progetti` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_ibfk_2` FOREIGN KEY (`assegnato_a`) REFERENCES `utenti` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `task_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `utenti` (`id`) ON DELETE SET NULL;

--
-- Limiti per la tabella `task_allegati`
--
ALTER TABLE `task_allegati`
  ADD CONSTRAINT `task_allegati_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_allegati_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `utenti` (`id`) ON DELETE SET NULL;

--
-- Limiti per la tabella `task_commenti`
--
ALTER TABLE `task_commenti`
  ADD CONSTRAINT `task_commenti_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_commenti_ibfk_2` FOREIGN KEY (`utente_id`) REFERENCES `utenti` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `timeline`
--
ALTER TABLE `timeline`
  ADD CONSTRAINT `timeline_ibfk_1` FOREIGN KEY (`utente_id`) REFERENCES `utenti` (`id`) ON DELETE SET NULL;

--
-- Limiti per la tabella `transazioni_economiche`
--
ALTER TABLE `transazioni_economiche`
  ADD CONSTRAINT `transazioni_economiche_ibfk_1` FOREIGN KEY (`progetto_id`) REFERENCES `progetti` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transazioni_economiche_ibfk_2` FOREIGN KEY (`utente_id`) REFERENCES `utenti` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
