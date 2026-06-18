<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20 relative z-10 text-center">
    <div class="w-24 h-24 rounded-full bg-success/10 border-4 border-success/20 flex items-center justify-center mx-auto mb-6 transform hover:scale-110 transition-transform duration-500">
        <i data-lucide="check-circle-2" class="w-12 h-12 text-success"></i>
    </div>
    
    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-3">Booking Berhasil Dibuat!</h1>
    <p class="text-lg text-gray-600 dark:text-gray-400 mb-10 max-w-lg mx-auto">Satu langkah lagi. Silakan selesaikan pembayaran untuk mengkonfirmasi reservasi lapangan Anda.</p>

    <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl rounded-3xl border border-gray-100 dark:border-gray-800 shadow-zp p-8 text-left max-w-xl mx-auto relative overflow-hidden">
        <!-- Decorative Header -->
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-sport-400 to-sport-600"></div>
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-sport-50 dark:bg-sport-900/20 rounded-full blur-2xl"></div>

        <h3 class="font-bold text-xl text-gray-900 dark:text-white mb-6 relative z-10 flex items-center gap-2">
            <i data-lucide="receipt" class="w-6 h-6 text-sport-500"></i>
            Detail Pemesanan
        </h3>
        
        <div class="space-y-4 text-sm md:text-base relative z-10">
            <div class="flex justify-between items-center py-2 border-b border-gray-50 dark:border-gray-800/50">
                <span class="text-gray-500 dark:text-gray-400">ID Booking</span>
                <span class="font-mono font-bold text-sport-600 dark:text-sport-400 text-lg">#<?= e($booking['id']) ?></span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-50 dark:border-gray-800/50">
                <span class="text-gray-500 dark:text-gray-400">Lapangan</span>
                <span class="font-bold text-gray-900 dark:text-white"><?= e($booking['field_name']) ?></span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-50 dark:border-gray-800/50">
                <span class="text-gray-500 dark:text-gray-400">Nama Pemesan</span>
                <span class="font-medium text-gray-900 dark:text-white"><?= e($booking['customer_name']) ?></span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-50 dark:border-gray-800/50">
                <span class="text-gray-500 dark:text-gray-400">Tanggal Main</span>
                <span class="font-medium text-gray-900 dark:text-white"><?= date('d M Y', strtotime($booking['booking_date'])) ?></span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-50 dark:border-gray-800/50">
                <span class="text-gray-500 dark:text-gray-400">Waktu Main</span>
                <span class="font-medium text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-800 px-3 py-1 rounded-md"><?= e(substr($booking['start_time'], 0, 5)) ?> - <?= sprintf('%02d:00', intval(substr($booking['start_time'], 0, 2)) + intval($booking['duration_hours'])) ?></span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-50 dark:border-gray-800/50">
                <span class="text-gray-500 dark:text-gray-400">Status Pembayaran</span>
                <?php
                $statusLabels = [
                    'waiting' => ['Menunggu Pembayaran', 'bg-sport-50 text-sport-600 border-sport-200 dark:bg-sport-900/20 dark:text-sport-400 dark:border-sport-800/50'],
                    'pending_validation' => ['Menunggu Verifikasi', 'bg-yellow-50 text-yellow-600 border-yellow-200 dark:bg-yellow-900/20 dark:text-yellow-400 dark:border-yellow-800/50'],
                    'paid' => ['Lunas', 'bg-success/10 text-success border-success/20'],
                    'cancelled' => ['Dibatalkan', 'bg-danger/10 text-danger border-danger/20'],
                ];
                $label = $statusLabels[$booking['payment_status']] ?? ['Unknown', 'bg-gray-100 text-gray-600 border-gray-200'];
                ?>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border <?= $label[1] ?>">
                    <?= $label[0] ?>
                </span>
            </div>
            
            <div class="pt-6 mt-4 border-t-2 border-dashed border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-end">
                    <span class="font-semibold text-gray-500 dark:text-gray-400 text-lg">Total Pembayaran</span>
                    <span class="font-extrabold text-3xl text-sport-600 dark:text-sport-400">Rp<?= number_format($booking['total_price'], 0, ',', '.') ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
        <?php if ($booking['payment_status'] === 'waiting'): ?>
        <a href="<?= base_url('payment?id=' . $booking['id']) ?>" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-sport-500 to-sport-600 text-white text-base font-bold rounded-xl hover:shadow-sport-500/40 transition-all duration-300 shadow-xl flex items-center justify-center gap-2 transform hover:-translate-y-1">
            <i data-lucide="upload" class="w-5 h-5"></i>
            Upload Bukti Pembayaran
        </a>
        <?php endif; ?>
        
        <?php if ($booking['status'] === 'confirmed'): ?>
        <a href="<?= base_url('rating?booking_id=' . $booking['id']) ?>" class="w-full sm:w-auto px-8 py-4 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white text-base font-bold rounded-xl hover:border-yellow-400 hover:text-yellow-600 dark:hover:border-yellow-500 dark:hover:text-yellow-400 transition-all duration-300 shadow-sm flex items-center justify-center gap-2 group transform hover:-translate-y-1">
            <i data-lucide="star" class="w-5 h-5 text-gray-400 group-hover:text-yellow-500 transition-colors"></i>
            Beri Rating
        </a>
        <?php endif; ?>
        
        <a href="<?= base_url('home') ?>" class="w-full sm:w-auto px-8 py-4 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white text-base font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-300 shadow-sm flex items-center justify-center gap-2 transform hover:-translate-y-1">
            <i data-lucide="home" class="w-5 h-5"></i>
            Kembali ke Beranda
        </a>
    </div>
</div>
