<div class="bg-gray-900 min-h-screen">
    <div class="mx-auto max-w-7xl px-6 py-8 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-lg bg-[#5865F2]/10 flex items-center justify-center border border-[#5865F2]/20">
                    <svg class="w-5 h-5 text-[#5865F2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-100">Print Data</h1>
            </div>
            <p class="text-gray-400">Pilih tabel yang ingin di-print atau export ke PDF</p>
        </div>

        <!-- Search & Filter -->
        <div class="mb-6">
            <div class="relative max-w-md">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" 
                       id="searchTable"
                       placeholder="Search tables..." 
                       class="w-full pl-10 pr-4 py-2.5 bg-[#1f2937] border border-gray-700 rounded-lg text-sm text-gray-200 placeholder-gray-500 focus:border-[#5865F2] focus:ring-1 focus:ring-[#5865F2] focus:outline-none transition-colors">
            </div>
        </div>

        <!-- Tables Grid -->
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 mb-8" id="tablesGrid">
            <?php
            global $config;
            $sql = "SHOW TABLES";
            $result = mysqli_query($config, $sql);
            $tables = [];
            
            if ($result) {
                while ($row = mysqli_fetch_array($result)) {
                    $tables[] = $row[0];
                }
                mysqli_free_result($result);
            }
            
            foreach ($tables as $table):
                $tableName = ucfirst(str_replace('_', ' ', $table));
                $countSql = "SELECT COUNT(*) as total FROM `{$table}`";
                $countResult = mysqli_query($config, $countSql);
                $count = 0;
                if ($countResult) {
                    $countRow = mysqli_fetch_assoc($countResult);
                    $count = $countRow['total'] ?? 0;
                    mysqli_free_result($countResult);
                }
            ?>
                <div class="table-card bg-[#1f2937] border border-gray-700 rounded-lg overflow-hidden hover:border-gray-600 transition-colors" 
                     data-table-name="<?= htmlspecialchars(strtolower($tableName)) ?>">
                    <!-- Card Header -->
                    <div class="px-5 py-4 border-b border-gray-700 bg-[#111827]">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-[#5865F2]/10 flex items-center justify-center border border-[#5865F2]/20">
                                    <svg class="w-5 h-5 text-[#5865F2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-base font-semibold text-gray-100"><?= htmlspecialchars($tableName) ?></h3>
                            </div>
                            <span class="px-2.5 py-1 bg-gray-800 text-gray-400 text-xs font-medium rounded-full">
                                <?= number_format($count) ?>
                            </span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="px-5 py-4">
                        <div class="mb-4">
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                <span>Table: <code class="text-[#5865F2] font-mono"><?= htmlspecialchars($table) ?></code></span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col gap-2">
                            <a href="index.php?page=print_table&table=<?= urlencode($table) ?>" 
                               class="flex items-center justify-center gap-2 px-4 py-2 bg-[#5865F2] hover:bg-[#4752C4] text-white rounded-md transition-colors text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                                Print Table
                            </a>
                            <button onclick="exportTable('<?= htmlspecialchars($table) ?>')"
                                    class="flex items-center justify-center gap-2 px-4 py-2 bg-[#111827] hover:bg-gray-700 border border-gray-700 text-gray-300 rounded-md transition-colors text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                                </svg>
                                Export CSV
                            </button>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="px-5 py-3 bg-[#111827] border-t border-gray-700">
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span><?= $count ?> record<?= $count != 1 ? 's' : '' ?></span>
                            <a href="index.php?page=view_table&table=<?= urlencode($table) ?>" 
                               class="text-gray-400 hover:text-[#5865F2] transition-colors flex items-center gap-1">
                                View data
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Batch/Print-All functionality removed from UI -->
    </div>
</div>

<script>
// Search functionality
document.getElementById('searchTable').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const cards = document.querySelectorAll('.table-card');
    
    cards.forEach(card => {
        const tableName = card.getAttribute('data-table-name');
        if (tableName.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

// Export single table
function exportTable(tableName) {
    window.location.href = `index.php?page=export_table&table=${encodeURIComponent(tableName)}&format=csv`;
}

// Export all tables
function exportAllTables() {
    if (confirm('Export semua tabel ke CSV? Ini mungkin memakan waktu beberapa saat.')) {
        window.location.href = 'index.php?page=export_all_tables&format=csv';
    }
}
</script>














