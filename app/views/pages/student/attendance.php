<?php
// Student: Attendance
// Expects: $attendances (array), summary counts: $presentCount, $lateCount, $leaveCount, $sickCount
$attendances = $attendances ?? [];
$presentCount = $presentCount ?? 0;
$lateCount = $lateCount ?? 0;
$leaveCount = $leaveCount ?? 0;
$sickCount = $sickCount ?? 0;
?>
<div class="max-w-4xl mx-auto p-6">
  <div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-semibold text-white">Kehadiran Saya</h2>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-gray-800 border border-gray-700 rounded-lg p-4 text-center">
      <div class="text-sm text-gray-400">Hadir</div>
      <div class="text-xl font-semibold text-white"><?= intval($presentCount) ?></div>
    </div>
    <div class="bg-gray-800 border border-gray-700 rounded-lg p-4 text-center">
      <div class="text-sm text-gray-400">Terlambat</div>
      <div class="text-xl font-semibold text-white"><?= intval($lateCount) ?></div>
    </div>
    <div class="bg-gray-800 border border-gray-700 rounded-lg p-4 text-center">
      <div class="text-sm text-gray-400">Izin</div>
      <div class="text-xl font-semibold text-white"><?= intval($leaveCount) ?></div>
    </div>
    <div class="bg-gray-800 border border-gray-700 rounded-lg p-4 text-center">
      <div class="text-sm text-gray-400">Sakit</div>
      <div class="text-xl font-semibold text-white"><?= intval($sickCount) ?></div>
    </div>
  </div>

  <div class="bg-gray-800 border border-gray-700 rounded-lg p-4">
    <table class="w-full text-sm text-left">
      <thead class="text-gray-400 text-xs uppercase">
        <tr>
          <th class="p-2">Tanggal</th>
          <th class="p-2">Status</th>
          <th class="p-2">Check-in</th>
          <th class="p-2">Check-out</th>
        </tr>
      </thead>
      <tbody class="text-gray-300">
        <?php if (empty($attendances)): ?>
          <tr><td colspan="4" class="p-4 text-gray-400">Belum ada data kehadiran.</td></tr>
        <?php else: foreach ($attendances as $a): ?>
          <tr class="border-t border-gray-700">
            <td class="p-2"><?= htmlspecialchars($a['date']) ?></td>
            <td class="p-2"><?= htmlspecialchars($a['status']) ?></td>
            <td class="p-2"><?= htmlspecialchars($a['check_in'] ?? '-') ?></td>
            <td class="p-2"><?= htmlspecialchars($a['check_out'] ?? '-') ?></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
