<div id="tasksContent" class="min-h-[80vh] bg-slate-900 text-slate-200">
  <div class="max-w-7xl mx-auto p-6">

    <?php if (isset($_SESSION['success'])): ?>
      <div class="mb-6 bg-emerald-700/10 border border-emerald-600/20 text-emerald-300 px-4 py-3 rounded-md flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <div class="text-sm"><?= htmlspecialchars($_SESSION['success']) ?></div>
      </div>
      <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="mb-6 bg-rose-700/10 border border-rose-600/20 text-rose-300 px-4 py-3 rounded-md flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div class="text-sm"><?= htmlspecialchars($_SESSION['error']) ?></div>
      </div>
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="mb-8">
      <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-4">
          <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-sky-600 to-indigo-600 flex items-center justify-center shadow-md">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
          </div>
          <div>
            <h1 class="text-3xl font-semibold">Tasks</h1>
            <p class="text-slate-400 text-sm mt-1">Kelola tugas dan assignment siswa</p>
          </div>
        </div>

        <?php 
        $userLevel = $_SESSION['level'] ?? 'user';
        if ($userLevel === 'admin' || $userLevel === 'guru'): 
        ?>
        <div class="flex items-center gap-3">
          <form method="post" action="index.php?page=tasks/send-reminders" onsubmit="return confirm('Kirim reminder ke siswa?')" class="hidden sm:block">
            <button type="submit" class="px-4 py-2 text-sm bg-slate-800 border border-slate-700 text-slate-200 rounded-md hover:bg-slate-700 transition flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-9.33-4.98"/></svg>
              Kirim Reminder
            </button>
          </form>
          <a href="index.php?page=tasks/create" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-md font-medium transition-all duration-200 flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Task
          </a>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
      <div class="flex items-center gap-3 w-full sm:w-auto">
        <div class="relative w-full max-w-md">
          <input id="taskSearch" type="search" placeholder="Cari tugas, kelas, guru..." class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-md text-slate-200 focus:border-sky-500" />
          <svg class="w-4 h-4 text-slate-400 absolute right-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35"/></svg>
        </div>
        <select id="filterStatus" class="px-3 py-2 bg-slate-800 border border-slate-700 rounded-md text-slate-200">
          <option value="all">All Status</option>
          <option value="published">Published</option>
          <option value="draft">Draft</option>
          <option value="in_review">In Review</option>
          <option value="closed">Closed</option>
        </select>
      </div>
      <div class="flex items-center gap-3">
        <button id="clearFilters" class="px-3 py-2 bg-slate-700 text-slate-200 rounded-md">Clear</button>
      </div>
    </div>

    <?php 
    $statCards = [
      ['label' => 'Total Tugas', 'value' => (int)($progressSummary['total'] ?? 0), 'caption' => 'Tersedia'],
      ['label' => 'Sudah Dikirim', 'value' => (int)($progressSummary['submitted'] ?? 0), 'caption' => 'Submissions'],
      ['label' => 'Perlu Review', 'value' => (int)($progressSummary['in_review'] ?? 0), 'caption' => 'In review'],
      ['label' => 'Perlu Revisi', 'value' => (int)($progressSummary['needs_revision'] ?? 0), 'caption' => 'Needs revision'],
      ['label' => 'Ternilai', 'value' => (int)($progressSummary['graded'] ?? 0), 'caption' => 'Graded'],
    ];
    ?>
    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-5 gap-4 mb-8">
      <?php foreach ($statCards as $card): ?>
      <div class="p-4 rounded-md bg-slate-800 border border-slate-700 shadow-sm">
        <p class="text-sm text-slate-400"><?= $card['label'] ?></p>
        <p class="text-2xl font-semibold text-slate-100 mt-1"><?= $card['value'] ?></p>
        <p class="text-xs text-slate-400 mt-1"><?= $card['caption'] ?></p>
      </div>
      <?php endforeach; ?>
    </div>

    <?php 
      $pendingList = array_slice($pendingReviewTasks ?? [], 0, 4);
      $dueSoonList = array_slice($dueSoonTasks ?? [], 0, 4);
    ?>
    <?php if ((!empty($pendingList) && $userLevel !== 'user') || !empty($dueSoonList)): ?>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      <?php if ($userLevel !== 'user' && !empty($pendingList)): ?>
      <div class="bg-slate-800 border border-slate-700 rounded-md p-4">
        <div class="flex items-center justify-between mb-3">
          <div>
            <h3 class="text-white font-semibold">Menunggu Review</h3>
            <p class="text-slate-400 text-sm">Prioritaskan tugas dengan status pending/revisi.</p>
          </div>
          <span class="px-2 py-1 text-xs rounded-md bg-rose-600/10 text-rose-300 border border-rose-600/20"><?= count($pendingReviewTasks) ?> task</span>
        </div>
        <div class="space-y-3">
          <?php foreach ($pendingList as $item): 
            $task = $item['task'];
            $stat = $item['stats'];
          ?>
          <div class="p-3 rounded-md bg-slate-900 border border-slate-700 flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-slate-100"><?= htmlspecialchars($task['title'] ?? '-') ?></p>
              <p class="text-xs text-slate-400"><?= htmlspecialchars($task['class_name'] ?? '-') ?> • Deadline <?= htmlspecialchars($task['deadline'] ?? '-') ?></p>
            </div>
            <div class="text-right text-xs text-slate-300">
              <div>Pending: <span class="text-slate-100 font-semibold"><?= intval($stat['pending'] ?? 0) ?></span></div>
              <div>Revisi: <span class="text-slate-100 font-semibold"><?= intval($stat['needs_revision'] ?? 0) ?></span></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <?php if (!empty($dueSoonList)): ?>
      <div class="bg-slate-800 border border-slate-700 rounded-md p-4">
        <div class="flex items-center justify-between mb-3">
          <div>
            <h3 class="text-white font-semibold">Deadline Terdekat</h3>
            <p class="text-slate-400 text-sm">Fokus pada tugas yang akan jatuh tempo.</p>
          </div>
          <span class="px-2 py-1 text-xs rounded-md bg-amber-600/10 text-amber-200 border border-amber-600/20"><?= count($dueSoonTasks ?? []) ?> task</span>
        </div>
        <div class="space-y-3">
          <?php foreach ($dueSoonList as $task): ?>
          <div class="p-3 rounded-md bg-slate-900 border border-slate-700">
            <p class="text-sm font-medium text-slate-100"><?= htmlspecialchars($task['title'] ?? '-') ?></p>
            <div class="flex items-center justify-between text-xs text-slate-400 mt-1">
              <span><?= htmlspecialchars($task['class_name'] ?? '-') ?></span>
              <span>Deadline: <?= htmlspecialchars($task['deadline'] ?? '-') ?></span>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($tasks ?? [])): ?>
    <div id="tasksGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
      <?php foreach (($tasks ?? []) as $t): ?>
      <?php 
        $workflowState = strtolower($t['workflow_state'] ?? 'published');
        $stateConfig = [
          'draft' => ['label' => 'Draft', 'class' => 'bg-slate-700 text-slate-200 border-slate-600'],
          'published' => ['label' => 'Published', 'class' => 'bg-sky-700/10 text-sky-400 border-sky-600/20'],
          'in_review' => ['label' => 'In Review', 'class' => 'bg-violet-700/10 text-violet-300 border-violet-600/20'],
          'closed' => ['label' => 'Closed', 'class' => 'bg-slate-700 text-slate-300 border-slate-600'],
        ];
        $state = $stateConfig[$workflowState] ?? $stateConfig['published'];

        $userLevel = $_SESSION['level'] ?? 'user';
        $userId = $_SESSION['user_id'] ?? 0;
        $canEdit = ($userLevel === 'admin') || ($userLevel === 'guru' && $t['user_id'] == $userId);

        $cardTitleAttr = htmlspecialchars(strtolower($t['title'] ?? ''));
        $cardClassAttr = htmlspecialchars(strtolower($t['class_name'] ?? ''));
        $cardSubjectAttr = htmlspecialchars(strtolower($t['subject_name'] ?? ''));
        $cardStatusAttr = htmlspecialchars(strtolower($workflowState));
      ?>

      <div id="task-row-<?= $t['id'] ?>" class="bg-slate-800 border border-slate-700 rounded-md overflow-hidden hover:shadow-md transition" 
           data-title="<?= $cardTitleAttr ?>" 
           data-class="<?= $cardClassAttr ?>" 
           data-subject="<?= $cardSubjectAttr ?>" 
           data-status="<?= $cardStatusAttr ?>">
        
        <div class="p-4 border-b border-slate-700">
          <div class="flex items-start justify-between gap-3 mb-3">
            <div class="flex-1 min-w-0">
              <h4 class="text-base font-semibold text-slate-100 truncate mb-1"><?= htmlspecialchars($t['title'] ?? '-') ?></h4>
              <div class="flex items-center gap-2 text-xs text-slate-400">
                <span><?= htmlspecialchars($t['subject_name'] ?? '-') ?></span>
                <span>•</span>
                <span><?= htmlspecialchars($t['class_name'] ?? '-') ?></span>
              </div>
            </div>
            <div class="text-right flex-shrink-0">
              <div class="text-xs text-slate-400">Deadline</div>
              <div class="text-xs text-slate-100 font-medium mt-0.5"><?= htmlspecialchars($t['deadline'] ?? '-') ?></div>
            </div>
          </div>

          <div class="flex items-center gap-2 flex-wrap">
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border <?= $state['class'] ?>"><?= $state['label'] ?></span>
            <?php if (!empty($t['approval_required'])): ?>
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-700/10 text-amber-300 border border-amber-600/20">Approval</span>
            <?php endif; ?>
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-700 text-slate-200 border border-slate-600"><?= intval($t['max_attempts'] ?? 1) ?>x</span>
            <?php if (!empty($t['allow_late'])): ?>
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-700/10 text-emerald-300 border border-emerald-600/20">Late OK</span>
            <?php endif; ?>
          </div>
        </div>

        <div class="p-4 bg-slate-900">
          <div class="flex items-center gap-3 mb-3 pb-3 border-b border-slate-700">
            <div class="w-9 h-9 rounded-full bg-sky-600 flex items-center justify-center flex-shrink-0">
              <span class="text-white text-xs font-semibold"><?= strtoupper(substr($t['teacher_name'] ?? 'N', 0, 1)) ?></span>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-sm text-slate-100 font-medium truncate"><?= htmlspecialchars($t['teacher_name'] ?? 'N/A') ?></div>
              <div class="text-xs text-slate-400">
                <?php 
                $levelBadges = ['guru' => 'Teacher','admin' => 'Admin'];
                echo $levelBadges[$t['teacher_level'] ?? ''] ?? 'User';
                ?>
              </div>
            </div>
            <div>
              <?php if (($t['status'] ?? '') === 'Completed'): ?>
              <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-700/10 text-emerald-300 border border-emerald-600/20">Done</span>
              <?php else: ?>
              <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-700/10 text-amber-300 border border-amber-600/20">Open</span>
              <?php endif; ?>
            </div>
          </div>

          <div class="text-sm">
            <?php if ($userLevel === 'user'): ?>
              <?php $sub = $t['student_submission'] ?? null; ?>
              <?php if ($sub): 
                $reviewStatus = $sub['review_status'] ?? 'pending';
                $statusConfig = [
                  'pending' => 'bg-amber-700/10 text-amber-300 border-amber-600/20',
                  'in_review' => 'bg-sky-700/10 text-sky-300 border-sky-600/20',
                  'needs_revision' => 'bg-rose-700/10 text-rose-300 border-rose-600/20',
                  'approved' => 'bg-emerald-700/10 text-emerald-300 border-emerald-600/20',
                  'graded' => 'bg-indigo-700/10 text-indigo-300 border-indigo-600/20'
                ];
                $statusClass = $statusConfig[$reviewStatus] ?? 'bg-slate-700 text-slate-200 border-slate-600';
              ?>
              <div class="space-y-2">
                <div class="flex items-center justify-between">
                  <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border <?= $statusClass ?>"><?= ucfirst(str_replace('_', ' ', $reviewStatus)) ?></span>
                  <span class="text-xs text-slate-400">Attempt #<?= intval($sub['attempt_no'] ?? 1) ?> <?= !empty($sub['is_final']) ? '• Final' : '' ?></span>
                </div>
                <?php if (!empty($sub['grade'])): ?>
                <div class="text-xs text-emerald-300 font-semibold">Grade: <?= floatval($sub['grade']) ?></div>
                <?php endif; ?>
              </div>
              <?php else: ?>
              <div class="text-xs text-slate-400">No submission yet</div>
              <?php endif; ?>
            <?php else: ?>
              <?php $stat = $teacherSubmissionStats[$t['id']] ?? null; ?>
              <?php if ($stat): ?>
              <div class="grid grid-cols-2 gap-2 text-xs">
                <div class="text-slate-400">Pending: <span class="text-slate-100 font-medium"><?= intval($stat['pending'] ?? 0) ?></span></div>
                <div class="text-slate-400">Review: <span class="text-slate-100 font-medium"><?= intval($stat['in_review'] ?? 0) ?></span></div>
                <div class="text-slate-400">Revision: <span class="text-slate-100 font-medium"><?= intval($stat['needs_revision'] ?? 0) ?></span></div>
                <div class="text-slate-400">Graded: <span class="text-slate-100 font-medium"><?= intval($stat['graded'] ?? 0) ?></span></div>
              </div>
              <?php else: ?>
              <div class="text-xs text-slate-400">No submissions yet</div>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>

        <div class="p-3 bg-slate-900 border-t border-slate-700 flex items-center gap-2 justify-end">
          <?php if ($canEdit): ?>
          <a href="index.php?page=tasks/edit&id=<?= $t['id'] ?>" class="px-3 py-1.5 bg-sky-700/10 hover:bg-sky-700/20 border border-sky-700/20 text-sky-300 rounded-md text-xs font-medium">Edit</a>
          <button type="button" 
                  class="delete-btn px-3 py-1.5 bg-rose-700/10 hover:bg-rose-700/20 border border-rose-700/20 text-rose-300 rounded-md text-xs font-medium"
                  data-id="<?= $t['id'] ?>"
                  data-url="index.php?page=tasks/delete"
                  data-item-name="<?= htmlspecialchars($t['title'] ?? 'Task', ENT_QUOTES, 'UTF-8') ?>"
                  data-row-selector=".bg-slate-800">Delete</button>
          <?php endif; ?>

          <?php if ($userLevel === 'user' && !empty($_SESSION['user_id'])): ?>
          <button onclick="openSubmitModal({ taskId: <?= $t['id'] ?>, classId: <?= intval($t['class_id'] ?? 0) ?> })" class="px-3 py-1.5 bg-emerald-700/10 hover:bg-emerald-700/20 border border-emerald-700/20 text-emerald-300 rounded-md text-xs font-medium">Submit</button>
          <?php endif; ?>

          <?php if (($userLevel === 'admin' || $userLevel === 'guru') && $canEdit): ?>
          <button onclick="viewSubmissions(<?= $t['id'] ?>)" class="px-3 py-1.5 bg-violet-700/10 hover:bg-violet-700/20 border border-violet-700/20 text-violet-300 rounded-md text-xs font-medium">Submissions</button>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="bg-slate-800 border border-slate-700 rounded-md px-4 py-3">
      <div class="flex items-center justify-between text-xs text-slate-400">
        <div class="flex items-center gap-2">
          <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2"/></svg>
          <span>Total: <span class="text-slate-100 font-medium"><?= count($tasks ?? []) ?></span> tasks</span>
        </div>
        <div class="flex items-center gap-2">
          <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/></svg>
          <span>Updated: <?= date('d M Y, H:i') ?></span>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <?php if (empty($tasks ?? [])): ?>
    <div class="bg-slate-800 border border-slate-700 rounded-md overflow-hidden mt-6">
      <div class="p-8 text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-lg bg-slate-700 flex items-center justify-center">
          <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2"/></svg>
        </div>
        <h3 class="text-lg font-semibold text-slate-100 mb-2">Belum Ada Tasks</h3>
        <p class="text-sm text-slate-400 mb-6">Belum ada tugas yang tersedia. Mulai tambahkan tugas pertama untuk siswa.</p>
        <?php 
        $userLevel = $_SESSION['level'] ?? 'user';
        if ($userLevel === 'admin' || $userLevel === 'guru'): 
        ?>
        <a href="index.php?page=tasks/create" class="inline-flex items-center gap-2 px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white text-sm font-medium rounded-md">Add First Task</a>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

    <div class="mt-6 bg-slate-800 border border-slate-700 rounded-md overflow-hidden">
      <div class="px-5 py-3 border-b border-slate-700 bg-slate-900">
        <div class="flex items-center gap-2">
          <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <h3 class="text-sm font-semibold text-slate-100">Tentang Tasks</h3>
        </div>
      </div>
      <div class="p-5">
        <ul class="space-y-2">
          <li class="flex items-start gap-2 text-sm text-slate-300">
            <svg class="w-4 h-4 text-sky-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>Tasks dapat diberikan kepada kelas atau siswa tertentu</span>
          </li>
          <li class="flex items-start gap-2 text-sm text-slate-300">
            <svg class="w-4 h-4 text-sky-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>Siswa dapat mengumpulkan tugas sebelum deadline</span>
          </li>
          <li class="flex items-start gap-2 text-sm text-slate-300">
            <svg class="w-4 h-4 text-sky-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>Guru dapat memberikan feedback dan nilai untuk setiap submission</span>
          </li>
        </ul>
      </div>
    </div>

  </div>

  <div id="submitModal" class="fixed inset-0 bg-black/70 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-slate-800 border border-slate-700 rounded-md max-w-lg w-full">
      <div class="flex items-center justify-between px-5 py-3 border-b border-slate-700">
        <h3 class="text-base font-semibold text-slate-100">Submit Task</h3>
        <button onclick="closeSubmitModal()" class="text-slate-400 hover:text-slate-200">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>

      <form id="submitForm" action="index.php?page=tasks/submit" method="post" enctype="multipart/form-data" class="p-5">
        <input type="hidden" name="task_id" id="submit_task_id">
        <input type="hidden" name="class_id" id="submit_class_id">

        <div class="mb-4">
          <label class="block text-xs font-medium text-slate-400 mb-2">File Upload</label>
          <input id="submit_file" type="file" name="file" required class="w-full text-sm text-slate-200 bg-slate-900 border border-slate-700 rounded-md file:mr-3 file:py-2 file:px-3 file:rounded-l-md file:bg-slate-700 file:text-slate-200 hover:file:bg-slate-600 cursor-pointer" />
        </div>

        <div id="filePreview" class="mb-4 text-xs text-slate-400 bg-slate-900 rounded-md p-3 hidden"></div>

        <div class="mb-4">
          <label class="block text-xs font-medium text-slate-400 mb-2">Catatan (Opsional)</label>
          <textarea id="submit_note" name="note" rows="3" placeholder="Tambahkan catatan..." class="w-full px-3 py-2 text-sm bg-slate-900 border border-slate-700 rounded-md text-slate-200 placeholder-slate-500 focus:border-sky-500 focus:ring-1 focus:ring-sky-500"></textarea>
        </div>

        <div class="mb-4">
          <div class="w-full bg-slate-700 rounded-full h-1.5 overflow-hidden">
            <div id="uploadProgress" class="h-1.5 bg-sky-500 w-0 transition-all duration-300"></div>
          </div>
        </div>

        <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-700">
          <button type="button" onclick="closeSubmitModal()" class="px-4 py-2 text-sm font-medium text-slate-300 bg-slate-700 hover:bg-slate-600 border border-slate-600 rounded-md">Cancel</button>
          <button id="submitBtn" type="button" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-md">Submit</button>
        </div>
      </form>
    </div>
  </div>

  <script src="<?= base_url('js/tasks.js') ?>"></script>

  <div id="submissionsModal" class="fixed inset-0 bg-black/70 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-slate-800 border border-slate-700 rounded-md max-w-4xl w-full max-h-[80vh] flex flex-col">
      <div class="flex items-center justify-between px-5 py-3 border-b border-slate-700 flex-shrink-0">
        <div>
          <h3 class="text-base font-semibold text-slate-100">Task Submissions</h3>
          <p class="text-xs text-slate-400 mt-0.5">Lihat semua pengumpulan tugas</p>
        </div>
        <button onclick="closeSubmissionsModal()" class="text-slate-400 hover:text-slate-200">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>

      <div id="submissionsContent" class="p-5 overflow-y-auto flex-1 bg-slate-900">
        <div class="flex items-center justify-center py-8">
          <div class="text-center">
            <div class="w-12 h-12 mx-auto mb-3 rounded-lg bg-slate-700 flex items-center justify-center animate-pulse">
              <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581"/></svg>
            </div>
            <p class="text-sm text-slate-400">Loading submissions...</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
  function viewSubmissions(taskId) {
    const modal = document.getElementById('submissionsModal');
    const content = document.getElementById('submissionsContent');
    content.innerHTML = '<div class="text-center text-slate-400">Loading submissions...</div>';
    modal.classList.remove('hidden');

    fetch(`index.php?page=tasks/submissions&task_id=${taskId}`)
      .then(response => response.text())
      .then(data => { content.innerHTML = data; })
      .catch(() => { content.innerHTML = '<div class="text-center text-rose-400">Error loading submissions</div>'; });
  }

  function closeSubmissionsModal() { document.getElementById('submissionsModal').classList.add('hidden'); }
  document.getElementById('submissionsModal').addEventListener('click', function(e){ if (e.target === this) closeSubmissionsModal(); });

  function initTasksPage(){
    const modal = document.getElementById('submissionsModal');
    if(!modal) return;
    modal.removeEventListener('click', window._tasks_modal_click_handler || (()=>{}));
    window._tasks_modal_click_handler = function(e){ if(e.target === modal){ modal.classList.add('hidden'); } };
    modal.addEventListener('click', window._tasks_modal_click_handler);
  }
  (function(){ initTasksPage(); })();
  </script>
</div>