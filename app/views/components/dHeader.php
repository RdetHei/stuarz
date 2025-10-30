<?php
// Title maps
$pageTitles = [
    'dashboard' => 'Dashboard',
    'dashboard-admin' => 'Admin Dashboard',
    'profile' => 'Profile',
    'account' => 'Account Management',
    'certificates' => 'Sertifikat',
    'dashboard-admin-docs' => 'Dokumentasi',
    'class' => 'Kelas',
    'dashboard-admin-docs-create' => 'Documentations - Create',
    'dashboard-admin-news' => 'Berita',
    'announcement' => 'Pengumuman',
    'subjects' => 'Mata Pelajar',
    'grades' => 'Grades',
    'schedule' => 'Jadwal',
    'tasks' => 'Tugas',
    'attendance' => 'Kehadiran',
];

$dashboardTitles = [
    'account'  => 'Daftar Akun',
    'overview' => 'Ringkasan',
    'settings' => 'Pengaturan Dashboard',
    'docs'     => 'Dokumentasi',
];

// read same params as index.php / sidebar
$currentPage = $_GET['page'] ?? 'home';
$currentSub  = $_GET['dashboard'] ?? null;

// decide title: prefer dashboard subpage title when on dashboard+sub
if ($currentPage === 'dashboard' && $currentSub) {
    $title = $dashboardTitles[$currentSub] ?? '-';
} else {
    $title = $pageTitles[$currentPage] ?? '-';
}
?>
<header id="dHeader" class="bg-slate-900 text-white h-14 flex items-center justify-between px-4 transition-all duration-300">
    <div class="flex items-center gap-4">
        <!-- Mobile menu button -->
        <button id="mobileMenuToggle" class="lg:hidden p-2 hover:bg-slate-700 rounded" aria-label="Toggle mobile menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <div class="font-bold"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></div>
    </div>
    <div class="relative">
        <button id="supportDropdownBtn" class="p-2 hover:bg-slate-700 rounded flex items-center gap-2" aria-haspopup="true" aria-expanded="false">
            Support
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        <div id="supportDropdownMenu" class="absolute right-0 mt-2 w-40 bg-white text-black rounded shadow-lg hidden z-10">
            <a href="mailto:support@example.com" class="block px-4 py-2 hover:bg-slate-100">Email Support</a>
            <a href="https://wa.me/628123456789" class="block px-4 py-2 hover:bg-slate-100">WhatsApp</a>
        </div>
    </div>
</header>

<script>
    (function() {
        const btn = document.getElementById('supportDropdownBtn');
        const menu = document.getElementById('supportDropdownMenu');
        if (!btn || !menu) return;

        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            menu.classList.toggle('hidden');
        });

        document.addEventListener('click', function() {
            menu.classList.add('hidden');
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') menu.classList.add('hidden');
        });
    })();
</script>