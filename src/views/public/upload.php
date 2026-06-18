<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12 relative z-10">
    <?php if ($booking): ?>
    <div class="mb-8">
        <a href="<?= base_url('home') ?>" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-sport-600 dark:text-gray-400 dark:hover:text-sport-400 mb-6 transition-colors group bg-white/50 dark:bg-gray-900/50 backdrop-blur-sm px-4 py-2 rounded-none border border-gray-200 dark:border-gray-800 w-max">
            <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
            Kembali
        </a>
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-2">Upload Bukti Pembayaran</h1>
        <p class="text-gray-600 dark:text-gray-400">Selesaikan pembayaran untuk Booking <span class="font-bold text-sport-500">#<?= e($booking['id']) ?></span> — <?= e($booking['field_name']) ?></p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Order Detail Panel -->
        <div class="md:col-span-1">
            <div class="bg-white dark:bg-gray-900 rounded-none border border-gray-100 dark:border-gray-800 shadow-zp p-6 sticky top-24">
                <div class="w-12 h-12 rounded-none bg-sport-50 dark:bg-sport-900/20 flex items-center justify-center mb-6">
                    <i data-lucide="wallet" class="w-6 h-6 text-sport-500"></i>
                </div>
                
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 border-b border-gray-100 dark:border-gray-800 pb-4">Info Pembayaran</h3>
                
                <div class="space-y-6">
                    <div>
                        <span class="text-gray-500 dark:text-gray-400 block text-sm mb-1">Status Pembayaran</span>
                        <?php
                        $statusLabels = [
                            'waiting' => ['Menunggu Pembayaran', 'bg-sport-50 text-sport-600 border-sport-200 dark:bg-sport-900/20 dark:text-sport-400 dark:border-sport-800/50'],
                            'pending_validation' => ['Menunggu Verifikasi', 'bg-yellow-50 text-yellow-600 border-yellow-200 dark:bg-yellow-900/20 dark:text-yellow-400 dark:border-yellow-800/50'],
                            'paid' => ['Lunas', 'bg-success/10 text-success border-success/20'],
                            'cancelled' => ['Dibatalkan', 'bg-danger/10 text-danger border-danger/20'],
                        ];
                        $lbl = $statusLabels[$booking['payment_status']] ?? ['Unknown', 'bg-gray-100 text-gray-600'];
                        ?>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-none text-xs font-bold border <?= $lbl[1] ?>"><?= $lbl[0] ?></span>
                    </div>
                    
                    <div>
                        <span class="text-gray-500 dark:text-gray-400 block text-sm mb-1">Total Tagihan</span>
                        <span class="text-3xl font-extrabold text-sport-600 dark:text-sport-400 tracking-tight">Rp<?= number_format($booking['total_price'], 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Panel -->
        <div class="md:col-span-2">
            <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl rounded-none border border-gray-100 dark:border-gray-800 shadow-zp p-6 md:p-10 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-sport-400 to-sport-600"></div>

                <?php if ($booking['payment_status'] === 'waiting' || $booking['payment_status'] === 'pending_validation'): ?>
                <form method="POST" action="<?= base_url('payment?id=' . $booking['id']) ?>" enctype="multipart/form-data" class="space-y-6" onsubmit="return validateUpload(this)">
                    <?= csrf_field() ?>
                    <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                    <div id="uploadError" class="hidden flex items-center gap-2 px-4 py-3 bg-danger/10 text-danger text-sm font-medium rounded-none border border-danger/20">
                        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                        <span id="uploadErrorMessage"></span>
                    </div>

                    <?php if ($booking['payment_proof']): ?>
                    <div class="flex gap-4 p-5 rounded-none bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800/50 text-yellow-800 dark:text-yellow-400">
                        <i data-lucide="info" class="w-6 h-6 shrink-0 mt-0.5"></i>
                        <div>
                            <p class="font-bold mb-1">Anda sudah mengupload bukti sebelumnya.</p>
                            <p class="text-sm opacity-90">Upload ulang akan menggantikan file yang lama.</p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="border-2 border-dashed border-gray-200 dark:border-gray-700 hover:border-sport-500 dark:hover:border-sport-500 bg-gray-50 dark:bg-gray-800/50 rounded-none p-10 md:p-16 text-center transition-all cursor-pointer group" onclick="document.getElementById('fileInput').click()">
                        <div class="w-20 h-20 rounded-none bg-white dark:bg-gray-800 shadow-sm flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300 group-hover:shadow-md">
                            <i data-lucide="cloud-upload" class="w-10 h-10 text-gray-400 group-hover:text-sport-500 transition-colors"></i>
                        </div>
                        <p class="text-lg font-bold text-gray-900 dark:text-white mb-2">Klik untuk memilih file</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Mendukung JPG, PNG, WEBP, atau PDF (Max 2MB).</p>
                        
                        <input type="file" id="fileInput" name="payment_proof" accept=".jpg,.jpeg,.png,.webp,.pdf" required class="hidden" onchange="showFileName(this)">
                        
                        <div id="fileContainer" class="hidden items-center justify-center gap-3 bg-white dark:bg-gray-900 px-4 py-3 rounded-none border border-gray-200 dark:border-gray-700 w-max mx-auto shadow-sm">
                            <i data-lucide="file-check" class="w-5 h-5 text-success"></i>
                            <span id="fileName" class="text-sm font-semibold text-gray-700 dark:text-gray-300 truncate max-w-[200px]"></span>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100 dark:border-gray-800">
                        <button type="submit" class="w-full px-8 py-4 bg-gradient-to-r from-sport-500 to-sport-600 text-white text-lg font-bold rounded-none hover:shadow-sport-500/40 transition-all duration-300 shadow-xl flex items-center justify-center gap-2 transform hover:-translate-y-1">
                            <i data-lucide="upload-cloud" class="w-6 h-6"></i>
                            Upload & Konfirmasi
                        </button>
                    </div>
                </form>
                <?php elseif ($booking['payment_status'] === 'paid'): ?>
                <div class="text-center py-12">
                    <div class="w-24 h-24 rounded-none bg-success/10 flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="check-circle-2" class="w-12 h-12 text-success"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Pembayaran Selesai</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-sm mx-auto">Terima kasih, pembayaran Anda telah diverifikasi dan dikonfirmasi.</p>
                    
                    <a href="<?= base_url('summary') ?>" class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-bold bg-sport-50 dark:bg-sport-900/20 text-sport-600 dark:text-sport-400 rounded-none hover:bg-sport-100 dark:hover:bg-sport-900/40 transition-all">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                        Lihat Detail Booking
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php else: ?>
    <!-- Search state when no booking provided -->
    <div class="max-w-md mx-auto text-center py-16 md:py-24">
        <div class="w-20 h-20 rounded-none bg-sport-50 dark:bg-sport-900/20 flex items-center justify-center mx-auto mb-6 shadow-inner">
            <i data-lucide="upload" class="w-10 h-10 text-sport-500"></i>
        </div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4">Upload Pembayaran</h1>
        <p class="text-gray-600 dark:text-gray-400 mb-8">Masukkan email untuk mencari booking Anda yang memerlukan pembayaran.</p>
        
        <form method="GET" action="<?= base_url('payment') ?>" class="bg-white dark:bg-gray-900 p-2 rounded-none flex flex-col sm:flex-row gap-2 border border-gray-100 dark:border-gray-800 shadow-zp-sm">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i data-lucide="mail" class="w-5 h-5 text-gray-400"></i>
                </div>
                <input type="email" name="email" value="<?= e($email ?? '') ?>" placeholder="email@domain.com" required class="w-full pl-11 pr-4 py-3.5 text-sm font-medium bg-transparent border-none focus:ring-0 outline-none text-gray-900 dark:text-white">
            </div>
            <button type="submit" class="px-6 py-3.5 bg-sport-500 text-white text-sm font-bold rounded-none hover:bg-sport-600 transition-colors flex items-center justify-center gap-2">
                <i data-lucide="search" class="w-4 h-4"></i>
                Cari Booking
            </button>
        </form>
    </div>
    <?php endif; ?>
</div>

<script>
function showFileName(input) {
    const fileContainer = document.getElementById('fileContainer');
    const fileNameDisplay = document.getElementById('fileName');
    const errorDiv = document.getElementById('uploadError');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const maxSize = 2 * 1024 * 1024;
        
        if (file.size > maxSize) {
            errorDiv.classList.remove('hidden');
            document.getElementById('uploadErrorMessage').textContent = 'Ukuran file melebihi 2MB. Pilih file yang lebih kecil.';
            fileContainer.classList.add('hidden');
            fileContainer.classList.remove('flex');
            input.value = '';
            return;
        }
        
        errorDiv.classList.add('hidden');
        fileNameDisplay.textContent = file.name;
        fileContainer.classList.remove('hidden');
        fileContainer.classList.add('flex');
    } else {
        fileContainer.classList.add('hidden');
        fileContainer.classList.remove('flex');
    }
}

function validateUpload(form) {
    const fileInput = form.querySelector('#fileInput');
    if (fileInput && fileInput.files && fileInput.files[0]) {
        const file = fileInput.files[0];
        const maxSize = 2 * 1024 * 1024;
        if (file.size > maxSize) {
            const errorDiv = document.getElementById('uploadError');
            errorDiv.classList.remove('hidden');
            document.getElementById('uploadErrorMessage').textContent = 'Ukuran file melebihi 2MB. Pilih file yang lebih kecil.';
            return false;
        }
    }
    return true;
}
</script>
