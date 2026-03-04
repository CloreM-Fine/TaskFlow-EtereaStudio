/**
 * TaskFlow Onboarding
 * Gestione delle schermate introduttive con swipe e animazioni
 */

(function() {
    'use strict';

    // ==========================================
    // STATO
    // ==========================================
    const state = {
        currentSlide: 1,
        totalSlides: 4,
        isAnimating: false,
        touchStartX: 0,
        touchEndX: 0,
        loadingProgress: 0,
        csrfToken: ''
    };

    // ==========================================
    // ELEMENTI DOM
    // ==========================================
    let elements = {};

    // ==========================================
    // INIZIALIZZAZIONE
    // ==========================================
    function init() {
        // Recupera CSRF token
        const metaToken = document.querySelector('meta[name="csrf-token"]');
        state.csrfToken = metaToken ? metaToken.content : '';

        // Cache elementi DOM
        cacheElements();

        // Aggiungi event listeners
        setupEventListeners();

        // Inizializza prima slide
        updateSlide(1);

        console.log('🚀 TaskFlow Onboarding initialized');
    }

    function cacheElements() {
        elements = {
            slides: {
                1: document.getElementById('slide-1'),
                2: document.getElementById('slide-2'),
                3: document.getElementById('slide-3'),
                4: document.getElementById('slide-4'),
                loading: document.getElementById('slide-loading')
            },
            indicators: document.querySelectorAll('.indicator-dot'),
            btnNext: document.getElementById('btn-next'),
            btnPrev: document.getElementById('btn-prev'),
            btnSkip: document.getElementById('btn-skip'),
            btnInizia: document.getElementById('btn-inizia'),
            footer: document.getElementById('onboarding-footer'),
            rocket: document.getElementById('rocket'),
            rocketTrail: document.getElementById('rocket-trail'),
            loadingProgress: document.getElementById('loading-progress'),
            loadingText: document.getElementById('loading-text'),
            container: document.getElementById('onboarding-container')
        };
    }

    function setupEventListeners() {
        // Bottoni navigazione
        elements.btnNext.addEventListener('click', goNext);
        elements.btnPrev.addEventListener('click', goPrev);
        elements.btnSkip.addEventListener('click', skipOnboarding);
        elements.btnInizia.addEventListener('click', startLoading);

        // Indicatori
        elements.indicators.forEach(dot => {
            dot.addEventListener('click', () => {
                const slide = parseInt(dot.dataset.slide);
                if (slide !== state.currentSlide) {
                    goToSlide(slide);
                }
            });
        });

        // Touch/Swipe support
        setupSwipe();

        // Keyboard navigation
        document.addEventListener('keydown', handleKeyboard);
    }

    // ==========================================
    // NAVIGAZIONE SLIDE
    // ==========================================
    function goNext() {
        if (state.isAnimating || state.currentSlide >= state.totalSlides) return;
        goToSlide(state.currentSlide + 1);
    }

    function goPrev() {
        if (state.isAnimating || state.currentSlide <= 1) return;
        goToSlide(state.currentSlide - 1);
    }

    function goToSlide(slideNumber) {
        if (state.isAnimating || slideNumber < 1 || slideNumber > state.totalSlides) return;
        if (slideNumber === state.currentSlide) return;

        state.isAnimating = true;

        const direction = slideNumber > state.currentSlide ? 'next' : 'prev';
        const currentEl = elements.slides[state.currentSlide];
        const nextEl = elements.slides[slideNumber];

        // Rimuovi classi precedenti
        currentEl.classList.remove('active');
        currentEl.classList.add(direction === 'next' ? 'prev' : 'next');

        // Prepara nuova slide
        nextEl.classList.remove('prev', 'next');
        nextEl.classList.add('active');

        // Aggiorna stato
        state.currentSlide = slideNumber;
        updateUI();

        // Reset animazione flag
        setTimeout(() => {
            state.isAnimating = false;
        }, 500);
    }

    function updateSlide(slideNumber) {
        // Imposta slide iniziale senza animazione
        Object.values(elements.slides).forEach((slide, index) => {
            if (!slide) return;
            slide.classList.remove('active', 'prev', 'next');
            if (index + 1 === slideNumber) {
                slide.classList.add('active');
            } else if (index + 1 < slideNumber) {
                slide.classList.add('prev');
            } else {
                slide.classList.add('next');
            }
        });
        updateUI();
    }

    function updateUI() {
        // Aggiorna indicatori
        elements.indicators.forEach((dot, index) => {
            if (index + 1 === state.currentSlide) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });

        // Aggiorna bottoni
        elements.btnPrev.disabled = state.currentSlide === 1;
        
        // Cambia icona next su ultima slide
        if (state.currentSlide === state.totalSlides) {
            elements.btnNext.style.opacity = '0';
            elements.btnNext.style.pointerEvents = 'none';
        } else {
            elements.btnNext.style.opacity = '1';
            elements.btnNext.style.pointerEvents = 'auto';
        }
    }

    // ==========================================
    // GESTIONE SWIPE
    // ==========================================
    function setupSwipe() {
        const container = elements.container;

        container.addEventListener('touchstart', handleTouchStart, { passive: true });
        container.addEventListener('touchend', handleTouchEnd, { passive: true });
        container.addEventListener('touchmove', handleTouchMove, { passive: true });
    }

    function handleTouchStart(e) {
        state.touchStartX = e.changedTouches[0].screenX;
    }

    function handleTouchMove(e) {
        // Previene scroll orizzontale della pagina
        if (Math.abs(e.changedTouches[0].screenX - state.touchStartX) > 10) {
            e.preventDefault();
        }
    }

    function handleTouchEnd(e) {
        state.touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }

    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = state.touchStartX - state.touchEndX;

        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                // Swipe left -> next
                goNext();
            } else {
                // Swipe right -> prev
                goPrev();
            }
        }
    }

    // ==========================================
    // KEYBOARD NAVIGATION
    // ==========================================
    function handleKeyboard(e) {
        switch (e.key) {
            case 'ArrowRight':
            case 'ArrowDown':
            case ' ':
                e.preventDefault();
                if (state.currentSlide === state.totalSlides) {
                    startLoading();
                } else {
                    goNext();
                }
                break;
            case 'ArrowLeft':
            case 'ArrowUp':
                e.preventDefault();
                goPrev();
                break;
            case 'Escape':
                skipOnboarding();
                break;
            case 'Enter':
                if (state.currentSlide === state.totalSlides) {
                    startLoading();
                }
                break;
        }
    }

    // ==========================================
    // SALTA ONBOARDING
    // ==========================================
    function skipOnboarding() {
        if (confirm('Vuoi saltare la guida introduttiva?')) {
            startLoading();
        }
    }

    // ==========================================
    // SCHERMATA CARICAMENTO
    // ==========================================
    function startLoading() {
        state.isAnimating = true;

        // Nascondi footer
        elements.footer.style.transition = 'all 0.3s ease';
        elements.footer.style.opacity = '0';
        elements.footer.style.transform = 'translateY(20px)';
        elements.footer.style.pointerEvents = 'none';

        // Nascondi slide corrente
        const currentEl = elements.slides[state.currentSlide];
        currentEl.style.transition = 'opacity 0.3s ease';
        currentEl.style.opacity = '0';

        // Mostra schermata caricamento
        setTimeout(() => {
            elements.slides.loading.classList.remove('opacity-0', 'pointer-events-none');
            elements.slides.loading.classList.add('active');
            
            // Avvia animazioni
            startRocketAnimation();
            startProgressBar();
            
            // Marca guida come vista
            markGuidaVista();
            
            // Redirect dopo 5 secondi
            setTimeout(() => {
                redirectToDashboard();
            }, 5000);
        }, 300);
    }

    function startRocketAnimation() {
        // Aggiungi classe per animazione razzo
        elements.rocket.classList.add('launching');
        
        // Mostra scia dopo un attimo
        setTimeout(() => {
            elements.rocketTrail.classList.add('visible');
        }, 200);
    }

    function startProgressBar() {
        const duration = 5000; // 5 secondi
        const interval = 50; // update ogni 50ms
        const steps = duration / interval;
        let currentStep = 0;

        const updateProgress = () => {
            currentStep++;
            const progress = Math.min((currentStep / steps) * 100, 100);
            
            elements.loadingProgress.style.width = progress + '%';
            elements.loadingText.textContent = Math.round(progress) + '%';

            if (currentStep < steps) {
                setTimeout(updateProgress, interval);
            }
        };

        updateProgress();
    }

    // ==========================================
    // API - SEGNA GUIDA VISTA
    // ==========================================
    async function markGuidaVista() {
        try {
            const response = await fetch('api/guida.php?action=mark_guida', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'csrf_token=' + encodeURIComponent(state.csrfToken)
            });

            const data = await response.json();
            
            if (data.success) {
                console.log('✅ Guida segnata come vista');
                // Salva anche in localStorage come backup
                localStorage.setItem('taskflow_guida_vista', '1');
                localStorage.setItem('taskflow_guida_data', new Date().toISOString());
            } else {
                console.warn('⚠️ Errore nel salvataggio stato guida:', data.message);
                // Fallback: salva in localStorage
                localStorage.setItem('taskflow_guida_vista', '1');
            }
        } catch (error) {
            console.warn('⚠️ Errore API guida:', error);
            // Fallback: salva in localStorage
            localStorage.setItem('taskflow_guida_vista', '1');
        }
    }

    // ==========================================
    // REDIRECT
    // ==========================================
    function redirectToDashboard() {
        // Imposta flag per attivare la guida dashboard
        localStorage.setItem('taskflow_mostra_guida', 'true');
        
        // Effetto fade out
        elements.container.style.transition = 'opacity 0.5s ease';
        elements.container.style.opacity = '0';

        setTimeout(() => {
            // Redirect con parametro per triggerare la guida
            window.location.href = 'dashboard.php?guida=true';
        }, 500);
    }

    // ==========================================
    // UTILITÀ
    // ==========================================
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // ==========================================
    // AUTO-INIT
    // ==========================================
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Espone API pubbliche (opzionale)
    window.TaskFlowOnboarding = {
        goNext,
        goPrev,
        goToSlide,
        skip: skipOnboarding,
        start: startLoading
    };

})();
