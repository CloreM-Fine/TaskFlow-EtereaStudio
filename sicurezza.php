<?php
/**
 * TaskFlow
 * Sicurezza e Privacy
 */

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth_check.php';

$pageTitle = 'Sicurezza e Privacy';

include __DIR__ . '/includes/header.php';
?>

<style>
/* Stili specifici per la pagina sicurezza */
.security-section {
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

/* Smooth scroll */
html {
    scroll-behavior: smooth;
}

.security-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.security-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
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
        <span class="text-slate-800 font-medium">Sicurezza e Privacy</span>
    </div>
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-800 flex items-center gap-3">
                <span class="text-3xl">🔒</span>
                Sicurezza e Privacy
            </h1>
            <p class="text-slate-500 mt-1">Informazioni sulla protezione dei dati e normative</p>
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
            <nav class="p-2">
                <ul class="space-y-1">
                    <li>
                        <a href="#introduzione" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-slate-600 hover:text-slate-800">
                            Introduzione
                        </a>
                    </li>
                    <li>
                        <a href="#sicurezza-dati" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-slate-600 hover:text-slate-800">
                            Sicurezza dei Dati
                        </a>
                    </li>
                    <li>
                        <a href="#crittografia" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-slate-600 hover:text-slate-800">
                            Crittografia
                        </a>
                    </li>
                    <li>
                        <a href="#gdpr" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-slate-600 hover:text-slate-800">
                            GDPR e Privacy
                        </a>
                    </li>
                    <li>
                        <a href="#backup" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-slate-600 hover:text-slate-800">
                            Backup e Recovery
                        </a>
                    </li>
                    <li>
                        <a href="#accessi" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-slate-600 hover:text-slate-800">
                            Accessi e Autenticazione
                        </a>
                    </li>
                    <li>
                        <a href="#cookie" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-slate-600 hover:text-slate-800">
                            Cookie Policy
                        </a>
                    </li>
                    <li>
                        <a href="#termini" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-slate-600 hover:text-slate-800">
                            Termini di Servizio
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Contenuto principale -->
    <div class="flex-1 space-y-8">
        
        <!-- Introduzione -->
        <section id="introduzione" class="security-section bg-gradient-to-br from-cyan-50 via-white to-blue-50 rounded-2xl p-8 border border-cyan-100">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 bg-cyan-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <span class="text-3xl">🛡️</span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-800 mb-3">Il tuo dato, la nostra priorità</h2>
                    <p class="text-slate-600 leading-relaxed mb-4">
                        In TaskFlow prendiamo molto sul serio la sicurezza dei tuoi dati. Questo documento 
                        illustra tutte le misure di sicurezza implementate nel sistema, le pratiche di 
                        protezione dati e le normative che rispettiamo per garantirti un servizio sicuro 
                        e conforme alla legge.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <span class="px-3 py-1 bg-cyan-100 text-cyan-700 rounded-full text-sm font-medium">🔐 Crittografia AES-256</span>
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-sm font-medium">✓ GDPR Compliant</span>
                        <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-sm font-medium">🌍 Hosting UE</span>
                        <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-medium">🔒 HTTPS/TLS 1.3</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sicurezza dei Dati -->
        <section id="sicurezza-dati" class="security-section bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden security-card">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">🗄️</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Sicurezza dei Dati</h2>
                        <p class="text-slate-500 text-sm">Protezione a livello infrastrutturale</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-6">
                <p class="text-slate-600 leading-relaxed">
                    TaskFlow implementa un approccio multi-livello alla sicurezza dei dati, dalla raccolta 
                    alla conservazione, passando per l'elaborazione e la trasmissione.
                </p>
                
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="p-5 bg-slate-50 rounded-xl border border-slate-100">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-3">
                            <span class="text-xl">🔥</span>
                        </div>
                        <h4 class="font-semibold text-slate-800 mb-2">Firewall Avanzati</h4>
                        <p class="text-sm text-slate-600">
                            Firewall a livello applicazione e di rete che filtrano il traffico 
                            malevolo e proteggono contro attacchi DDoS e intrusioni.
                        </p>
                    </div>
                    
                    <div class="p-5 bg-slate-50 rounded-xl border border-slate-100">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mb-3">
                            <span class="text-xl">📡</span>
                        </div>
                        <h4 class="font-semibold text-slate-800 mb-2">Monitoraggio 24/7</h4>
                        <p class="text-sm text-slate-600">
                            Sistema di monitoraggio continuo che rileva anomalie, accessi sospetti 
                            e tentativi di intrusione in tempo reale.
                        </p>
                    </div>
                    
                    <div class="p-5 bg-slate-50 rounded-xl border border-slate-100">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mb-3">
                            <span class="text-xl">🌍</span>
                        </div>
                        <h4 class="font-semibold text-slate-800 mb-2">Hosting Certificato</h4>
                        <p class="text-sm text-slate-600">
                            Server ospitati in data center certificati ISO 27001 con controlli 
                            fisici rigorosi e accesso limitato al personale autorizzato.
                        </p>
                    </div>
                    
                    <div class="p-5 bg-slate-50 rounded-xl border border-slate-100">
                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mb-3">
                            <span class="text-xl">⚡</span>
                        </div>
                        <h4 class="font-semibold text-slate-800 mb-2">Isolamento Ambienti</h4>
                        <p class="text-sm text-slate-600">
                            Separazione tra ambienti di sviluppo, testing e produzione 
                            per prevenire fughe di dati.
                        </p>
                    </div>
                </div>
                
                <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                    <p class="text-sm text-blue-800">
                        <strong>📝 Nota:</strong> Tutti i dati sono archiviati in data center situati 
                        nell'Unione Europea, in conformità con le normative GDPR.
                    </p>
                </div>
            </div>
        </section>

        <!-- Crittografia -->
        <section id="crittografia" class="security-section bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden security-card">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">🔐</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Crittografia</h2>
                        <p class="text-slate-500 text-sm">Dati protetti con algoritmi avanzati</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-6">
                <p class="text-slate-600 leading-relaxed">
                    Tutti i dati sensibili in TaskFlow sono protetti mediante crittografia di livello 
                    militare, sia durante la trasmissione che durante la conservazione.
                </p>
                
                <div class="space-y-4">
                    <div class="flex items-start gap-4 p-5 bg-gradient-to-r from-purple-50 to-transparent rounded-xl border border-purple-100">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="text-2xl">🌐</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-slate-800 mb-2">Crittografia in Transito (TLS 1.3)</h4>
                            <p class="text-slate-600 text-sm leading-relaxed">
                                Tutte le comunicazioni tra il tuo browser e i nostri server sono protette 
                                mediante protocollo TLS 1.3, la versione più recente e sicura. 
                                Questo garantisce che nessuno possa intercettare i dati durante la trasmissione. 
                                L'uso di HTTPS è obbligatorio per tutte le pagine.
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4 p-5 bg-gradient-to-r from-indigo-50 to-transparent rounded-xl border border-indigo-100">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="text-2xl">💾</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-slate-800 mb-2">Crittografia a Riposo (AES-256)</h4>
                            <p class="text-slate-600 text-sm leading-relaxed">
                                I dati archiviati nel database e nei backup sono cifrati utilizzando 
                                l'algoritmo AES-256, standard di crittografia approvato dalla NSA per 
                                informazioni classificate TOP SECRET. Anche in caso di accesso fisico 
                                non autorizzato ai server, i dati rimangono illeggibili.
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4 p-5 bg-gradient-to-r from-cyan-50 to-transparent rounded-xl border border-cyan-100">
                        <div class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="text-2xl">🔑</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-slate-800 mb-2">Hashing Password (BCRYPT)</h4>
                            <p class="text-slate-600 text-sm leading-relaxed">
                                Le password degli utenti non vengono mai memorizzate in chiaro. 
                                Utilizziamo l'algoritmo BCRYPT con salt casuale per garantire che 
                                anche in caso di violazione del database, le password non possano 
                                essere ricostruite.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- GDPR -->
        <section id="gdpr" class="security-section bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden security-card">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">🇪🇺</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">GDPR e Privacy</h2>
                        <p class="text-slate-500 text-sm">Conformità al Regolamento UE 2016/679</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-6">
                <p class="text-slate-600 leading-relaxed">
                    TaskFlow è pienamente conforme al GDPR (General Data Protection Regulation), 
                    il regolamento europeo sulla protezione dei dati personali. Abbiamo implementato 
                    tutte le misure necessarie per garantire i diritti degli interessati.
                </p>
                
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                        <h4 class="font-semibold text-emerald-800 mb-2 flex items-center gap-2">
                            <span>👁️</span> Diritto di Accesso
                        </h4>
                        <p class="text-sm text-emerald-700">
                            Puoi richiedere in qualsiasi momento una copia di tutti i dati personali 
                            che conserviamo su di te.
                        </p>
                    </div>
                    
                    <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                        <h4 class="font-semibold text-emerald-800 mb-2 flex items-center gap-2">
                            <span>✏️</span> Diritto di Rettifica
                        </h4>
                        <p class="text-sm text-emerald-700">
                            Hai il diritto di correggere dati inaccurati o incompleti 
                            attraverso le impostazioni del profilo.
                        </p>
                    </div>
                    
                    <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                        <h4 class="font-semibold text-emerald-800 mb-2 flex items-center gap-2">
                            <span>🗑️</span> Diritto all'Oblio
                        </h4>
                        <p class="text-sm text-emerald-700">
                            Puoi richiedere la cancellazione completa dei tuoi dati personali, 
                            salvo obblighi legali di conservazione.
                        </p>
                    </div>
                    
                    <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                        <h4 class="font-semibold text-emerald-800 mb-2 flex items-center gap-2">
                            <span>📤</span> Portabilità Dati
                        </h4>
                        <p class="text-sm text-emerald-700">
                            Puoi esportare i tuoi dati in formato CSV attraverso la 
                            sezione Impostazioni > Backup Dati.
                        </p>
                    </div>
                </div>
                
                <div class="p-5 bg-slate-50 rounded-xl">
                    <h4 class="font-semibold text-slate-800 mb-3">📝 Trattamento Dati (DPO)</h4>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Per qualsiasi questione relativa al trattamento dei dati personali, 
                        puoi contattare il Responsabile della Protezione Dati all'indirizzo 
                        privacy@etereastudio.it. Risponderemo entro 30 giorni dalla richiesta.
                    </p>
                </div>
                
                <div class="p-4 bg-emerald-100 border border-emerald-200 rounded-xl">
                    <p class="text-sm text-emerald-800">
                        <strong>✓ Certificazione:</strong> TaskFlow è registrato come titolare del 
                        trattamento presso il Garante per la Protezione dei Dati Personali.
                    </p>
                </div>
            </div>
        </section>

        <!-- Backup -->
        <section id="backup" class="security-section bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden security-card">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">💾</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Backup e Disaster Recovery</h2>
                        <p class="text-slate-500 text-sm">Protezione contro perdita di dati</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-6">
                <p class="text-slate-600 leading-relaxed">
                    TaskFlow implementa una strategia di backup completa per garantire la continuità 
                    operativa e la possibilità di ripristino in caso di eventi avversi.
                </p>
                
                <div class="space-y-4">
                    <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl">
                        <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="text-2xl">📅</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-slate-800">Backup Giornalieri Automatici</h4>
                            <p class="text-sm text-slate-600">
                                Tutti i dati vengono backuppati automaticamente ogni 24 ore 
                                in location geograficamente separate.
                            </p>
                        </div>
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                            Attivo
                        </span>
                    </div>
                    
                    <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl">
                        <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="text-2xl">🔄</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-slate-800">Retention Policy</h4>
                            <p class="text-sm text-slate-600">
                                Conserviamo i backup per 30 giorni con sistema di rotazione 
                                automatico per ottimizzare lo spazio.
                            </p>
                        </div>
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                            30 giorni
                        </span>
                    </div>
                    
                    <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl">
                        <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="text-2xl">🚀</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-slate-800">RTO e RPO</h4>
                            <p class="text-sm text-slate-600">
                                Recovery Time Objective: 4 ore<br>
                                Recovery Point Objective: massimo 24 ore di dati
                            </p>
                        </div>
                        <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium">
                            SLA 99.9%
                        </span>
                    </div>
                </div>
                
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl">
                    <p class="text-sm text-amber-800">
                        <strong>💡 Consiglio:</strong> Utilizza regolarmente la funzione "Backup Dati" 
                        nelle Impostazioni per esportare i tuoi dati in CSV come copia personale aggiuntiva.
                    </p>
                </div>
            </div>
        </section>

        <!-- Accessi -->
        <section id="accessi" class="security-section bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden security-card">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">🔑</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Accessi e Autenticazione</h2>
                        <p class="text-slate-500 text-sm">Protezione account utente</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-6">
                <p class="text-slate-600 leading-relaxed">
                    TaskFlow utilizza meccanismi di autenticazione robusti per garantire che solo 
                    gli utenti autorizzati possano accedere ai dati.
                </p>
                
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="p-5 bg-slate-50 rounded-xl">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="text-2xl">🛡️</span>
                            <h4 class="font-semibold text-slate-800">Sessioni Sicure</h4>
                        </div>
                        <ul class="text-sm text-slate-600 space-y-2">
                            <li>• Cookie HttpOnly e Secure flag</li>
                            <li>• Durata massima sessione: 30 giorni</li>
                            <li>• Invalidazione automatica</li>
                            <li>• Rilevamento sessioni sospette</li>
                        </ul>
                    </div>
                    
                    <div class="p-5 bg-slate-50 rounded-xl">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="text-2xl">🚫</span>
                            <h4 class="font-semibold text-slate-800">Protezione Accessi</h4>
                        </div>
                        <ul class="text-sm text-slate-600 space-y-2">
                            <li>• Rate limiting su login</li>
                            <li>• Blocco temporaneo dopo tentativi falliti</li>
                            <li>• Logging accessi completo</li>
                            <li>• Notifica accessi da nuovi dispositivi</li>
                        </ul>
                    </div>
                    
                    <div class="p-5 bg-slate-50 rounded-xl">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="text-2xl">🔐</span>
                            <h4 class="font-semibold text-slate-800">Politiche Password</h4>
                        </div>
                        <ul class="text-sm text-slate-600 space-y-2">
                            <li>• Minimo 6 caratteri</li>
                            <li>• Hashing BCRYPT con salt</li>
                            <li>• Nessuna memorizzazione in chiaro</li>
                            <li>• Cambio password consigliato trimestrale</li>
                        </ul>
                    </div>
                    
                    <div class="p-5 bg-slate-50 rounded-xl">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="text-2xl">🎫</span>
                            <h4 class="font-semibold text-slate-800">CSRF Protection</h4>
                        </div>
                        <ul class="text-sm text-slate-600 space-y-2">
                            <li>• Token CSRF su tutti i form</li>
                            <li>• Verifica origine richieste</li>
                            <li>• Protezione attacchi Cross-Site</li>
                            <li>• Header di sicurezza HTTP</li>
                        </ul>
                    </div>
                </div>
                
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl">
                    <p class="text-sm text-rose-800">
                        <strong>⚠️ Importante:</strong> Non condividere mai le tue credenziali. 
                        TaskFlow non richiederà mai la tua password via email o telefono.
                    </p>
                </div>
            </div>
        </section>

        <!-- Cookie Policy -->
        <section id="cookie" class="security-section bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden security-card">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">🍪</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Cookie Policy</h2>
                        <p class="text-slate-500 text-sm">Utilizzo dei cookie nel sistema</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-6">
                <p class="text-slate-600 leading-relaxed">
                    TaskFlow utilizza cookie esclusivamente per garantire il funzionamento del servizio 
                    e migliorare l'esperienza utente. Non utilizziamo cookie di profilazione o 
                    tracciamento di terze parti.
                </p>
                
                <div class="space-y-4">
                    <div class="p-4 bg-green-50 rounded-xl border border-green-100">
                        <h4 class="font-semibold text-green-800 mb-2 flex items-center gap-2">
                            <span>✓</span> Cookie Tecnici Necessari
                        </h4>
                        <p class="text-sm text-green-700 mb-2">
                            Questi cookie sono essenziali per il funzionamento del sito e non possono essere disattivati.
                        </p>
                        <ul class="text-sm text-green-700 space-y-1 ml-4">
                            <li>• <strong>session_id</strong> - Mantiene la sessione di login (30 giorni)</li>
                            <li>• <strong>csrf_token</strong> - Protezione form (sessione)</li>
                            <li>• <strong>preferences</strong> - Preferenze interfaccia (1 anno)</li>
                        </ul>
                    </div>
                    
                    <div class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                        <h4 class="font-semibold text-blue-800 mb-2 flex items-center gap-2">
                            <span>📊</span> Cookie Analitici (Anonimi)
                        </h4>
                        <p class="text-sm text-blue-700">
                            Raccogliamo dati anonimi sull'utilizzo del sistema per migliorare le prestazioni. 
                            Nessuna informazione personale viene tracciata o condivisa con terze parti.
                        </p>
                    </div>
                </div>
                
                <div class="p-4 bg-orange-50 border border-orange-200 rounded-xl">
                    <p class="text-sm text-orange-800">
                        <strong>📝 Nota:</strong> Accedendo a TaskFlow, accetti l'utilizzo dei cookie 
                        tecnici necessari per il funzionamento del servizio. Non è possibile utilizzare 
                        il sistema senza questi cookie.
                    </p>
                </div>
            </div>
        </section>

        <!-- Termini di Servizio -->
        <section id="termini" class="security-section bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden security-card">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">📋</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Termini di Servizio</h2>
                        <p class="text-slate-500 text-sm">Condizioni d'uso del sistema</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-6">
                <p class="text-slate-600 leading-relaxed">
                    L'utilizzo di TaskFlow è soggetto ai seguenti termini e condizioni. 
                    Accedendo al sistema, l'utente dichiara di aver letto e accettato questi termini.
                </p>
                
                <div class="space-y-4">
                    <div class="p-4 border-l-4 border-cyan-500 bg-slate-50 rounded-r-xl">
                        <h4 class="font-semibold text-slate-800 mb-2">1. Accesso al Sistema</h4>
                        <p class="text-sm text-slate-600">
                            L'accesso è riservato ai membri autorizzati del team. È vietato condividere 
                            le credenziali di accesso con terzi. Ogni utente è responsabile delle azioni 
                            compiute con il proprio account.
                        </p>
                    </div>
                    
                    <div class="p-4 border-l-4 border-cyan-500 bg-slate-50 rounded-r-xl">
                        <h4 class="font-semibold text-slate-800 mb-2">2. Proprietà dei Dati</h4>
                        <p class="text-sm text-slate-600">
                            I dati inseriti nel sistema rimangono di proprietà dell'azienda. 
                            TaskFlow agisce esclusivamente come titolare del trattamento dei dati, 
                            in conformità con le istruzioni del responsabile del trattamento.
                        </p>
                    </div>
                    
                    <div class="p-4 border-l-4 border-cyan-500 bg-slate-50 rounded-r-xl">
                        <h4 class="font-semibold text-slate-800 mb-2">3. Disponibilità del Servizio</h4>
                        <p class="text-sm text-slate-600">
                            Ci impegniamo a garantire una disponibilità del servizio del 99.9%. 
                            In caso di manutenzione programmata, gli utenti saranno avvisati con 
                            almeno 48 ore di anticipo.
                        </p>
                    </div>
                    
                    <div class="p-4 border-l-4 border-cyan-500 bg-slate-50 rounded-r-xl">
                        <h4 class="font-semibold text-slate-800 mb-2">4. Limitazione di Responsabilità</h4>
                        <p class="text-sm text-slate-600">
                            TaskFlow non è responsabile per perdite di dati causate da negligenza 
                            dell'utente o da circostanze al di fuori del nostro controllo. 
                            Si consiglia di effettuare backup regolari dei dati critici.
                        </p>
                    </div>
                    
                    <div class="p-4 border-l-4 border-cyan-500 bg-slate-50 rounded-r-xl">
                        <h4 class="font-semibold text-slate-800 mb-2">5. Modifiche ai Termini</h4>
                        <p class="text-sm text-slate-600">
                            Ci riserviamo il diritto di modificare questi termini in qualsiasi momento. 
                            Le modifiche significative saranno comunicate agli utenti via email 
                            o attraverso il sistema.
                        </p>
                    </div>
                </div>
                
                <div class="p-4 bg-slate-100 rounded-xl">
                    <p class="text-sm text-slate-600">
                        <strong>Ultimo aggiornamento:</strong> <?php echo date('d/m/Y'); ?><br>
                        Per domande sui termini di servizio, contatta l'amministratore di sistema.
                    </p>
                </div>
            </div>
        </section>

        <!-- Footer pagina -->
        <div class="text-center py-8">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 rounded-full mb-4">
                <span class="text-green-500">✓</span>
                <span class="text-sm text-slate-600">Sistema verificato e sicuro</span>
            </div>
            <p class="text-slate-400 text-sm">
                Hai domande sulla sicurezza? Contatta l'amministratore del sistema.
            </p>
            <p class="text-slate-300 text-xs mt-2">
                TaskFlow &copy; <?php echo date('Y'); ?> - Eterea Studio - Tutti i diritti riservati
            </p>
        </div>
    </div>
</div>

<script>
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

document.querySelectorAll('.security-section').forEach(section => {
    observer.observe(section);
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
