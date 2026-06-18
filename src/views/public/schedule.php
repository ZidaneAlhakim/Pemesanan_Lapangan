<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12 relative z-10">
    
    <!-- Breadcrumb & Back -->
    <a href="<?= base_url('home') ?>" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-sport-600 dark:text-gray-400 dark:hover:text-sport-400 mb-6 transition-colors group bg-white/50 dark:bg-gray-900/50 backdrop-blur-sm px-4 py-2 rounded-full border border-gray-200 dark:border-gray-800">
        <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
        Kembali ke Katalog Lapangan
    </a>

    <!-- Field Details Header -->
    <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-zp overflow-hidden mb-10 flex flex-col md:flex-row">
        <!-- Field Image -->
        <div class="md:w-2/5 lg:w-1/3 relative h-64 md:h-auto bg-gray-100 dark:bg-gray-800">
            <?php if (!empty($field['image']) && file_exists(__DIR__ . '/../../../public/assets/uploads/' . $field['image'])): ?>
                <img src="<?= base_url('assets/uploads/' . $field['image']) ?>" alt="<?= e($field['name']) ?>" class="w-full h-full object-cover">
            <?php else: ?>
                <div class="absolute inset-0 bg-gradient-to-br from-sport-100 to-sport-200 dark:from-gray-800 dark:to-gray-700 flex items-center justify-center">
                    <i data-lucide="image" class="w-16 h-16 text-sport-300 dark:text-gray-600"></i>
                </div>
            <?php endif; ?>
            <div class="absolute top-4 left-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-white/90 dark:bg-gray-900/90 text-sport-600 dark:text-sport-400 backdrop-blur-md shadow-sm">
                    <i data-lucide="trophy" class="w-4 h-4"></i>
                    <?= e($field['sport']) ?>
                </span>
            </div>
        </div>

        <!-- Field Info -->
        <div class="p-6 md:p-8 md:w-3/5 lg:w-2/3 flex flex-col justify-center">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-4"><?= e($field['name']) ?></h1>
            <p class="text-gray-600 dark:text-gray-400 leading-relaxed mb-6 max-w-2xl text-sm md:text-base">
                <?= e($field['description']) ?>
            </p>
            <div class="flex flex-wrap items-center gap-4 text-sm font-medium">
                <div class="flex items-center gap-2 bg-gray-50 dark:bg-gray-800 px-4 py-2 rounded-xl border border-gray-100 dark:border-gray-700">
                    <i data-lucide="users" class="w-5 h-5 text-gray-400"></i>
                    <span class="text-gray-700 dark:text-gray-300">Kapasitas: <span class="font-bold text-gray-900 dark:text-white"><?= e($field['capacity']) ?></span></span>
                </div>
                <div class="flex items-center gap-2 bg-sport-50 dark:bg-sport-900/20 px-4 py-2 rounded-xl border border-sport-100 dark:border-sport-800">
                    <i data-lucide="wallet" class="w-5 h-5 text-sport-500"></i>
                    <span class="text-sport-600 dark:text-sport-400 font-bold text-lg">Rp<?= number_format($field['price_per_hour'], 0, ',', '.') ?><span class="text-sm font-medium opacity-80">/jam</span></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Selection & Legend -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6 mb-8">
        <form method="GET" action="<?= base_url('schedule') ?>" class="w-full lg:w-auto flex flex-col sm:flex-row items-end gap-3 p-5 bg-white/70 dark:bg-gray-900/70 backdrop-blur-xl rounded-2xl border border-gray-100 dark:border-gray-800 shadow-zp-sm">
            <input type="hidden" name="id" value="<?= $field['id'] ?>">
            <div class="w-full sm:w-auto">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Pilih Tanggal Reservasi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="calendar" class="w-5 h-5 text-sport-500"></i>
                    </div>
                    <input type="date" name="date" value="<?= e($date) ?>" min="<?= date('Y-m-d') ?>" class="w-full sm:w-64 pl-10 pr-4 py-3 text-sm font-medium border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 focus:ring-0 focus:border-sport-500 outline-none transition-colors text-gray-900 dark:text-white">
                </div>
            </div>
            <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-sport-500 to-sport-600 text-white text-sm font-bold rounded-xl hover:shadow-sport-500/30 transition-all shadow-lg flex items-center justify-center gap-2">
                <i data-lucide="search" class="w-4 h-4"></i>
                Cek Jadwal
            </button>
        </form>

        <div class="flex gap-4 p-4 bg-white/70 dark:bg-gray-900/70 backdrop-blur-md rounded-xl border border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-300">
                <span class="w-3 h-3 rounded-full bg-success ring-4 ring-success/20"></span> Tersedia
            </div>
            <div class="flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-300">
                <span class="w-3 h-3 rounded-full bg-danger ring-4 ring-danger/20"></span> Terisi
            </div>
        </div>
    </div>

    <!-- Slots Grid -->
    <?php if (empty($slots)): ?>
    <div class="text-center py-16 bg-white dark:bg-gray-900 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-zp-sm">
        <div class="w-20 h-20 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="calendar-x" class="w-10 h-10 text-gray-400"></i>
        </div>
        <p class="text-lg font-medium text-gray-500 dark:text-gray-400">Tidak ada jadwal tersedia untuk tanggal ini.</p>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        <?php foreach ($slots as $slot): ?>
            <?php if ($slot['available']): ?>
            <a href="<?= base_url('booking?id=' . $field['id'] . '&date=' . e($date) . '&time=' . e($slot['time'])) ?>" class="group flex flex-col items-center justify-center gap-2 p-5 rounded-2xl border-2 border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 hover:border-success hover:bg-success/5 hover:shadow-lg transition-all cursor-pointer transform hover:-translate-y-1">
                <i data-lucide="clock" class="w-6 h-6 text-success group-hover:scale-110 transition-transform"></i>
                <span class="text-lg font-bold text-gray-900 dark:text-white"><?= e($slot['time']) ?></span>
                <span class="text-xs font-semibold text-success uppercase tracking-wider">Pesan</span>
            </a>
            <?php else: ?>
            <div class="flex flex-col items-center justify-center gap-2 p-5 rounded-2xl border-2 border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50 opacity-60 cursor-not-allowed">
                <i data-lucide="lock" class="w-6 h-6 text-danger"></i>
                <span class="text-lg font-bold text-gray-400 dark:text-gray-500 line-through"><?= e($slot['time']) ?></span>
                <span class="text-xs font-semibold text-danger uppercase tracking-wider">Penuh</span>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
