<?php
/**
 * TaskFlow
 * Guida Utente
 */

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth_check.php';

$pageTitle = 'Guida Utente TaskFlow';

include __DIR__ . '/includes/header.php';
?>

<style>
/* Stili specifici per la pagina guida */
.guide-section {
    scroll-margin-top: 100px;
}

.sidebar-link {
    transition: all 0.2s ease;
}

.sidebar-link:hover {
    background-color: #f1f5f9;
    padding-left: 1rem;
}

.sidebar-link.active {
    background-color: #0891b2;
    color: white;
    padding-left: 1rem;
}

.sidebar-link.active:hover {
    background-color: #0e7490;
}

/* Search highlight */
.highlight {
    background-color: #fef08a;
    padding: 0 2px;
    border-radius: 2px;
}

/* Smooth scroll per anchor links */
html {
    scroll-behavior: smooth;
}

/* Screenshot placeholder */
.screenshot-placeholder {
    aspect-ratio: 16/9;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    font-weight: 500;
    color: #64748b;
}
</style>

<!-- Header con breadcrumb -->
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
        <a href="impostazioni.php" class="hover:text-cyan-600 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Impostazioni
        </a>
        <span>/</span>
        <span class="text-slate-800 font-medium">Guida Utente</span>
    </div>
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Guida Utente TaskFlow</h1>
            <p class="text-slate-500 mt-1">Manuale completo per l'utilizzo del gestionale</p>
        </div>
        <a href="impostazioni.php" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition-colors text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Torna alle impostazioni
        </a>
    </div>
</div>

<!-- Barra di ricerca -->
<div class="mb-6">
    <div class="relative max-w-2xl">
        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="text" id="searchGuide" placeholder="Cerca nella guida..." 
               class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 outline-none bg-white shadow-sm">
    </div>
</div>

<div class="flex flex-col lg:flex-row gap-6">
    <!-- Sidebar Indice -->
    <aside class="lg:w-64 lg:flex-shrink-0">
        <div class="lg:sticky lg:top-24 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-4 border-b border-slate-100">
                <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                    </svg>
                    Indice
                </h3>
            </div>
            <nav class="p-2 max-h-[calc(100vh-200px)] overflow-y-auto">
                <ul class="space-y-1">
                    <li>
                        <a href="#dashboard" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-slate-600 hover:text-slate-800">
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="#progetti" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-slate-600 hover:text-slate-800 font-medium">
                            Progetti
                        </a>
                        <ul class="ml-4 space-y-1 mt-1">
                            <li><a href="#creare-progetto" class="sidebar-link block px-3 py-1.5 rounded-lg text-xs text-slate-500 hover:text-slate-700">Creare un progetto</a></li>
                            <li><a href="#gestire-stato" class="sidebar-link block px-3 py-1.5 rounded-lg text-xs text-slate-500 hover:text-slate-700">Gestire lo stato</a></li>
                            <li><a href="#distribuzione-economica" class="sidebar-link block px-3 py-1.5 rounded-lg text-xs text-slate-500 hover:text-slate-700">Distribuzione economica</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#clienti" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-slate-600 hover:text-slate-800 font-medium">
                            Clienti
                        </a>
                        <ul class="ml-4 space-y-1 mt-1">
                            <li><a href="#anagrafica" class="sidebar-link block px-3 py-1.5 rounded-lg text-xs text-slate-500 hover:text-slate-700">Anagrafica</a></li>
                            <li><a href="#storico-progetti" class="sidebar-link block px-3 py-1.5 rounded-lg text-xs text-slate-500 hover:text-slate-700">Storico progetti</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#task" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-slate-600 hover:text-slate-800 font-medium">
                            Task
                        </a>
                        <ul class="ml-4 space-y-1 mt-1">
                            <li><a href="#creare-task" class="sidebar-link block px-3 py-1.5 rounded-lg text-xs text-slate-500 hover:text-slate-700">Creare task</a></li>
                            <li><a href="#assegnare-task" class="sidebar-link block px-3 py-1.5 rounded-lg text-xs text-slate-500 hover:text-slate-700">Assegnare task</a></li>
                            <li><a href="#completare-task" class="sidebar-link block px-3 py-1.5 rounded-lg text-xs text-slate-500 hover:text-slate-700">Completare task</a></li>
                        </ul>
                    </li>
                    <li><a href="#calendario" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-slate-600 hover:text-slate-800">Calendario</a></li>
                    <li><a href="#scadenze" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-slate-600 hover:text-slate-800">Scadenze</a></li>
                    <li><a href="#preventivi" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-slate-600 hover:text-slate-800">Preventivi</a></li>
                    <li>
                        <a href="#finanze" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-slate-600 hover:text-slate-800 font-medium">
                            Finanze
                        </a>
                        <ul class="ml-4 space-y-1 mt-1">
                            <li><a href="#cassa-aziendale" class="sidebar-link block px-3 py-1.5 rounded-lg text-xs text-slate-500 hover:text-slate-700">Cassa aziendale</a></li>
                            <li><a href="#wallet-personale" class="sidebar-link block px-3 py-1.5 rounded-lg text-xs text-slate-500 hover:text-slate-700">Wallet personale</a></li>
                        </ul>
                    </li>
                    <li><a href="#tasse" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-slate-600 hover:text-slate-800">Tasse</a></li>
                    <li><a href="#impostazioni" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-slate-600 hover:text-slate-800">Impostazioni</a></li>
                    <li><a href="#briefing-ai" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-slate-600 hover:text-slate-800">Briefing AI</a></li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Contenuto principale -->
    <div class="flex-1 space-y-8">
        
        <!-- Introduzione -->
        <div class="bg-gradient-to-r from-cyan-50 to-blue-50 rounded-2xl p-6 border border-cyan-100">
            <h2 class="text-lg font-bold text-slate-800 mb-3">Benvenuto in TaskFlow!</h2>
            <p class="text-slate-600 leading-relaxed">
                TaskFlow è il gestionale completo per la tua agenzia creativa. Questa guida ti aiuterà a utilizzare 
                tutte le funzionalità del sistema, dalla gestione dei progetti al controllo delle finanze. 
                Usa l'indice laterale per navigare rapidamente alle sezioni di tuo interesse.
            </p>
        </div>

        <!-- Dashboard -->
        <section id="dashboard" class="guide-section bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Dashboard</h2>
                        <p class="text-slate-500 text-sm">Panoramica delle attività e statistiche</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-6">
                <p class="text-slate-600 leading-relaxed">
                    La Dashboard è la tua homepage personale in TaskFlow. Qui trovi una panoramica completa delle attività 
                    in corso, i progetti attivi, le scadenze imminenti e le statistiche delle tue performance.
                </p>
                
                <div class="bg-slate-100 rounded-xl screenshot-placeholder">
                    [Screenshot Dashboard]
                </div>
                
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="p-4 bg-slate-50 rounded-xl">
                        <h4 class="font-semibold text-slate-800 mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            Statistiche
                        </h4>
                        <ul class="text-sm text-slate-600 space-y-1 ml-7">
                            <li>• Progetti completati questo mese</li>
                            <li>• Entrate totali del periodo</li>
                            <li>• Task in scadenza</li>
                            <li>• Le tue performance</li>
                        </ul>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl">
                        <h4 class="font-semibold text-slate-800 mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Scadenze
                        </h4>
                        <ul class="text-sm text-slate-600 space-y-1 ml-7">
                            <li>• Progetti in scadenza oggi</li>
                            <li>• Task da completare</li>
                            <li>• Appuntamenti del giorno</li>
                            <li>• Notifiche importanti</li>
                        </ul>
                    </div>
                </div>
                
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl">
                    <p class="text-sm text-amber-800">
                        <strong>💡 Tip:</strong> Clicca su qualsiasi elemento della dashboard per accedere direttamente 
                        alla sezione corrispondente.
                    </p>
                </div>
            </div>
        </section>

        <!-- Progetti -->
        <section id="progetti" class="guide-section bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Progetti</h2>
                        <p class="text-slate-500 text-sm">Gestione completa dei progetti</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-8">
                
                <div id="creare-progetto" class="space-y-4">
                    <h3 class="text-lg font-semibold text-slate-800">Creare un progetto</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Per creare un nuovo progetto, vai su <strong>Progetti</strong> e clicca sul pulsante verde 
                        "Nuovo Progetto". Compila il form con:
                    </p>
                    <ul class="list-disc list-inside text-slate-600 space-y-2 ml-4">
                        <li><strong>Nome progetto:</strong> Un titolo descrittivo</li>
                        <li><strong>Cliente:</strong> Seleziona dall'anagrafica o crea un nuovo cliente</li>
                        <li><strong>Tipologia:</strong> Sito Web, Branding, Social, ecc.</li>
                        <li><strong>Data scadenza:</strong> Quando il progetto deve essere consegnato</li>
                        <li><strong>Assegnazione:</strong> Il progetto sarà gestito da te</li>
                        <li><strong>Budget:</strong> Importo totale del progetto</li>
                        <li><strong>Distribuzione:</strong> 90% al tuo wallet, 10% cassa aziendale</li>
                    </ul>
                    <div class="bg-slate-100 rounded-xl screenshot-placeholder">
                        [Screenshot Creazione Progetto]
                    </div>
                </div>
                
                <hr class="border-slate-200">
                
                <div id="gestire-stato" class="space-y-4">
                    <h3 class="text-lg font-semibold text-slate-800">Gestire lo stato</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Ogni progetto ha uno stato che ne indica l'avanzamento. Puoi modificare lo stato 
                        dalla pagina di dettaglio del progetto:
                    </p>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div class="p-3 bg-slate-100 rounded-lg text-center">
                            <span class="inline-block w-3 h-3 bg-slate-400 rounded-full mb-2"></span>
                            <p class="text-sm font-medium text-slate-700">Da Iniziare</p>
                        </div>
                        <div class="p-3 bg-amber-50 rounded-lg text-center">
                            <span class="inline-block w-3 h-3 bg-amber-500 rounded-full mb-2"></span>
                            <p class="text-sm font-medium text-amber-700">In Lavorazione</p>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-lg text-center">
                            <span class="inline-block w-3 h-3 bg-purple-500 rounded-full mb-2"></span>
                            <p class="text-sm font-medium text-purple-700">In Revisione</p>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-lg text-center">
                            <span class="inline-block w-3 h-3 bg-emerald-500 rounded-full mb-2"></span>
                            <p class="text-sm font-medium text-emerald-700">Completato</p>
                        </div>
                    </div>
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                        <p class="text-sm text-blue-800">
                            <strong>💡 Tip:</strong> Quando un progetto viene segnato come "Completato", 
                            il sistema distribuisce automaticamente il profitto: 90% al tuo wallet e 10% in cassa aziendale.
                        </p>
                    </div>
                </div>
                
                <hr class="border-slate-200">
                
                <div id="distribuzione-economica" class="space-y-4">
                    <h3 class="text-lg font-semibold text-slate-800">Distribuzione economica</h3>
                    <p class="text-slate-600 leading-relaxed">
                        TaskFlow calcola automaticamente la ripartizione del profitto quando un progetto viene completato:
                    </p>
                    <div class="space-y-3">
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <p class="font-medium text-slate-800 mb-2">👤 Sistema mono-utente</p>
                            <p class="text-sm text-slate-600">90% al tuo wallet personale + 10% cassa aziendale</p>
                        </div>
                    </div>
                    <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                        <p class="text-sm text-emerald-800">
                            <strong>💡 Nota:</strong> La distribuzione avviene automaticamente al completamento del progetto. 
                            Il 90% viene accreditato sul tuo wallet personale, mentre il 10% viene destinato alla cassa aziendale 
                            per spese operative e investimenti.
                        </p>
                    </div>
                    <div class="bg-slate-100 rounded-xl screenshot-placeholder">
                        [Screenshot Distribuzione Economica]
                    </div>
                </div>
            </div>
        </section>

        <!-- Clienti -->
        <section id="clienti" class="guide-section bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Clienti</h2>
                        <p class="text-slate-500 text-sm">Gestione anagrafica clienti</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-8">
                
                <div id="anagrafica" class="space-y-4">
                    <h3 class="text-lg font-semibold text-slate-800">Anagrafica</h3>
                    <p class="text-slate-600 leading-relaxed">
                        La sezione Clienti ti permette di gestire tutti i contatti della tua agenzia. 
                        Puoi registrare sia clienti privati che aziende, con tutti i dati necessari per 
                        fatturazione e comunicazione:
                    </p>
                    <ul class="list-disc list-inside text-slate-600 space-y-2 ml-4">
                        <li>Dati anagrafici (nome, cognome, ragione sociale)</li>
                        <li>Contatti (email, telefono, indirizzo)</li>
                        <li>Dati fiscali (P.IVA, Codice Fiscale, SDI, PEC)</li>
                        <li>Note e informazioni aggiuntive</li>
                    </ul>
                    <div class="bg-slate-100 rounded-xl screenshot-placeholder">
                        [Screenshot Anagrafica Clienti]
                    </div>
                </div>
                
                <hr class="border-slate-200">
                
                <div id="storico-progetti" class="space-y-4">
                    <h3 class="text-lg font-semibold text-slate-800">Storico progetti</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Per ogni cliente puoi visualizzare lo storico completo dei progetti, con:
                    </p>
                    <ul class="list-disc list-inside text-slate-600 space-y-2 ml-4">
                        <li>Elenco di tutti i progetti associati</li>
                        <li>Budget totale generato dal cliente</li>
                        <li>Stato dei progetti (attivi/completati)</li>
                        <li>Data dell'ultimo progetto</li>
                    </ul>
                    <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                        <p class="text-sm text-emerald-800">
                            <strong>💡 Tip:</strong> Clicca sul nome di un cliente per aprire la sua scheda 
                            dettagliata con tutti i progetti associati.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Task -->
        <section id="task" class="guide-section bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Task</h2>
                        <p class="text-slate-500 text-sm">Gestione delle attività</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-8">
                
                <div id="creare-task" class="space-y-4">
                    <h3 class="text-lg font-semibold text-slate-800">Creare task</h3>
                    <p class="text-slate-600 leading-relaxed">
                        I task sono le singole attività all'interno di un progetto. Per creare un task:
                    </p>
                    <ol class="list-decimal list-inside text-slate-600 space-y-2 ml-4">
                        <li>Apri il progetto desiderato</li>
                        <li>Vai alla sezione "Task"</li>
                        <li>Clicca su "Nuovo Task"</li>
                        <li>Compila titolo, descrizione e data di scadenza</li>
                        <li>Assegna il task a te stesso</li>
                    </ol>
                    <div class="bg-slate-100 rounded-xl screenshot-placeholder">
                        [Screenshot Creazione Task]
                    </div>
                </div>
                
                <hr class="border-slate-200">
                
                <div id="assegnare-task" class="space-y-4">
                    <h3 class="text-lg font-semibold text-slate-800">Assegnare task</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Ogni task può essere assegnato a te stesso. I task assegnati 
                        saranno visibili nella tua dashboard e nelle scadenze personali.
                    </p>
                    <div class="p-4 bg-purple-50 border border-purple-200 rounded-xl">
                        <p class="text-sm text-purple-800">
                            <strong>💡 Tip:</strong> Usa le etichette di priorità (Alta, Media, Bassa) 
                            per indicare l'urgenza del task.
                        </p>
                    </div>
                </div>
                
                <hr class="border-slate-200">
                
                <div id="completare-task" class="space-y-4">
                    <h3 class="text-lg font-semibold text-slate-800">Completare task</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Per segnare un task come completato, clicca sulla checkbox accanto al titolo 
                        o apri il task e cambia lo stato. I task completati rimangono visibili 
                        nello storico del progetto.
                    </p>
                    <div class="bg-slate-100 rounded-xl screenshot-placeholder">
                        [Screenshot Task Completati]
                    </div>
                </div>
            </div>
        </section>

        <!-- Calendario -->
        <section id="calendario" class="guide-section bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Calendario</h2>
                        <p class="text-slate-500 text-sm">Appuntamenti e scadenze</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-6">
                <p class="text-slate-600 leading-relaxed">
                    Il Calendario integrato ti permette di gestire appuntamenti, riunioni e scadenze. 
                    Puoi visualizzare gli eventi in modalità mese, settimana o giorno.
                </p>
                
                <div class="bg-slate-100 rounded-xl screenshot-placeholder">
                    [Screenshot Calendario]
                </div>
                
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="p-4 bg-slate-50 rounded-xl">
                        <h4 class="font-semibold text-slate-800 mb-3">📝 Creare eventi</h4>
                        <ul class="text-sm text-slate-600 space-y-2">
                            <li>• Clicca su una data o trascina per selezionare l'orario</li>
                            <li>• Inserisci titolo e descrizione</li>
                            <li>• Seleziona il progetto associato (opzionale)</li>
                            <li>• Aggiungi descrizione e dettagli</li>
                        </ul>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl">
                        <h4 class="font-semibold text-slate-800 mb-3">🔗 Integrazioni</h4>
                        <ul class="text-sm text-slate-600 space-y-2">
                            <li>• Scadenze progetti mostrate automaticamente</li>
                            <li>• Task in scadenza visibili nel calendario</li>
                            <li>• Colori diversi per tipo di evento</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Scadenze -->
        <section id="scadenze" class="guide-section bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Scadenze</h2>
                        <p class="text-slate-500 text-sm">Monitoraggio deadline</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-6">
                <p class="text-slate-600 leading-relaxed">
                    La pagina Scadenze raccoglie tutte le deadline imminenti: progetti da consegnare, 
                    task da completare e appuntamenti importanti. Una visualizzazione dedicata 
                    per non perdere mai una scadenza.
                </p>
                
                <div class="bg-slate-100 rounded-xl screenshot-placeholder">
                    [Screenshot Scadenze]
                </div>
                
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl">
                    <p class="text-sm text-rose-800">
                        <strong>⚠️ Nota:</strong> Le scadenze urgenti (entro 24 ore) sono evidenziate 
                        in rosso nella dashboard e nelle notifiche.
                    </p>
                </div>
            </div>
        </section>

        <!-- Preventivi -->
        <section id="preventivi" class="guide-section bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 15.536c-1.171 1.952-3.07 1.952-4.242 0-1.172-1.953-1.172-5.119 0-7.072 1.171-1.952 3.07-1.952 4.242 0M8 10.5h4m-4 3h4m9-1.5a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Preventivi</h2>
                        <p class="text-slate-500 text-sm">Generazione preventivi professionali</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-6">
                <p class="text-slate-600 leading-relaxed">
                    Il sistema di preventivi ti permette di creare offerte professionali in pochi click. 
                    Compila i servizi, le quantità e i prezzi per generare un PDF pronto da inviare al cliente.
                </p>
                
                <div class="bg-slate-100 rounded-xl screenshot-placeholder">
                    [Screenshot Preventivi]
                </div>
                
                <div class="space-y-4">
                    <h4 class="font-semibold text-slate-800">Funzionalità principali:</h4>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <p class="font-medium text-slate-700 mb-2">📋 Listini prezzi</p>
                            <p class="text-sm text-slate-600">Crea listini predefiniti per velocizzare la creazione dei preventivi</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <p class="font-medium text-slate-700 mb-2">📄 Template condizioni</p>
                            <p class="text-sm text-slate-600">Gestisci le condizioni generali da includere nei preventivi</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <p class="font-medium text-slate-700 mb-2">🖊️ Firme digitali</p>
                            <p class="text-sm text-slate-600">Inserisci la firma del fornitore nel PDF del preventivo</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <p class="font-medium text-slate-700 mb-2">💾 Salvataggio</p>
                            <p class="text-sm text-slate-600">Salva i preventivi per modificarli o duplicarli in futuro</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl">
                    <p class="text-sm text-amber-800">
                        <strong>💡 Tip:</strong> I preventivi salvati possono essere convertiti 
                        direttamente in progetti con un solo click.
                    </p>
                </div>
            </div>
        </section>

        <!-- Finanze -->
        <section id="finanze" class="guide-section bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Finanze</h2>
                        <p class="text-slate-500 text-sm">Gestione economica e wallet</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-8">
                
                <div id="cassa-aziendale" class="space-y-4">
                    <h3 class="text-lg font-semibold text-slate-800">Cassa aziendale</h3>
                    <p class="text-slate-600 leading-relaxed">
                        La Cassa rappresenta il fondo comune dell'agenzia. Ogni progetto completato 
                        versa automaticamente una quota in cassa (10%). Questi fondi possono essere 
                        utilizzati per spese operative, investimenti e costi aziendali.
                    </p>
                    <div class="bg-slate-100 rounded-xl screenshot-placeholder">
                        [Screenshot Cassa Aziendale]
                    </div>
                </div>
                
                <hr class="border-slate-200">
                
                <div id="wallet-personale" class="space-y-4">
                    <h3 class="text-lg font-semibold text-slate-800">Wallet personale</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Hai un wallet personale dove vengono accreditate le quote (90%) 
                        dai progetti completati. Dal wallet puoi:
                    </p>
                    <ul class="list-disc list-inside text-slate-600 space-y-2 ml-4">
                        <li>Visualizzare il saldo attuale</li>
                        <li>Consultare lo storico delle transazioni</li>
                        <li>Richiedere un prelievo (registrato manualmente)</li>
                        <li>Monitorare le entrate per progetto</li>
                    </ul>
                    <div class="bg-slate-100 rounded-xl screenshot-placeholder">
                        [Screenshot Wallet Personale]
                    </div>
                    <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                        <p class="text-sm text-emerald-800">
                            <strong>💡 Tip:</strong> Usa la sezione Finanze per tenere traccia 
                            di tutti i movimenti economici e avere una visione chiara della situazione.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Tasse -->
        <section id="tasse" class="guide-section bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Tasse</h2>
                        <p class="text-slate-500 text-sm">Riepilogo fiscale</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-6">
                <p class="text-slate-600 leading-relaxed">
                    La sezione Tasse fornisce un riepilogo automatico dei dati fiscali basato sulle entrate 
                    registrate nel sistema. Calcola automaticamente imposte, contributi e tasse in base 
                    ai codici ATECO configurati.
                </p>
                
                <div class="bg-slate-100 rounded-xl screenshot-placeholder">
                    [Screenshot Sezione Tasse]
                </div>
                
                <div class="grid md:grid-cols-3 gap-4">
                    <div class="p-4 bg-slate-50 rounded-xl text-center">
                        <p class="text-2xl font-bold text-teal-600 mb-1">📊</p>
                        <p class="font-medium text-slate-700">Coefficienti ATECO</p>
                        <p class="text-sm text-slate-500">Gestione multipla attività</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl text-center">
                        <p class="text-2xl font-bold text-teal-600 mb-1">🧮</p>
                        <p class="font-medium text-slate-700">Calcolo automatico</p>
                        <p class="text-sm text-slate-500">INPS, IRPEF, acconti</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl text-center">
                        <p class="text-2xl font-bold text-teal-600 mb-1">📅</p>
                        <p class="font-medium text-slate-700">Periodo fiscale</p>
                        <p class="text-sm text-slate-500">Personalizzabile (mese/anno)</p>
                    </div>
                </div>
                
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl">
                    <p class="text-sm text-amber-800">
                        <strong>⚠️ Nota:</strong> I calcoli forniti sono indicativi. Consulta sempre 
                        il tuo commercialista per la dichiarazione fiscale ufficiale.
                    </p>
                </div>
            </div>
        </section>

        <!-- Impostazioni -->
        <section id="impostazioni" class="guide-section bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Impostazioni</h2>
                        <p class="text-slate-500 text-sm">Configurazione avanzata</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-6">
                <p class="text-slate-600 leading-relaxed">
                    Le Impostazioni permettono di configurare tutti gli aspetti del gestionale, 
                    dai dati aziendali ai parametri di sicurezza.
                </p>
                
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="p-4 bg-slate-50 rounded-xl">
                        <h4 class="font-semibold text-slate-800 mb-2">👤 Profilo</h4>
                        <p class="text-sm text-slate-600">Cambia avatar, password e preferenze personali</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl">
                        <h4 class="font-semibold text-slate-800 mb-2">🏢 Dati Azienda</h4>
                        <p class="text-sm text-slate-600">Configura ragione sociale, P.IVA, indirizzo per fatturazione</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl">
                        <h4 class="font-semibold text-slate-800 mb-2">📊 Contabilità</h4>
                        <p class="text-sm text-slate-600">Imposta periodo di riepilogo e parametri fiscali</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl">
                        <h4 class="font-semibold text-slate-800 mb-2">💾 Backup</h4>
                        <p class="text-sm text-slate-600">Esporta dati in CSV e gestisci la sicurezza</p>
                    </div>
                </div>
                
                <div class="bg-slate-100 rounded-xl screenshot-placeholder">
                    [Screenshot Impostazioni]
                </div>
            </div>
        </section>

        <!-- Briefing AI -->
        <section id="briefing-ai" class="guide-section bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Briefing AI</h2>
                        <p class="text-slate-500 text-sm">Trascrizione brief vocali</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-6">
                <p class="text-slate-600 leading-relaxed">
                    Il Briefing AI è uno strumento avanzato che permette di registrare brief vocali con i clienti 
                    e trascriverli automaticamente in documenti PDF professionali. Perfetto per catturare 
                    ogni dettaglio di un progetto durante call o riunioni.
                </p>
                
                <div class="bg-slate-100 rounded-xl screenshot-placeholder">
                    [Screenshot Briefing AI]
                </div>
                
                <div class="space-y-4">
                    <h4 class="font-semibold text-slate-800">Come funziona:</h4>
                    <div class="grid md:grid-cols-4 gap-4">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-violet-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <span class="text-lg font-bold text-violet-600">1</span>
                            </div>
                            <p class="text-sm text-slate-600">Registra il brief vocale</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 bg-violet-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <span class="text-lg font-bold text-violet-600">2</span>
                            </div>
                            <p class="text-sm text-slate-600">Trascrivi con AI</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 bg-violet-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <span class="text-lg font-bold text-violet-600">3</span>
                            </div>
                            <p class="text-sm text-slate-600">Rivedi e modifica</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 bg-violet-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <span class="text-lg font-bold text-violet-600">4</span>
                            </div>
                            <p class="text-sm text-slate-600">Salva nel progetto</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 bg-violet-50 border border-violet-200 rounded-xl">
                    <p class="text-sm text-violet-800">
                        <strong>💡 Tip:</strong> Il brief trasformato in PDF può essere allegato 
                        direttamente al progetto come documento ufficiale di riferimento.
                    </p>
                </div>
            </div>
        </section>

        <!-- Footer pagina -->
        <div class="text-center py-8">
            <p class="text-slate-400 text-sm">
                Hai bisogno di ulteriore assistenza? Contatta l'amministratore del sistema.
            </p>
            <p class="text-slate-300 text-xs mt-2">
                TaskFlow &copy; <?php echo date('Y'); ?> - Eterea Studio
            </p>
        </div>
    </div>
</div>

<script>
// Funzione di ricerca
document.getElementById('searchGuide').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const sections = document.querySelectorAll('.guide-section');
    
    // Rimuovi highlight precedenti
    document.querySelectorAll('.highlight').forEach(el => {
        const parent = el.parentNode;
        parent.replaceChild(document.createTextNode(el.textContent), el);
        parent.normalize();
    });
    
    if (searchTerm.length < 2) {
        sections.forEach(section => {
            section.style.display = 'block';
        });
        return;
    }
    
    sections.forEach(section => {
        const text = section.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            section.style.display = 'block';
            // Highlight del testo
            highlightText(section, searchTerm);
        } else {
            section.style.display = 'none';
        }
    });
});

function highlightText(element, searchTerm) {
    const walker = document.createTreeWalker(
        element,
        NodeFilter.SHOW_TEXT,
        null,
        false
    );
    
    const textNodes = [];
    let node;
    while (node = walker.nextNode()) {
        if (node.parentNode.tagName !== 'SCRIPT' && 
            node.textContent.toLowerCase().includes(searchTerm)) {
            textNodes.push(node);
        }
    }
    
    textNodes.forEach(node => {
        const span = document.createElement('span');
        const regex = new RegExp(`(${escapeRegex(searchTerm)})`, 'gi');
        span.innerHTML = node.textContent.replace(regex, '<span class="highlight">$1</span>');
        node.parentNode.replaceChild(span, node);
    });
}

function escapeRegex(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

// Active link on scroll
const observerOptions = {
    root: null,
    rootMargin: '-20% 0px -80% 0px',
    threshold: 0
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const id = entry.target.getAttribute('id');
            document.querySelectorAll('.sidebar-link').forEach(link => {
                link.classList.remove('active');
            });
            const activeLink = document.querySelector(`.sidebar-link[href="#${id}"]`);
            if (activeLink) {
                activeLink.classList.add('active');
            }
        }
    });
}, observerOptions);

document.querySelectorAll('.guide-section, [id]').forEach(section => {
    if (section.id) {
        observer.observe(section);
    }
});

// Smooth scroll per i link
document.querySelectorAll('.sidebar-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            targetElement.scrollIntoView({ behavior: 'smooth' });
        }
    });
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
