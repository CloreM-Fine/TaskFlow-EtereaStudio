<?php
/**
 * TaskFlow
 * Onboarding - Guida introduttiva per nuovi utenti
 */

// Configurazione sessione sicura
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
           (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
ini_set('session.cookie_secure', $isHttps ? 1 : 0);
ini_set('session.cookie_samesite', 'Lax');

session_start();

// Verifica autenticazione
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/includes/functions.php';

$userId = $_SESSION['user_id'];
$userName = $_SESSION['user_name'] ?? 'Utente';

// Verifica se guida già vista - se sì, redirect a dashboard
try {
    $stmt = $pdo->prepare("SELECT guidavista FROM utenti WHERE id = ?");
    $stmt->execute([$userId]);
    $guidavista = $stmt->fetchColumn();
    
    if ($guidavista == 1) {
        header('Location: dashboard.php');
        exit;
    }
} catch (PDOException $e) {
    // Se c'è un errore, proseguiamo comunque con l'onboarding
    error_log("Errore verifica guida vista: " . $e->getMessage());
}

$pageTitle = 'Benvenuto su TaskFlow';

// Ottieni logo personalizzato
try {
    $stmt = $pdo->prepare("SELECT valore FROM impostazioni WHERE chiave = 'logo_gestionale'");
    $stmt->execute();
    $logoNavbar = $stmt->fetchColumn() ?: '';
} catch (PDOException $e) {
    $logoNavbar = '';
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicons/favicon-16x16.png">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '#0891B2',
                        'primary-dark': '#0E7490',
                        'primary-light': '#22D3EE',
                        secondary: '#1E293B',
                    },
                    animation: {
                        'float': 'float 3s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'slide-in-right': 'slideInRight 0.5s ease-out',
                        'slide-in-left': 'slideInLeft 0.5s ease-out',
                        'scale-in': 'scaleIn 0.5s ease-out',
                        'rocket': 'rocket 2s ease-in-out infinite',
                        'rocket-launch': 'rocketLaunch 5s ease-in-out forwards',
                        'progress': 'progress 5s linear forwards',
                        'stars': 'stars 2s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        slideInRight: {
                            '0%': { opacity: '0', transform: 'translateX(50px)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' },
                        },
                        slideInLeft: {
                            '0%': { opacity: '0', transform: 'translateX(-50px)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' },
                        },
                        scaleIn: {
                            '0%': { opacity: '0', transform: 'scale(0.8)' },
                            '100%': { opacity: '1', transform: 'scale(1)' },
                        },
                        rocket: {
                            '0%, 100%': { transform: 'translateY(0) rotate(-5deg)' },
                            '50%': { transform: 'translateY(-15px) rotate(5deg)' },
                        },
                        rocketLaunch: {
                            '0%': { transform: 'translateY(0) scale(1)' },
                            '20%': { transform: 'translateY(-20px) scale(1.1)' },
                            '100%': { transform: 'translateY(-100vh) scale(0.8)', opacity: '0' },
                        },
                        progress: {
                            '0%': { width: '0%' },
                            '100%': { width: '100%' },
                        },
                        stars: {
                            '0%, 100%': { opacity: '0.3', transform: 'scale(1)' },
                            '50%': { opacity: '1', transform: 'scale(1.2)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Stili Onboarding -->
    <link rel="stylesheet" href="assets/css/onboarding.css">
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen overflow-hidden">
    
    <!-- Container principale -->
    <div id="onboarding-container" class="relative min-h-screen flex flex-col">
        
        <!-- Header con logo -->
        <header class="absolute top-0 left-0 right-0 z-20 p-6">
            <div class="flex items-center justify-center">
                <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md rounded-2xl px-5 py-3 border border-white/10">
                    <?php if ($logoNavbar): ?>
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center overflow-hidden bg-white">
                            <img src="assets/uploads/logo/<?php echo htmlspecialchars($logoNavbar); ?>" 
                                 alt="Logo" class="w-full h-full object-contain p-1">
                        </div>
                    <?php else: ?>
                        <div class="w-10 h-10 bg-gradient-to-br from-cyan-400 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                    <span class="text-white font-bold text-lg">TaskFlow</span>
                </div>
            </div>
        </header>

        <!-- Schermate Carousel -->
        <main class="flex-1 flex items-center justify-center relative">
            
            <!-- Schermata 1: Benvenuto -->
            <div id="slide-1" class="onboarding-slide absolute inset-0 flex flex-col items-center justify-center p-6 transition-all duration-500">
                <div class="w-full max-w-md mx-auto text-center">
                    <!-- Immagine/Illustrazione -->
                    <div class="relative mb-8">
                        <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/20 to-purple-500/20 rounded-full blur-3xl transform scale-150"></div>
                        <div class="relative bg-gradient-to-br from-slate-800/80 to-slate-900/80 backdrop-blur-xl rounded-3xl p-8 border border-white/10 shadow-2xl animate-scale-in">
                            <div class="w-40 h-40 mx-auto bg-gradient-to-br from-cyan-400 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg animate-float">
                                <span class="text-7xl">👋</span>
                            </div>
                        </div>
                        <!-- Elementi decorativi -->
                        <div class="absolute -top-4 -right-4 w-8 h-8 bg-yellow-400 rounded-full animate-pulse-slow"></div>
                        <div class="absolute -bottom-2 -left-6 w-6 h-6 bg-cyan-400 rounded-full animate-pulse-slow" style="animation-delay: 0.5s;"></div>
                        <div class="absolute top-1/2 -right-8 w-4 h-4 bg-purple-400 rounded-full animate-pulse-slow" style="animation-delay: 1s;"></div>
                    </div>
                    
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-4 animate-slide-up">
                        Benvenuto su <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-400">TaskFlow</span>
                    </h1>
                    <p class="text-slate-300 text-lg mb-2 animate-slide-up" style="animation-delay: 0.1s;">
                        Il tuo studio organizzato
                    </p>
                    <p class="text-slate-400 text-base animate-slide-up" style="animation-delay: 0.2s;">
                        Gestisci progetti, clienti e finanze in un'unica piattaforma potente e intuitiva.
                    </p>
                </div>
            </div>

            <!-- Schermata 2: Progetti -->
            <div id="slide-2" class="onboarding-slide absolute inset-0 flex flex-col items-center justify-center p-6 transition-all duration-500 opacity-0 translate-x-full pointer-events-none">
                <div class="w-full max-w-md mx-auto text-center">
                    <div class="relative mb-8">
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/20 to-cyan-500/20 rounded-full blur-3xl transform scale-150"></div>
                        <div class="relative bg-gradient-to-br from-slate-800/80 to-slate-900/80 backdrop-blur-xl rounded-3xl p-8 border border-white/10 shadow-2xl">
                            <div class="w-40 h-40 mx-auto bg-gradient-to-br from-emerald-400 to-cyan-600 rounded-2xl flex items-center justify-center shadow-lg animate-float">
                                <span class="text-7xl">📁</span>
                            </div>
                        </div>
                        <!-- Elementi decorativi -->
                        <div class="absolute top-0 -left-4 w-8 h-8 bg-emerald-400 rounded-lg rotate-12 animate-pulse-slow"></div>
                        <div class="absolute -bottom-4 right-0 w-6 h-6 bg-cyan-400 rounded-lg -rotate-12 animate-pulse-slow" style="animation-delay: 0.5s;"></div>
                    </div>
                    
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                        Gestisci i tuoi <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-400">progetti</span>
                    </h2>
                    <p class="text-slate-400 text-base">
                        Organizza i tuoi lavori, traccia lo stato di avanzamento e collabora con il tuo team in tempo reale.
                    </p>
                </div>
            </div>

            <!-- Schermata 3: Finanze -->
            <div id="slide-3" class="onboarding-slide absolute inset-0 flex flex-col items-center justify-center p-6 transition-all duration-500 opacity-0 translate-x-full pointer-events-none">
                <div class="w-full max-w-md mx-auto text-center">
                    <div class="relative mb-8">
                        <div class="absolute inset-0 bg-gradient-to-r from-amber-500/20 to-rose-500/20 rounded-full blur-3xl transform scale-150"></div>
                        <div class="relative bg-gradient-to-br from-slate-800/80 to-slate-900/80 backdrop-blur-xl rounded-3xl p-8 border border-white/10 shadow-2xl">
                            <div class="w-40 h-40 mx-auto bg-gradient-to-br from-amber-400 to-rose-600 rounded-2xl flex items-center justify-center shadow-lg animate-float">
                                <span class="text-7xl">💰</span>
                            </div>
                        </div>
                        <!-- Elementi decorativi -->
                        <div class="absolute -top-2 right-4 w-8 h-8 bg-amber-400 rounded-full animate-pulse-slow"></div>
                        <div class="absolute bottom-4 -left-6 w-6 h-6 bg-rose-400 rounded-full animate-pulse-slow" style="animation-delay: 0.5s;"></div>
                        <div class="absolute top-1/3 -right-6 w-5 h-5 bg-yellow-400 rounded-full animate-pulse-slow" style="animation-delay: 1s;"></div>
                    </div>
                    
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                        Tieni sotto controllo le <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-rose-400">finanze</span>
                    </h2>
                    <p class="text-slate-400 text-base">
                        Monitora cassa e wallet, gestisci pagamenti e tieni traccia delle tue entrate in modo semplice.
                    </p>
                </div>
            </div>

            <!-- Schermata 4: Pronto -->
            <div id="slide-4" class="onboarding-slide absolute inset-0 flex flex-col items-center justify-center p-6 transition-all duration-500 opacity-0 translate-x-full pointer-events-none">
                <div class="w-full max-w-md mx-auto text-center">
                    <div class="relative mb-8">
                        <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/20 to-purple-500/20 rounded-full blur-3xl transform scale-150"></div>
                        <div class="relative bg-gradient-to-br from-slate-800/80 to-slate-900/80 backdrop-blur-xl rounded-3xl p-8 border border-white/10 shadow-2xl">
                            <div class="w-40 h-40 mx-auto bg-gradient-to-br from-cyan-400 via-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg animate-float">
                                <span class="text-7xl">🚀</span>
                            </div>
                        </div>
                        <!-- Elementi decorativi stelle -->
                        <div class="absolute top-0 left-1/4 text-yellow-400 text-xl animate-stars">✦</div>
                        <div class="absolute top-1/4 right-0 text-cyan-400 text-lg animate-stars" style="animation-delay: 0.3s;">✦</div>
                        <div class="absolute bottom-1/4 left-0 text-purple-400 text-lg animate-stars" style="animation-delay: 0.6s;">✦</div>
                        <div class="absolute bottom-0 right-1/4 text-pink-400 text-xl animate-stars" style="animation-delay: 0.9s;">✦</div>
                    </div>
                    
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                        Sei <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-400">pronto</span> per iniziare!
                    </h2>
                    <p class="text-slate-400 text-base mb-8">
                        Inizia a usare TaskFlow e porta il tuo studio al livello successivo.
                    </p>
                    
                    <button id="btn-inizia" class="group relative inline-flex items-center justify-center px-8 py-4 font-bold text-white transition-all duration-200 bg-gradient-to-r from-cyan-500 to-purple-600 rounded-2xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 hover:shadow-lg hover:shadow-cyan-500/25 hover:-translate-y-1 active:translate-y-0">
                        <span class="mr-2 text-lg">Inizia</span>
                        <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Schermata Caricamento -->
            <div id="slide-loading" class="onboarding-slide absolute inset-0 flex flex-col items-center justify-center p-6 transition-all duration-500 opacity-0 pointer-events-none">
                <div class="w-full max-w-md mx-auto text-center">
                    <!-- Animazione Razzo -->
                    <div class="relative mb-12">
                        <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/30 to-purple-500/30 rounded-full blur-3xl transform scale-150"></div>
                        
                        <!-- Razzo -->
                        <div id="rocket" class="relative z-10 text-8xl animate-rocket">
                            🚀
                        </div>
                        
                        <!-- Scia del razzo -->
                        <div id="rocket-trail" class="absolute top-full left-1/2 -translate-x-1/2 w-2 h-20 bg-gradient-to-b from-cyan-400 to-transparent rounded-full opacity-0 transition-opacity duration-300"></div>
                    </div>
                    
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-4">
                        Stiamo preparando tutto!
                    </h2>
                    <p class="text-slate-400 text-base mb-8">
                        Configurazione del tuo workspace in corso...
                    </p>
                    
                    <!-- Progress Bar -->
                    <div class="w-full max-w-xs mx-auto">
                        <div class="h-2 bg-slate-700 rounded-full overflow-hidden">
                            <div id="loading-progress" class="h-full bg-gradient-to-r from-cyan-400 to-purple-500 rounded-full" style="width: 0%;"></div>
                        </div>
                        <p id="loading-text" class="text-slate-500 text-sm mt-3">0%</p>
                    </div>
                </div>
            </div>

        </main>

        <!-- Footer con indicatori e controlli -->
        <footer id="onboarding-footer" class="relative z-20 p-6 pb-8">
            <div class="max-w-md mx-auto">
                <!-- Indicatori di progresso -->
                <div class="flex items-center justify-center gap-3 mb-8">
                    <button class="indicator-dot w-3 h-3 rounded-full bg-cyan-400 transition-all duration-300" data-slide="1"></button>
                    <button class="indicator-dot w-3 h-3 rounded-full bg-slate-600 transition-all duration-300" data-slide="2"></button>
                    <button class="indicator-dot w-3 h-3 rounded-full bg-slate-600 transition-all duration-300" data-slide="3"></button>
                    <button class="indicator-dot w-3 h-3 rounded-full bg-slate-600 transition-all duration-300" data-slide="4"></button>
                </div>

                <!-- Bottoni navigazione -->
                <div class="flex items-center justify-between">
                    <button id="btn-skip" class="text-slate-400 hover:text-white transition-colors text-sm font-medium px-4 py-2">
                        Salta
                    </button>
                    
                    <div class="flex items-center gap-3">
                        <button id="btn-prev" class="w-12 h-12 rounded-full bg-slate-700/50 hover:bg-slate-600 text-white flex items-center justify-center transition-all disabled:opacity-30 disabled:cursor-not-allowed" disabled>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        
                        <button id="btn-next" class="w-12 h-12 rounded-full bg-gradient-to-r from-cyan-500 to-cyan-600 hover:from-cyan-400 hover:to-cyan-500 text-white flex items-center justify-center transition-all shadow-lg shadow-cyan-500/25">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </footer>

    </div>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo generateCsrfToken(); ?>">
    
    <!-- Script Onboarding -->
    <script src="assets/js/onboarding.js"></script>
</body>
</html>
