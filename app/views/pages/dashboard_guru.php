<?php
// Sample data fallbacks (controllers should provide real data)
$teacher = $teacher ?? [
    'name' => 'Nama Guru',
    'subject' => 'Kimia',
    'email' => 'guru@example.com',
    'joined' => '2022-08-01',
    'bio' => 'Pengajar Kimia dengan fokus praktik laboratorium.'
];

$summary = $summary ?? [
    'classes' => 3,
    'students' => 92,
    'pending_grading' => 12,
    'messages' => 4
];

$submissions = $submissions ?? [
    ['title'=>'Laporan Praktikum','class'=>'X IPA 2','meta'=>'12 submissions','age'=>'1d'],
    ['title'=>'Ulangan Harian','class'=>'X IPA 1','meta'=>'8 submissions','age'=>'3d']
];

$classes = $classes ?? [
    ['name'=>'X IPA 1','students'=>25,'time'=>'Mon/Wed 08:00'],
    ['name'=>'X IPA 2','students'=>27,'time'=>'Tue/Thu 10:00'],
    ['name'=>'X IPA 3','students'=>40,'time'=>'Fri 09:00']
];

$performance = $performance ?? [
    'average' => 84,
    'min' => 55,
    'max' => 98,
    'grades' => [95,93,90,88,85,82,78,75,70,65]
];

?>

<!-- Dashboard Guru view (partial) -->
<main class="max-w-7xl mx-auto p-6 bg-gray-900">
  <!-- Header Profile -->
  <section class="bg-gray-800 rounded-lg p-6 mb-6">
    <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
      <img src="<?= htmlspecialchars($teacher['avatar'] ?? ('https://ui-avatars.com/api/?name=' . urlencode($teacher['name']) . '&background=5865f2&color=fff')) ?>"
           alt="avatar" class="w-24 h-24 rounded-full border-4 border-gray-700 object-cover flex-shrink-0">
      <div class="flex-1">
        <div class="flex items-center gap-3">
          <h1 class="text-2xl font-bold text-white"><?= htmlspecialchars($teacher['name']) ?></h1>
          <span class="px-3 py-1 rounded-md bg-green-500/10 border border-green-500/20 text-green-400 text-xs font-medium">Active</span>
        </div>
        <div class="text-sm text-gray-400 mt-2"><?= htmlspecialchars($teacher['subject']) ?> • <?= htmlspecialchars($teacher['email']) ?></div>
        <div class="mt-2 text-xs text-gray-500">Member since <span class="text-gray-400"><?= htmlspecialchars($teacher['joined']) ?></span></div>
        <?php if (!empty($teacher['bio'])): ?>
          <p class="mt-4 text-sm text-gray-300 max-w-2xl leading-relaxed"><?= htmlspecialchars($teacher['bio']) ?></p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Summary Cards -->
  <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-gray-800 rounded-lg p-5 hover:bg-gray-750 transition-colors">
      <div class="flex items-center justify-between mb-3">
        <span class="text-gray-400 text-sm font-medium">Total Classes</span>
        <div class="p-2 rounded-md bg-blue-500/10">
          <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z"/></svg>
        </div>
      </div>
      <div class="text-3xl font-bold text-white mb-1"><?= (int)$summary['classes'] ?></div>
      <div class="text-xs text-gray-500">Active classes</div>
    </div>

    <div class="bg-gray-800 rounded-lg p-5 hover:bg-gray-750 transition-colors">
      <div class="flex items-center justify-between mb-3">
        <span class="text-gray-400 text-sm font-medium">Total Students</span>
        <div class="p-2 rounded-md bg-purple-500/10">
          <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M12 14a4 4 0 100-8 4 4 0 000 8z"/></svg>
        </div>
      </div>
      <div class="text-3xl font-bold text-white mb-1"><?= (int)$summary['students'] ?></div>
      <div class="text-xs text-gray-500">Enrolled</div>
    </div>

    <div class="bg-gray-800 rounded-lg p-5 hover:bg-gray-750 transition-colors">
      <div class="flex items-center justify-between mb-3">
        <span class="text-gray-400 text-sm font-medium">Pending Grading</span>
        <div class="p-2 rounded-md bg-yellow-500/10">
          <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
      </div>
      <div class="text-3xl font-bold text-white mb-1"><?= (int)$summary['pending_grading'] ?></div>
      <div class="text-xs text-gray-500">Needs grading</div>
    </div>

    <div class="bg-gray-800 rounded-lg p-5 hover:bg-gray-750 transition-colors">
      <div class="flex items-center justify-between mb-3">
        <span class="text-gray-400 text-sm font-medium">Messages</span>
        <div class="p-2 rounded-md bg-green-500/10">
          <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4-.85L3 20l1.25-3.2A7.967 7.967 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        </div>
      </div>
      <div class="text-3xl font-bold text-white mb-1"><?= (int)$summary['messages'] ?></div>
      <div class="text-xs text-gray-500">Unread</div>
    </div>
  </section>

  <!-- Main Content Grid -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column - Submissions & Performance -->
    <div class="lg:col-span-2 space-y-6">
      
      <!-- Submissions to Grade -->
      <div class="bg-gray-800 rounded-lg p-6">
        <div class="flex items-center justify-between mb-5">
          <h2 class="text-lg font-semibold text-white">Submissions to Grade</h2>
          <a href="#" class="text-sm text-blue-400 hover:text-blue-300 transition-colors">View all →</a>
        </div>
        <div class="space-y-3">
          <?php foreach ($submissions as $s): ?>
          <div class="bg-gray-750 hover:bg-gray-700 rounded-lg p-4 transition-colors border border-gray-700">
            <div class="flex justify-between items-start">
              <div class="flex-1">
                <div class="text-sm font-medium text-white"><?= htmlspecialchars($s['title']) ?></div>
                <div class="text-xs text-gray-400 mt-1"><?= htmlspecialchars($s['class']) ?> • <?= htmlspecialchars($s['meta']) ?></div>
              </div>
              <div class="flex items-center gap-3">
                <span class="text-xs text-gray-500"><?= htmlspecialchars($s['age']) ?> ago</span>
                <a href="#" class="px-3 py-1.5 text-xs font-medium rounded-md bg-blue-500 hover:bg-blue-600 text-white transition-colors">Grade</a>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Performance Chart -->
      <div class="bg-gray-800 rounded-lg p-6">
        <div class="flex items-center justify-between mb-5">
          <h2 class="text-lg font-semibold text-white">Class Performance</h2>
          <a href="#" class="text-sm text-blue-400 hover:text-blue-300 transition-colors">Details →</a>
        </div>
        
        <div class="grid grid-cols-2 gap-4 mb-6">
          <div class="bg-gray-750 rounded-lg p-4 border border-gray-700">
            <div class="text-xs text-gray-400 mb-2">Average Score</div>
            <div class="text-2xl font-bold text-white"><?= (int)$performance['average'] ?><span class="text-lg text-gray-500">%</span></div>
            <div class="text-xs text-gray-500 mt-2">Min: <?= (int)$performance['min'] ?> • Max: <?= (int)$performance['max'] ?></div>
          </div>
          
          <div class="bg-gray-750 rounded-lg p-4 border border-gray-700">
            <div class="text-xs text-gray-400 mb-2">Top Students</div>
            <div class="space-y-1.5 text-xs">
              <div class="flex justify-between text-white"><span>🥇 Ahmad</span><span class="text-yellow-400 font-medium">95</span></div>
              <div class="flex justify-between text-white"><span>🥈 Siti</span><span class="text-gray-400 font-medium">93</span></div>
              <div class="flex justify-between text-white"><span>🥉 Rina</span><span class="text-orange-400 font-medium">90</span></div>
            </div>
          </div>
        </div>

        <div class="bg-gray-750 rounded-lg p-4 border border-gray-700">
          <canvas id="teacherPerformanceChart" width="600" height="200"></canvas>
        </div>
      </div>

    </div>

    <!-- Right Column - Classes & Quick Actions -->
    <div class="space-y-6">
      
      <!-- Class Overview -->
      <div class="bg-gray-800 rounded-lg p-6">
        <div class="flex items-center justify-between mb-5">
          <h2 class="text-lg font-semibold text-white">My Classes</h2>
          <a href="#" class="text-sm text-blue-400 hover:text-blue-300 transition-colors">All →</a>
        </div>
        <div class="space-y-3">
          <?php foreach ($classes as $c): ?>
          <div class="bg-gray-750 hover:bg-gray-700 rounded-lg p-4 transition-colors border border-gray-700 cursor-pointer">
            <div class="flex items-center justify-between">
              <div>
                <div class="text-sm font-medium text-white"><?= htmlspecialchars($c['name']) ?></div>
                <div class="text-xs text-gray-400 mt-1"><?= htmlspecialchars($c['time']) ?></div>
              </div>
              <div class="px-2.5 py-1 rounded-md bg-gray-600/50 text-xs font-medium text-gray-300">
                <?= (int)$c['students'] ?> students
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="bg-gray-800 rounded-lg p-6">
        <h2 class="text-lg font-semibold text-white mb-4">Quick Actions</h2>
        <div class="space-y-2">
          <a href="#" class="flex items-center gap-3 w-full p-3 bg-gray-750 hover:bg-blue-500/10 hover:border-blue-500/20 border border-gray-700 rounded-lg text-sm text-gray-300 hover:text-blue-400 transition-all group">
            <span class="text-lg group-hover:scale-110 transition-transform">📝</span>
            <span class="font-medium">Create Assignment</span>
          </a>
          <a href="#" class="flex items-center gap-3 w-full p-3 bg-gray-750 hover:bg-purple-500/10 hover:border-purple-500/20 border border-gray-700 rounded-lg text-sm text-gray-300 hover:text-purple-400 transition-all group">
            <span class="text-lg group-hover:scale-110 transition-transform">📨</span>
            <span class="font-medium">Send Announcement</span>
          </a>
          <a href="#" class="flex items-center gap-3 w-full p-3 bg-gray-750 hover:bg-green-500/10 hover:border-green-500/20 border border-gray-700 rounded-lg text-sm text-gray-300 hover:text-green-400 transition-all group">
            <span class="text-lg group-hover:scale-110 transition-transform">📊</span>
            <span class="font-medium">Export Scores</span>
          </a>
        </div>
      </div>

    </div>

  </div>

</main>

<!-- Chart.js initialization for teacher performance -->
<script>
document.addEventListener('DOMContentLoaded', function(){
  try{
    const grades = <?= json_encode(array_values($performance['grades'])) ?>;
    const ctx = document.getElementById('teacherPerformanceChart');
    if (ctx) {
      new Chart(ctx.getContext('2d'), {
        type: 'bar',
        data: { 
          labels: grades.map((_,i) => 'S'+(i+1)), 
          datasets:[{ 
            label:'Scores', 
            data:grades, 
            backgroundColor:'#5865f2',
            borderRadius: 6,
            borderSkipped: false
          }] 
        },
        options: { 
          responsive:true, 
          maintainAspectRatio:false, 
          plugins:{
            legend:{display:false}
          }, 
          scales:{
            y:{
              beginAtZero:true,
              grid: {
                color: '#374151',
                drawBorder: false
              },
              ticks: {
                color: '#9ca3af'
              }
            },
            x: {
              grid: {
                display: false
              },
              ticks: {
                color: '#9ca3af'
              }
            }
          }
        }
      });
    }
  }catch(e){console.error(e)}
});
</script>

<?php
// end of dashboard_guru.php
?>