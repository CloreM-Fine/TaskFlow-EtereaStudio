<?php
/**
 * TaskFlow - Onboarding Iniziale
 * 4 schermate con design coerente all'app
 * Best practice UX applicate:
 * - Massimo 3-4 step
 * - Progress indicator visibile (numerico + dots)
 * - Skip sempre disponibile
 * - CTA chiara alla fine
 * - Animazioni fluide ma non invasive
 * - Mobile responsive
 * - Navigazione avanti/indietro esplicita
 * - Accessibilità migliorata
 */

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth_check.php';

$pageTitle = 'Benvenuto';

// Se guida già vista, redirect a dashboard
try {
    $stmt = $pdo->prepare("SELECT guidavista FROM utenti WHERE id = ?");
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
        
        @media (max-width: 640px) {
            .slide-image {
                width: 220px;
                height: 220px;
            }
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
            filter: drop-shadow(0 10px 30px rgba(8, 145, 178, 0.5));
            animation: rocketFloat 2s ease-in-out infinite;
        }
        
        @keyframes rocketFloat {
            0%, 100% { transform: translateY(0) rotate(-2deg); }
            50% { transform: translateY(-8px) rotate(2deg); }
        }
        
        .rocket-container.launching .rocket {
            animation: none;
        }
        
        .rocket-flame {
            position: absolute;
            bottom: -40px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 60px;
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .rocket-flame::before,
        .rocket-flame::after {
            content: '';
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
        }
        
        .rocket-flame::before {
            bottom: 0;
            width: 30px;
            height: 50px;
            background: linear-gradient(to bottom, #fbbf24, #f59e0b, #ef4444, transparent);
            filter: blur(2px);
        }
        
        .rocket-flame::after {
            bottom: 5px;
            width: 20px;
            height: 35px;
            background: linear-gradient(to bottom, #fff, #fcd34d, #f59e0b, transparent);
            filter: blur(1px);
        }
        
        .rocket-container.launching .rocket-flame {
            opacity: 1;
            animation: flameFlicker 0.15s infinite alternate;
        }
        
        @keyframes flameFlicker {
            0% { transform: translateX(-50%) scaleY(1) scaleX(1); }
            100% { transform: translateX(-50%) scaleY(1.4) scaleX(0.9); }
        }
        
        /* Particelle scintille */
        .spark {
            position: absolute;
            width: 4px;
            height: 4px;
            background: #fbbf24;
            border-radius: 50%;
            opacity: 0;
        }
        
        .rocket-container.launching .spark {
            animation: sparkFly 0.5s infinite;
        }
        
        @keyframes sparkFly {
            0% { 
                opacity: 1; 
                transform: translateY(0) scale(1); 
            }
            100% { 
                opacity: 0; 
                transform: translateY(60px) scale(0.5); 
            }
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
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0891b2 100%);
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 50;
            overflow: hidden;
        }
        
        .loading-screen.active {
            display: flex;
        }
        
        /* Background animato con stelle che si muovono */
        .loading-screen::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: 
                radial-gradient(2px 2px at 20px 30px, white, transparent),
                radial-gradient(2px 2px at 40px 70px, rgba(255,255,255,0.8), transparent),
                radial-gradient(1px 1px at 90px 40px, white, transparent),
                radial-gradient(2px 2px at 160px 120px, rgba(255,255,255,0.9), transparent),
                radial-gradient(1px 1px at 230px 80px, white, transparent),
                radial-gradient(2px 2px at 300px 150px, rgba(255,255,255,0.8), transparent),
                radial-gradient(1px 1px at 400px 60px, white, transparent),
                radial-gradient(2px 2px at 500px 200px, rgba(255,255,255,0.9), transparent);
            background-size: 550px 250px;
            animation: starsMove 20s linear infinite;
            opacity: 0.6;
        }
        
        @keyframes starsMove {
            from { transform: translateY(0); }
            to { transform: translateY(-250px); }
        }
        
        .loading-content {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 2rem;
        }
        
        .loading-logo {
            font-size: 4rem;
            margin-bottom: 1rem;
            animation: float 3s ease-in-out infinite;
            filter: drop-shadow(0 0 30px rgba(8, 145, 178, 0.6));
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .loading-title {
            font-size: 2rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        
        .loading-subtitle {
            font-size: 1rem;
            color: rgba(255,255,255,0.8);
            margin-bottom: 2rem;
        }
        
        .loading-pulse {
            display: inline-flex;
            gap: 8px;
            margin-bottom: 2rem;
        }
        
        .loading-pulse span {
            width: 12px;
            height: 12px;
            background: linear-gradient(135deg, #22d3ee, #0891b2);
            border-radius: 50%;
            animation: pulse 1.4s ease-in-out infinite both;
            box-shadow: 0 0 10px rgba(34, 211, 238, 0.5);
        }
        
        .loading-pulse span:nth-child(1) { animation-delay: -0.32s; }
        .loading-pulse span:nth-child(2) { animation-delay: -0.16s; }
        .loading-pulse span:nth-child(3) { animation-delay: 0s; }
        
        @keyframes pulse {
            0%, 80%, 100% { transform: scale(0.6); opacity: 0.5; }
            40% { transform: scale(1); opacity: 1; }
        }
        
        .progress-container {
            width: 280px;
            max-width: 80vw;
        }
        
        .progress-bar {
            width: 100%;
            height: 8px;
            background: rgba(255,255,255,0.15);
            border-radius: 4px;
            overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #22d3ee, #0891b2, #06b6d4);
            width: 0%;
            transition: width 5s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 4px;
            box-shadow: 0 0 20px rgba(34, 211, 238, 0.6);
            position: relative;
        }
        
        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 30px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4));
            animation: shimmer 1.5s infinite;
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-30px); }
            100% { transform: translateX(30px); }
        }
        
        .progress-fill.animate {
            width: 100%;
        }
        
        .progress-text {
            display: flex;
            justify-content: space-between;
            margin-top: 0.75rem;
            font-size: 0.875rem;
            color: rgba(255,255,255,0.7);
        }
        
        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #cbd5e1;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .dot:hover {
            background: #94a3b8;
            transform: scale(1.2);
        }
        
        .dot.active {
            background: #0891b2;
            width: 24px;
            border-radius: 4px;
        }
        
        /* Progress indicator numerico */
        .progress-indicator {
            position: fixed;
            top: 24px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            color: #475569;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            z-index: 50;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .progress-indicator span {
            color: #0891b2;
        }
        
        /* Navigazione avanti/indietro */
        .nav-button {
            position: fixed;
            top: 50%;
            transform: translateY(-50%);
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            z-index: 40;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .nav-button:hover {
            background: white;
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        
        .nav-button:active {
            transform: translateY(-50%) scale(0.95);
        }
        
        .nav-button.prev {
            left: 24px;
        }
        
        .nav-button.next {
            right: 24px;
        }
        
        .nav-button:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            transform: translateY(-50%);
        }
        
        .nav-button:disabled:hover {
            transform: translateY(-50%);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        @media (max-width: 640px) {
            .nav-button {
                display: none;
            }
            
            .progress-indicator {
                top: 16px;
                padding: 6px 12px;
                font-size: 12px;
            }
        }
        
        /* Animazione pulse sul CTA finale */
        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 4px 12px rgba(8, 145, 178, 0.3);
            }
            50% {
                box-shadow: 0 4px 24px rgba(8, 145, 178, 0.5);
            }
        }
        
        .cta-button {
            animation: pulse-glow 2s infinite;
        }
        
        /* Focus states per accessibilità */
        button:focus-visible,
        .nav-button:focus-visible,
        .dot:focus-visible {
            outline: 2px solid #0891b2;
            outline-offset: 2px;
        }
    </style>
</head>
<body class="overflow-hidden">

    <!-- Progress indicator numerico -->
    <div class="progress-indicator" id="progressIndicator" aria-label="Progresso onboarding">
        <span id="currentStepNum">1</span> di 4
    </div>

    <!-- Stelle di sfondo (appare durante lancio) -->
    <div class="stars" id="stars" aria-hidden="true"></div>

    <!-- Container slide -->
    <div class="slide-container flex" id="slideContainer" role="region" aria-label="Onboarding TaskFlow">
        
        <!-- Slide 1: Benvenuto -->
        <div class="slide" role="group" aria-label="Slide 1 di 4">
            <div class="text-center max-w-md">
                <div class="mb-8 relative">
                    <div class="absolute inset-0 bg-cyan-400/20 rounded-full blur-3xl transform scale-150"></div>
                    <img src="assets/guida/home.png" alt="Dashboard TaskFlow" class="slide-image relative mx-auto" loading="eager">
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
        <div class="slide" role="group" aria-label="Slide 2 di 4">
            <div class="text-center max-w-md">
                <div class="mb-8 relative">
                    <div class="absolute inset-0 bg-emerald-400/20 rounded-full blur-3xl transform scale-150"></div>
                    <img src="assets/guida/progetto.png" alt="Gestione progetti" class="slide-image relative mx-auto" loading="lazy">
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
        <div class="slide" role="group" aria-label="Slide 3 di 4">
            <div class="text-center max-w-md">
                <div class="mb-8 relative">
                    <div class="absolute inset-0 bg-amber-400/20 rounded-full blur-3xl transform scale-150"></div>
                    <img src="assets/guida/tasse.png" alt="Controllo finanze" class="slide-image relative mx-auto" loading="lazy">
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
        <div class="slide" role="group" aria-label="Slide 4 di 4">
            <div class="text-center max-w-md">
                <div class="mb-8">
                    <div class="w-40 h-40 mx-auto bg-gradient-to-br from-cyan-400 to-purple-500 rounded-3xl flex items-center justify-center shadow-2xl">
                        <span class="text-6xl" role="img" aria-label="Razzo">🚀</span>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-slate-800 mb-4">
                    Sei <span class="text-cyan-600">pronto</span>!
                </h1>
                <p class="text-slate-600 text-lg mb-8">
                    Inizia a usare TaskFlow e porta il tuo studio al livello successivo.
                </p>
                <button onclick="startApp()" class="cta-button px-8 py-4 bg-cyan-600 hover:bg-cyan-700 text-white font-semibold rounded-xl shadow-lg transition-all transform hover:scale-105 active:scale-95" aria-label="Inizia ad usare TaskFlow">
                    Inizia ora →
                </button>
            </div>
        </div>
    </div>

    <!-- Razzo che parte -->
    <div class="rocket-container" id="rocket" aria-hidden="true">
        <div class="rocket">🚀</div>
        <div class="rocket-flame">
            <div class="spark" style="left: 10px; animation-delay: 0s;"></div>
            <div class="spark" style="left: 20px; animation-delay: 0.1s;"></div>
            <div class="spark" style="left: 30px; animation-delay: 0.2s;"></div>
        </div>
    </div>

    <!-- Schermata caricamento -->
    <div class="loading-screen" id="loadingScreen" role="status" aria-label="Caricamento in corso">
        <div class="loading-content">
            <h2 class="loading-title">Stiamo preparando tutto!</h2>
            <p class="loading-subtitle">Configurazione del tuo spazio di lavoro...</p>
            
            <div class="loading-pulse">
                <span></span>
                <span></span>
                <span></span>
            </div>
            
            <div class="progress-container">
                <div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" id="loadingProgressBar">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
                <div class="progress-text">
                    <span>Caricamento</span>
                    <span id="progressPercent">0%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation dots -->
    <nav class="fixed bottom-8 left-1/2 transform -translate-x-1/2 flex items-center gap-2 z-40" aria-label="Navigazione slide">
        <button class="dot active" onclick="goToSlide(0)" aria-label="Vai alla slide 1" aria-current="step"></button>
        <button class="dot" onclick="goToSlide(1)" aria-label="Vai alla slide 2"></button>
        <button class="dot" onclick="goToSlide(2)" aria-label="Vai alla slide 3"></button>
        <button class="dot" onclick="goToSlide(3)" aria-label="Vai alla slide 4"></button>
    </nav>

    <!-- Navigazione avanti/indietro -->
    <button class="nav-button prev" id="prevBtn" onclick="prevSlide()" aria-label="Slide precedente" disabled>
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>
    <button class="nav-button next" id="nextBtn" onclick="nextSlide()" aria-label="Slide successiva">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
    </button>

    <!-- Skip button -->
    <button onclick="skipOnboarding()" class="fixed top-4 right-4 text-slate-400 hover:text-slate-600 text-sm font-medium z-50 px-4 py-2 rounded-lg hover:bg-slate-100 transition-colors" aria-label="Salta onboarding">
        Salta
    </button>

    <script>
        let currentSlide = 0;
        const totalSlides = 4;
        const container = document.getElementById('slideContainer');
        const dots = document.querySelectorAll('.dot');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const progressIndicator = document.getElementById('currentStepNum');
        
        // Touch/swipe support
        let touchStartX = 0;
        let touchEndX = 0;
        
        document.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        
        document.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, { passive: true });
        
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
                const isActive = i === currentSlide;
                dot.classList.toggle('active', isActive);
                dot.setAttribute('aria-current', isActive ? 'step' : 'false');
            });
            
            // Aggiorna progress indicator
            progressIndicator.textContent = currentSlide + 1;
            
            // Aggiorna stato bottoni navigazione
            prevBtn.disabled = currentSlide === 0;
            nextBtn.disabled = currentSlide === totalSlides - 1;
            
            // Aggiorma next button per ultima slide
            if (currentSlide === totalSlides - 1) {
                nextBtn.innerHTML = `
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                `;
                nextBtn.setAttribute('aria-label', 'Completa onboarding');
                nextBtn.onclick = startApp;
            } else {
                nextBtn.innerHTML = `
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                `;
                nextBtn.setAttribute('aria-label', 'Slide successiva');
                nextBtn.onclick = nextSlide;
            }
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
            if (index >= 0 && index < totalSlides) {
                currentSlide = index;
                updateSlides();
            }
        }
        
        // Keyboard navigation
        document.addEventListener('keydown', e => {
            if (e.key === 'ArrowRight') {
                if (currentSlide === totalSlides - 1) {
                    startApp();
                } else {
                    nextSlide();
                }
            }
            if (e.key === 'ArrowLeft') prevSlide();
            if (e.key === 'Escape') skipOnboarding();
        });
        
        async function startApp() {
            // Disabilita interazioni durante il caricamento
            document.body.style.pointerEvents = 'none';
            
            // Nascondi slide
            container.style.opacity = '0';
            
            // Mostra stelle
            document.getElementById('stars').classList.add('active');
            createStars();
            
            // Nascondi razzo e tutti gli elementi della slide
            const rocket = document.getElementById('rocket');
            if (rocket) rocket.remove();
            document.getElementById('progressIndicator').style.display = 'none';
            document.querySelector('.nav-button.prev').style.display = 'none';
            document.querySelector('.nav-button.next').style.display = 'none';
            document.querySelector('nav[aria-label="Navigazione slide"]').style.display = 'none';
            document.querySelector('button[onclick="skipOnboarding()"]').style.display = 'none';
            
            // Mostra schermata caricamento dopo 1s
            setTimeout(() => {
                document.getElementById('loadingScreen').classList.add('active');
                document.getElementById('progressFill').classList.add('animate');
                document.getElementById('loadingProgressBar').setAttribute('aria-valuenow', '100');
                
                // Anima la percentuale
                let percent = 0;
                const percentEl = document.getElementById('progressPercent');
                const interval = setInterval(() => {
                    percent += 2;
                    if (percentEl) percentEl.textContent = percent + '%';
                    if (percent >= 100) clearInterval(interval);
                }, 100);
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
                localStorage.setItem('taskflow_mostra_guida', 'true');
                window.location.href = 'dashboard.php?first=true';
            }, 5000);
        }
        
        async function skipOnboarding() {
            // Salva stato anche se skippato
            try {
                await fetch('api/guida.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'action=mark_guida'
                });
            } catch (e) {
                console.log('API non disponibile');
            }
            
            localStorage.setItem('taskflow_mostra_guida', 'true');
            window.location.href = 'dashboard.php?first=true';
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
        
        // Inizializza stato bottoni
        updateSlides();
    </script>

</body>
</html>
