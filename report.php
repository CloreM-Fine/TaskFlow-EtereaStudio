<?php
/**
 * TaskFlow - Report e Analytics
 * Dashboard report dettagliati
 */

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth_check.php';

$pageTitle = 'Report';

include __DIR__ . '/includes/header.php';
?>

<!-- Chart.js per grafici -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="min-h-screen bg-slate-50 pb-20 lg:pb-0">
    <!-- Header -->
    <div class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-800">📊 Report</h1>
                    <p class="text-xs sm:text-sm text-slate-500">Analytics e statistiche dettagliate</p>
                </div>
                <button onclick="refreshAllData()" class="p-2 text-slate-400 hover:text-cyan-600 transition-colors" title="Aggiorna dati">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex overflow-x-auto scrollbar-hide gap-2 py-3">
                <button onclick="switchTab('overview')" id="tab-overview" class="tab-btn active px-4 py-2 rounded-lg font-medium text-sm whitespace-nowrap transition-all bg-cyan-600 text-white">
                    Panoramica
                </button>
                <button onclick="switchTab('progetti')" id="tab-progetti" class="tab-btn px-4 py-2 rounded-lg font-medium text-sm whitespace-nowrap transition-all text-slate-600 hover:bg-slate-100">
                    Progetti
                </button>
                <button onclick="switchTab('finanze')" id="tab-finanze" class="tab-btn px-4 py-2 rounded-lg font-medium text-sm whitespace-nowrap transition-all text-slate-600 hover:bg-slate-100">
                    Finanze
                </button>
                <button onclick="switchTab('task')" id="tab-task" class="tab-btn px-4 py-2 rounded-lg font-medium text-sm whitespace-nowrap transition-all text-slate-600 hover:bg-slate-100">
                    Task
                </button>
                <button onclick="switchTab('clienti')" id="tab-clienti" class="tab-btn px-4 py-2 rounded-lg font-medium text-sm whitespace-nowrap transition-all text-slate-600 hover:bg-slate-100">
                    Clienti
                </button>
                <button onclick="switchTab('tempi')" id="tab-tempi" class="tab-btn px-4 py-2 rounded-lg font-medium text-sm whitespace-nowrap transition-all text-slate-600 hover:bg-slate-100">
                    Tempi
                </button>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        
        <!-- Loading State -->
        <div id="loadingState" class="hidden">
            <div class="flex items-center justify-center py-20">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-cyan-600"></div>
                <span class="ml-3 text-slate-600">Caricamento dati...</span>
            </div>
        </div>

        <!-- TAB: OVERVIEW -->
        <div id="content-overview" class="tab-content space-y-6">
            <!-- KPI Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-slate-500">Progetti Totali</p>
                            <p class="text-2xl sm:text-3xl font-bold text-slate-800" id="ov-progetti-totali">-</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-cyan-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center gap-2 text-xs">
                        <span class="text-amber-600" id="ov-progetti-corso">- in corso</span>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-slate-500">Fatturato</p>
                            <p class="text-2xl sm:text-3xl font-bold text-slate-800" id="ov-fatturato">-</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center gap-2 text-xs">
                        <span class="text-emerald-600" id="ov-pagato">- pagato</span>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-slate-500">Task Complete</p>
                            <p class="text-2xl sm:text-3xl font-bold text-slate-800" id="ov-task-complete">-</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center gap-2 text-xs">
                        <span class="text-slate-500" id="ov-task-percent">-</span>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-slate-500">Clienti</p>
                            <p class="text-2xl sm:text-3xl font-bold text-slate-800" id="ov-clienti">-</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center gap-2 text-xs">
                        <span class="text-slate-500">attivi</span>
                    </div>
                </div>
            </div>

            <!-- Grafico Trend -->
            <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm border border-slate-200">
                <h3 class="font-semibold text-slate-800 mb-4">📈 Trend Progetti (Ultimi 12 mesi)</h3>
                <div class="h-64 sm:h-80">
                    <canvas id="chartTrendOverview"></canvas>
                </div>
            </div>

            <!-- Progetti Recenti -->
            <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm border border-slate-200">
                <h3 class="font-semibold text-slate-800 mb-4">📋 Progetti Recenti</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="text-left p-3 rounded-l-lg">Progetto</th>
                                <th class="text-left p-3">Stato</th>
                                <th class="text-right p-3 rounded-r-lg">Valore</th>
                            </tr>
                        </thead>
                        <tbody id="tableProgettiRecenti" class="divide-y divide-slate-100">
                            <tr><td colspan="3" class="p-4 text-center text-slate-400">Caricamento...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB: PROGETTI -->
        <div id="content-progetti" class="tab-content hidden space-y-6">
            <div class="grid lg:grid-cols-2 gap-6">
                <!-- Grafico per Tipologia -->
                <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm border border-slate-200">
                    <h3 class="font-semibold text-slate-800 mb-4">📊 Progetti per Tipologia</h3>
                    <div class="h-64">
                        <canvas id="chartProgettiTipo"></canvas>
                    </div>
                </div>

                <!-- Grafico per Stato -->
                <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm border border-slate-200">
                    <h3 class="font-semibold text-slate-800 mb-4">📊 Progetti per Stato</h3>
                    <div class="h-64">
                        <canvas id="chartProgettiStato"></canvas>
                    </div>
                </div>
            </div>

            <!-- Andamento Mensile -->
            <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm border border-slate-200">
                <h3 class="font-semibold text-slate-800 mb-4">📈 Andamento Mensile</h3>
                <div class="h-64 sm:h-80">
                    <canvas id="chartProgettiMensile"></canvas>
                </div>
            </div>

            <!-- Top Progetti -->
            <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm border border-slate-200">
                <h3 class="font-semibold text-slate-800 mb-4">🏆 Top 5 Progetti per Valore</h3>
                <div id="listTopProgetti" class="space-y-3">
                    <p class="text-center text-slate-400 py-4">Caricamento...</p>
                </div>
            </div>
        </div>

        <!-- TAB: FINANZE -->
        <div id="content-finanze" class="tab-content hidden space-y-6">
            <!-- Cassa -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-emerald-50 rounded-xl p-4 sm:p-6 border border-emerald-100">
                    <p class="text-sm text-emerald-600 font-medium">Entrate Cassa</p>
                    <p class="text-2xl sm:text-3xl font-bold text-emerald-700 mt-1" id="fin-entrate">-</p>
                </div>
                <div class="bg-rose-50 rounded-xl p-4 sm:p-6 border border-rose-100">
                    <p class="text-sm text-rose-600 font-medium">Uscite Cassa</p>
                    <p class="text-2xl sm:text-3xl font-bold text-rose-700 mt-1" id="fin-uscite">-</p>
                </div>
                <div class="bg-blue-50 rounded-xl p-4 sm:p-6 border border-blue-100">
                    <p class="text-sm text-blue-600 font-medium">Saldo Cassa</p>
                    <p class="text-2xl sm:text-3xl font-bold text-blue-700 mt-1" id="fin-saldo">-</p>
                </div>
            </div>

            <!-- Grafico Andamento -->
            <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm border border-slate-200">
                <h3 class="font-semibold text-slate-800 mb-4">📈 Andamento Cassa Mensile</h3>
                <div class="h-64 sm:h-80">
                    <canvas id="chartFinanzeMensile"></canvas>
                </div>
            </div>

            <!-- Stato Pagamenti -->
            <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm border border-slate-200">
                <h3 class="font-semibold text-slate-800 mb-4">💰 Stato Pagamenti Progetti</h3>
                <div class="h-64">
                    <canvas id="chartStatoPagamenti"></canvas>
                </div>
            </div>
        </div>

        <!-- TAB: TASK -->
        <div id="content-task" class="tab-content hidden space-y-6">
            <div class="grid lg:grid-cols-2 gap-6">
                <!-- Per Priorità -->
                <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm border border-slate-200">
                    <h3 class="font-semibold text-slate-800 mb-4">🔥 Task per Priorità</h3>
                    <div class="h-64">
                        <canvas id="chartTaskPriorita"></canvas>
                    </div>
                </div>

                <!-- Per Stato -->
                <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm border border-slate-200">
                    <h3 class="font-semibold text-slate-800 mb-4">✅ Task per Stato</h3>
                    <div class="h-64">
                        <canvas id="chartTaskStato"></canvas>
                    </div>
                </div>
            </div>

            <!-- Task in Scadenza -->
            <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm border border-slate-200">
                <h3 class="font-semibold text-slate-800 mb-4">⏰ Task in Scadenza (Prossimi 7 giorni)</h3>
                <div id="listTaskScadenza" class="space-y-2">
                    <p class="text-center text-slate-400 py-4">Caricamento...</p>
                </div>
            </div>
        </div>

        <!-- TAB: CLIENTI -->
        <div id="content-clienti" class="tab-content hidden space-y-6">
            <div class="grid lg:grid-cols-2 gap-6">
                <!-- Top Clienti per Fatturato -->
                <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm border border-slate-200">
                    <h3 class="font-semibold text-slate-800 mb-4">💎 Top Clienti per Fatturato</h3>
                    <div id="listTopClientiFatturato" class="space-y-3">
                        <p class="text-center text-slate-400 py-4">Caricamento...</p>
                    </div>
                </div>

                <!-- Clienti per Numero Progetti -->
                <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm border border-slate-200">
                    <h3 class="font-semibold text-slate-800 mb-4">📁 Clienti per Numero Progetti</h3>
                    <div id="listTopClientiProgetti" class="space-y-3">
                        <p class="text-center text-slate-400 py-4">Caricamento...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB: TEMPI -->
        <div id="content-tempi" class="tab-content hidden space-y-6">
            <!-- KPI Tempo -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-cyan-50 rounded-xl p-4 sm:p-6 border border-cyan-100">
                    <p class="text-sm text-cyan-600 font-medium">Tempo Totale Tracciato</p>
                    <p class="text-2xl font-bold text-cyan-700 mt-1" id="tempo-totale">-</p>
                </div>
                <div class="bg-purple-50 rounded-xl p-4 sm:p-6 border border-purple-100">
                    <p class="text-sm text-purple-600 font-medium">Sessioni Timer</p>
                    <p class="text-2xl font-bold text-purple-700 mt-1" id="tempo-sessioni">-</p>
                </div>
                <div class="bg-emerald-50 rounded-xl p-4 sm:p-6 border border-emerald-100">
                    <p class="text-sm text-emerald-600 font-medium">Costi Totali</p>
                    <p class="text-2xl font-bold text-emerald-700 mt-1" id="tempo-costi">-</p>
                </div>
                <div class="bg-amber-50 rounded-xl p-4 sm:p-6 border border-amber-100">
                    <p class="text-sm text-amber-600 font-medium">Fatturato</p>
                    <p class="text-2xl font-bold text-amber-700 mt-1" id="tempo-fatturato">-</p>
                </div>
            </div>

            <!-- Margine -->
            <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm border border-slate-200">
                <h3 class="font-semibold text-slate-800 mb-4">📊 Margine Costi vs Fatturato</h3>
                <div class="grid sm:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-slate-50 rounded-lg">
                        <p class="text-sm text-slate-500">Costi (tempo)</p>
                        <p class="text-xl font-bold text-rose-600 mt-1" id="margine-costi">-</p>
                    </div>
                    <div class="text-center p-4 bg-slate-50 rounded-lg">
                        <p class="text-sm text-slate-500">Fatturato</p>
                        <p class="text-xl font-bold text-emerald-600 mt-1" id="margine-fatturato">-</p>
                    </div>
                    <div class="text-center p-4 bg-slate-50 rounded-lg">
                        <p class="text-sm text-slate-500">Margine</p>
                        <p class="text-xl font-bold text-cyan-600 mt-1" id="margine-totale">-</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Store chart instances
const charts = {};

// Switch tab
function switchTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(el => {
        el.classList.remove('active', 'bg-cyan-600', 'text-white');
        el.classList.add('text-slate-600');
    });
    
    // Show selected tab
    document.getElementById(`content-${tabName}`).classList.remove('hidden');
    document.getElementById(`tab-${tabName}`).classList.add('active', 'bg-cyan-600', 'text-white');
    document.getElementById(`tab-${tabName}`).classList.remove('text-slate-600');
    
    // Load data for this tab
    loadTabData(tabName);
}

// Load data based on tab
async function loadTabData(tabName) {
    showLoading(true);
    
    try {
        switch(tabName) {
            case 'overview':
                await loadOverviewData();
                break;
            case 'progetti':
                await loadProgettiData();
                break;
            case 'finanze':
                await loadFinanzeData();
                break;
            case 'task':
                await loadTaskData();
                break;
            case 'clienti':
                await loadClientiData();
                break;
            case 'tempi':
                await loadTempiData();
                break;
        }
    } catch (error) {
        console.error(`Errore caricamento ${tabName}:`, error);
    } finally {
        showLoading(false);
    }
}

function showLoading(show) {
    const el = document.getElementById('loadingState');
    if (show) el.classList.remove('hidden');
    else el.classList.add('hidden');
}

// Format currency
function formatCurrency(value) {
    return '€' + parseFloat(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// Format duration
function formatDuration(seconds) {
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    return `${hours}h ${minutes}m`;
}

// ============== OVERVIEW ==============
async function loadOverviewData() {
    const response = await fetch('api/report.php?action=overview');
    const data = await response.json();
    
    if (!data.success) return;
    
    const d = data.data;
    
    // KPI Cards
    document.getElementById('ov-progetti-totali').textContent = d.progetti.totali;
    document.getElementById('ov-progetti-corso').textContent = d.progetti.in_corso + ' in corso';
    document.getElementById('ov-fatturato').textContent = formatCurrency(d.finanze.fatturato_totale);
    document.getElementById('ov-pagato').textContent = formatCurrency(d.finanze.pagato) + ' pagato';
    document.getElementById('ov-task-complete').textContent = d.task.completate;
    document.getElementById('ov-task-percent').textContent = d.task.percentuale_completamento + '% completate';
    document.getElementById('ov-clienti').textContent = d.clienti.totali;
    
    // Progetti recenti
    const tbody = document.getElementById('tableProgettiRecenti');
    if (d.progetti_recenti.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3" class="p-4 text-center text-slate-400">Nessun progetto</td></tr>';
    } else {
        tbody.innerHTML = d.progetti_recenti.map(p => `
            <tr class="hover:bg-slate-50">
                <td class="p-3">${p.titolo}</td>
                <td class="p-3"><span class="px-2 py-1 rounded text-xs ${getStatoClass(p.stato)}">${p.stato}</span></td>
                <td class="p-3 text-right font-medium">${formatCurrency(p.valore)}</td>
            </tr>
        `).join('');
    }
    
    // Grafico trend
    await loadTrendChart('chartTrendOverview');
}

function getStatoClass(stato) {
    const classes = {
        'da_iniziare': 'bg-gray-100 text-gray-700',
        'in_corso': 'bg-cyan-100 text-cyan-700',
        'completato': 'bg-emerald-100 text-emerald-700',
        'consegnato': 'bg-blue-100 text-blue-700',
        'archiviato': 'bg-slate-100 text-slate-700'
    };
    return classes[stato] || 'bg-gray-100';
}

// ============== PROGETTI ==============
async function loadProgettiData() {
    const response = await fetch('api/report.php?action=progetti');
    const data = await response.json();
    
    if (!data.success) return;
    
    const d = data.data;
    
    // Grafico tipologia
    createPieChart('chartProgettiTipo', 
        d.per_tipologia.map(t => t.tipologia || 'Altro'),
        d.per_tipologia.map(t => t.count),
        ['#0891B2', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6']
    );
    
    // Grafico stato
    createPieChart('chartProgettiStato',
        d.per_stato.map(s => s.stato),
        d.per_stato.map(s => s.count),
        ['#0891B2', '#10B981', '#F59E0B', '#EF4444']
    );
    
    // Grafico mensile
    createLineChart('chartProgettiMensile',
        d.per_mese.map(m => m.mese),
        d.per_mese.map(m => m.count),
        'Progetti creati'
    );
    
    // Top progetti
    const listTop = document.getElementById('listTopProgetti');
    listTop.innerHTML = d.top_progetti.map((p, i) => `
        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
            <div class="flex items-center gap-3">
                <span class="w-6 h-6 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center text-xs font-bold">${i + 1}</span>
                <span class="font-medium text-slate-800">${p.titolo}</span>
            </div>
            <span class="font-bold text-emerald-600">${formatCurrency(p.valore)}</span>
        </div>
    `).join('');
}

// ============== FINANZE ==============
async function loadFinanzeData() {
    const response = await fetch('api/report.php?action=finanze');
    const data = await response.json();
    
    if (!data.success) return;
    
    const d = data.data;
    
    document.getElementById('fin-entrate').textContent = formatCurrency(d.cassa.entrate);
    document.getElementById('fin-uscite').textContent = formatCurrency(d.cassa.uscite);
    document.getElementById('fin-saldo').textContent = formatCurrency(d.cassa.saldo);
    
    // Grafico andamento mensile
    createBarChart('chartFinanzeMensile',
        d.andamento_mensile.map(m => m.mese),
        d.andamento_mensile.map(m => m.entrate),
        d.andamento_mensile.map(m => m.uscite)
    );
    
    // Grafico stato pagamenti
    createPieChart('chartStatoPagamenti',
        d.stato_pagamenti.map(s => s.stato_pagamento || 'N/D'),
        d.stato_pagamenti.map(s => s.totale),
        ['#10B981', '#F59E0B', '#EF4444', '#0891B2']
    );
}

// ============== TASK ==============
async function loadTaskData() {
    const response = await fetch('api/report.php?action=task');
    const data = await response.json();
    
    if (!data.success) return;
    
    const d = data.data;
    
    // Grafico priorita
    createPieChart('chartTaskPriorita',
        d.per_priorita.map(p => p.priorita),
        d.per_priorita.map(p => p.count),
        ['#EF4444', '#F59E0B', '#3B82F6']
    );
    
    // Grafico stato
    createPieChart('chartTaskStato',
        d.per_stato.map(s => s.stato),
        d.per_stato.map(s => s.count),
        ['#10B981', '#F59E0B']
    );
    
    // Task in scadenza
    const listScadenza = document.getElementById('listTaskScadenza');
    if (d.in_scadenza.length === 0) {
        listScadenza.innerHTML = '<p class="text-center text-slate-400 py-4">Nessuna task in scadenza imminente 🎉</p>';
    } else {
        listScadenza.innerHTML = d.in_scadenza.map(t => `
            <div class="flex items-center justify-between p-3 bg-amber-50 border border-amber-100 rounded-lg">
                <div>
                    <p class="font-medium text-slate-800">${t.titolo}</p>
                    <p class="text-xs text-slate-500">${t.progetto_titolo}</p>
                </div>
                <span class="text-xs text-amber-600 font-medium">${new Date(t.scadenza).toLocaleDateString('it-IT')}</span>
            </div>
        `).join('');
    }
}

// ============== CLIENTI ==============
async function loadClientiData() {
    const response = await fetch('api/report.php?action=clienti');
    const data = await response.json();
    
    if (!data.success) return;
    
    const d = data.data;
    
    // Top clienti fatturato
    const listFatturato = document.getElementById('listTopClientiFatturato');
    listFatturato.innerHTML = d.top_clienti.map((c, i) => `
        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
            <div class="flex items-center gap-3">
                <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold">${i + 1}</span>
                <div>
                    <p class="font-medium text-slate-800">${c.ragione_sociale}</p>
                    <p class="text-xs text-slate-500">${c.num_progetti} progetti</p>
                </div>
            </div>
            <span class="font-bold text-emerald-600">${formatCurrency(c.fatturato)}</span>
        </div>
    `).join('');
    
    // Top clienti progetti
    const listProgetti = document.getElementById('listTopClientiProgetti');
    listProgetti.innerHTML = d.clienti_per_progetti.map((c, i) => `
        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
            <div class="flex items-center gap-3">
                <span class="w-6 h-6 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center text-xs font-bold">${i + 1}</span>
                <span class="font-medium text-slate-800">${c.ragione_sociale}</span>
            </div>
            <span class="px-2 py-1 bg-cyan-100 text-cyan-700 rounded text-xs font-bold">${c.num_progetti} progetti</span>
        </div>
    `).join('');
}

// ============== TEMPI ==============
async function loadTempiData() {
    const response = await fetch('api/report.php?action=tempi');
    const data = await response.json();
    
    if (!data.success) return;
    
    const d = data.data;
    
    document.getElementById('tempo-totale').textContent = formatDuration(d.tempo_totale.secondi);
    document.getElementById('tempo-sessioni').textContent = d.tempo_totale.sessioni;
    document.getElementById('tempo-costi').textContent = formatCurrency(d.margine.costi);
    document.getElementById('tempo-fatturato').textContent = formatCurrency(d.margine.fatturato);
    
    document.getElementById('margine-costi').textContent = formatCurrency(d.margine.costi);
    document.getElementById('margine-fatturato').textContent = formatCurrency(d.margine.fatturato);
    document.getElementById('margine-totale').textContent = formatCurrency(d.margine.margine);
}

// ============== CHART HELPERS ==============
async function loadTrendChart(canvasId) {
    const response = await fetch('api/report.php?action=trend&tipo=progetti&mesi=12');
    const data = await response.json();
    
    if (!data.success) return;
    
    createLineChart(canvasId, data.data.labels, data.data.data, 'Progetti creati');
}

function createLineChart(canvasId, labels, data, label) {
    const ctx = document.getElementById(canvasId)?.getContext('2d');
    if (!ctx) return;
    
    if (charts[canvasId]) charts[canvasId].destroy();
    
    charts[canvasId] = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data,
                borderColor: '#0891B2',
                backgroundColor: 'rgba(8, 145, 178, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } }
            }
        }
    });
}

function createBarChart(canvasId, labels, data1, data2) {
    const ctx = document.getElementById(canvasId)?.getContext('2d');
    if (!ctx) return;
    
    if (charts[canvasId]) charts[canvasId].destroy();
    
    charts[canvasId] = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                { label: 'Entrate', data: data1, backgroundColor: '#10B981' },
                { label: 'Uscite', data: data2, backgroundColor: '#EF4444' }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true } }
        }
    });
}

function createPieChart(canvasId, labels, data, colors) {
    const ctx = document.getElementById(canvasId)?.getContext('2d');
    if (!ctx) return;
    
    if (charts[canvasId]) charts[canvasId].destroy();
    
    charts[canvasId] = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12 } }
            }
        }
    });
}

function refreshAllData() {
    const activeTab = document.querySelector('.tab-btn.active').id.replace('tab-', '');
    loadTabData(activeTab);
    showToast('Dati aggiornati', 'success');
}

// Load on page load
document.addEventListener('DOMContentLoaded', () => {
    loadOverviewData();
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
