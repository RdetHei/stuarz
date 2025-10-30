<div class="bg-gray-900 min-h-screen">
  <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-white">Documentation</h1>
    </div>

    <!-- 🔍 Search Bar (sticky on large) -->
    <div class="mb-8 lg:mb-10">
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
            class="flex-1 pl-11 pr-4 py-2 rounded-l-xl rounded-r-none bg-gray-800/70 border border-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
          <button type="submit"
            class="px-5 py-2 rounded-r-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold border border-l-0 border-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            Search
          </button>
        </div>
      </form>
    </div>

    <div class="grid gap-8 lg:grid-cols-12">
      <!-- Sidebar Navigation -->
      <aside class="lg:col-span-3">
        <div class="lg:sticky lg:top-16 lg:self-start lg:z-10">
          <?php if (!empty($docs)): ?>
            <nav class="space-y-8">
              <?php foreach ($docs as $section => $items): ?>
                <div>
                  <div class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-3"><?= htmlspecialchars($section) ?></div>
                  <ul class="space-y-1">
                    <?php foreach ($items as $doc): ?>
                      <?php $isActive = isset($_GET['doc']) && $_GET['doc'] === $doc['slug']; ?>
                      <li>
                        <a href="index.php?page=docs&doc=<?= urlencode($doc['slug']) ?>"
                          class="block rounded-lg px-3 py-2 text-sm <?= $isActive ? 'bg-gray-800 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-800' ?>">
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
  <main class="lg:col-span-9 lg:sticky lg:top-22 lg:self-start lg:z-10">
        <?php if ($currentDoc): ?>
          <!-- Detail dokumen -->
          <article class="bg-gray-800/60 border border-gray-700 rounded-2xl p-8 ring-1 ring-white/5">
            <h1 class="text-3xl font-bold text-white mb-4">
              <?= htmlspecialchars($currentDoc['title']) ?>
            </h1>
            <div class="prose prose-invert max-w-none text-gray-300 leading-relaxed">
              <?= nl2br(htmlspecialchars($currentDoc['content'])) ?>
            </div>
          </article>
          <a href="index.php?page=docs" class="mt-6 inline-block text-indigo-400 hover:text-indigo-300">← Back to documentation</a>
        <?php else: ?>
          <!-- Daftar dokumen sebagai list (ala Tailwind) -->
          <div class="space-y-10">
            <?php if (!empty($docs)): ?>
              <?php foreach ($docs as $section => $items): ?>
                <section>
                  <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-400 mb-3"><?= htmlspecialchars($section) ?></h2>
                  <ul class="divide-y divide-gray-800 rounded-xl border border-gray-800 overflow-hidden">
                    <?php foreach ($items as $doc): ?>
                      <li>
                        <a href="index.php?page=docs&doc=<?= urlencode($doc['slug']) ?>" class="group block p-4 hover:bg-gray-800/60">
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