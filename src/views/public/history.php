<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12 relative z-10">
    <div class="text-center mb-10">
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-4">Riwayat Reservasi</h1>
        <p class="text-gray-600 dark:text-gray-400 max-w-lg mx-auto">Pantau status reservasi dan lakukan pembayaran dengan mudah hanya menggunakan email Anda.</p>
    </div>

    <!-- Search Form -->
    <div class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-xl p-6 md:p-8 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-zp mb-10">
        <form method="GET" action="<?= base_url('history') ?>" class="flex flex-col sm:flex-row gap-4">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i data-lucide="mail" class="w-5 h-5 text-gray-400"></i>
                </div>
                <input type="email" name="email" value="<?= e($email) ?>" placeholder="Masukkan email Anda" required class="w-full pl-11 pr-4 py-4 text-sm md:text-base font-medium border-2 border-gray-200 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-0 focus:border-sport-500 outline-none transition-colors text-gray-900 dark:text-white">
            </div>
            <button type="submit" class="px-8 py-4 bg-gradient-to-r from-sport-500 to-sport-600 text-white font-bold rounded-2xl hover:shadow-sport-500/30 transition-all shadow-lg flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                <i data-lucide="search" class="w-5 h-5"></i>
                Cari Riwayat
            </button>
        </form>
    </div>

    <?php if ($email && empty($bookings)): ?>
    <!-- Empty State -->
    <div class="text-center py-16 bg-white dark:bg-gray-900 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-zp-sm">
        <div class="w-24 h-24 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6">
            <i data-lucide="inbox" class="w-12 h-12 text-gray-300 dark:text-gray-600"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Riwayat Kosong</h3>
        <p class="text-gray-500 dark:text-gray-400">Tidak ada reservasi yang ditemukan untuk email <strong><?= e($email) ?></strong>.</p>
    </div>
    <?php elseif (!empty($bookings)): ?>
    <!-- Booking List -->
    <div class="space-y-6">
        <?php foreach ($bookings as $b): ?>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-zp-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
            <div class="p-6 md:p-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    
                    <!-- Left info -->
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold bg-sport-50 dark:bg-sport-900/20 text-sport-600 dark:text-sport-400">
                                #<?= e($b['id']) ?>
                            </span>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white"><?= e($b['field_name']) ?></h3>
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-x-6 gap-y-3 text-sm font-medium text-gray-600 dark:text-gray-400">
                            <span class="flex items-center gap-2">
                                <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
                                <?= date('d M Y', strtotime($b['booking_date'])) ?>
                            </span>
                            <span class="flex items-center gap-2">
                                <i data-lucide="clock" class="w-4 h-4 text-gray-400"></i>
                                <?= e(substr($b['start_time'], 0, 5)) ?> - <?= sprintf('%02d:00', intval(substr($b['start_time'], 0, 2)) + intval($b['duration_hours'])) ?>
                            </span>
                            <span class="flex items-center gap-2 font-bold text-gray-900 dark:text-white">
                                <i data-lucide="wallet" class="w-4 h-4 text-gray-400"></i>
                                Rp<?= number_format($b['total_price'], 0, ',', '.') ?>
                            </span>
                        </div>
                    </div>

                    <!-- Right Status & Action -->
                    <div class="flex flex-col md:items-end gap-4">
                        <div class="flex flex-wrap gap-2">
                            <?php
                            $statusLabels = [
                                'pending' => ['Pending', 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400 border-gray-200 dark:border-gray-700'],
                                'confirmed' => ['Dikonfirmasi', 'bg-success/10 text-success border-success/20'],
                                'cancelled' => ['Dibatalkan', 'bg-danger/10 text-danger border-danger/20'],
                            ];
                            $paymentLabels = [
                                'waiting' => ['Belum Bayar', 'bg-sport-50 text-sport-600 dark:bg-sport-900/20 dark:text-sport-400 border-sport-200 dark:border-sport-800/50'],
                                'pending_validation' => ['Verifikasi', 'bg-yellow-50 text-yellow-600 dark:bg-yellow-900/20 dark:text-yellow-400 border-yellow-200 dark:border-yellow-800/50'],
                                'paid' => ['Lunas', 'bg-success/10 text-success border-success/20'],
                                'cancelled' => ['Batal', 'bg-danger/10 text-danger border-danger/20'],
                            ];
                            $sl = $statusLabels[$b['status']] ?? ['', ''];
                            $pl = $paymentLabels[$b['payment_status']] ?? ['', ''];
                            ?>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border <?= $sl[1] ?>">
                                <?= $sl[0] ?>
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border <?= $pl[1] ?>">
                                <?= $pl[0] ?>
                            </span>
                        </div>

                        <div class="flex gap-2 w-full md:w-auto">
                            <?php if ($b['payment_status'] === 'waiting'): ?>
                            <a href="<?= base_url('payment?id=' . $b['id']) ?>" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold bg-sport-500 text-white rounded-xl hover:bg-sport-600 hover:shadow-lg transition-all">
                                <i data-lucide="upload" class="w-4 h-4"></i> Upload Bukti
                            </a>
                            <?php endif; ?>
                            <?php if ($b['status'] === 'confirmed'): ?>
                            <a href="<?= base_url('rating?booking_id=' . $b['id']) ?>" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold bg-yellow-400 text-yellow-900 rounded-xl hover:bg-yellow-500 hover:shadow-lg transition-all">
                                <i data-lucide="star" class="w-4 h-4"></i> Beri Rating
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <div class="flex items-center justify-center gap-2 mt-10">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="<?= base_url('history?email=' . urlencode($email) . '&page=' . $i) ?>" class="w-10 h-10 flex items-center justify-center text-sm font-bold rounded-xl transition-all <?= $i === $currentPage ? 'bg-sport-500 text-white shadow-md' : 'bg-white dark:bg-gray-900 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 border border-gray-200 dark:border-gray-700' ?>">
            <?= $i ?>
        </a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>
