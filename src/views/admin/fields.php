<?php 
$title = 'Kelola Lapangan - SportVenue';
include __DIR__ . '/../layouts/admin_header.php'; 
?>

<button onclick="document.getElementById('createModal').classList.remove('hidden')" class="px-4 py-2 bg-sport-500 text-white text-sm font-semibold rounded-zp-pill hover:bg-sport-600 transition-all flex items-center gap-1.5 shadow-zp-sm hover:shadow-zp">
    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Lapangan
</button>

<div class="bg-white dark:bg-gray-900 rounded-zp-lg border border-gray-100 dark:border-gray-800 overflow-hidden shadow-zp-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800/50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400 w-16">Gambar</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Nama</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Olahraga</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Kapasitas</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Harga</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Booking</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                <?php foreach ($fields as $f): ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <td class="px-4 py-3">
                        <?php if (!empty($f['image'])): ?>
                            <img src="<?= storage_url($f['image']) ?>" alt="<?= e($f['name']) ?>" class="w-12 h-12 rounded object-cover border border-gray-200 dark:border-gray-700">
                        <?php else: ?>
                            <div class="w-12 h-12 rounded bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400 border border-gray-200 dark:border-gray-700">
                                <i data-lucide="image" class="w-5 h-5"></i>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 font-medium"><?= e($f['name']) ?></td>
                    <td class="px-4 py-3"><?= e($f['sport']) ?></td>
                    <td class="px-4 py-3"><?= e($f['capacity']) ?></td>
                    <td class="px-4 py-3 font-semibold text-sport-500">Rp<?= number_format($f['price_per_hour'], 0, ',', '.') ?></td>
                    <td class="px-4 py-3"><?= $f['total_bookings'] ?? 0 ?></td>
                    <td class="px-4 py-3">
                        <?php if ($f['is_active']): ?>
                        <span class="px-2 py-0.5 rounded-zp-pill text-xs font-semibold bg-success/10 text-success border border-green-200">Aktif</span>
                        <?php else: ?>
                        <span class="px-2 py-0.5 rounded-zp-pill text-xs font-semibold bg-danger/10 text-danger border border-red-200">Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <button onclick="editField(<?= htmlspecialchars(json_encode($f)) ?>)" class="px-2 py-1 text-xs font-medium text-sport-500 hover:bg-sport-25 dark:hover:bg-sport-900/20 rounded-zp-pill transition-colors">Edit</button>
                        <?php if ($f['is_active']): ?>
                        <form method="POST" action="<?= base_url('admin/fields') ?>" class="inline" onsubmit="return confirm('Nonaktifkan lapangan ini?')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $f['id'] ?>">
                            <button type="submit" class="px-2 py-1 text-xs font-medium text-danger hover:bg-danger/5 rounded-zp-pill transition-colors">Nonaktifkan</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="createModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 overflow-y-auto">
    <div class="bg-white dark:bg-gray-900 rounded-zp-lg border border-gray-100 dark:border-gray-800 shadow-zp p-6 w-full max-w-md my-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-lg">Tambah Lapangan</h3>
            <button onclick="document.getElementById('createModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form method="POST" action="<?= base_url('admin/fields') ?>" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="create">
            <div><label class="block text-sm font-medium mb-1">Nama Lapangan <span class="text-danger">*</span></label><input type="text" name="name" required class="w-full px-3.5 py-2.5 text-sm border border-gray-200 dark:border-gray-700 rounded-zp bg-white dark:bg-gray-800 focus:ring-2 focus:ring-sport-500/50 outline-none transition-shadow"></div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-sm font-medium mb-1">Olahraga <span class="text-danger">*</span></label><input type="text" name="sport" required placeholder="Futsal, Basket..." class="w-full px-3.5 py-2.5 text-sm border border-gray-200 dark:border-gray-700 rounded-zp bg-white dark:bg-gray-800 focus:ring-2 focus:ring-sport-500/50 outline-none transition-shadow"></div>
                <div><label class="block text-sm font-medium mb-1">Kapasitas <span class="text-danger">*</span></label><input type="text" name="capacity" required placeholder="10 orang" class="w-full px-3.5 py-2.5 text-sm border border-gray-200 dark:border-gray-700 rounded-zp bg-white dark:bg-gray-800 focus:ring-2 focus:ring-sport-500/50 outline-none transition-shadow"></div>
            </div>
            <div><label class="block text-sm font-medium mb-1">Harga per Jam (Rp) <span class="text-danger">*</span></label><input type="number" name="price_per_hour" required min="0" placeholder="150000" class="w-full px-3.5 py-2.5 text-sm border border-gray-200 dark:border-gray-700 rounded-zp bg-white dark:bg-gray-800 focus:ring-2 focus:ring-sport-500/50 outline-none transition-shadow"></div>
            <div><label class="block text-sm font-medium mb-1">Gambar Lapangan</label><input type="file" name="image" accept="image/jpeg, image/png, image/webp" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-zp-pill file:border-0 file:text-sm file:font-semibold file:bg-sport-50 file:text-sport-600 hover:file:bg-sport-100 dark:file:bg-sport-900/30 dark:file:text-sport-400"></div>
            <div><label class="block text-sm font-medium mb-1">Deskripsi</label><textarea name="description" rows="3" class="w-full px-3.5 py-2.5 text-sm border border-gray-200 dark:border-gray-700 rounded-zp bg-white dark:bg-gray-800 focus:ring-2 focus:ring-sport-500/50 outline-none transition-shadow"></textarea></div>
            <div class="flex gap-2 justify-end pt-4 border-t border-gray-100 dark:border-gray-800 mt-6">
                <button type="button" onclick="this.closest('#createModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium border border-gray-200 dark:border-gray-700 rounded-zp-pill hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-semibold bg-sport-500 text-white rounded-zp-pill hover:bg-sport-600 transition-colors shadow-zp-sm">Simpan Lapangan</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 overflow-y-auto">
    <div class="bg-white dark:bg-gray-900 rounded-zp-lg border border-gray-100 dark:border-gray-800 shadow-zp p-6 w-full max-w-md my-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-lg">Edit Lapangan</h3>
            <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form method="POST" action="<?= base_url('admin/fields') ?>" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            <div><label class="block text-sm font-medium mb-1">Nama Lapangan <span class="text-danger">*</span></label><input type="text" name="name" id="edit_name" required class="w-full px-3.5 py-2.5 text-sm border border-gray-200 dark:border-gray-700 rounded-zp bg-white dark:bg-gray-800 focus:ring-2 focus:ring-sport-500/50 outline-none transition-shadow"></div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-sm font-medium mb-1">Olahraga <span class="text-danger">*</span></label><input type="text" name="sport" id="edit_sport" required class="w-full px-3.5 py-2.5 text-sm border border-gray-200 dark:border-gray-700 rounded-zp bg-white dark:bg-gray-800 focus:ring-2 focus:ring-sport-500/50 outline-none transition-shadow"></div>
                <div><label class="block text-sm font-medium mb-1">Kapasitas <span class="text-danger">*</span></label><input type="text" name="capacity" id="edit_capacity" required class="w-full px-3.5 py-2.5 text-sm border border-gray-200 dark:border-gray-700 rounded-zp bg-white dark:bg-gray-800 focus:ring-2 focus:ring-sport-500/50 outline-none transition-shadow"></div>
            </div>
            <div><label class="block text-sm font-medium mb-1">Harga per Jam (Rp) <span class="text-danger">*</span></label><input type="number" name="price_per_hour" id="edit_price" required min="0" class="w-full px-3.5 py-2.5 text-sm border border-gray-200 dark:border-gray-700 rounded-zp bg-white dark:bg-gray-800 focus:ring-2 focus:ring-sport-500/50 outline-none transition-shadow"></div>
            
            <div>
                <label class="block text-sm font-medium mb-1">Gambar Baru (Opsional)</label>
                <div class="flex items-center gap-3">
                    <img id="edit_image_preview" src="" alt="Preview" class="hidden w-12 h-12 rounded object-cover border border-gray-200 dark:border-gray-700">
                    <input type="file" name="image" accept="image/jpeg, image/png, image/webp" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-zp-pill file:border-0 file:text-sm file:font-semibold file:bg-sport-50 file:text-sport-600 hover:file:bg-sport-100 dark:file:bg-sport-900/30 dark:file:text-sport-400">
                </div>
            </div>

            <div><label class="block text-sm font-medium mb-1">Deskripsi</label><textarea name="description" id="edit_desc" rows="3" class="w-full px-3.5 py-2.5 text-sm border border-gray-200 dark:border-gray-700 rounded-zp bg-white dark:bg-gray-800 focus:ring-2 focus:ring-sport-500/50 outline-none transition-shadow"></textarea></div>
            <div class="flex gap-2 justify-end pt-4 border-t border-gray-100 dark:border-gray-800 mt-6">
                <button type="button" onclick="this.closest('#editModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium border border-gray-200 dark:border-gray-700 rounded-zp-pill hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-semibold bg-sport-500 text-white rounded-zp-pill hover:bg-sport-600 transition-colors shadow-zp-sm">Simpan Lapangan</button>
            </div>
        </form>
    </div>
</div>

<?php
$storageBase = base_url('assets/uploads/');
$extraScripts = "
<script>
function editField(f) {
    document.getElementById('edit_id').value = f.id;
    document.getElementById('edit_name').value = f.name;
    document.getElementById('edit_sport').value = f.sport;
    document.getElementById('edit_capacity').value = f.capacity;
    document.getElementById('edit_price').value = f.price_per_hour;
    document.getElementById('edit_desc').value = f.description || '';
    
    var preview = document.getElementById('edit_image_preview');
    if (f.image) {
        preview.src = '{$storageBase}' + f.image;
        preview.classList.remove('hidden');
    } else {
        preview.src = '';
        preview.classList.add('hidden');
    }
    
    document.getElementById('editModal').classList.remove('hidden');
}
</script>
";
include __DIR__ . '/../layouts/admin_footer.php'; 
?>
