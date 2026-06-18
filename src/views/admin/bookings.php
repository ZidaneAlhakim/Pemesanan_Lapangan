<?php 
$title = 'Reservasi - SportVenue';
include __DIR__ . '/../layouts/admin_header.php'; 
?>

<div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between mb-6">
    <form method="GET" action="<?= base_url('admin/bookings') ?>" class="flex flex-wrap gap-2 w-full sm:w-auto">
        <input type="text" name="search" value="<?= e($filters['search'] ?? '') ?>" placeholder="Cari nama/email..." class="px-3.5 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-none bg-white dark:bg-gray-800 focus:ring-2 focus:ring-sport-500/50 w-full sm:w-64 outline-none transition-shadow">
        
        <select name="status" class="px-3.5 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-none bg-white dark:bg-gray-800 outline-none focus:ring-2 focus:ring-sport-500/50">
            <option value="">Status Booking</option>
            <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="confirmed" <?= ($filters['status'] ?? '') === 'confirmed' ? 'selected' : '' ?>>Dikonfirmasi</option>
            <option value="cancelled" <?= ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
        </select>
        
        <select name="payment_status" class="px-3.5 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-none bg-white dark:bg-gray-800 outline-none focus:ring-2 focus:ring-sport-500/50">
            <option value="">Status Bayar</option>
            <option value="waiting" <?= ($filters['payment_status'] ?? '') === 'waiting' ? 'selected' : '' ?>>Belum Bayar</option>
            <option value="pending_validation" <?= ($filters['payment_status'] ?? '') === 'pending_validation' ? 'selected' : '' ?>>Verifikasi</option>
            <option value="paid" <?= ($filters['payment_status'] ?? '') === 'paid' ? 'selected' : '' ?>>Lunas</option>
            <option value="cancelled" <?= ($filters['payment_status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Batal</option>
        </select>
        
        <button type="submit" class="px-4 py-2 bg-sport-500 text-white text-sm font-semibold rounded-none hover:bg-sport-600 flex items-center gap-1.5 shadow-zp-sm transition-all"><i data-lucide="search" class="w-4 h-4"></i> Filter</button>
    </form>
</div>

<div class="bg-white dark:bg-gray-900 rounded-none border border-gray-100 dark:border-gray-800 overflow-hidden shadow-zp-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800/50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">ID</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Pelanggan</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Lapangan</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Tanggal & Jam</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Total</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                <?php foreach ($bookings as $b): ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <td class="px-4 py-3 font-mono text-sport-500 font-semibold">#<?= $b['id'] ?></td>
                    <td class="px-4 py-3">
                        <p class="font-medium"><?= e($b['customer_name']) ?></p>
                        <div class="flex items-center gap-2 mt-0.5 text-xs text-gray-500">
                            <span class="flex items-center gap-1"><i data-lucide="mail" class="w-3 h-3"></i> <?= e($b['customer_email']) ?></span>
                        </div>
                    </td>
                    <td class="px-4 py-3 font-medium"><?= e($b['field_name']) ?></td>
                    <td class="px-4 py-3">
                        <p><?= date('d M Y', strtotime($b['booking_date'])) ?></p>
                        <p class="text-xs text-gray-500 mt-0.5"><?= e(substr($b['start_time'], 0, 5)) ?> - <?= e(date('H:i', strtotime($b['start_time']) + $b['duration_hours'] * 3600)) ?> (<?= $b['duration_hours'] ?>j)</p>
                    </td>
                    <td class="px-4 py-3 font-semibold text-sport-600 dark:text-sport-400">Rp<?= number_format($b['total_price'], 0, ',', '.') ?></td>
                    <td class="px-4 py-3">
                        <div class="flex flex-col gap-1.5 items-start">
                            <?php
                            $sl = ['pending' => ['Pending', 'bg-sport-25 dark:bg-sport-900/20 text-sport-500 border-sport-200'], 'confirmed' => ['Dikonfirmasi', 'bg-success/10 text-success border-green-200'], 'cancelled' => ['Batal', 'bg-danger/10 text-danger border-red-200']];
                            $s = $sl[$b['status']] ?? ['', ''];
                            ?>
                            <span class="px-2 py-0.5 rounded-none text-xs font-semibold border <?= $s[1] ?>"><?= $s[0] ?></span>
                            
                            <?php
                            $pl = ['waiting' => ['Belum Bayar', 'bg-sport-25 dark:bg-sport-900/20 text-sport-500 border-sport-200'], 'pending_validation' => ['Verifikasi', 'bg-yellow-50 text-yellow-600 border-yellow-200'], 'paid' => ['Lunas', 'bg-success/10 text-success border-green-200'], 'cancelled' => ['Batal', 'bg-danger/10 text-danger border-red-200']];
                            $p = $pl[$b['payment_status']] ?? ['', ''];
                            ?>
                            <span class="px-2 py-0.5 rounded-none text-[10px] uppercase tracking-wider font-bold border <?= $p[1] ?>"><?= $p[0] ?></span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <?php if ($b['payment_status'] === 'pending_validation' && $b['payment_proof']): ?>
                            <button onclick="openValidateModal(<?= htmlspecialchars(json_encode($b)) ?>)" class="px-3 py-1.5 text-xs font-semibold bg-sport-500 text-white rounded-none hover:bg-sport-600 transition-colors shadow-zp-sm">Validasi Pembayaran</button>
                        <?php elseif ($b['payment_proof']): ?>
                            <a href="<?= storage_url($b['payment_proof']) ?>" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-none hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                                <i data-lucide="external-link" class="w-3 h-3"></i> Bukti Bayar
                            </a>
                        <?php else: ?>
                            <span class="text-xs text-gray-400 italic">Tidak ada aksi</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if (empty($bookings)): ?>
    <div class="text-center py-12">
        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-800 rounded-none flex items-center justify-center mx-auto mb-3">
            <i data-lucide="calendar-x" class="w-8 h-8 text-gray-400"></i>
        </div>
        <p class="text-gray-500 dark:text-gray-400 font-medium">Tidak ada reservasi ditemukan.</p>
    </div>
    <?php endif; ?>
</div>

<?php if ($totalPages > 1): ?>
<div class="flex items-center justify-center gap-2 mt-6">
    <?php
    $params = $_GET;
    for ($i = 1; $i <= $totalPages; $i++):
        $params['page'] = $i;
    ?>
    <a href="<?= base_url('admin/bookings') . '?' . http_build_query($params) ?>" class="w-8 h-8 flex items-center justify-center text-sm font-medium rounded-none transition-colors <?= $i === $currentPage ? 'bg-sport-500 text-white shadow-zp-sm' : 'bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' ?>">
        <?= $i ?>
    </a>
    <?php endfor; ?>
</div>
<?php endif; ?>


<!-- Modal Validasi -->
<div id="validateModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 overflow-y-auto">
    <div class="bg-white dark:bg-gray-900 rounded-none border border-gray-100 dark:border-gray-800 shadow-zp p-6 w-full max-w-lg my-8">
        <div class="flex justify-between items-center mb-5">
            <h3 class="font-bold text-lg">Validasi Pembayaran</h3>
            <button onclick="document.getElementById('validateModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        
        <div class="space-y-4">
            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-none p-4 text-sm border border-gray-100 dark:border-gray-800">
                <div class="grid grid-cols-2 gap-2">
                    <p class="text-gray-500 dark:text-gray-400">ID Booking</p>
                    <p class="font-semibold" id="val_id">-</p>
                    <p class="text-gray-500 dark:text-gray-400">Pelanggan</p>
                    <p class="font-semibold" id="val_customer">-</p>
                    <p class="text-gray-500 dark:text-gray-400">Total Harga</p>
                    <p class="font-semibold text-sport-500" id="val_total">-</p>
                </div>
            </div>

            <div>
                <p class="text-sm font-medium mb-2">Bukti Pembayaran</p>
                <div class="border border-gray-200 dark:border-gray-700 rounded-none overflow-hidden bg-gray-50 dark:bg-gray-800 flex justify-center">
                    <img id="val_proof_img" src="" alt="Bukti Pembayaran" class="max-h-64 object-contain">
                </div>
                <a id="val_proof_link" href="#" target="_blank" class="text-sm text-sport-500 hover:underline mt-2 inline-block">Buka gambar penuh &rarr;</a>
            </div>
            
            <form method="POST" action="<?= base_url('admin/validate') ?>" class="flex gap-2 pt-4 border-t border-gray-100 dark:border-gray-800 mt-6">
                <?= csrf_field() ?>
                <input type="hidden" name="booking_id" id="val_booking_id">
                
                <button type="submit" name="action" value="reject" class="flex-1 py-2 text-sm font-semibold border border-danger/20 text-danger bg-danger/5 rounded-none hover:bg-danger hover:text-white transition-colors" onclick="return confirm('Tolak bukti pembayaran ini?')">Tolak</button>
                <button type="submit" name="action" value="cancel" class="flex-1 py-2 text-sm font-semibold border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 rounded-none hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" onclick="return confirm('Batalkan seluruh reservasi ini?')">Batalkan Reservasi</button>
                <button type="submit" name="action" value="confirm" class="flex-1 py-2 text-sm font-semibold bg-success text-white rounded-none hover:bg-green-600 transition-colors shadow-zp-sm">Terima & Lunas</button>
            </form>
        </div>
    </div>
</div>

<?php
$storageBase = base_url('assets/uploads/');
$extraScripts = "
<script>
function openValidateModal(b) {
    document.getElementById('val_booking_id').value = b.id;
    document.getElementById('val_id').innerText = '#' + b.id;
    document.getElementById('val_customer').innerText = b.customer_name;
    document.getElementById('val_total').innerText = 'Rp' + new Intl.NumberFormat('id-ID').format(b.total_price);
    
    var proofUrl = '{$storageBase}' + b.payment_proof;
    document.getElementById('val_proof_img').src = proofUrl;
    document.getElementById('val_proof_link').href = proofUrl;
    
    document.getElementById('validateModal').classList.remove('hidden');
}
</script>
";
include __DIR__ . '/../layouts/admin_footer.php'; 
?>
