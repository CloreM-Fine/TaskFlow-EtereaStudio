<?php
/**
 * TaskFlow - Onboarding Iniziale
 * 4 schermate con design coerente all'app
 */

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth_check.php';

$pageTitle = 'Benvenuto';

// Se guida già vista, redirect a dashboard
try {
    $stmt = $pdo->prepare("SELECT guida_vista FROM utenti WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    if ($stmt->fetchColumn() == 1) {
        header('Location: dashboard.php');
        exit;
    }
} catch (Exception $e) {
    // Se il campo non esiste, continua con l'onboarding
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>Benvenuto su TaskFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        
        .slide-container {
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .slide {
            min-width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .slide-image {
            width: 280px;
            height: 280px;
            object-fit: cover;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(8, 145, 178, 0.25);
            border: 4px solid white;
        }
        
        .rocket-container {
            position: fixed;
            bottom: -100px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 100;
            transition: all 0.3s ease;
        }
        
        .rocket-container.launching {
            animation: rocketLaunch 5s ease-in forwards;
        }
        
        @keyframes rocketLaunch {
            0% {
                bottom: -100px;
                transform: translateX(-50%) rotate(-5deg);
            }
            10% {
                bottom: 20%;
                transform: translateX(-50%) rotate(0deg);
            }
            100% {
                bottom: 120%;
                transform: translateX(-50%) rotate(5deg);
            }
        }
        
        .rocket {
            font-size: 80px;
            filter: drop-shadow(0 10px 20px rgba(8, 145, 178, 0.4));
        }
        
        .rocket-flame {
            position: absolute;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 50px;
            background: linear-gradient(to bottom, #f59e0b, #ef4444, transparent);
            border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .rocket-container.launching .rocket-flame {
            opacity: 1;
            animation: flame 0.2s infinite alternate;
        }
        
        @keyframes flame {
            from { transform: translateX(-50%) scaleY(1); }
            to { transform: translateX(-50%) scaleY(1.3); }
        }
        
        .stars {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            opacity: 0;
            transition: opacity 1s;
        }
        
        .stars.active {
            opacity: 1;
        }
        
        .star {
            position: absolute;
            width: 2px;
            height: 2px;
            background: #0891b2;
            border-radius: 50%;
            animation: twinkle 2s infinite;
        }
        
        @keyframes twinkle {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 1; }
        }
        
        .loading-screen {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, #f8fafc 0%, #0891b2 100%);
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 50;
        }
        
        .loading-screen.active {
            display: flex;
        }
        
        .progress-bar {
            width: 200px;
            height: 4px;
            background: rgba(255,255,255,0.3);
            border-radius: 2px;
            overflow: hidden;
            margin-top: 20px;
        }
        
        .progress-fill {
            height: 100%;
            background: white;
            width: 0%;
            transition: width 5s linear;
        }
        
        .progress-fill.animate {
            width: 100%;
        }
        
        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #cbd5e1;
            transition: all 0.3s;
        }
        
        .dot.active {
            background: #0891b2;
            width: 24px;
            border-radius: 4px;
        }
    </style>
</head>
<body class="overflow-hidden">

    <!-- Stelle di sfondo (appare durante lancio) -->
    <div class="stars" id="stars"></div>

    <!-- Container slide -->
    <div class="slide-container flex" id="slideContainer">
        
        <!-- Slide 1: Benvenuto -->
        <div class="slide">
            <div class="text-center max-w-md">
                <div class="mb-8 relative">
                    <div class="absolute inset-0 bg-cyan-400/20 rounded-full blur-3xl transform scale-150"></div>
                    <img src="assets/guida/home.png" alt="Dashboard" class="slide-image relative">
                </div>
                <h1 class="text-3xl font-bold text-slate-800 mb-4">
                    Benvenuto su <span class="text-cyan-600">TaskFlow</span>
                </h1>
                <p class="text-slate-600 text-lg leading-relaxed">
                    Il gestionale completo per il tuo studio creativo. Organizza progetti, clienti e finanze in un'unica piattaforma.
                </p>
            </div>
        </div>

        <!-- Slide 2: Progetti -->
        <div class="slide">
            <div class="text-center max-w-md">
                <div class="mb-8 relative">
                    <div class="absolute inset-0 bg-emerald-400/20 rounded-full blur-3xl transform scale-150"></div>
                    <img src="assets/guida/progetto.png" alt="Nuovo Progetto" class="slide-image relative">
                </div>
                <h1 class="text-3xl font-bold text-slate-800 mb-4">
                    Gestisci i tuoi <span class="text-emerald-600">Progetti</span>
                </h1>
                <p class="text-slate-600 text-lg leading-relaxed">
                    Crea e organizza i tuoi lavori, traccia lo stato di avanzamento e collabora con il tuo team in tempo reale.
                </p>
            </div>
        </div>

        <!-- Slide 3: Finanze -->
        <div class="slide">
            <div class="text-center max-w-md">
                <div class="mb-8 relative">
                    <div class="absolute inset-0 bg-amber-400/20 rounded-full blur-3xl transform scale-150"></div>
                    <img src="assets/guida/tasse.png" alt="Finanze" class="slide-image relative">
                </div>
                <h1 class="text-3xl font-bold text-slate-800 mb-4">
                    Controlla le <span class="text-amber-600">Finanze</span>
                </h1>
                <p class="text-slate-600 text-lg leading-relaxed">
                    Monitora cassa e wallet, gestisci pagamenti e tieni traccia delle tue entrate in modo semplice e intuitivo.
                </p>
            </div>
        </div>

        <!-- Slide 4: Pronto -->
        <div class="slide">
            <div class="text-center max-w-md">
                <div class="mb-8">
                    <div class="w-40 h-40 mx-auto bg-gradient-to-br from-cyan-400 to-purple-500 rounded-3xl flex items-center justify-center shadow-2xl">
                        <span class="text-6xl">🚀</span>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-slate-800 mb-4">
                    Sei <span class="text-cyan-600">pronto</span>!
                </h1>
                <p class="text-slate-600 text-lg mb-8">
                    Inizia a usare TaskFlow e porta il tuo studio al livello successivo.
                </p>
                <button onclick="startApp()" class="px-8 py-4 bg-cyan-600 hover:bg-cyan-700 text-white font-semibold rounded-xl shadow-lg shadow-cyan-600/30 transition-all transform hover:scale-105 active:scale-95">
                    Inizia ora →
                </button>
            </div>
        </div>
    </div>

    <!-- Razzo che parte -->
    <div class="rocket-container" id="rocket">
        <div class="rocket">🚀</div>
        <div class="rocket-flame"></div>
    </div>

    <!-- Schermata caricamento -->
    <div class="loading-screen" id="loadingScreen">
        <h2 class="text-3xl font-bold text-white mb-4">Stiamo preparando tutto!</h2>
        <div class="progress-bar">
            <div class="progress-fill" id="progressFill"></div>
        </div>
    </div>

    <!-- Navigation dots -->
    <div class="fixed bottom-8 left-1/2 transform -translate-x-1/2 flex items-center gap-2 z-40">
        <button class="dot active" onclick="goToSlide(0)"></button>
        <button class="dot" onclick="goToSlide(1)"></button>
        <button class="dot" onclick="goToSlide(2)"></button>
        <button class="dot" onclick="goToSlide(3)"></button>
    </div>

    <!-- Skip button -->
    <button onclick="skipOnboarding()" class="fixed top-4 right-4 text-slate-400 hover:text-slate-600 text-sm font-medium z-50 px-4 py-2 rounded-lg hover:bg-slate-100 transition-colors">
        Salta
    </button>

    <script>
        let currentSlide = 0;
        const totalSlides = 4;
        const container = document.getElementById('slideContainer');
        const dots = document.querySelectorAll('.dot');
        
        // Touch/swipe support
        let touchStartX = 0;
        let touchEndX = 0;
        
        document.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        });
        
        document.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });
        
        function handleSwipe() {
            const diff = touchStartX - touchEndX;
            if (Math.abs(diff) > 50) {
                if (diff > 0 && currentSlide < totalSlides - 1) {
                    nextSlide();
                } else if (diff < 0 && currentSlide > 0) {
                    prevSlide();
                }
            }
        }
        
        function updateSlides() {
            container.style.transform = `translateX(-${currentSlide * 100}vw)`;
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === currentSlide);
            });
        }
        
        function nextSlide() {
            if (currentSlide < totalSlides - 1) {
                currentSlide++;
                updateSlides();
            }
        }
        
        function prevSlide() {
            if (currentSlide > 0) {
                currentSlide--;
                updateSlides();
            }
        }
        
        function goToSlide(index) {
            currentSlide = index;
            updateSlides();
        }
        
        // Keyboard navigation
        document.addEventListener('keydown', e => {
            if (e.key === 'ArrowRight') nextSlide();
            if (e.key === 'ArrowLeft') prevSlide();
            if (e.key === 'Escape') skipOnboarding();
        });
        
        async function startApp() {
            // Nascondi slide
            container.style.opacity = '0';
            
            // Mostra stelle
            document.getElementById('stars').classList.add('active');
            createStars();
            
            // Lancia razzo
            const rocket = document.getElementById('rocket');
            rocket.classList.add('launching');
            
            // Mostra schermata caricamento dopo 1s
            setTimeout(() => {
                document.getElementById('loadingScreen').classList.add('active');
                document.getElementById('progressFill').classList.add('animate');
            }, 1000);
            
            // Salva stato guida
            try {
                await fetch('api/guida.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'action=mark_guida'
                });
            } catch (e) {
                console.log('API non disponibile, continuo...');
            }
            
            // Redirect dopo 5 secondi
            setTimeout(() => {
                localStorage.setItem('taskflow_guida_da_onboarding', 'true');
                window.location.href = 'dashboard.php?guida=true';
            }, 5000);
        }
        
        function skipOnboarding() {
            localStorage.setItem('taskflow_guida_da_onboarding', 'true');
            window.location.href = 'dashboard.php?guida=true';
        }
        
        function createStars() {
            const starsContainer = document.getElementById('stars');
            for (let i = 0; i < 50; i++) {
                const star = document.createElement('div');
                star.className = 'star';
                star.style.left = Math.random() * 100 + '%';
                star.style.top = Math.random() * 100 + '%';
                star.style.animationDelay = Math.random() * 2 + 's';
                starsContainer.appendChild(star);
            }
        }
    </script>

</body>
</html>
