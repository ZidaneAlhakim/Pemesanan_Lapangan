<?php
$title = 'Manajemen Admin - SportVenue';
include __DIR__ . '/../layouts/admin_header.php';
?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Manajemen Admin</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola akun administrator yang memiliki akses ke panel ini.</p>
    </div>
    <button onclick="openCreateModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-sport-500 text-white text-sm font-semibold rounded-none hover:bg-sport-600 transition-all shadow-sm">
        <i data-lucide="user-plus" class="w-4 h-4"></i>
        Tambah Admin
    </button>
</div>

<!-- Stats Card -->
<div class="bg-white dark:bg-gray-900 rounded-none border border-gray-100 dark:border-gray-800 p-5 shadow-zp-sm mb-6 flex items-center gap-4">
    <div class="w-12 h-12 rounded-none bg-sport-500/10 flex items-center justify-center shrink-0">
        <i data-lucide="shield" class="w-6 h-6 text-sport-500"></i>
    </div>
    <div>
        <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= count($users) ?></p>
        <p class="text-sm text-gray-500 dark:text-gray-400">Total Administrator Aktif</p>
    </div>
</div>

<!-- Users Table -->
<div class="bg-white dark:bg-gray-900 rounded-none border border-gray-100 dark:border-gray-800 shadow-zp-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center gap-2">
        <i data-lucide="users" class="w-4 h-4 text-gray-400"></i>
        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Daftar Administrator</span>
    </div>

    <?php if (empty($users)): ?>
    <div class="text-center py-16">
        <div class="w-16 h-16 rounded-none bg-gray-50 dark:bg-gray-800 flex items-center justify-center mx-auto mb-4">
            <i data-lucide="users" class="w-8 h-8 text-gray-300 dark:text-gray-600"></i>
        </div>
        <p class="text-gray-500 dark:text-gray-400 font-medium">Belum ada administrator terdaftar.</p>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800/50 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    <th class="px-6 py-3 text-left">Nama / Username</th>
                    <th class="px-6 py-3 text-left">Role</th>
                    <th class="px-6 py-3 text-left">Bergabung</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                <?php foreach ($users as $u): ?>
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-none bg-gradient-to-br from-sport-400 to-sport-600 flex items-center justify-center text-white font-bold text-sm shrink-0">
                                <?= strtoupper(substr($u['display_name'], 0, 1)) ?>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white"><?= e($u['display_name']) ?></p>
                                <p class="text-xs text-gray-400">@<?= e($u['username']) ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-none text-xs font-semibold <?= $u['role'] === 'superadmin' ? 'bg-purple-50 text-purple-600 dark:bg-purple-900/20 dark:text-purple-400' : 'bg-sport-50 text-sport-600 dark:bg-sport-900/20 dark:text-sport-400' ?>">
                            <i data-lucide="<?= $u['role'] === 'superadmin' ? 'crown' : 'shield' ?>" class="w-3 h-3"></i>
                            <?= ucfirst(e($u['role'])) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                        <?= date('d M Y', strtotime($u['created_at'])) ?>
                    </td>
                    <td class="px-6 py-4">
                        <?php if ($u['id'] == $currentUser['id']): ?>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-none text-xs font-semibold bg-success/10 text-success">
                            <span class="w-1.5 h-1.5 rounded-none bg-success animate-pulse"></span>
                            Anda (Aktif)
                        </span>
                        <?php else: ?>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-none text-xs font-semibold bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                            <span class="w-1.5 h-1.5 rounded-none bg-gray-400"></span>
                            Admin
                        </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="openEditModal(<?= htmlspecialchars(json_encode($u)) ?>)" class="p-1.5 text-gray-400 hover:text-sport-500 hover:bg-sport-50 dark:hover:bg-sport-900/20 rounded-none transition-all" title="Edit">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </button>
                            <?php if ($u['id'] != $currentUser['id']): ?>
                            <button onclick="confirmDelete(<?= $u['id'] ?>, '<?= e($u['display_name']) ?>')" class="p-1.5 text-gray-400 hover:text-danger hover:bg-danger/10 rounded-none transition-all" title="Hapus">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                            <?php else: ?>
                            <span class="p-1.5 text-gray-200 dark:text-gray-700 cursor-not-allowed" title="Tidak dapat menghapus akun aktif">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<!-- Security Note -->
<div class="mt-4 flex items-start gap-3 p-4 rounded-none bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/50">
    <i data-lucide="info" class="w-4 h-4 text-blue-500 mt-0.5 shrink-0"></i>
    <div class="text-xs text-blue-600 dark:text-blue-400">
        <p class="font-semibold mb-0.5">Keamanan Akun</p>
        <p>Semua password disimpan dengan enkripsi bcrypt. Anda tidak dapat melihat password yang sudah ada. Kosongkan field password saat edit jika tidak ingin mengubahnya.</p>
    </div>
</div>

<!-- ===================== MODALS ===================== -->
<!-- Create Modal -->
<div id="createModal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-900 rounded-none shadow-2xl w-full max-w-md border border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-none bg-sport-500/10 flex items-center justify-center">
                    <i data-lucide="user-plus" class="w-4 h-4 text-sport-500"></i>
                </div>
                <h3 class="font-bold text-gray-900 dark:text-white">Tambah Administrator</h3>
            </div>
            <button onclick="closeCreateModal()" class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-none hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form method="POST" action="<?= base_url('admin/users') ?>" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="create">

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Nama Tampilan</label>
                <input type="text" name="display_name" placeholder="John Doe" required class="w-full px-3.5 py-2.5 text-sm border border-gray-200 dark:border-gray-700 rounded-none bg-white dark:bg-gray-800 focus:ring-2 focus:ring-sport-500/50 focus:border-sport-500 text-gray-900 dark:text-white outline-none transition-all">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Username</label>
                <input type="text" name="username" placeholder="johndoe" required autocomplete="off" class="w-full px-3.5 py-2.5 text-sm border border-gray-200 dark:border-gray-700 rounded-none bg-white dark:bg-gray-800 focus:ring-2 focus:ring-sport-500/50 focus:border-sport-500 text-gray-900 dark:text-white outline-none transition-all">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="createPassword" placeholder="Min. 8 karakter" required autocomplete="new-password" class="w-full px-3.5 py-2.5 pr-10 text-sm border border-gray-200 dark:border-gray-700 rounded-none bg-white dark:bg-gray-800 focus:ring-2 focus:ring-sport-500/50 focus:border-sport-500 text-gray-900 dark:text-white outline-none transition-all">
                    <button type="button" onclick="togglePassword('createPassword', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Role</label>
                <select name="role" class="w-full px-3.5 py-2.5 text-sm border border-gray-200 dark:border-gray-700 rounded-none bg-white dark:bg-gray-800 focus:ring-2 focus:ring-sport-500/50 focus:border-sport-500 text-gray-900 dark:text-white outline-none transition-all">
                    <option value="admin">Admin</option>
                    <option value="superadmin">Superadmin</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeCreateModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-none hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                    Batal
                </button>
                <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-semibold bg-sport-500 text-white rounded-none hover:bg-sport-600 transition-colors shadow-sm">
                    Tambah
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-900 rounded-none shadow-2xl w-full max-w-md border border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-none bg-yellow-500/10 flex items-center justify-center">
                    <i data-lucide="pencil" class="w-4 h-4 text-yellow-600"></i>
                </div>
                <h3 class="font-bold text-gray-900 dark:text-white">Edit Administrator</h3>
            </div>
            <button onclick="closeEditModal()" class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-none hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form method="POST" action="<?= base_url('admin/users') ?>" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="editId">

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Nama Tampilan</label>
                <input type="text" name="display_name" id="editDisplayName" placeholder="John Doe" required class="w-full px-3.5 py-2.5 text-sm border border-gray-200 dark:border-gray-700 rounded-none bg-white dark:bg-gray-800 focus:ring-2 focus:ring-sport-500/50 focus:border-sport-500 text-gray-900 dark:text-white outline-none transition-all">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Username</label>
                <input type="text" name="username" id="editUsername" placeholder="johndoe" required autocomplete="off" class="w-full px-3.5 py-2.5 text-sm border border-gray-200 dark:border-gray-700 rounded-none bg-white dark:bg-gray-800 focus:ring-2 focus:ring-sport-500/50 focus:border-sport-500 text-gray-900 dark:text-white outline-none transition-all">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                    Password Baru
                    <span class="text-gray-400 font-normal normal-case">(kosongkan jika tidak berubah)</span>
                </label>
                <div class="relative">
                    <input type="password" name="password" id="editPassword" placeholder="Min. 8 karakter" autocomplete="new-password" class="w-full px-3.5 py-2.5 pr-10 text-sm border border-gray-200 dark:border-gray-700 rounded-none bg-white dark:bg-gray-800 focus:ring-2 focus:ring-sport-500/50 focus:border-sport-500 text-gray-900 dark:text-white outline-none transition-all">
                    <button type="button" onclick="togglePassword('editPassword', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Role</label>
                <select name="role" id="editRole" class="w-full px-3.5 py-2.5 text-sm border border-gray-200 dark:border-gray-700 rounded-none bg-white dark:bg-gray-800 focus:ring-2 focus:ring-sport-500/50 focus:border-sport-500 text-gray-900 dark:text-white outline-none transition-all">
                    <option value="admin">Admin</option>
                    <option value="superadmin">Superadmin</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeEditModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-none hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                    Batal
                </button>
                <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-semibold bg-sport-500 text-white rounded-none hover:bg-sport-600 transition-colors shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-900 rounded-none shadow-2xl w-full max-w-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="p-6 text-center">
            <div class="w-16 h-16 rounded-none bg-danger/10 flex items-center justify-center mx-auto mb-4">
                <i data-lucide="alert-triangle" class="w-8 h-8 text-danger"></i>
            </div>
            <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-2">Konfirmasi Hapus</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Apakah Anda yakin ingin menghapus admin <strong id="deleteUserName" class="text-gray-900 dark:text-white"></strong>? Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        <form method="POST" action="<?= base_url('admin/users') ?>" class="flex border-t border-gray-100 dark:border-gray-800">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" id="deleteId">
            <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-3.5 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors border-r border-gray-100 dark:border-gray-800">
                Batal
            </button>
            <button type="submit" class="flex-1 px-4 py-3.5 text-sm font-semibold text-danger hover:bg-danger/5 transition-colors">
                Ya, Hapus
            </button>
        </form>
    </div>
</div>

<script>
// Create Modal
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Edit Modal
function openEditModal(user) {
    document.getElementById('editId').value = user.id;
    document.getElementById('editDisplayName').value = user.display_name;
    document.getElementById('editUsername').value = user.username;
    document.getElementById('editRole').value = user.role;
    document.getElementById('editPassword').value = '';
    document.getElementById('editModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Delete Modal
function confirmDelete(id, name) {
    document.getElementById('deleteId').value = id;
    document.getElementById('deleteUserName').textContent = name;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Password Toggle
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';
    const icon = btn.querySelector('i');
    icon.setAttribute('data-lucide', isPassword ? 'eye-off' : 'eye');
    lucide.createIcons();
}

// Close modals on backdrop click
['createModal', 'editModal', 'deleteModal'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
            document.body.style.overflow = '';
        }
    });
});
</script>

<?php include __DIR__ . '/../layouts/admin_footer.php'; ?>
