<div class="max-w-7xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-xl font-bold text-white">Documentation</h1>
    </div>

    <!-- 🔍 Search Bar -->
    <div class="mb-6">
      <form method="GET" action="index.php" class="w-full lg:max-w-xl">
        <input type="hidden" name="page" value="docs">
        <div class="relative flex items-stretch">
          <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
            <span class="material-symbols-outlined mr-3">search</span>
          </div>
          <input
            type="text"
            name="q"
          
            value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
            class="flex-1 pl-11 pr-4 py-2 rounded-l-lg bg-gray-700 text-white border border-gray-600 focus:ring-indigo-500 focus:border-indigo-500">
          <button type="submit"
            class="px-5 py-2 rounded-r-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium transition">
            Search
          </button>
        </div>
      </form>
    </div>

    <div class="grid gap-6 lg:grid-cols-12">
      <!-- Sidebar Navigation -->
      <aside class="lg:col-span-3">
        <div class="lg:sticky lg:top-20">
          <?php if (!empty($docs)): ?>
            <nav class="space-y-6">
              <?php foreach ($docs as $section => $items): ?>
                <div>
                  <div class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2"><?= htmlspecialchars($section) ?></div>
                  <div class="rounded-2xl shadow-md bg-gray-800 overflow-hidden">
                    <ul class="divide-y divide-gray-700">
                      <?php foreach ($items as $doc): ?>
                        <?php $isActive = isset($_GET['doc']) && $_GET['doc'] === $doc['slug']; ?>
                        <li>
                          <a href="index.php?page=docs&doc=<?= urlencode($doc['slug']) ?>"
                            class="block px-4 py-3 text-sm <?= $isActive ? 'bg-gray-700 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-700' ?> transition">
                          <?= htmlspecialchars($doc['title']) ?>
                        </a>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              <?php endforeach; ?>
            </nav>
          <?php endif; ?>
        </div>
      </aside>

      <!-- Content -->
      <main class="lg:col-span-9 lg:sticky lg:top-20 lg:self-start lg:z-10">
        <?php if ($currentDoc): ?>
          <!-- Detail dokumen -->
          <div class="rounded-2xl shadow-md bg-gray-800 p-8">
            <h1 class="text-xl font-bold text-white mb-4">
              <?= htmlspecialchars($currentDoc['title']) ?>
            </h1>
            <div class="prose prose-invert max-w-none text-gray-300">
              <?= nl2br(htmlspecialchars($currentDoc['content'])) ?>
            </div>
          </div>
          <a href="index.php?page=docs" class="mt-6 inline-block text-indigo-400 hover:text-indigo-300">← Back to documentation</a>
        <?php else: ?>
          <!-- Daftar dokumen sebagai list -->
          <div class="space-y-6">
            <?php if (!empty($docs)): ?>
              <?php foreach ($docs as $section => $items): ?>
                <section>
                  <h2 class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2"><?= htmlspecialchars($section) ?></h2>
                  <div class="rounded-2xl shadow-md bg-gray-800 overflow-hidden">
                    <ul class="divide-y divide-gray-700">
                      <?php foreach ($items as $doc): ?>
                        <li>
                          <a href="index.php?page=docs&doc=<?= urlencode($doc['slug']) ?>" class="block px-4 py-3 hover:bg-gray-700 transition">
                          <div class="flex items-start justify-between gap-4">
                            <div>
                              <h3 class="text-base font-semibold text-white group-hover:text-white">
                                <?= htmlspecialchars($doc['title']) ?>
                              </h3>
                              <p class="mt-1 text-sm text-gray-400 line-clamp-2">
                                <?= htmlspecialchars($doc['description']) ?>
                              </p>
                            </div>
                            <span class="mt-1 text-indigo-400 group-hover:text-indigo-300">→</span>
                          </div>
                        </a>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                </section>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="text-gray-400">No documentation found.</p>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </main>
    </div>
  </div>
</div>