/**
 * TaskFlow - Sistema di Guida Interattiva
 * 
 * Sistema a step per guidare l'utente attraverso la dashboard
 * Caratteristiche:
 * - 10 step interattivi
 * - Overlay scuro con spotlight
 * - Tooltip con frecce
 * - Navigazione avanti/indietro
 * - Auto-scroll
 * - Animazioni smooth
 * - API per gestione stato guidavista
 */

(function() {
    'use strict';

    // ==========================================
    // CONFIGURAZIONE DEGLI STEP
    // ==========================================
    const GUIDA_STEPS = [
        {
            id: 'benvenuto',
            title: 'Benvenuto su TaskFlow! 👋',
            content: 'TaskFlow è il gestionale completo per il tuo studio creativo. Ti guideremo alla scoperta delle principali funzionalità in pochi semplici passi.',
            target: null, // Centro schermo
            position: 'center',
            actionType: 'click', // L'utente deve cliccare "Inizia"
            highlight: false
        },
        {
            id: 'sidebar',
            title: 'La tua navigazione',
            content: 'Qui trovi tutte le sezioni del gestionale: Dashboard, Progetti, Clienti, Scadenze, Preventivi, Calendario, Finanze, Tasse e Impostazioni.',
            target: '#sidebar' // sidebar esiste già come ID
            position: 'right',
            actionType: 'auto', // Passa automaticamente dopo 3 secondi o con click
            highlight: true
        },
        {
            id: 'cassa',
            title: 'Cassa Aziendale 💰',
            content: 'Tieni sempre sotto controllo il saldo della cassa aziendale. Questo importo rappresenta il totale accumulato dalle distribuzioni dei progetti.',
            target: '[data-guida="cassa"]',
            position: 'bottom',
            actionType: 'click', // L'utente deve cliccare sulla card
            highlight: true
        },
        {
            id: 'progetti',
            title: 'Progetti Attivi 📁',
            content: 'Visualizza in tempo reale quanti progetti sono attivi e in cui sei coinvolto. Clicca per espandere il resoconto dettagliato.',
            target: '[data-guida="progetti-stats"]',
            position: 'bottom',
            actionType: 'click',
            highlight: true
        },
        {
            id: 'task-oggi',
            title: 'Task di Oggi ✅',
            content: 'Visualizza e gestisci le task assegnate per oggi. Clicca sul cerchio per segnarle come completate. Resta sempre organizzato!',
            target: '[data-guida="task-oggi"]'
            position: 'right',
            actionType: 'click',
            highlight: true
        },
        {
            id: 'scadenze',
            title: 'Prossime Scadenze ⏰',
            content: 'Non perdere mai una deadline! Qui vedi i progetti in scadenza nei prossimi 7 giorni, con indicatore di urgenza.',
            target: '[data-guida="scadenze"]'
            position: 'right',
            actionType: 'click',
            highlight: true
        },
        {
            id: 'appuntamenti',
            title: 'Prossimi Appuntamenti 📅',
            content: 'Il calendario mini ti mostra gli appuntamenti imminenti. Clicca sui giorni per vedere gli eventi specifici.',
            target: '[data-guida="appuntamenti"]'
            position: 'left',
            actionType: 'click',
            highlight: true
        },
        {
            id: 'timeline',
            title: 'Timeline Attività 📊',
            content: 'Tieni traccia di tutte le azioni recenti nel sistema. La timeline mostra le ultime 10 attività di tutti gli utenti.',
            target: '[data-guida="timeline"]'
            position: 'top',
            actionType: 'click',
            highlight: true
        },
        {
            id: 'profilo',
            title: 'Profilo e Impostazioni ⚙️',
            content: 'Accedi al tuo profilo, alle notifiche e alle impostazioni del sistema dal menu in alto a destra o dalla sidebar.',
            target: '.dropdown' // profilo dropdown
            position: 'bottom',
            actionType: 'click',
            highlight: true
        },
        {
            id: 'conclusione',
            title: 'Sei pronto per iniziare! 🚀',
            content: 'Ora conosci le basi di TaskFlow. Inizia a creare progetti, gestire clienti e tenere traccia delle tue attività. Buon lavoro!',
            target: null,
            position: 'center',
            actionType: 'button', // Bottone "Inizia a usare TaskFlow"
            highlight: false
        }
    ];

    // ==========================================
    // STATO DELLA GUIDA
    // ==========================================
    let guidaState = {
        isActive: false,
        currentStep: 0,
        totalSteps: GUIDA_STEPS.length,
        completed: false,
        elements: {},
        highlightedElement: null // Traccia l'elemento evidenziato per pulizia
    };

    // ==========================================
    // STILI CSS INJECTATI
    // ==========================================
    const GUIDA_STYLES = `
        /* Overlay principale */
        .guida-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            backdrop-filter: blur(1px);
            z-index: 9998;
            opacity: 0;
            transition: opacity 0.4s ease;
            pointer-events: auto;
        }
        
        .guida-overlay.active {
            opacity: 1;
        }

        /* Cutout per spotlight */
        .guida-spotlight {
            position: absolute;
            border-radius: 16px;
            box-shadow: 
                0 0 0 9999px rgba(0, 0, 0, 0.45),
                0 0 0 5px #0891B2,
                0 0 0 8px rgba(8, 145, 178, 0.5),
                0 0 30px rgba(8, 145, 178, 0.8),
                0 0 60px rgba(8, 145, 178, 0.4);
            z-index: 9999;
            opacity: 0;
            transform: scale(0.95);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: none;
        }
        
        .guida-spotlight.active {
            opacity: 1;
            transform: scale(1);
        }
        
        .guida-spotlight.interactive {
            pointer-events: auto;
            cursor: pointer;
        }
        
        .guida-spotlight.interactive:hover {
            box-shadow: 
                0 0 0 9999px rgba(0, 0, 0, 0.55),
                0 0 0 6px #06B6D4,
                0 0 0 10px rgba(6, 182, 212, 0.5),
                0 0 50px rgba(6, 182, 212, 1),
                0 0 80px rgba(6, 182, 212, 0.5);
        }

        /* Pulsazione dello spotlight */
        @keyframes guidapulse {
            0%, 100% { 
                box-shadow: 
                    0 0 0 9999px rgba(0, 0, 0, 0.45),
                    0 0 0 5px #0891B2,
                    0 0 0 8px rgba(8, 145, 178, 0.5),
                    0 0 30px rgba(8, 145, 178, 0.8),
                    0 0 60px rgba(8, 145, 178, 0.4);
            }
            50% { 
                box-shadow: 
                    0 0 0 9999px rgba(0, 0, 0, 0.50),
                    0 0 0 7px #06B6D4,
                    0 0 0 12px rgba(6, 182, 212, 0.6),
                    0 0 50px rgba(6, 182, 212, 1),
                    0 0 90px rgba(6, 182, 212, 0.6);
            }
        }
        
        .guida-spotlight.pulse {
            animation: guidapulse 1.5s infinite ease-in-out;
        }

        /* Elemento target evidenziato */
        .guida-target-highlight {
            position: relative !important;
            z-index: 9999 !important;
            outline: 3px solid #0891B2 !important;
            outline-offset: 4px !important;
            border-radius: 8px !important;
        }

        /* Tooltip/Bubble */
        .guida-tooltip {
            position: absolute;
            background: white;
            border-radius: 16px;
            padding: 24px;
            max-width: 360px;
            width: 90vw;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(0, 0, 0, 0.05);
            z-index: 10000;
            opacity: 0;
            transform: translateY(10px) scale(0.98);
            pointer-events: auto !important;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .guida-tooltip.active {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        /* Freccia del tooltip */
        .guida-tooltip::before {
            content: '';
            position: absolute;
            width: 0;
            height: 0;
            border-style: solid;
        }
        
        .guida-tooltip.position-bottom::before {
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            border-width: 0 10px 10px 10px;
            border-color: transparent transparent white transparent;
        }
        
        .guida-tooltip.position-top::before {
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            border-width: 10px 10px 0 10px;
            border-color: white transparent transparent transparent;
        }
        
        .guida-tooltip.position-left::before {
            right: -10px;
            top: 50%;
            transform: translateY(-50%);
            border-width: 10px 0 10px 10px;
            border-color: transparent transparent transparent white;
        }
        
        .guida-tooltip.position-right::before {
            left: -10px;
            top: 50%;
            transform: translateY(-50%);
            border-width: 10px 10px 10px 0;
            border-color: transparent white transparent transparent;
        }
        
        .guida-tooltip.position-center::before {
            display: none;
        }

        /* Header tooltip */
        .guida-tooltip-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }
        
        .guida-tooltip-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #0891B2 0%, #06B6D4 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            flex-shrink: 0;
        }
        
        .guida-tooltip-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.3;
            margin: 0;
        }
        
        .guida-tooltip-content {
            font-size: 15px;
            line-height: 1.6;
            color: #64748b;
            margin-bottom: 20px;
        }

        /* Azione richiesta */
        .guida-action-hint {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 1px solid #7dd3fc;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 14px;
            color: #0369a1;
            font-weight: 500;
        }
        
        .guida-action-hint svg {
            width: 18px;
            height: 18px;
            animation: guidabounce 1s infinite;
        }
        
        @keyframes guidabounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }

        /* Progress bar */
        .guida-progress {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }
        
        .guida-progress-bar {
            flex: 1;
            height: 6px;
            background: #e2e8f0;
            border-radius: 3px;
            overflow: hidden;
        }
        
        .guida-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #0891B2 0%, #06B6D4 100%);
            border-radius: 3px;
            transition: width 0.4s ease;
        }
        
        .guida-progress-text {
            font-size: 13px;
            font-weight: 600;
            color: #0891B2;
            white-space: nowrap;
        }

        /* Bottoni */
        .guida-buttons {
            display: flex;
            gap: 10px;
            justify-content: space-between;
        }
        
        .guida-btn {
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            display: flex;
            align-items: center;
            gap: 6px;
            pointer-events: auto !important;
            position: relative;
            z-index: 10000;
        }
        
        .guida-btn-primary {
            background: linear-gradient(135deg, #0891B2 0%, #06B6D4 100%);
            color: white;
            box-shadow: 0 4px 14px rgba(8, 145, 178, 0.3);
        }
        
        .guida-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(8, 145, 178, 0.4);
        }
        
        .guida-btn-primary:active {
            transform: translateY(0);
        }
        
        .guida-btn-secondary {
            background: #f1f5f9;
            color: #64748b;
        }
        
        .guida-btn-secondary:hover {
            background: #e2e8f0;
            color: #475569;
        }
        
        .guida-btn-skip {
            background: transparent;
            color: #94a3b8;
            padding: 8px 12px;
            font-size: 13px;
        }
        
        .guida-btn-skip:hover {
            color: #64748b;
            background: #f1f5f9;
        }

        /* Bottone chiusura */
        .guida-close {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            background: #f1f5f9;
            color: #64748b;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            z-index: 10001;
            pointer-events: auto !important;
        }
        
        .guida-close:hover {
            background: #e2e8f0;
            color: #475569;
        }

        /* Skip button fixed */
        .guida-skip-fixed {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 10001;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.95);
            color: #64748b;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            transition: all 0.2s ease;
            opacity: 0;
            transform: translateY(10px);
        }
        
        .guida-skip-fixed.active {
            opacity: 1;
            transform: translateY(0);
        }
        
        .guida-skip-fixed:hover {
            background: white;
            color: #475569;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.2);
        }

        /* Animazione entrata/uscita */
        @keyframes guidafadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        
        @keyframes guidafadeOut {
            from { opacity: 1; transform: scale(1); }
            to { opacity: 0; transform: scale(0.95); }
        }
        
        .guida-anim-in {
            animation: guidafadeIn 0.4s ease forwards;
        }
        
        .guida-anim-out {
            animation: guidafadeOut 0.3s ease forwards;
        }

        /* Blocco interazioni pagina */
        .guida-block-interactions {
            pointer-events: none !important;
        }
        
        .guida-block-interactions .guida-spotlight,
        .guida-block-interactions .guida-tooltip,
        .guida-block-interactions .guida-skip-fixed {
            pointer-events: auto !important;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .guida-tooltip {
                padding: 20px;
                max-width: calc(100vw - 32px);
                left: 16px !important;
                right: 16px !important;
            }
            
            .guida-tooltip-title {
                font-size: 16px;
            }
            
            .guida-tooltip-content {
                font-size: 14px;
            }
            
            .guida-btn {
                padding: 10px 16px;
                font-size: 13px;
            }
        }
    `;

    // ==========================================
    // API PUBBLICHE
    // ==========================================
    
    /**
     * Verifica se l'utente ha già visto la guida
     * @returns {Promise<boolean>}
     */
    async function verificaGuidaVista() {
        try {
            const response = await fetch('api/guida.php?action=check_guida', {
                credentials: 'same-origin'
            });
            const data = await response.json();
            return data.success && data.data && data.data.guidavista === 1;
        } catch (e) {
            console.warn('TaskFlow Guida: Impossibile verificare stato guida', e);
            // Fallback: controlla localStorage
            return localStorage.getItem('taskflow_guida_vista') === '1';
        }
    }

    /**
     * Segna la guida come vista
     * @returns {Promise<boolean>}
     */
    async function segnaGuidaVista() {
        try {
            const response = await fetch('api/guida.php?action=mark_guida', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'csrf_token=' + encodeURIComponent(getCsrfToken())
            });
            const data = await response.json();
            
            // Salva anche in localStorage come backup
            localStorage.setItem('taskflow_guida_vista', '1');
            localStorage.setItem('taskflow_guida_data', new Date().toISOString());
            
            return data.success;
        } catch (e) {
            console.warn('TaskFlow Guida: Impossibile salvare stato guida', e);
            // Fallback: salva in localStorage
            localStorage.setItem('taskflow_guida_vista', '1');
            localStorage.setItem('taskflow_guida_data', new Date().toISOString());
            return true;
        }
    }

    /**
     * Resetta lo stato della guida (da usare nelle impostazioni)
     * @returns {Promise<boolean>}
     */
    async function resetGuidaVista() {
        try {
            const response = await fetch('api/guida.php?action=reset_guida', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'csrf_token=' + encodeURIComponent(getCsrfToken())
            });
            const data = await response.json();
            
            // Resetta anche localStorage
            localStorage.removeItem('taskflow_guida_vista');
            localStorage.removeItem('taskflow_guida_data');
            
            return data.success;
        } catch (e) {
            console.warn('TaskFlow Guida: Impossibile resettare stato guida', e);
            // Fallback: resetta localStorage
            localStorage.removeItem('taskflow_guida_vista');
            localStorage.removeItem('taskflow_guida_data');
            return true;
        }
    }

    /**
     * Ottiene il token CSRF dalla pagina
     */
    function getCsrfToken() {
        // Cerca il meta tag CSRF
        const meta = document.querySelector('meta[name="csrf-token"]');
        if (meta) return meta.content;
        
        // Cerca in input hidden
        const input = document.querySelector('input[name="csrf_token"]');
        if (input) return input.value;
        
        // Fallback: stringa vuota
        return '';
    }

    // ==========================================
    // FUNZIONI INTERNE
    // ==========================================

    /**
     * Inietta gli stili CSS
     */
    function injectStyles() {
        if (document.getElementById('taskflow-guida-styles')) return;
        
        const style = document.createElement('style');
        style.id = 'taskflow-guida-styles';
        style.textContent = GUIDA_STYLES;
        document.head.appendChild(style);
    }

    /**
     * Crea gli elementi DOM della guida
     */
    function createElements() {
        // Se gli elementi esistono già, non ricrearli
        if (document.getElementById('guida-overlay')) {
            guidaState.elements = {
                overlay: document.getElementById('guida-overlay'),
                spotlight: document.getElementById('guida-spotlight'),
                tooltip: document.getElementById('guida-tooltip'),
                skipBtn: document.getElementById('guida-skip')
            };
            return;
        }
        
        // Overlay
        const overlay = document.createElement('div');
        overlay.className = 'guida-overlay';
        overlay.id = 'guida-overlay';
        
        // Spotlight
        const spotlight = document.createElement('div');
        spotlight.className = 'guida-spotlight';
        spotlight.id = 'guida-spotlight';
        
        // Tooltip
        const tooltip = document.createElement('div');
        tooltip.className = 'guida-tooltip';
        tooltip.id = 'guida-tooltip';
        
        // Skip button
        const skipBtn = document.createElement('button');
        skipBtn.className = 'guida-skip-fixed';
        skipBtn.id = 'guida-skip';
        skipBtn.innerHTML = '⏭ Salta guida';
        skipBtn.onclick = () => fineGuida(true);
        
        document.body.appendChild(overlay);
        document.body.appendChild(spotlight);
        document.body.appendChild(tooltip);
        document.body.appendChild(skipBtn);
        
        guidaState.elements = {
            overlay,
            spotlight,
            tooltip,
            skipBtn
        };
    }
    
    /**
     * Pulisce l'elemento precedentemente evidenziato
     */
    function clearHighlightedElement() {
        if (guidaState.highlightedElement) {
            guidaState.highlightedElement.classList.remove('guida-target-highlight');
            guidaState.highlightedElement = null;
        }
    }

    /**
     * Posiziona lo spotlight su un elemento
     */
    function posizionaSpotlight(targetSelector, highlight = true) {
        const spotlight = guidaState.elements.spotlight;
        
        // Pulisci elemento precedente
        clearHighlightedElement();
        
        if (!targetSelector || !highlight) {
            spotlight.classList.remove('active');
            return;
        }
        
        const target = document.querySelector(targetSelector);
        if (!target) {
            console.warn('TaskFlow Guida: Elemento non trovato', targetSelector);
            spotlight.classList.remove('active');
            return;
        }
        
        const rect = target.getBoundingClientRect();
        const padding = 8;
        
        spotlight.style.left = (rect.left - padding) + 'px';
        spotlight.style.top = (rect.top - padding + window.scrollY) + 'px';
        spotlight.style.width = (rect.width + padding * 2) + 'px';
        spotlight.style.height = (rect.height + padding * 2) + 'px';
        spotlight.classList.add('active', 'pulse');
        
        // Aggiungi classe highlight all'elemento target per z-index e bordo
        target.classList.add('guida-target-highlight');
        guidaState.highlightedElement = target;
        
        return target;
    }

    /**
     * Posiziona il tooltip
     */
    function posizionaTooltip(step, targetElement) {
        const tooltip = guidaState.elements.tooltip;
        const spotlight = guidaState.elements.spotlight;
        
        // Reset classi posizione
        tooltip.className = 'guida-tooltip position-' + step.position;
        
        let left, top;
        
        if (step.position === 'center') {
            // Centro schermo
            left = window.innerWidth / 2 - 180;
            top = window.innerHeight / 2 - 100 + window.scrollY;
        } else if (step.position === 'right' && targetElement) {
            // A destra dell'elemento
            const rect = targetElement.getBoundingClientRect();
            left = rect.right + 20;
            top = rect.top + window.scrollY;
            
            // Se esce dallo schermo, metti a sinistra
            if (left + 360 > window.innerWidth) {
                left = rect.left - 380;
                tooltip.className = 'guida-tooltip position-left';
            }
        } else if (step.position === 'left' && targetElement) {
            // A sinistra dell'elemento
            const rect = targetElement.getBoundingClientRect();
            left = rect.left - 380;
            top = rect.top + window.scrollY;
            
            if (left < 10) {
                left = rect.right + 20;
                tooltip.className = 'guida-tooltip position-right';
            }
        } else if (step.position === 'bottom' && targetElement) {
            // Sotto l'elemento
            const rect = targetElement.getBoundingClientRect();
            left = rect.left + rect.width / 2 - 180;
            top = rect.bottom + 20 + window.scrollY;
            
            // Se esce in basso, metti sopra
            if (top + 200 > window.innerHeight + window.scrollY) {
                top = rect.top - 220 + window.scrollY;
                tooltip.className = 'guida-tooltip position-top';
            }
        } else if (step.position === 'top' && targetElement) {
            // Sopra l'elemento
            const rect = targetElement.getBoundingClientRect();
            left = rect.left + rect.width / 2 - 180;
            top = rect.top - 220 + window.scrollY;
            
            if (top < window.scrollY) {
                top = rect.bottom + 20 + window.scrollY;
                tooltip.className = 'guida-tooltip position-bottom';
            }
        }
        
        // Assicurati che sia visibile
        left = Math.max(16, Math.min(left, window.innerWidth - 376));
        top = Math.max(16 + window.scrollY, top);
        
        tooltip.style.left = left + 'px';
        tooltip.style.top = top + 'px';
    }

    /**
     * Costruisce il contenuto del tooltip
     */
    function buildTooltipContent(step, stepIndex) {
        const isFirst = stepIndex === 0;
        const isLast = stepIndex === GUIDA_STEPS.length - 1;
        const progress = ((stepIndex + 1) / GUIDA_STEPS.length) * 100;
        
        let actionHtml = '';
        if (step.actionType === 'click') {
            actionHtml = `
                <div class="guida-action-hint">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                    </svg>
                    <span>Clicca l'elemento evidenziato per continuare</span>
                </div>
            `;
        }
        
        let buttonsHtml = '';
        if (isFirst) {
            buttonsHtml = `
                <button class="guida-btn guida-btn-skip" onclick="TaskFlowGuida.fine(true)">Salta</button>
                <button class="guida-btn guida-btn-primary" onclick="TaskFlowGuida.prossimoStep()">
                    Inizia la guida
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            `;
        } else if (isLast) {
            buttonsHtml = `
                <button class="guida-btn guida-btn-secondary" onclick="TaskFlowGuida.stepPrecedente()">Indietro</button>
                <button class="guida-btn guida-btn-primary" onclick="TaskFlowGuida.fine()">
                    🚀 Inizia a usare TaskFlow
                </button>
            `;
        } else {
            buttonsHtml = `
                <div class="flex gap-2">
                    <button class="guida-btn guida-btn-secondary" onclick="TaskFlowGuida.stepPrecedente()" ${stepIndex <= 0 ? 'disabled style="opacity:0.5"' : ''}>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Indietro
                    </button>
                </div>
                ${step.actionType === 'auto' ? `
                    <button class="guida-btn guida-btn-primary" onclick="TaskFlowGuida.prossimoStep()">
                        Avanti
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                ` : ''}
            `;
        }
        
        return `
            <button class="guida-close" id="guida-close-btn" title="Chiudi guida">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            
            <div class="guida-progress">
                <div class="guida-progress-bar">
                    <div class="guida-progress-fill" style="width: ${progress}%"></div>
                </div>
                <span class="guida-progress-text">${stepIndex + 1}/${GUIDA_STEPS.length}</span>
            </div>
            
            <div class="guida-tooltip-header">
                <div class="guida-tooltip-icon">${stepIndex + 1}</div>
                <h3 class="guida-tooltip-title">${step.title}</h3>
            </div>
            
            <p class="guida-tooltip-content">${step.content}</p>
            
            ${actionHtml}
            
            <div class="guida-buttons">
                ${buttonsHtml}
            </div>
        `;
    }

    /**
     * Scrolla all'elemento target
     */
    function scrollToElement(targetElement) {
        if (!targetElement) return;
        
        const rect = targetElement.getBoundingClientRect();
        const scrollTop = window.scrollY || document.documentElement.scrollTop;
        const targetTop = rect.top + scrollTop - 150;
        
        window.scrollTo({
            top: Math.max(0, targetTop),
            behavior: 'smooth'
        });
    }

    /**
     * Mostra uno step specifico
     */
    function mostraStep(index) {
        if (index < 0 || index >= GUIDA_STEPS.length) return;
        
        guidaState.currentStep = index;
        const step = GUIDA_STEPS[index];
        
        // Blocca interazioni sulla pagina
        document.body.classList.add('guida-block-interactions');
        
        // Posiziona spotlight
        let targetElement = null;
        if (step.target) {
            targetElement = document.querySelector(step.target);
            if (targetElement) {
                scrollToElement(targetElement);
                setTimeout(() => {
                    posizionaSpotlight(step.target, step.highlight);
                }, 300);
            }
        } else {
            guidaState.elements.spotlight.classList.remove('active');
            clearHighlightedElement();
        }
        
        // Aggiorna tooltip
        const tooltip = guidaState.elements.tooltip;
        tooltip.innerHTML = buildTooltipContent(step, index);
        
        // Aggiungi event listener esplicito al bottone di chiusura
        const closeBtn = document.getElementById('guida-close-btn');
        if (closeBtn) {
            closeBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                e.preventDefault();
                fineGuida(true);
            });
        }
        
        setTimeout(() => {
            posizionaTooltip(step, targetElement);
            tooltip.classList.add('active');
        }, step.target ? 400 : 100);
        
        // Gestione click su spotlight per step interattivi
        const spotlight = guidaState.elements.spotlight;
        spotlight.onclick = null;
        spotlight.classList.remove('interactive');
        
        if (step.actionType === 'click' && step.target && targetElement) {
            spotlight.classList.add('interactive');
            spotlight.onclick = (e) => {
                e.stopPropagation();
                e.preventDefault();
                
                // Simula click sull'elemento reale
                targetElement.click();
                
                // Passa al prossimo step dopo un breve delay
                setTimeout(() => {
                    prossimoStep();
                }, 500);
            };
        }
    }

    /**
     * Passa al prossimo step
     */
    function prossimoStep() {
        if (guidaState.currentStep < GUIDA_STEPS.length - 1) {
            // Animazione uscita
            guidaState.elements.tooltip.classList.remove('active');
            
            setTimeout(() => {
                mostraStep(guidaState.currentStep + 1);
            }, 200);
        }
    }

    /**
     * Torna allo step precedente
     */
    function stepPrecedente() {
        if (guidaState.currentStep > 0) {
            // Animazione uscita
            guidaState.elements.tooltip.classList.remove('active');
            
            setTimeout(() => {
                mostraStep(guidaState.currentStep - 1);
            }, 200);
        }
    }

    /**
     * Avvia la guida
     */
    async function avviaGuida(forza = false) {
        // Verifica se la guida è già attiva per evitare duplicazioni
        if (guidaState.isActive) {
            console.log('TaskFlow Guida: Già in esecuzione');
            return;
        }
        
        // Verifica se esistono già elementi della guida nel DOM (anche multipli)
        const elementiEsistenti = document.querySelectorAll('.guida-overlay, .guida-tooltip');
        if (elementiEsistenti.length > 0) {
            console.log('TaskFlow Guida: Pulizia elementi esistenti in corso...');
            // Rimuovi TUTTI gli elementi senza animazione
            document.querySelectorAll('.guida-overlay, .guida-spotlight, .guida-tooltip, .guida-skip-btn').forEach(el => el.remove());
            document.body.classList.remove('guida-block-interactions');
            clearHighlightedElement();
        }
        
        // Verifica se già vista
        if (!forza) {
            const vista = await verificaGuidaVista();
            if (vista) {
                console.log('TaskFlow Guida: Già visualizzata, skip');
                return;
            }
        }
        
        // Verifica se siamo sulla dashboard
        if (!document.querySelector('main') || !window.location.pathname.includes('dashboard')) {
            console.log('TaskFlow Guida: Disponibile solo sulla dashboard');
            return;
        }
        
        // Inietta stili e crea elementi
        injectStyles();
        createElements();
        
        guidaState.isActive = true;
        guidaState.currentStep = 0;
        
        // Mostra overlay
        const overlay = guidaState.elements.overlay;
        overlay.classList.add('active');
        
        // Mostra skip button
        setTimeout(() => {
            guidaState.elements.skipBtn.classList.add('active');
        }, 500);
        
        // Mostra primo step
        mostraStep(0);
    }

    /**
     * Termina la guida
     */
    async function fineGuida(saltata = false) {
        // Rimuovi TUTTI gli elementi della guida, anche se ci sono multiple istanze
        const tuttiOverlay = document.querySelectorAll('.guida-overlay');
        const tuttiSpotlight = document.querySelectorAll('.guida-spotlight');
        const tuttiTooltip = document.querySelectorAll('.guida-tooltip');
        const tuttiSkipBtn = document.querySelectorAll('.guida-skip-btn');
        
        // Segna come vista se completata o saltata
        if (guidaState.isActive || tuttiOverlay.length > 0) {
            await segnaGuidaVista();
        }
        
        // Stato
        guidaState.isActive = false;
        guidaState.completed = !saltata;
        
        // Pulisci elemento evidenziato
        clearHighlightedElement();
        
        // Rimuovi classe blocco interazioni
        document.body.classList.remove('guida-block-interactions');
        
        // Animazione uscita per tutti gli elementi
        [...tuttiTooltip, ...tuttiSkipBtn, ...tuttiSpotlight].forEach(el => {
            el.classList.remove('active');
        });
        
        setTimeout(() => {
            tuttiOverlay.forEach(el => el.classList.remove('active'));
        }, 100);
        
        // Rimuovi TUTTO dal DOM dopo animazione
        setTimeout(() => {
            tuttiOverlay.forEach(el => el.remove());
            tuttiSpotlight.forEach(el => el.remove());
            tuttiTooltip.forEach(el => el.remove());
            tuttiSkipBtn.forEach(el => el.remove());
            
            // Reset stato
            guidaState.elements = {};
            guidaState.highlightedElement = null;
            
            // Mostra toast di completamento
            if (!saltata && typeof showToast === 'function') {
                showToast('Guida completata! Sei pronto per usare TaskFlow 🎉', 'success');
            }
        }, 400);
    }

    /**
     * Verifica se la guida è attiva
     */
    function isGuidaActive() {
        return guidaState.isActive;
    }

    /**
     * Ottiene lo step corrente
     */
    function getCurrentStep() {
        return guidaState.currentStep;
    }

    // ==========================================
    // INIZIALIZZAZIONE
    // ==========================================
    
    // Auto-avvio su dashboard (dopo 1 secondo)
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => avviaGuida(), 1000);
        });
    } else {
        setTimeout(() => avviaGuida(), 1000);
    }

    // ==========================================
    // ESPORTAZIONE API PUBBLICA
    // ==========================================
    window.TaskFlowGuida = {
        // Controllo
        avvia: avviaGuida,
        fine: fineGuida,
        prossimoStep,
        stepPrecedente,
        isActive: isGuidaActive,
        getCurrentStep,
        
        // API stato
        verificaGuidaVista,
        segnaGuidaVista,
        resetGuidaVista,
        
        // Configurazione
        getSteps: () => GUIDA_STEPS,
        version: '1.0.0'
    };

    // Log iniziale
    console.log('📘 TaskFlow Guida caricata. Usa TaskFlowGuida.avvia() per avviare manualmente.');

})();
