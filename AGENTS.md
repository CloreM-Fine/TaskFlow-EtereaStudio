# TaskFlow - AI Agent Documentation

## Project Overview

**TaskFlow** è un sistema ERP (Enterprise Resource Planning) completo per la gestione di progetti, clienti, preventivi, finanze, task, calendario, briefing AI e tassazione, con supporto per la distribuzione economica automatica dei profitti tra i membri del team.

**URL di produzione**: https://taskflow.it  
**Versione**: 1.0.0  
**Linguaggio**: Italiano (tutti i testi, commenti e documentazione)

---

## Technology Stack

### Backend
- **PHP 8.x** - Linguaggio principale (nessun framework, vanilla PHP)
- **MySQL 8.x / MariaDB** - Database relazionale
- **PDO** - Database abstraction layer con prepared statements
- **Sessioni PHP** - Gestione autenticazione con sicurezza avanzata

### Frontend
- **Tailwind CSS** (via CDN) - Framework CSS utility-first
- **Vanilla JavaScript** - Nessun framework JS (ES6+)
- **Font Inter** (Google Fonts) - Tipografia principale
- **SVG inline** - Icone (nessuna libreria icone esterna)

### Librerie di Terze Parti
- **DOMPDF** (`vendor/dompdf/`) - Generazione PDF
- **HTML5 Parser** (`vendor/masterminds/`) - Parsing HTML5 per DOMPDF
- **PHP Font Lib** (`vendor/phenx/`) - Gestione font per DOMPDF

### Infrastruttura
- **Apache 2.4+** con `mod_rewrite` (Pretty URLs)
- **SiteGround** - Hosting di produzione
- **HTTPS obbligatorio** (redirect automatico in .htaccess)
- **GitHub Actions** - Deployment automatico via FTP

---

## Directory Structure

```
/Users/lorenzopuccetti/Lavoro/Eterea Studio/TaskFlow/
├── api/                          # Endpoint API REST
│   ├── auth.php                 # Login/logout/sessione
│   ├── clienti.php              # CRUD clienti
│   ├── progetti.php             # CRUD progetti
│   ├── task.php                 # Gestione task
│   ├── calendario.php           # Eventi calendario
│   ├── finanze.php              # Transazioni economiche
│   ├── preventivi.php           # Gestione preventivi
│   ├── briefing_ai.php          # API briefing
│   ├── briefing_pdf.php         # Generazione PDF briefing
│   ├── checklist_controllo.php  # Checklist progetti
│   ├── scadenze.php             # Gestione scadenze
│   ├── tasse.php                # Gestione tasse
│   ├── contabilita.php          # Contabilità mensile
│   ├── notifiche.php            # Sistema notifiche
│   ├── timeline.php             # Log attività
│   ├── upload.php               # Upload file
│   └── ...
├── includes/                     # File PHP condivisi
│   ├── config.php               # Configurazione DB e costanti
│   ├── functions.php            # Funzioni utility globali
│   ├── functions_security.php   # Funzioni sicurezza avanzate
│   ├── auth_check.php           # Verifica autenticazione pagine
│   ├── auth.php                 # Verifica autenticazione API
│   ├── env_loader.php           # Loader file .env
│   ├── header.php               # Header HTML comune
│   └── footer.php               # Footer HTML comune
├── config/                       # Configurazioni specifiche
│   ├── openai.config.php        # API key OpenAI
│   ├── database.php             # Configurazione database legacy
│   └── *.sql                    # Script SQL setup
├── assets/                       # Asset statici
│   ├── css/                     # Fogli di stile Tailwind
│   ├── js/                      # JavaScript (app.js, components.js)
│   ├── uploads/                 # File caricati dagli utenti
│   │   ├── clienti/             # Loghi clienti
│   │   ├── progetti/            # Documenti progetti
│   │   ├── avatars/             # Avatar utenti
│   │   └── temp/                # File temporanei
│   └── favicons/                # Favicon del sito
├── vendor/                       # Librerie di terze parti
│   ├── autoload.php             # Autoloader per DOMPDF
│   ├── dompdf/                  # Libreria PDF
│   ├── masterminds/             # HTML5 Parser
│   └── phenx/                   # Font Lib
├── db/                           # Database dumps
│   └── db4qhf5gnmj3lz.sql       # Dump database completo
├── logs/                         # Log di sistema
├── .github/workflows/            # CI/CD GitHub Actions
│   └── deploy.yml               # Workflow deployment FTP
├── *.php                         # Pagine principali dell'applicazione
├── .htaccess                     # Configurazione Apache
├── .env                          # Variabili d'ambiente (NON committare!)
├── .env.example                  # Template .env
├── .user.ini                     # Configurazione PHP (SiteGround)
└── cron_pulizia.php              # Script cron per pulizia dati
```

---

## Main Pages

| File | Descrizione | Linee | Protezione |
|------|-------------|-------|------------|
| `index.php` | Pagina di login | 294 | Pubblica |
| `dashboard.php` | Dashboard con statistiche | 1,017 | Autenticata |
| `progetti.php` | Lista progetti | 1,043 | Autenticata |
| `progetto_dettaglio.php` | Dettaglio singolo progetto | 2,335 | Autenticata |
| `clienti.php` | Gestione anagrafica clienti | 845 | Autenticata |
| `preventivi.php` | Creazione/gestione preventivi | 1,903 | Autenticata |
| `listini.php` | Gestione listini prezzi | 12 | Autenticata |
| `finanze.php` | Gestione economica e wallet | 642 | Autenticata |
| `calendario.php` | Calendario appuntamenti | 807 | Autenticata |
| `scadenze.php` | Gestione scadenze | 709 | Autenticata |
| `tasse.php` | Gestione tasse e calcoli | 721 | Password aggiuntiva |
| `briefing_ai.php` | Form per briefing progetti | 545 | Autenticata |
| `impostazioni.php` | Configurazione sistema | 2,626 | Autenticata |

---

## Database Schema

### Tabelle Principali

- **utenti** - Utenti del sistema (3 record fissi)
- **clienti** - Anagrafica clienti (privati/aziende/partite IVA)
- **progetti** - Progetti con stato, partecipanti JSON, budget
- **task** - Task/attività associate ai progetti
- **appuntamenti** - Eventi calendario con supporto Google Calendar
- **preventivi_salvati** - Preventivi generati
- **preventivi_categorie** - Categorie per preventivi
- **preventivi_voci** - Voci/voci di listino
- **transazioni_economiche** - Movimenti economici (wallet/cassa)
- **timeline** - Log attività (auto-delete dopo 15 giorni)
- **notifiche** - Sistema notifiche
- **impostazioni** - Configurazioni variabili (key-value)
- **progetto_documenti** - File allegati ai progetti
- **progetti_checklist** - Checklist di controllo per progetti
- **briefing_conversations** - Conversazioni briefing AI
- **codici_ateco** - Codici ATECO per calcolo tasse
- **cronologia_calcoli_tasse** - Storico calcoli fiscali
- **contabilita_mensile** - Riepiloghi contabili mensili

### Relazioni Chiave

```
utenti (3 record fissi)
  ├── progetti (via partecipanti JSON)
  ├── task (via assegnato_a)
  ├── appuntamenti (via utente_id)
  └── transazioni_economiche (wallet)

clienti
  └── progetti (1:N via cliente_id)

progetti
  ├── task (1:N)
  ├── appuntamenti (1:N)
  ├── progetto_documenti (1:N)
  ├── progetti_checklist (1:1 per tipologia)
  └── transazioni_economiche (1:N)
```

### ID Utenti Fissi

```php
USERS = [
    'ucwurog3xr8tf' => ['nome' => 'Lorenzo Puccetti', 'colore' => '#0891B2'],
    'ukl9ipuolsebn' => ['nome' => 'Daniele Giuliani', 'colore' => '#10B981'],
    'u3ghz4f2lnpkx' => ['nome' => 'Edmir Likaj', 'colore' => '#F59E0B']
];
```

---

## Coding Conventions

### PHP
- **Namespace**: Nessun namespace (codice procedurale)
- **Funzioni**: `camelCase` per funzioni private, `PascalCase` per funzioni principali
- **Variabili**: `snake_case` per variabili database, `camelCase` per variabili locali
- **Costanti**: `MAIUSCOLE_CON_UNDERSCORE`
- **Commenti**: DocBlock PHPDoc per funzioni principali (in italiano)

### Pattern Comuni

#### Connessione Database
```php
require_once __DIR__ . '/includes/functions.php';  // Include anche config.php
global $pdo;  // Connessione PDO disponibile
```

#### Verifica Autenticazione (Pagine)
```php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth_check.php';  // Redirect a login se non autenticato
```

#### API Endpoint
```php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth_check.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';
switch ($action) {
    case 'create': createEntity(); break;
    case 'update': updateEntity(); break;
    case 'delete': deleteEntity(); break;
    default: jsonResponse(false, null, 'Azione non valida');
}
```

#### Risposta JSON Standard
```php
function jsonResponse(bool $success, $data = null, string $message = ''): void {
    header('Content-Type: application/json');
    echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
    exit;
}
```

### Frontend
- **Classi Tailwind**: Utility-first, nessun CSS custom se non necessario
- **ID Elementi**: `camelCase` (es. `progettoModal`, `searchInput`)
- **Funzioni JS**: `camelCase`, spesso globali per onclick inline

---

## Key Functions (functions.php)

| Funzione | Descrizione |
|----------|-------------|
| `isLoggedIn(): bool` | Verifica se l'utente è autenticato |
| `isAdmin(): bool` | Verifica se l'utente è admin |
| `requireAuth(): void` | Termina con 401 se non autenticato |
| `currentUserId(): string` | Restituisce ID utente corrente |
| `generateId(): string` | Genera ID univoco formato `u[hash]` |
| `generateEntityId($prefix): string` | Genera ID con prefisso (t=task, p=progetto, a=appuntamento) |
| `e($text): string` | Escape HTML per output (XSS protection) |
| `sanitizeInput($input): string` | Sanitizza input utente |
| `generateCsrfToken(): string` | Genera token CSRF |
| `verifyCsrfToken($token): bool` | Verifica token CSRF |
| `jsonResponse($success, $data, $message)` | Formatta risposta JSON |
| `logTimeline(...)` | Scrive nel log attività |
| `calcolaDistribuzione($totale, $partecipanti)` | Calcola split economico progetto |
| `eseguiDistribuzione(...)` | Esegue transazioni wallet |
| `formatCurrency($amount): string` | Formatta importo in € |
| `formatDate($date): string` | Formatta data in italiano |
| `formatDateTime($datetime): string` | Formatta datetime (UTC → Europe/Rome) |
| `uploadFileSecure(...)` | Upload file con validazione sicurezza |
| `getDashboardStats($utenteId): array` | Statistiche per dashboard |
| `checkScadenza($scadenza, $giorni): string` | Verifica se data è scaduta/in scadenza |
| `creaNotifica(...)` | Crea notifica nel database |

---

## Security Functions (functions_security.php)

| Funzione | Descrizione |
|----------|-------------|
| `checkRateLimit($action, $max, $window): bool` | Rate limiting file-based |
| `blockIp($ip, $minutes)` | Blocca IP temporaneamente |
| `isIpBlocked($ip): bool` | Verifica se IP è bloccato |
| `generateCsrfTokenSecure(): string` | Token CSRF hardened |
| `verifyCsrfTokenSecure($token): bool` | Verifica CSRF con timing safe |
| `requireCsrfToken(): void` | Middleware CSRF per API |
| `isSafeFilename($filename): bool` | Verifica sicurezza nome file |
| `uploadFileSecure(...): array|false` | Upload con validazione completa |
| `securityLog($event, $context)` | Log sicuro (maschera dati sensibili) |
| `validateInput($input, $type, $options)` | Validazione input tipizzata |
| `validateRequired($data, $fields): bool` | Verifica campi obbligatori |

---

## Configuration

### Environment Variables (.env)

```bash
# Database
DB_HOST=localhost
DB_NAME=db4qhf5gnmj3lz
DB_USER=ucwurog3xr8tf
DB_PASS=********

# Sicurezza
TASSE_PASSWORD_HASH=$2y$10$...      # Hash bcrypt per accesso sezione tasse
CSRF_SECRET_KEY=...                  # Chiave segreta CSRF
ENCRYPTION_KEY=...                   # Chiave crittografia

# API Esterne
OPENAI_API_KEY=sk-...                # API key OpenAI (opzionale)

# Configurazione App
APP_ENV=production                   # production | development
BASE_URL=https://taskflow.it
APP_DEBUG=false

# Sessione
SESSION_LIFETIME=7200                # 2 ore
COOKIE_LIFETIME=2592000              # 30 giorni
MAX_LOGIN_ATTEMPTS=20
LOGIN_LOCKOUT_MINUTES=5

# Upload
MAX_UPLOAD_SIZE_MB=10
ALLOWED_UPLOAD_TYPES=application/pdf,image/jpeg,image/png,image/webp,image/svg+xml
```

### Stati e Costanti (config.php)

```php
// Tipologie progetto
TIPOLOGIE_PROGETTO = ['Sito Web', 'Grafica', 'Video', 'Social Media', 'Branding', 'SEO', 'Fotografia', 'Altro']

// Stati progetto
STATI_PROGETTO = [
    'da_iniziare' => 'Da Iniziare',
    'in_corso' => 'In Corso',
    'completato' => 'Completato',
    'consegnato' => 'Consegnato',
    'archiviato' => 'Archiviato'
]

// Stati pagamento
STATI_PAGAMENTO = [
    'da_pagare' => 'Da Pagare',
    'da_pagare_acconto' => 'Da Pagare Acconto',
    'acconto_pagato' => 'Acconto Pagato',
    'da_saldare' => 'Da Saldare',
    'pagamento_completato' => 'Pagamento Completato'
]
```

---

## Security Considerations

### Implementazioni Attive

1. **Password hashing** con `password_hash()` (BCRYPT)
2. **CSRF token** su form e API (verify con `hash_equals`)
3. **Sessioni sicure**: 
   - httponly, secure flag, samesite=Lax
   - Durata: 30 giorni
   - Regenerazione ID ogni 30 minuti
   - Binding IP/User Agent (soft check)
4. **XSS protection** via `htmlspecialchars()` su output
5. **SQL Injection prevention** tramite PDO prepared statements
6. **File upload validation**:
   - MIME type verification con `finfo`
   - Estensioni pericolose bloccate
   - Nomi file randomizzati
   - Permessi 0640 su file caricati
7. **Header sicurezza**:
   - X-Frame-Options: DENY
   - X-Content-Type-Options: nosniff
   - X-XSS-Protection: 1; mode=block
   - CSP (Content Security Policy)
   - HSTS (HTTPS Strict Transport Security)
8. **HTTPS redirect** forzato
9. **Directory protection** via `.htaccess`
10. **Rate limiting**: 20 tentativi ogni 5 minuti, blocco 5 minuti
11. **Security logging**: Log su `logs/security-YYYY-MM-DD.log`

### File Sensibili Protetti (via .htaccess)
- `.env`, `.env.example`
- `*.sql`, `*.log`, `*.ini`
- `includes/` (deny from all)
- `logs/` (deny from all)
- File backup (`*.bak`, `*.backup`, `*.old`)

---

## Build & Deployment

### Nessun Processo di Build
L'applicazione **non richiede build**: è PHP puro con Tailwind CSS caricato via CDN.

### Deployment Automatico (GitHub Actions)

Il progetto è configurato per deployment automatico su SiteGround:

**File**: `.github/workflows/deploy.yml`

**Trigger**: Push su branch `main` o workflow_dispatch manuale

**Secrets richiesti**:
- `FTP_SERVER` - Server FTP SiteGround
- `FTP_USERNAME` - Username FTP
- `FTP_PASSWORD` - Password FTP

**File esclusi dal deploy**:
```
**/.git*/**
**/.git*
**/.github/**
**/README.md
**/.gitignore
**/*.sql
**/aggiorna_password.php
**/.env
**/.env.example
**/assets/temp/**
**/.DS_Store
```

### Deploy Manuale (se necessario)
1. Upload file via FTP/SFTP su hosting Apache
2. Verificare permessi directory `assets/uploads/` (755)
3. Configurare variabili in `.env`
4. Importare database se necessario

---

## Testing

Il progetto **non ha test automatizzati**. Il testing avviene manualmente:

1. **Testing funzionale**: verifica flussi utente principali
2. **Testing API**: chiamate manuali agli endpoint
3. **Testing sicurezza**: verifica autenticazione e autorizzazioni
4. **Testing cross-browser**: compatibilità principali browser

### File di Test Esistenti
- `test_simple.php` - Test connessione base
- `test_database.php` - Test connessione database
- `test_api.php` - Test endpoint API
- `test_scadenze.php` - Test sistema scadenze

---

## Cron Jobs

### Pulizia Timeline
**File**: `cron_pulizia.php`

**Uso via CLI**:
```bash
php /path/to/cron_pulizia.php
```

**Uso via web**:
```
https://taskflow.it/cron_pulizia.php?key=ldetimeline2026
```

**Funzione**: Elimina record `timeline` con `auto_delete_date < CURDATE()`

---

## API Endpoints

Tutti gli endpoint si trovano in `/api/` e restituiscono JSON.

| Endpoint | Azioni |
|----------|--------|
| `auth.php` | `login`, `logout`, `check` |
| `clienti.php` | `list`, `detail`, `create`, `update`, `delete`, `search` |
| `progetti.php` | `list`, `detail`, `create`, `update`, `delete`, `stats`, `distribuzione` |
| `task.php` | `create`, `update`, `delete`, `list`, `toggle_status` |
| `calendario.php` | `events`, `create`, `update`, `delete` |
| `finanze.php` | Transazioni wallet e cassa |
| `preventivi.php` | CRUD preventivi e categorie |
| `briefing_ai.php` | `save_to_project`, `get_by_project` |
| `briefing_pdf.php` | `generate` - Genera PDF da conversazione |
| `checklist_controllo.php` | CRUD checklist progetti |
| `scadenze.php` | Gestione scadenze, `count_oggi` |
| `tasse.php` | Calcolo tasse, storico |
| `contabilita.php` | Riepiloghi mensili |
| `impostazioni.php` | Gestione impostazioni sistema |
| `notifiche.php` | `list`, `count`, `mark_read`, `mark_all_read`, `delete_all` |
| `timeline.php` | Log attività |
| `upload.php` | Upload file generico |

---

## JavaScript Application

### Namespace Globale: `LDEApp`

**File**: `assets/js/app.js`

```javascript
// Funzioni globali esposte
window.LDEApp        // Namespace applicazione
window.showToast(message, type)     // Notifiche toast
window.openModal(modalId)           // Apri modal
window.closeModal(modalId)          // Chiudi modal
window.updateScadenzeBadge()        // Aggiorna badge scadenze
```

### Metodi principali LDEApp
- `init()` - Inizializzazione
- `showToast(message, type)` - Toast notification
- `openModal(modalId)`, `closeModal(modalId)` - Gestione modali
- `formatCurrency(amount)` - Formatta €
- `formatDate(dateString)` - Formatta data
- `debounce(func, wait)`, `throttle(func, limit)` - Utility
- `get(url)`, `post(url, data)` - API helpers

---

## Distribuzione Economica (Profit Sharing)

Il sistema implementa profit sharing automatico per i progetti completati:

| Partecipanti Attivi | Distribuzione |
|---------------------|---------------|
| 3 utenti | ~30% ciascuno + 10% cassa |
| 2 utenti | ~40% ciascuno attivo + 10% al terzo + 10% cassa |
| 1 utente | 70% attivo + 10% ciascun altro + 10% cassa |

Le transazioni vengono registrate in `transazioni_economiche` e i saldi utente in `utenti.wallet_saldo`.

**Funzione**: `calcolaDistribuzione($totale, $partecipantiIds)` in `functions.php`

---

## Briefing AI

Il sistema supporta la creazione di briefing progetti tramite:
- **Conversazione testuale** salvata in `briefing_conversations`
- **Trascrizione audio** (OpenAI Whisper API)
- **Generazione PDF** con DOMPDF

**File correlati**:
- `briefing_ai.php` - Interfaccia utente
- `api/briefing_ai.php` - API salvataggio
- `api/briefing_pdf.php` - Generazione PDF

---

## Notes per Sviluppo

### Quando Modificare
- **Pagine UI**: Modificare file `.php` root
- **API Backend**: Modificare file in `/api/`
- **Funzioni comuni**: Modificare `includes/functions.php`
- **Configurazione**: Modificare `.env` (non committare!) o `includes/config.php`
- **Sicurezza**: Modificare `includes/functions_security.php`
- **Stili CSS**: Aggiornare `assets/css/tailwind.min.css` o usare CDN
- **JavaScript**: Modificare `assets/js/app.js` o `components.js`

### Attenzione
- Mantenere compatibilità con SiteGround (PHP 8.x)
- Non rimuovere protezioni CSRF/XSS esistenti
- Verificare sempre prepared statements per query dinamiche
- Testare upload file su ambiente di staging
- Non esporre credenziali DB in repository pubblici
- Non committare mai il file `.env`

### Dipendenze Esterne (CDN)
- **Tailwind CSS**: `https://cdn.tailwindcss.com`
- **Font Inter**: Google Fonts
- **Icone**: SVG inline (nessuna libreria icone esterna)

---

## Troubleshooting

### Problemi comuni

**Sessione scaduta rapidamente**:
- Verificare `SESSION_LIFETIME` in `.env`
- Controllare configurazione PHP in `.user.ini`

**Upload file fallisce**:
- Verificare `MAX_UPLOAD_SIZE_MB` e `post_max_size`
- Controllare permessi directory `assets/uploads/`

**Errori 500**:
- Controllare `logs/php_errors.log`
- Verificare che `.env` sia presente e leggibile

**Rate limiting troppo restrittivo**:
- Modificare `MAX_LOGIN_ATTEMPTS` e `LOGIN_LOCKOUT_MINUTES` in `.env`

---

## Contatti e Riferimenti

- **Progetto**: TaskFlow
- **URL**: https://taskflow.it
- **Hosting**: SiteGround
- **Linguaggio**: Italiano
- **Framework**: Nessuno (PHP vanilla)
