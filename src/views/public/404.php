<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-32 text-center relative z-10">
    <!-- Decorative background blob -->
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none overflow-hidden">
        <div class="w-96 h-96 bg-sport-100 dark:bg-sport-900/20 rounded-none blur-3xl opacity-60"></div>
    </div>
    
    <p class="text-7xl md:text-9xl font-black text-sport-500 leading-none mb-2 relative">404</p>
    <div class="w-16 h-1 bg-gradient-to-r from-sport-400 to-sport-600 rounded-none mx-auto mb-8"></div>
    
    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white mb-4">Halaman Tidak Ditemukan</h1>
    <p class="text-gray-600 dark:text-gray-400 text-lg max-w-lg mx-auto mb-10">
        <?= e($message ?? 'Sepertinya halaman yang Anda cari tidak ada atau telah dipindahkan.') ?>
    </p>
    
    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
        <a href="<?= base_url('home') ?>" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-sport-500 to-sport-600 text-white font-bold rounded-none hover:shadow-xl transition-all duration-300 flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
            <i data-lucide="home" class="w-5 h-5"></i>
            Kembali ke Beranda
        </a>
        <a href="javascript:history.back()" class="w-full sm:w-auto px-8 py-4 bg-white dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white font-bold rounded-none hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-300 flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Halaman Sebelumnya
        </a>
    </div>
</div>
