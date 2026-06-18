<?php 
$title = 'Rating & Ulasan - SportVenue';
include __DIR__ . '/../layouts/admin_header.php'; 
?>

<div class="bg-white dark:bg-gray-900 rounded-zp-lg border border-gray-100 dark:border-gray-800 overflow-hidden shadow-zp-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800/50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Booking</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Pelanggan</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Lapangan</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Rating</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400 w-1/3">Ulasan</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                <?php foreach ($ratings as $r): ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <td class="px-4 py-4 align-top">
                        <span class="font-mono text-sport-500 font-semibold bg-sport-50 dark:bg-sport-900/20 px-2 py-1 rounded-md">#<?= $r['booking_id'] ?></span>
                    </td>
                    <td class="px-4 py-4 align-top">
                        <p class="font-medium text-gray-900 dark:text-gray-100"><?= e($r['customer_name']) ?></p>
                        <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                            <i data-lucide="mail" class="w-3 h-3"></i> <?= e($r['customer_email']) ?>
                        </p>
                    </td>
                    <td class="px-4 py-4 align-top">
                        <span class="font-medium text-gray-700 dark:text-gray-300"><?= e($r['field_name']) ?></span>
                    </td>
                    <td class="px-4 py-4 align-top">
                        <div class="flex items-center gap-0.5">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i data-lucide="star" class="w-4 h-4 <?= $i <= $r['rating'] ? 'text-yellow-400 fill-yellow-400' : 'text-gray-200 dark:text-gray-700' ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </td>
                    <td class="px-4 py-4 align-top">
                        <?php if (!empty($r['review'])): ?>
                            <p class="text-gray-600 dark:text-gray-400 line-clamp-3 text-sm italic">"<?= e($r['review']) ?>"</p>
                        <?php else: ?>
                            <span class="text-gray-400 text-sm italic">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-4 align-top">
                        <div class="flex items-center gap-1.5 text-gray-500 text-sm">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            <?= date('d M Y', strtotime($r['created_at'])) ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if (empty($ratings)): ?>
    <div class="text-center py-12">
        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-3">
            <i data-lucide="star-off" class="w-8 h-8 text-gray-400"></i>
        </div>
        <p class="text-gray-500 dark:text-gray-400 font-medium">Belum ada ulasan atau rating.</p>
    </div>
    <?php endif; ?>
</div>

<?php if ($totalPages > 1): ?>
<div class="flex items-center justify-center gap-2 mt-6">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <a href="<?= base_url('admin/ratings?page=' . $i) ?>" class="w-8 h-8 flex items-center justify-center text-sm font-medium rounded-zp-pill transition-colors <?= $i === $currentPage ? 'bg-sport-500 text-white shadow-zp-sm' : 'bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' ?>">
        <?= $i ?>
    </a>
    <?php endfor; ?>
</div>
<?php endif; ?>

<?php 
include __DIR__ . '/../layouts/admin_footer.php'; 
?>
