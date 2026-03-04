/**
 * TaskFlow - Guida Interattiva Dashboard
 * 
 * Guida passo-passo che mostra le funzionalità della sidebar
 * Parte automaticamente dopo l'onboarding
 * 
 * Best practice implementate:
 * - Spotlight con overlay scuro
 * - Tooltip posizionati correttamente con freccia
 * - Freccia che punta all'elemento
 * - Navigazione avanti/indietro
 * - Chiusura con ESC o click fuori
 * - Salvataggio progresso nel localStorage
 * - Non bloccare l'utente (sempre possibile uscire)
 * - Testi chiari e concisi
 * - Focus su funzionalità principali
 * - Call to action evidente
 */

(function() {
    'use strict';

    // Configurazione della guida
    const CONFIG = {
        // Colore del bordo spotlight (cyan-600)
        spotlightColor: '#0891B2',
        // Durata transizioni (ms)
        transitionDuration: 300,
        // Delay prima di iniziare dopo apertura sidebar
        sidebarOpenDelay: 400,
        // URL API per marcare guida come vista
        apiEndpoint: 'api/guida.php?action=mark_guida',
        // Chiave localStorage per salvare progresso
        storageKey: 'taskflow_guida_progress',
        // Chiave localStorage per mostrare guida
        showGuidaKey: 'taskflow_mostra_guida'
    };

    // Step della guida - 8 voci del menu sidebar
    const GUIDA_STEPS = [
        {
            id: 'dashboard',
            selector: '#sidebar nav a[href="dashboard.php"]',
            title: 'Dashboard',
            description: 'Qui hai il riepilogo completo della tua attività. Task del giorno, prossime scadenze, statistiche progetti e notifiche in un unico posto.',
            icon: 'dashboard'
        },
        {
            id: 'progetti',
            selector: '#sidebar nav a[href="progetti.php"]',
            title: 'Progetti',
            description: 'Gestisci tutti i tuoi progetti. Crea nuovi progetti, organizza i task, monitora lo stato di avanzamento e archivia i completati.',
            icon: 'folder'
        },
        {
            id: 'clienti',
            selector: '#sidebar nav a[href="clienti.php"]',
            title: 'Clienti',
            description: 'La tua rubrica clienti. Aggiungi nuovi contatti, visualizza lo storico dei progetti per cliente e gestisci le informazioni di fatturazione.',
            icon: 'users'
        },
        {
            id: 'scadenze',
            selector: '#sidebar nav a[href="scadenze.php"]',
            title: 'Scadenze',
            description: 'Tieni d\'occhio le deadline di tutti i progetti. Visualizza le scadenze imminenti, filtra per priorità e ricevi avvisi per i progetti in ritardo.',
            icon: 'clock'
        },
        {
            id: 'calendario',
            selector: '#sidebar nav a[href="calendario.php"]',
            title: 'Calendario',
            description: 'Organizza appuntamenti e riunioni. Visualizza le scadenze progetto in formato calendario e gestisci i tuoi impegni.',
            icon: 'calendar'
        },
        {
            id: 'finanze',
            selector: '#sidebar nav a[href="finanze.php"]',
            title: 'Finanze',
            description: 'Controlla cassa e wallet. Monitora le entrate e le uscite, visualizza il saldo del tuo wallet e gestisci i movimenti economici.',
            icon: 'dollar'
        },
        {
            id: 'tasse',
            selector: '#sidebar nav a[href="tasse.php"]',
            title: 'Tasse',
            description: 'Calcola le tue tasse in modo automatico. Inserisci i ricavi e le spese per ottenere una stima delle tasse da pagare con aliquote aggiornate.',
            icon: 'percent'
        },
        {
            id: 'impostazioni',
            selector: '#sidebar nav a[href="impostazioni.php"]',
            title: 'Impostazioni',
            description: 'Configura il tuo profilo e le preferenze. Personalizza il tuo account, gestisci le notifiche e modifica le impostazioni del sistema.',
            icon: 'settings'
        }
    ];

    // Stato della guida
    let currentStep = 0;
    let isActive = false;
    let overlayElement = null;
    let tooltipElement = null;
    let clickOutsideHandler = null;
    let resizeHandler = null;

    /**
     * Salva il progresso nel localStorage
     */
    function saveProgress() {
        const progress = {
            currentStep: currentStep,
            timestamp: Date.now(),
            totalSteps: GUIDA_STEPS.length
        };
        localStorage.setItem(CONFIG.storageKey, JSON.stringify(progress));
    }

    /**
     * Carica il progresso dal localStorage
     */
    function loadProgress() {
        try {
            const saved = localStorage.getItem(CONFIG.storageKey);
            if (saved) {
                const progress = JSON.parse(saved);
                // Verifica che il progresso non sia troppo vecchio (max 24 ore)
                const hoursSince = (Date.now() - progress.timestamp) / (1000 * 60 * 60);
                if (hoursSince < 24 && progress.currentStep < GUIDA_STEPS.length) {
                    return progress.currentStep;
                }
            }
        } catch (e) {
        }
        return 0;
    }

    /**
     * Crea l'elemento overlay con lo spotlight
     */
    function createOverlay() {
        // Rimuovi overlay esistente se presente
        removeOverlay();

        // Crea overlay principale
        overlayElement = document.createElement('div');
        overlayElement.id = 'guida-overlay';
        overlayElement.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9998;
            pointer-events: auto;
        `;

        // Crea layer scuro con cutout per lo spotlight
        const darkLayer = document.createElement('div');
        darkLayer.id = 'guida-dark-layer';
        darkLayer.style.cssText = `
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.85);
            transition: opacity 0.3s ease;
        `;

        overlayElement.appendChild(darkLayer);
        document.body.appendChild(overlayElement);

        // Aggiungi handler per chiusura al click fuori
        clickOutsideHandler = function(e) {
            // Se il click è sull'overlay (non sul tooltip o spotlight)
            if (e.target === overlayElement || e.target === darkLayer) {
                // Chiedi conferma prima di chiudere
                if (confirm('Vuoi interrompere la guida? Potrai riprenderla più tardi.')) {
                    saveProgress();
                    skipGuida();
                }
            }
        };
        overlayElement.addEventListener('click', clickOutsideHandler);

        // Handler per resize
        resizeHandler = debounce(function() {
            if (isActive) {
                showStep(currentStep);
            }
        }, 250);
        window.addEventListener('resize', resizeHandler);

        return overlayElement;
    }

    /**
     * Rimuove l'overlay
     */
    function removeOverlay() {
        if (overlayElement) {
            if (clickOutsideHandler) {
                overlayElement.removeEventListener('click', clickOutsideHandler);
                clickOutsideHandler = null;
            }
            overlayElement.remove();
            overlayElement = null;
        }
        if (resizeHandler) {
            window.removeEventListener('resize', resizeHandler);
            resizeHandler = null;
        }
        // Rimuovi spotlight e resetta z-index elementi
        const spotlight = document.getElementById('guida-spotlight');
        if (spotlight) spotlight.remove();
        document.querySelectorAll('.guida-spotlight-target').forEach(el => {
            el.classList.remove('guida-spotlight-target');
            el.style.zIndex = '';
            el.style.position = '';
        });
    }

    /**
     * Utility: debounce function
     */
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

    /**
     * Crea lo spotlight intorno a un elemento
     */
    function createSpotlight(element) {
        if (!overlayElement) return;

        const rect = element.getBoundingClientRect();
        const padding = 8;
        const borderRadius = 8;

        // Rimuovi spotlight precedente e resetta z-index elementi precedenti
        const oldSpotlight = document.getElementById('guida-spotlight');
        if (oldSpotlight) oldSpotlight.remove();
        document.querySelectorAll('.guida-spotlight-target').forEach(el => {
            el.classList.remove('guida-spotlight-target');
            el.style.zIndex = '';
            el.style.position = '';
        });

        // Porta l'elemento target in primo piano
        element.classList.add('guida-spotlight-target');
        const originalZIndex = window.getComputedStyle(element).zIndex;
        element.style.zIndex = '10000';
        if (window.getComputedStyle(element).position === 'static') {
            element.style.position = 'relative';
        }

        // Crea elemento spotlight
        const spotlight = document.createElement('div');
        spotlight.id = 'guida-spotlight';
        spotlight.style.cssText = `
            position: absolute;
            left: ${rect.left - padding}px;
            top: ${rect.top - padding}px;
            width: ${rect.width + (padding * 2)}px;
            height: ${rect.height + (padding * 2)}px;
            border: 3px solid ${CONFIG.spotlightColor};
            border-radius: ${borderRadius}px;
            box-shadow: 
                0 0 0 9999px rgba(15, 23, 42, 0.75),
                0 0 30px ${CONFIG.spotlightColor},
                0 0 60px rgba(8, 145, 178, 0.3),
                inset 0 0 20px rgba(8, 145, 178, 0.2);
            z-index: 9999;
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            animation: guidaspotlight-pulse 2s infinite;
            background: rgba(8, 145, 178, 0.05);
        `;

        // Aggiungi animazione CSS se non esiste
        if (!document.getElementById('guida-animations')) {
            const style = document.createElement('style');
            style.id = 'guida-animations';
            style.textContent = `
                @keyframes guidaspotlight-pulse {
                    0%, 100% { 
                        box-shadow: 0 0 0 9999px rgba(15, 23, 42, 0.85), 0 0 20px ${CONFIG.spotlightColor}, inset 0 0 20px rgba(8, 145, 178, 0.1); 
                    }
                    50% { 
                        box-shadow: 0 0 0 9999px rgba(15, 23, 42, 0.85), 0 0 30px ${CONFIG.spotlightColor}, inset 0 0 30px rgba(8, 145, 178, 0.2); 
                    }
                }
                @keyframes guidabounce {
                    0%, 100% { transform: translateY(0); }
                    50% { transform: translateY(-5px); }
                }
            `;
            document.head.appendChild(style);
        }

        overlayElement.appendChild(spotlight);

        // Scrolla l'elemento in vista con margine
        element.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'nearest' });

        return { rect, padding };
    }

    /**
     * Crea il tooltip con le informazioni dello step
     */
    function createTooltip(stepData, stepIndex, totalSteps) {
        // Rimuovi tooltip precedente
        if (tooltipElement) {
            tooltipElement.remove();
        }

        const targetElement = document.querySelector(stepData.selector);
        if (!targetElement) return;

        const targetRect = targetElement.getBoundingClientRect();
        const spotlightInfo = createSpotlight(targetElement);

        // Crea tooltip
        tooltipElement = document.createElement('div');
        tooltipElement.id = 'guida-tooltip';
        tooltipElement.style.cssText = `
            position: fixed;
            z-index: 10000;
            background: white;
            border-radius: 12px;
            padding: 0;
            max-width: 340px;
            width: 90vw;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(0, 0, 0, 0.1);
            font-family: 'Inter', sans-serif;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: auto;
        `;

        // Calcola posizione ottimale
        const position = calculateTooltipPosition(targetRect, 340, 200);

        tooltipElement.style.left = position.left + 'px';
        tooltipElement.style.top = position.top + 'px';

        // Crea contenuto tooltip
        const progressPercent = ((stepIndex + 1) / totalSteps) * 100;

        tooltipElement.innerHTML = `
            <!-- Freccia che punta all'elemento -->
            <div class="guida-arrow ${position.arrowClass}" style="${position.arrowStyle}"></div>
            
            <div style="padding: 20px;">
                <!-- Progress bar -->
                <div style="margin-bottom: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">
                            Passo ${stepIndex + 1} di ${totalSteps}
                        </span>
                        <span style="font-size: 12px; color: #0891B2; font-weight: 500;">
                            ${Math.round(progressPercent)}%
                        </span>
                    </div>
                    <div style="height: 4px; background: #e2e8f0; border-radius: 2px; overflow: hidden;">
                        <div style="height: 100%; width: ${progressPercent}%; background: linear-gradient(90deg, #0891B2, #22d3ee); border-radius: 2px; transition: width 0.3s ease;"></div>
                    </div>
                </div>

                <!-- Icona e Titolo -->
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <div style="width: 44px; height: 44px; background: linear-gradient(135deg, #0891B2, #0e7490); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 12px rgba(8, 145, 178, 0.3);">
                        ${getIconSvg(stepData.icon)}
                    </div>
                    <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #0f172a;">${stepData.title}</h3>
                </div>

                <!-- Descrizione -->
                <p style="margin: 0 0 20px 0; font-size: 14px; line-height: 1.6; color: #475569;">
                    ${stepData.description}
                </p>

                <!-- Bottoni -->
                <div style="display: flex; gap: 10px; align-items: center;">
                    ${stepIndex > 0 ? `
                        <button id="guida-btn-prev" style="
                            padding: 10px 16px;
                            border: 1px solid #e2e8f0;
                            background: white;
                            color: #64748b;
                            border-radius: 8px;
                            font-size: 14px;
                            font-weight: 500;
                            cursor: pointer;
                            transition: all 0.2s;
                            display: flex;
                            align-items: center;
                            gap: 4px;
                        " onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#cbd5e1';" onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0';">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            Indietro
                        </button>
                    ` : '<div></div>'}
                    <div style="display: flex; gap: 10px; margin-left: auto;">
                        <button id="guida-btn-skip" style="
                            padding: 10px 12px;
                            border: none;
                            background: transparent;
                            color: #94a3b8;
                            font-size: 13px;
                            font-weight: 500;
                            cursor: pointer;
                            transition: color 0.2s;
                        " onmouseover="this.style.color='#64748b';" onmouseout="this.style.color='#94a3b8';">
                            Salta
                        </button>
                        <button id="guida-btn-next" style="
                            padding: 10px 20px;
                            border: none;
                            background: linear-gradient(135deg, #0891B2, #0e7490);
                            color: white;
                            border-radius: 8px;
                            font-size: 14px;
                            font-weight: 600;
                            cursor: pointer;
                            transition: all 0.2s;
                            box-shadow: 0 4px 12px rgba(8, 145, 178, 0.3);
                            display: flex;
                            align-items: center;
                            gap: 4px;
                        " onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 16px rgba(8, 145, 178, 0.4)';" onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 12px rgba(8, 145, 178, 0.3)';">
                            ${stepIndex === totalSteps - 1 ? 'Fine' : 'Avanti'}
                            ${stepIndex === totalSteps - 1 
                                ? '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>'
                                : '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>'
                            }
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Keyboard hint -->
            <div style="padding: 8px 20px; background: #f8fafc; border-top: 1px solid #e2e8f0; border-radius: 0 0 12px 12px; font-size: 11px; color: #94a3b8; display: flex; justify-content: space-between; align-items: center;">
                <span>Use <kbd style="background: white; padding: 2px 6px; border-radius: 4px; border: 1px solid #e2e8f0; font-family: monospace;">←</kbd> <kbd style="background: white; padding: 2px 6px; border-radius: 4px; border: 1px solid #e2e8f0; font-family: monospace;">→</kbd> per navigare</span>
                <span><kbd style="background: white; padding: 2px 6px; border-radius: 4px; border: 1px solid #e2e8f0; font-family: monospace;">ESC</kbd> per chiudere</span>
            </div>
        `;

        // Aggiungi stili per la freccia
        if (!document.getElementById('guida-arrow-styles')) {
            const arrowStyle = document.createElement('style');
            arrowStyle.id = 'guida-arrow-styles';
            arrowStyle.textContent = `
                .guida-arrow {
                    position: absolute;
                    width: 0;
                    height: 0;
                    border-style: solid;
                }
                .guida-arrow.left {
                    left: -10px;
                    top: 50%;
                    transform: translateY(-50%);
                    border-width: 10px 10px 10px 0;
                    border-color: transparent white transparent transparent;
                }
                .guida-arrow.right {
                    right: -10px;
                    top: 50%;
                    transform: translateY(-50%);
                    border-width: 10px 0 10px 10px;
                    border-color: transparent transparent transparent white;
                }
                .guida-arrow.top {
                    top: -10px;
                    left: 50%;
                    transform: translateX(-50%);
                    border-width: 0 10px 10px 10px;
                    border-color: transparent transparent white transparent;
                }
                .guida-arrow.bottom {
                    bottom: -10px;
                    left: 50%;
                    transform: translateX(-50%);
                    border-width: 10px 10px 0 10px;
                    border-color: white transparent transparent transparent;
                }
            `;
            document.head.appendChild(arrowStyle);
        }

        document.body.appendChild(tooltipElement);

        // Aggiungi event listeners
        const btnPrev = document.getElementById('guida-btn-prev');
        const btnNext = document.getElementById('guida-btn-next');
        const btnSkip = document.getElementById('guida-btn-skip');

        if (btnPrev) btnPrev.addEventListener('click', prevStep);
        if (btnNext) btnNext.addEventListener('click', nextStep);
        if (btnSkip) btnSkip.addEventListener('click', () => {
            if (confirm('Vuoi interrompere la guida? Potrai riprenderla più tardi.')) {
                saveProgress();
                skipGuida();
            }
        });
    }

    /**
     * Calcola la posizione ottimale del tooltip
     */
    function calculateTooltipPosition(targetRect, tooltipWidth, tooltipHeight) {
        const margin = 20;
        const arrowSize = 10;
        const viewportWidth = window.innerWidth;
        const viewportHeight = window.innerHeight;
        
        let left, top, arrowClass, arrowStyle;
        
        // Su mobile: sempre al centro in basso
        if (viewportWidth < 768) {
            left = (viewportWidth - Math.min(tooltipWidth, viewportWidth - 40)) / 2;
            top = Math.min(targetRect.bottom + arrowSize + margin, viewportHeight - tooltipHeight - margin);
            arrowClass = 'top';
            arrowStyle = `left: ${targetRect.left + targetRect.width / 2 - left}px;`;
            return { left, top, arrowClass, arrowStyle };
        }
        
        // Su desktop: preferisci destra, fallback a sinistra o sotto
        const spaceRight = viewportWidth - (targetRect.right + margin);
        const spaceLeft = targetRect.left - margin;
        const spaceBelow = viewportHeight - (targetRect.bottom + margin);
        
        // Prova a destra (preferito per sidebar)
        if (spaceRight >= tooltipWidth) {
            left = targetRect.right + arrowSize + margin;
            top = Math.max(margin, Math.min(targetRect.top, viewportHeight - tooltipHeight - margin));
            arrowClass = 'left';
            arrowStyle = `top: ${Math.min(targetRect.height / 2 + 20, 100)}px;`;
        } 
        // Prova a sinistra
        else if (spaceLeft >= tooltipWidth) {
            left = targetRect.left - tooltipWidth - arrowSize - margin;
            top = Math.max(margin, Math.min(targetRect.top, viewportHeight - tooltipHeight - margin));
            arrowClass = 'right';
            arrowStyle = `top: ${Math.min(targetRect.height / 2 + 20, 100)}px;`;
        }
        // Fallback sotto
        else {
            left = Math.max(margin, Math.min(targetRect.left, viewportWidth - tooltipWidth - margin));
            top = targetRect.bottom + arrowSize + margin;
            arrowClass = 'top';
            arrowStyle = `left: ${Math.min(targetRect.width / 2 + 20, tooltipWidth - 20)}px;`;
        }
        
        // Assicurati che sia nel viewport
        left = Math.max(margin, Math.min(left, viewportWidth - tooltipWidth - margin));
        top = Math.max(margin, Math.min(top, viewportHeight - tooltipHeight - margin));
        
        return { left, top, arrowClass, arrowStyle };
    }

    /**
     * Restituisce l'icona SVG in base al tipo
     */
    function getIconSvg(iconType) {
        const icons = {
            dashboard: '<svg width="22" height="22" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>',
            folder: '<svg width="22" height="22" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>',
            users: '<svg width="22" height="22" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
            clock: '<svg width="22" height="22" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            calendar: '<svg width="22" height="22" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
            dollar: '<svg width="22" height="22" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            percent: '<svg width="22" height="22" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>',
            settings: '<svg width="22" height="22" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>'
        };
        return icons[iconType] || icons.dashboard;
    }

    /**
     * Apre la sidebar se è collassata
     */
    function openSidebar() {
        const sidebar = document.getElementById('sidebar');
        if (!sidebar) return Promise.resolve();

        const isCollapsed = sidebar.classList.contains('sidebar-collapsed');
        if (isCollapsed) {
            sidebar.classList.remove('sidebar-collapsed');
            // Attendi che la transizione finisca
            return new Promise(resolve => setTimeout(resolve, CONFIG.sidebarOpenDelay));
        }
        return Promise.resolve();
    }

    /**
     * Mostra uno specifico step
     */
    function showStep(stepIndex) {
        if (stepIndex < 0 || stepIndex >= GUIDA_STEPS.length) return;

        currentStep = stepIndex;
        const stepData = GUIDA_STEPS[stepIndex];

        // Trova elemento target
        const targetElement = document.querySelector(stepData.selector);
        if (!targetElement) {
            // Passa al prossimo step
            if (stepIndex < GUIDA_STEPS.length - 1) {
                showStep(stepIndex + 1);
            }
            return;
        }

        // Salva progresso
        saveProgress();

        // Crea tooltip (include spotlight)
        createTooltip(stepData, stepIndex, GUIDA_STEPS.length);
    }

    /**
     * Passa al prossimo step
     */
    function nextStep() {
        if (currentStep < GUIDA_STEPS.length - 1) {
            showStep(currentStep + 1);
        } else {
            completeGuida();
        }
    }

    /**
     * Torna allo step precedente
     */
    function prevStep() {
        if (currentStep > 0) {
            showStep(currentStep - 1);
        }
    }

    /**
     * Salta la guida
     */
    function skipGuida() {
        completeGuida(true);
    }

    /**
     * Completa la guida
     */
    function completeGuida(skipped = false) {
        isActive = false;

        // Rimuovi overlay e tooltip
        removeOverlay();
        if (tooltipElement) {
            tooltipElement.remove();
            tooltipElement = null;
        }

        // Pulisci localStorage
        localStorage.removeItem(CONFIG.storageKey);
        localStorage.removeItem(CONFIG.showGuidaKey);

        // Chiudi la sidebar
        const sidebar = document.getElementById('sidebar');
        if (sidebar && !sidebar.classList.contains('sidebar-collapsed')) {
            sidebar.classList.add('sidebar-collapsed');
        }

        // Marca guida come vista
        markGuidaAsSeen();

        // Mostra messaggio di completamento (solo se non saltata)
        if (!skipped) {
            showCompletionMessage();
        }
    }

    /**
     * Mostra messaggio di completamento
     */
    function showCompletionMessage() {
        const message = document.createElement('div');
        message.style.cssText = `
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #0891B2, #0e7490);
            color: white;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(8, 145, 178, 0.4);
            z-index: 10001;
            font-family: 'Inter', sans-serif;
            animation: guidaslideIn 0.5s ease;
        `;
        message.innerHTML = `
            <div style="display: flex; align-items: center; gap: 12px;">
                <svg width="24" height="24" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <div>
                    <p style="margin: 0; font-weight: 600;">Guida completata! 🎉</p>
                    <p style="margin: 4px 0 0 0; font-size: 13px; opacity: 0.9;">Ora conosci tutte le sezioni di TaskFlow</p>
                </div>
            </div>
        `;

        // Aggiungi animazione
        if (!document.getElementById('guida-completion-anim')) {
            const style = document.createElement('style');
            style.id = 'guida-completion-anim';
            style.textContent = `
                @keyframes guidaslideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
            `;
            document.head.appendChild(style);
        }

        document.body.appendChild(message);

        // Rimuovi dopo 4 secondi
        setTimeout(() => {
            message.style.transition = 'all 0.3s ease';
            message.style.opacity = '0';
            message.style.transform = 'translateX(100%)';
            setTimeout(() => message.remove(), 300);
        }, 4000);
    }

    /**
     * Chiama l'API per marcare la guida come vista
     */
    function markGuidaAsSeen() {
        fetch(CONFIG.apiEndpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': getCsrfToken()
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
        })
        .catch(error => {
            // Silenzio
        });
    }

    /**
     * Ottiene il token CSRF se disponibile
     */
    function getCsrfToken() {
        // Cerca il token in un meta tag o in un input nascosto
        const metaToken = document.querySelector('meta[name="csrf-token"]');
        if (metaToken) return metaToken.content;

        const inputToken = document.querySelector('input[name="csrf_token"]');
        if (inputToken) return inputToken.value;

        // Fallback: cerca in localStorage
        return localStorage.getItem('csrf_token') || '';
    }

    /**
     * Keyboard navigation handler
     */
    function handleKeyDown(e) {
        if (!isActive) return;
        
        switch(e.key) {
            case 'ArrowRight':
            case 'ArrowDown':
                e.preventDefault();
                nextStep();
                break;
            case 'ArrowLeft':
            case 'ArrowUp':
                e.preventDefault();
                prevStep();
                break;
            case 'Escape':
                e.preventDefault();
                if (confirm('Vuoi interrompere la guida? Potrai riprenderla più tardi.')) {
                    saveProgress();
                    skipGuida();
                }
                break;
        }
    }

    /**
     * Inizia la guida
     */
    function startGuida() {
        if (isActive) return;
        
        isActive = true;
        
        // Carica progresso salvato
        currentStep = loadProgress();
        
        // Aggiungi keyboard listener
        document.addEventListener('keydown', handleKeyDown);

        // Crea overlay
        createOverlay();

        // Apri sidebar e poi mostra primo step
        openSidebar().then(() => {
            showStep(currentStep);
        });
    }

    /**
     * Resetta la guida (utile per il bottone in impostazioni)
     */
    function resetGuida() {
        // Resetta progresso
        localStorage.removeItem(CONFIG.storageKey);
        currentStep = 0;
        
        // Imposta flag per mostrare guida al prossimo caricamento
        localStorage.setItem(CONFIG.showGuidaKey, 'true');
        
        // Se siamo sulla dashboard, avvia subito
        if (window.location.pathname.includes('dashboard.php')) {
            startGuida();
        }
    }

    /**
     * Riprende la guida dal punto salvato
     */
    function resumeGuida() {
        const saved = localStorage.getItem(CONFIG.storageKey);
        if (saved) {
            const progress = JSON.parse(saved);
            if (progress.currentStep > 0 && progress.currentStep < GUIDA_STEPS.length) {
                if (confirm(`Vuoi riprendere la guida dal passo ${progress.currentStep + 1}?`)) {
                    localStorage.setItem(CONFIG.showGuidaKey, 'true');
                    startGuida();
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Inizializza la guida
     */
    function init() {
        const urlParams = new URLSearchParams(window.location.search);
        let shouldStart = false;
        
        // Se c'è un parametro URL che richiede la guida
        if (urlParams.get('guida') === 'true' || urlParams.get('first') === 'true' || urlParams.get('show_guida') === 'true') {
            localStorage.setItem(CONFIG.showGuidaKey, 'true');
            shouldStart = true;
            // Rimuovi il parametro dall'URL senza ricaricare
            const newUrl = window.location.pathname;
            window.history.replaceState({}, document.title, newUrl);
        }
        
        // Se non c'è parametro URL, verifica localStorage
        if (!shouldStart && localStorage.getItem(CONFIG.showGuidaKey) === 'true') {
            shouldStart = true;
        }

        // Avvia la guida se necessario
        if (shouldStart) {
            // Verifica se c'è un progresso salvato da riprendere
            const saved = localStorage.getItem(CONFIG.storageKey);
            if (saved) {
                const progress = JSON.parse(saved);
                if (progress.currentStep > 0 && !resumeGuida()) {
                    // Se l'utente non vuole riprendere, inizia dall'inizio
                    currentStep = 0;
                }
            }
            
            // Piccolo delay per assicurarsi che il DOM sia pronto
            setTimeout(startGuida, 500);
        }
    }

    // Esporta funzioni pubbliche
    window.TaskFlowGuidaDashboard = {
        start: startGuida,
        reset: resetGuida,
        skip: skipGuida,
        resume: resumeGuida,
        isActive: () => isActive,
        next: nextStep,
        prev: prevStep,
        goToStep: showStep,
        getCurrentStep: () => currentStep
    };

    // Avvia automaticamente quando il DOM è pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
