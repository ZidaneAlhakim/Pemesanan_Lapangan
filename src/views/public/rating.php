<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-16 relative z-10">
    <?php if ($booking): ?>
    <div class="text-center mb-10">
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-3">Beri Kami Penilaian</h1>
        <p class="text-gray-600 dark:text-gray-400 max-w-lg mx-auto">Bagaimana pengalaman Anda menggunakan lapangan <span class="font-bold text-gray-900 dark:text-white"><?= e($booking['field_name']) ?></span> (Booking #<?= e($booking['id']) ?>)?</p>
    </div>

    <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl rounded-3xl border border-gray-100 dark:border-gray-800 shadow-zp p-8 md:p-12 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-yellow-400 to-yellow-500"></div>

        <form method="POST" action="<?= base_url('rating') ?>" class="space-y-8">
            <?= csrf_field() ?>
            <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">

            <div class="text-center">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-6 uppercase tracking-wider">Tingkat Kepuasan Anda</label>
                <div class="flex items-center justify-center gap-2 sm:gap-4" id="starContainer">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <button type="button" onclick="setRating(<?= $i ?>)" class="star-btn p-2 transition-all duration-300 hover:scale-125 focus:outline-none group" data-rating="<?= $i ?>">
                        <i data-lucide="star" class="w-10 h-10 sm:w-14 sm:h-14 text-gray-200 dark:text-gray-700 transition-colors drop-shadow-sm group-hover:text-yellow-300"></i>
                    </button>
                    <?php endfor; ?>
                </div>
                <input type="hidden" name="rating" id="ratingInput" value="0">
                <p id="ratingMessage" class="h-6 mt-4 text-sm font-bold text-yellow-500 transition-all opacity-0 transform translate-y-2"></p>
            </div>

            <div class="border-t border-gray-100 dark:border-gray-800 pt-8">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">Tulis Ulasan (Opsional)</label>
                <textarea name="review" rows="4" maxlength="1000" placeholder="Ceritakan pengalaman Anda di sini..." class="w-full px-5 py-4 text-sm md:text-base border-2 border-gray-200 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-800 focus:ring-0 focus:bg-white dark:focus:bg-gray-900 focus:border-yellow-400 outline-none transition-all resize-none text-gray-900 dark:text-white"></textarea>
                <div class="flex justify-between items-center mt-2">
                    <p class="text-xs text-gray-400">Maksimal 1000 karakter</p>
                </div>
            </div>

            <button type="submit" id="submitBtn" disabled class="w-full px-8 py-4 bg-gray-200 dark:bg-gray-800 text-gray-500 dark:text-gray-400 text-lg font-bold rounded-xl transition-all duration-300 flex items-center justify-center gap-2 pointer-events-none">
                <i data-lucide="send" class="w-5 h-5"></i>
                Kirim Penilaian
            </button>
        </form>
    </div>
    
    <?php else: ?>
    <!-- Search state when no booking provided -->
    <div class="max-w-md mx-auto text-center py-16 md:py-24">
        <div class="w-20 h-20 rounded-full bg-yellow-50 dark:bg-yellow-900/20 flex items-center justify-center mx-auto mb-6 shadow-inner">
            <i data-lucide="star" class="w-10 h-10 text-yellow-500"></i>
        </div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4">Beri Penilaian</h1>
        <p class="text-gray-600 dark:text-gray-400 mb-8">Masukkan ID booking Anda untuk memberikan penilaian dan ulasan pengalaman Anda.</p>
        
        <form method="GET" action="<?= base_url('rating') ?>" class="bg-white dark:bg-gray-900 p-2 rounded-2xl flex flex-col sm:flex-row gap-2 border border-gray-100 dark:border-gray-800 shadow-zp-sm">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i data-lucide="hash" class="w-5 h-5 text-gray-400"></i>
                </div>
                <input type="number" name="booking_id" placeholder="ID Booking" required class="w-full pl-11 pr-4 py-3.5 text-sm font-medium bg-transparent border-none focus:ring-0 outline-none text-gray-900 dark:text-white">
            </div>
            <button type="submit" class="px-6 py-3.5 bg-yellow-500 text-white text-sm font-bold rounded-xl hover:bg-yellow-600 hover:shadow-lg hover:shadow-yellow-500/30 transition-all flex items-center justify-center gap-2">
                <i data-lucide="search" class="w-4 h-4"></i>
                Cari Booking
            </button>
        </form>
        <p class="mt-6 text-sm font-medium text-gray-500 dark:text-gray-400">
            Lupa ID? Cari dari <a href="<?= base_url('history') ?>" class="text-sport-500 hover:text-sport-600 dark:text-sport-400 hover:underline">Riwayat Reservasi</a>
        </p>
    </div>
    <?php endif; ?>
</div>

<script>
const messages = [
    "Sangat Buruk 😞",
    "Kurang Memuaskan 😕",
    "Cukup Baik 😐",
    "Sangat Baik! 🙂",
    "Luar Biasa! 🤩"
];

function setRating(val) {
    document.getElementById('ratingInput').value = val;
    var stars = document.querySelectorAll('.star-btn');
    stars.forEach(function(btn, i) {
        var icon = btn.querySelector('i');
        // Reset classes
        icon.className = 'w-10 h-10 sm:w-14 sm:h-14 transition-colors drop-shadow-sm group-hover:text-yellow-300';
        
        if (i < val) {
            icon.classList.add('text-yellow-400');
            // Add a little pop animation
            icon.classList.add('scale-110');
            setTimeout(() => icon.classList.remove('scale-110'), 150);
        } else {
            icon.classList.add('text-gray-200', 'dark:text-gray-700');
        }
    });

    // Update Message
    const msgEl = document.getElementById('ratingMessage');
    msgEl.textContent = messages[val - 1];
    msgEl.classList.remove('opacity-0', 'translate-y-2');
    
    // Enable submit button
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = false;
    submitBtn.className = "w-full px-8 py-4 bg-gradient-to-r from-sport-500 to-sport-600 text-white text-lg font-bold rounded-xl hover:shadow-sport-500/40 transition-all duration-300 shadow-xl flex items-center justify-center gap-2 transform hover:-translate-y-1 cursor-pointer";
}
</script>
