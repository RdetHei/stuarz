<?php
// ScheduleTable component
// Expects: $schedules (array of ['day','start_time','end_time','subject','teacher_name'])
$schedules = $schedules ?? [];
?>
<div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow-md p-4">
  <table class="w-full text-sm text-left">
    <thead>
      <tr class="text-xs text-gray-500">
        <th class="p-2">Hari</th>
        <th class="p-2">Jam</th>
        <th class="p-2">Mata Pelajaran</th>
        <th class="p-2">Guru</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($schedules)): ?>
        <tr><td colspan="4" class="p-4 text-sm text-gray-500">Belum ada jadwal.</td></tr>
      <?php endif; ?>
      <?php foreach ($schedules as $s): ?>
        <tr class="border-t border-gray-100 dark:border-gray-700">
          <td class="p-2 align-top"><?= htmlspecialchars($s['day'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
          <td class="p-2 align-top"><?= htmlspecialchars(($s['start_time'] ?? '-') . ' - ' . ($s['end_time'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
          <td class="p-2 align-top"><?= htmlspecialchars($s['subject'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
          <td class="p-2 align-top"><?= htmlspecialchars($s['teacher_name'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
