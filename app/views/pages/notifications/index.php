<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$user = $_SESSION['user'] ?? null;
?>

<div class="bg-gray-900 min-h-screen">
    <div class="max-w-5xl mx-auto px-6 py-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-100">Notifikasi</h1>
                <p class="text-sm text-gray-400 mt-1">
                    <?php if (!empty($notifications)): ?>
                        <?= count($notifications) ?> notifikasi
                    <?php else: ?>
                        Belum ada notifikasi
                    <?php endif; ?>
                </p>
            </div>
            <?php if (!empty($notifications)): ?>
            <button onclick="markAllAsRead()" 
                    class="px-4 py-2 bg-[#1f2937] hover:bg-gray-700 border border-gray-700 text-sm text-gray-300 rounded-md transition-colors">
                Tandai Semua Dibaca
            </button>
            <?php endif; ?>
        </div>

        <!-- Notifications List -->
        <div class="space-y-3">
            <?php if (empty($notifications)): ?>
                <!-- Empty State -->
                <div class="bg-[#1f2937] border border-gray-700 rounded-lg p-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-xl bg-gray-800 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-100 mb-2">Belum Ada Notifikasi</h3>
                    <p class="text-gray-400 text-sm">Notifikasi akan muncul di sini ketika ada aktivitas baru</p>
                </div>
            <?php else: ?>
                <?php foreach ($notifications as $n): ?>
                    <?php 
                    $entity = $n['entity'] ?? ($n['type'] ?? 'general');
                    $entityId = $n['entity_id'] ?? ($n['reference_id'] ?? null);
                    ?>
                    <?php 
                    $type = $n['type'] ?? 'info';
                    $isDelete = $type === 'delete';
                    $isUnread = !($n['is_read'] ?? false);
                    ?>

                    <?php if ($isDelete): ?>
                        <!-- Delete Notification -->
                        <div class="bg-[#1f2937] border-l-4 border-red-500 rounded-lg overflow-hidden hover:bg-gray-800 transition-colors">
                            <div class="p-4">
                                <div class="flex items-start gap-4">
                                    <!-- Icon -->
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-red-500/10 flex items-center justify-center border border-red-500/20">
                                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-3 mb-2">
                                            <div>
                                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-red-500/10 text-red-400 border border-red-500/20 rounded text-xs font-medium">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Penghapusan
                                                </span>
                                                <span class="text-xs text-gray-500 ml-2">
                                                    <?= htmlspecialchars($entity) ?> 
                                                    <?= $entityId ? '#'.htmlspecialchars($entityId) : '' ?>
                                                </span>
                                            </div>
                                            <time class="text-xs text-gray-500 whitespace-nowrap">
                                                <?= htmlspecialchars($n['created_at']) ?>
                                            </time>
                                        </div>
                                        <p class="text-sm text-gray-300 leading-relaxed">
                                            <?= htmlspecialchars($n['message']) ?>
                                        </p>
                                        <?php if (!empty($n['url'])): ?>
                                        <div class="mt-3">
                                            <a href="<?= htmlspecialchars($n['url']) ?>" 
                                               class="inline-flex items-center gap-1.5 text-sm text-red-400 hover:text-red-300 transition-colors">
                                                Lihat Detail
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php else: ?>
                        <!-- Regular Notification -->
                        <?php
                        $iconBg = 'bg-[#5865F2]/10';
                        $iconBorder = 'border-[#5865F2]/20';
                        $iconColor = 'text-[#5865F2]';
                        $badgeBg = 'bg-[#5865F2]/10';
                        $badgeText = 'text-[#5865F2]';
                        $badgeBorder = 'border-[#5865F2]/20';
                        $linkColor = 'text-[#5865F2] hover:text-[#4752C4]';
                        $icon = 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';

                        if ($type === 'success') {
                            $iconBg = 'bg-emerald-500/10';
                            $iconBorder = 'border-emerald-500/20';
                            $iconColor = 'text-emerald-400';
                            $badgeBg = 'bg-emerald-500/10';
                            $badgeText = 'text-emerald-400';
                            $badgeBorder = 'border-emerald-500/20';
                            $linkColor = 'text-emerald-400 hover:text-emerald-300';
                            $icon = 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z';
                        } elseif ($type === 'warning') {
                            $iconBg = 'bg-amber-500/10';
                            $iconBorder = 'border-amber-500/20';
                            $iconColor = 'text-amber-400';
                            $badgeBg = 'bg-amber-500/10';
                            $badgeText = 'text-amber-400';
                            $badgeBorder = 'border-amber-500/20';
                            $linkColor = 'text-amber-400 hover:text-amber-300';
                            $icon = 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z';
                        } elseif ($type === 'error') {
                            $iconBg = 'bg-red-500/10';
                            $iconBorder = 'border-red-500/20';
                            $iconColor = 'text-red-400';
                            $badgeBg = 'bg-red-500/10';
                            $badgeText = 'text-red-400';
                            $badgeBorder = 'border-red-500/20';
                            $linkColor = 'text-red-400 hover:text-red-300';
                            $icon = 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z';
                        }
                        ?>

                        <div class="bg-[#1f2937] border border-gray-700 rounded-lg overflow-hidden hover:border-gray-600 transition-colors <?= $isUnread ? 'ring-1 ring-[#5865F2]/20' : '' ?>">
                            <div class="p-4">
                                <div class="flex items-start gap-4">
                                    <!-- Icon -->
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg <?= $iconBg ?> flex items-center justify-center border <?= $iconBorder ?>">
                                        <svg class="w-5 h-5 <?= $iconColor ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $icon ?>"/>
                                        </svg>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-3 mb-2">
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 <?= $badgeBg ?> <?= $badgeText ?> border <?= $badgeBorder ?> rounded text-xs font-medium">
                                                    <?= htmlspecialchars(ucfirst($type)) ?>
                                                </span>
                                                <span class="text-xs text-gray-500">
                                                    <?= htmlspecialchars($entity) ?> 
                                                    <?= $entityId ? '#'.htmlspecialchars($entityId) : '' ?>
                                                </span>
                                                <?php if ($isUnread): ?>
                                                <span class="flex-shrink-0 w-2 h-2 bg-[#5865F2] rounded-full"></span>
                                                <?php endif; ?>
                                            </div>
                                            <time class="text-xs text-gray-500 whitespace-nowrap">
                                                <?= htmlspecialchars($n['created_at']) ?>
                                            </time>
                                        </div>
                                        <p class="text-sm text-gray-300 leading-relaxed">
                                            <?= htmlspecialchars($n['message']) ?>
                                        </p>
                                        <?php if (!empty($n['url'])): ?>
                                        <div class="mt-3">
                                            <a href="<?= htmlspecialchars($n['url']) ?>" 
                                               class="inline-flex items-center gap-1.5 text-sm <?= $linkColor ?> transition-colors">
                                                Buka Halaman
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function markAllAsRead() {
    // Implement mark all as read functionality
    fetch('index.php?page=notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>