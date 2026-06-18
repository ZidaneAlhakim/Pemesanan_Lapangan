<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12 relative z-10">
    <!-- Back Button -->
    <a href="<?= base_url('schedule?id=' . $field['id'] . '&date=' . e($date)) ?>" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-sport-600 dark:text-gray-400 dark:hover:text-sport-400 mb-8 transition-colors group bg-white/50 dark:bg-gray-900/50 backdrop-blur-sm px-4 py-2 rounded-full border border-gray-200 dark:border-gray-800 w-max">
        <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
        Kembali ke Jadwal
    </a>

    <div class="flex flex-col lg:flex-row gap-8 xl:gap-12">
        <!-- Form Section -->
        <div class="lg:w-2/3">
            <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-zp-sm overflow-hidden relative">
                <!-- Decorative Top Bar -->
                <div class="h-2 bg-gradient-to-r from-sport-400 to-sport-600 w-full absolute top-0 left-0"></div>
                
                <div class="p-8 md:p-10">
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-2">Data Reservasi</h1>
                    <p class="text-gray-500 dark:text-gray-400 mb-8">Silakan lengkapi data pemesan di bawah ini untuk melanjutkan.</p>

                    <form method="POST" action="<?= base_url('booking/store') ?>" class="space-y-6">
                        <?= csrf_field() ?>
                        <input type="hidden" name="field_id" value="<?= $field['id'] ?>">
                        <input type="hidden" name="booking_date" value="<?= e($date) ?>">
                        <input type="hidden" name="start_time" value="<?= e($time) ?>">

                        <!-- Input Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Lengkap -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i data-lucide="user" class="w-5 h-5 text-gray-400"></i>
                                    </div>
                                    <input type="text" name="customer_name" value="<?= e(old('customer_name')) ?>" required maxlength="120" placeholder="John Doe" class="w-full pl-11 pr-4 py-3 text-sm font-medium border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 focus:ring-0 focus:border-sport-500 outline-none transition-colors text-gray-900 dark:text-white">
                                </div>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i data-lucide="mail" class="w-5 h-5 text-gray-400"></i>
                                    </div>
                                    <input type="email" name="customer_email" value="<?= e(old('customer_email')) ?>" required maxlength="150" placeholder="contoh@email.com" class="w-full pl-11 pr-4 py-3 text-sm font-medium border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 focus:ring-0 focus:border-sport-500 outline-none transition-colors text-gray-900 dark:text-white">
                                </div>
                            </div>

                            <!-- Nomor Telepon -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nomor WhatsApp</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i data-lucide="phone" class="w-5 h-5 text-gray-400"></i>
                                    </div>
                                    <input type="tel" name="customer_phone" value="<?= e(old('customer_phone')) ?>" required placeholder="08xxxxxxxxxx" class="w-full pl-11 pr-4 py-3 text-sm font-medium border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 focus:ring-0 focus:border-sport-500 outline-none transition-colors text-gray-900 dark:text-white">
                                </div>
                            </div>

                            <!-- Durasi -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Durasi Bermain</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i data-lucide="clock" class="w-5 h-5 text-gray-400"></i>
                                    </div>
                                    <select name="duration_hours" id="durationSelect" onchange="updatePrice()" class="w-full pl-11 pr-4 py-3 text-sm font-medium border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 focus:ring-0 focus:border-sport-500 outline-none transition-colors text-gray-900 dark:text-white appearance-none cursor-pointer">
                                        <?php for ($i = 1; $i <= 6; $i++): ?>
                                        <option value="<?= $i ?>" <?= old('duration_hours') == $i ? 'selected' : '' ?>><?= $i ?> Jam (Hingga <?= sprintf('%02d:00', intval(substr($time, 0, 2)) + $i) ?>)</option>
                                        <?php endfor; ?>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Area -->
                        <div class="pt-6 mt-6 border-t border-gray-100 dark:border-gray-800">
                            <button type="submit" class="w-full px-8 py-4 bg-gradient-to-r from-sport-500 to-sport-600 text-white text-lg font-bold rounded-xl hover:shadow-sport-500/40 transition-all duration-300 shadow-xl flex items-center justify-center gap-2 transform hover:-translate-y-1">
                                <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                                Konfirmasi & Buat Pesanan
                            </button>
                            <p class="text-center text-xs text-gray-400 mt-4">Dengan menekan tombol di atas, Anda menyetujui syarat & ketentuan yang berlaku.</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Summary Section -->
        <div class="lg:w-1/3">
            <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-zp-sm p-6 md:p-8 sticky top-24">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <i data-lucide="receipt" class="w-6 h-6 text-sport-500"></i>
                    Ringkasan Pesanan
                </h3>

                <!-- Field Preview inside summary -->
                <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-100 dark:border-gray-800">
                    <?php if (!empty($field['image']) && file_exists(__DIR__ . '/../../../public/assets/uploads/' . $field['image'])): ?>
                        <div class="w-16 h-16 rounded-xl overflow-hidden shrink-0">
                            <img src="<?= base_url('assets/uploads/' . $field['image']) ?>" alt="Field" class="w-full h-full object-cover">
                        </div>
                    <?php else: ?>
                        <div class="w-16 h-16 rounded-xl bg-sport-100 dark:bg-gray-800 flex items-center justify-center shrink-0">
                            <i data-lucide="image" class="w-8 h-8 text-sport-300 dark:text-gray-600"></i>
                        </div>
                    <?php endif; ?>
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white text-lg"><?= e($field['name']) ?></h4>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-sport-500">
                            <i data-lucide="trophy" class="w-3 h-3"></i> <?= e($field['sport']) ?>
                        </span>
                    </div>
                </div>

                <!-- Details -->
                <div class="space-y-4 text-sm mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 dark:text-gray-400 flex items-center gap-2">
                            <i data-lucide="calendar" class="w-4 h-4"></i> Tanggal
                        </span>
                        <span class="font-semibold text-gray-900 dark:text-white"><?= date('d M Y', strtotime(e($date))) ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 dark:text-gray-400 flex items-center gap-2">
                            <i data-lucide="clock" class="w-4 h-4"></i> Waktu Mulai
                        </span>
                        <span class="font-semibold text-gray-900 dark:text-white"><?= e($time) ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 dark:text-gray-400 flex items-center gap-2">
                            <i data-lucide="tag" class="w-4 h-4"></i> Harga / Jam
                        </span>
                        <span class="font-semibold text-gray-900 dark:text-white">Rp<?= number_format($field['price_per_hour'], 0, ',', '.') ?></span>
                    </div>
                </div>

                <div class="bg-sport-50 dark:bg-sport-900/20 rounded-2xl p-5 border border-sport-100 dark:border-sport-800/50">
                    <div class="flex justify-between items-center text-lg">
                        <span class="font-bold text-gray-900 dark:text-white">Total Bayar</span>
                        <span id="totalPrice" class="font-extrabold text-2xl text-sport-600 dark:text-sport-400">
                            Rp<?= number_format($field['price_per_hour'] * intval(old('duration_hours', 1)), 0, ',', '.') ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updatePrice() {
    var dur = parseInt(document.getElementById('durationSelect').value) || 1;
    var price = <?= $field['price_per_hour'] ?>;
    var total = dur * price;
    document.getElementById('totalPrice').textContent = 'Rp' + total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}
document.addEventListener('DOMContentLoaded', updatePrice);
</script>
