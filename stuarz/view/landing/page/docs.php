<div class="bg-gray-900 min-h-screen">
  <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">

    <?php if ($currentDoc): ?>
      <!-- Detail dokumen -->
      <div class="bg-gray-800 shadow-lg rounded-2xl p-6 mb-6 border border-gray-700">
        <h1 class="text-3xl font-bold text-indigo-400 mb-4">
          <?= htmlspecialchars($currentDoc['title']) ?>
        </h1>

        <div class="prose prose-invert max-w-none text-gray-300 leading-relaxed">
          <?= nl2br(htmlspecialchars($currentDoc['content'])) ?>
        </div>
      </div>

      <a href="index.php?page=docs" class="mt-8 inline-block text-indigo-400 hover:text-indigo-300">← Back to documentation</a>


    <?php else: ?>
      <!-- Daftar dokumen -->
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-white">Documentation</h1>
      </div>

      <!-- 🔍 Search Bar -->
      <form method="GET" action="index.php" class="mb-8 flex max-w-xl">
        <input type="hidden" name="page" value="docs">
        <input
          type="text"
          name="q"
          placeholder="Search documentation..."
          value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
          class="flex-1 px-4 py-2 rounded-l-lg bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <button type="submit"
          class="px-4 py-2 rounded-r-lg bg-indigo-600 hover:bg-indigo-500 text-white font-semibold">
          Search
        </button>
      </form>

      <div class="space-y-10">
        <?php if (!empty($docs)): ?>
          <?php foreach ($docs as $section => $items): ?>
            <div>
              <h2 class="text-2xl font-semibold text-indigo-400 mb-4"><?= htmlspecialchars($section) ?></h2>
              <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($items as $doc): ?>
                  <div class="rounded-xl bg-gray-800 p-6 shadow hover:bg-gray-700 transition">
                    <h3 class="text-lg font-semibold text-white mb-2"><?= htmlspecialchars($doc['title']) ?></h3>
                    <p class="text-gray-400 text-sm mb-3 line-clamp-3"><?= htmlspecialchars($doc['description']) ?></p>
                    <a href="index.php?page=docs&doc=<?= urlencode($doc['slug']) ?>">
                      <div class="mt-2 inline-block text-sm font-semibold text-indigo-400 hover:text-indigo-300">
                        Read more →
                      </div>
                    </a>

                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-gray-400">No documentation found.</p>
        <?php endif; ?>
      </div>
    <?php endif; ?>

  </div>
</div>