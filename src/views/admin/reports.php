<?php 
$title = 'Laporan Keuangan - SportVenue';
include __DIR__ . '/../layouts/admin_header.php'; 
?>

<div class="flex items-center justify-between mb-6">
    <form method="GET" action="<?= base_url('admin/reports') ?>" class="flex items-center gap-3">
        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Tahun:</label>
        <select name="year" onchange="this.form.submit()" class="px-4 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-zp bg-white dark:bg-gray-800 shadow-zp-sm focus:ring-2 focus:ring-sport-500/50 outline-none">
            <?php for ($y = date('Y'); $y >= date('Y') - 3; $y--): ?>
            <option value="<?= $y ?>" <?= $year == $y ? 'selected' : '' ?>><?= $y ?></option>
            <?php endfor; ?>
        </select>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-zp-lg border border-gray-100 dark:border-gray-800 p-5 shadow-zp-sm">
        <h3 class="font-bold mb-6 text-gray-800 dark:text-gray-200">Pendapatan per Bulan (<?= e($year) ?>)</h3>
        <div class="relative h-72">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-900 rounded-zp-lg border border-gray-100 dark:border-gray-800 p-5 shadow-zp-sm flex flex-col">
        <h3 class="font-bold mb-4 text-gray-800 dark:text-gray-200">Ringkasan Rating</h3>
        
        <div class="flex items-center gap-4 p-4 rounded-zp bg-gradient-to-br from-sport-50 to-sport-100 dark:from-sport-900/40 dark:to-sport-900/10 border border-sport-200/50 dark:border-sport-800/50 mb-6">
            <div class="w-12 h-12 rounded-full bg-white dark:bg-gray-800 flex items-center justify-center shadow-sm">
                <i data-lucide="star" class="w-6 h-6 text-yellow-500 fill-yellow-500"></i>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-sport-600 dark:text-sport-400">Rata-rata Rating</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-bold text-gray-900 dark:text-white">
                        <?= $avgRating ? number_format($avgRating['avg_rating'], 1) : '—' ?>
                    </span>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        / 5.0
                    </span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Berdasarkan <?= $avgRating['total_ratings'] ?? 0 ?> ulasan pelanggan</p>
            </div>
        </div>

        <h3 class="font-bold mb-4 text-gray-800 dark:text-gray-200">Rincian Bulanan</h3>
        <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
            <div class="space-y-2">
                <?php
                $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                $revenueMap = [];
                foreach ($revenueByMonth as $r) {
                    $revenueMap[(int)$r['month']] = $r['total'];
                }
                foreach ($months as $i => $name):
                    $total = $revenueMap[$i + 1] ?? 0;
                ?>
                <div class="flex items-center justify-between p-2.5 rounded-zp hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors border border-transparent hover:border-gray-100 dark:hover:border-gray-800">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300"><?= $name ?></span>
                    <span class="text-sm font-semibold <?= $total > 0 ? 'text-success' : 'text-gray-400' ?>">Rp<?= number_format($total, 0, ',', '.') ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
.dark .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #475569; }
</style>

<?php 
$extraScripts = "
<script>
var revChart;
function initRevenueChart() {
    if (!document.getElementById('revenueChart')) return;
    
    var ctx = document.getElementById('revenueChart').getContext('2d');
    var months = " . json_encode(['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des']) . ";
    var data = [];
    ";
    
$revenueMapJs = [];
foreach ($revenueByMonth as $r) $revenueMapJs[(int)$r['month']] = (float)$r['total'];
for ($i = 1; $i <= 12; $i++) {
    $extraScripts .= "data.push(" . ($revenueMapJs[$i] ?? 0) . ");\n";
}

$extraScripts .= "
    var textColor = isDark() ? '#9ca3af' : '#6b7280';
    var gridColor = isDark() ? '#374151' : '#e5e7eb';
    
    revChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Pendapatan',
                data: data,
                borderColor: '#f97316',
                backgroundColor: 'rgba(249,115,22,0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#f97316',
                pointBorderWidth: 2,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: isDark() ? '#1f2937' : '#ffffff',
                    titleColor: isDark() ? '#ffffff' : '#111827',
                    bodyColor: isDark() ? '#d1d5db' : '#4b5563',
                    borderColor: isDark() ? '#374151' : '#e5e7eb',
                    borderWidth: 1,
                    padding: 10,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Rp' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { 
                        color: textColor, 
                        callback: function(v) { 
                            if (v >= 1000000) return 'Rp' + (v/1000000) + 'M';
                            if (v >= 1000) return 'Rp' + (v/1000) + 'k';
                            return 'Rp' + v; 
                        } 
                    },
                    grid: { color: gridColor, drawBorder: false },
                    border: { display: false }
                },
                x: { 
                    ticks: { color: textColor }, 
                    grid: { display: false },
                    border: { display: false }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
        }
    });
}

function updateCharts() {
    if (revChart) {
        var textColor = isDark() ? '#9ca3af' : '#6b7280';
        var gridColor = isDark() ? '#374151' : '#e5e7eb';
        revChart.options.scales.y.ticks.color = textColor;
        revChart.options.scales.y.grid.color = gridColor;
        revChart.options.scales.x.ticks.color = textColor;
        
        revChart.options.plugins.tooltip.backgroundColor = isDark() ? '#1f2937' : '#ffffff';
        revChart.options.plugins.tooltip.titleColor = isDark() ? '#ffffff' : '#111827';
        revChart.options.plugins.tooltip.bodyColor = isDark() ? '#d1d5db' : '#4b5563';
        revChart.options.plugins.tooltip.borderColor = isDark() ? '#374151' : '#e5e7eb';
        
        revChart.update();
    }
}

document.addEventListener('DOMContentLoaded', initRevenueChart);
</script>
";
include __DIR__ . '/../layouts/admin_footer.php'; 
?>
