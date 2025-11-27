
<?php
if (!isset($allDocs)) $allDocs = [];
if (!isset($docs)) $docs = [];
if (!isset($currentDoc)) $currentDoc = null;
if (!isset($cats)) $cats = [];
if (!isset($q)) $q = '';
if (!isset($cat)) $cat = '';
if (!isset($page)) $page = 1;
if (!isset($totalPages)) $totalPages = 1;
if (!isset($ajax)) $ajax = false;
?>

<?php if (!$ajax): ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Documentation</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-900 min-h-screen">
  <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-white">Documentation</h1>
      <p class="text-gray-400 mb-6">The documentations of how the website should work and what it's use</p>
    </div>

    <div class="mb-6 bg-[#1f2937] border border-gray-700 rounded-lg p-4">
      <form method="GET" action="index.php" class="flex flex-col sm:flex-row gap-3">
        <input type="hidden" name="page" value="docs">
        <div class="flex-1 relative">
          <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
          </div>
          <input type="text" name="q" placeholder="Search Documentations..." value="<?= htmlspecialchars($q) ?>" class="w-full pl-10 pr-4 py-2 bg-[#111827] border border-gray-700 text-sm text-gray-200 rounded-md focus:border-[#5865F2] focus:ring-1 focus:ring-[#5865F2] focus:outline-none transition-colors placeholder-gray-500">
        </div>

        <select name="cat" class="px-3 py-2 bg-[#111827] border border-gray-700 text-sm text-gray-200 rounded-md focus:border-[#5865F2] focus:ring-1 focus:ring-[#5865F2] focus:outline-none transition-colors">
          <option value="">All Categories</option>
          <?php foreach ($cats as $c): ?>
            <option value="<?= htmlspecialchars($c) ?>" <?= $cat === $c ? 'selected' : '' ?>><?= htmlspecialchars($c) ?></option>
          <?php endforeach; ?>
        </select>

         <button type="submit"
  class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium
         rounded-md transition-colors whitespace-nowrap">
  Search
</button>

      </form>
    </div>

    <div class="grid gap-9 lg:grid-cols-12">
      <aside class="lg:col-span-3">
        <div class="lg:sticky lg:top-16 lg:self-start lg:z-10">
          <h2 class="text-sm uppercase tracking-wide text-gray-400 mb-4">Documentation Sections</h2>
          <ul class="mb-3">
            <a href="index.php?page=docs" class="block rounded-lg px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-800">
              <span class="inline-flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>All Documentations</span>
              </span>
            </a>
          </li>
          <li>
            <hr class="border-gray-700 mb-3">
          </li>
          </ul>

          </ul>
          <?php if (!empty($docs)): ?>
            <nav class="space-y-8">
              <ul>
                <li>
                  <div class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-3">All Documents</div>
                </li>
              </ul>
              <?php foreach ($docs as $section => $items): ?>
                <div>
                  <div class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-3"><?= htmlspecialchars($section) ?></div>
                  <ul class="space-y-1">
                    <?php foreach ($items as $doc): ?>
                      <?php $isActive = isset($_GET['doc']) && $_GET['doc'] === $doc['slug']; ?>
                      <li>
                        <a href="index.php?page=docs&doc=<?= urlencode($doc['slug']) ?>" class="block rounded-lg px-3 py-2 text-sm <?= $isActive ? 'bg-gray-800 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-800' ?>"><?= htmlspecialchars($doc['title']) ?></a>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              <?php endforeach; ?>
            </nav>
          <?php else: ?>
            <p class="text-gray-400">No documentation sections available.</p>
          <?php endif; ?>
        </div>
      </aside>

      <main id="docsContent" class="lg:col-span-9">
<?php endif; ?>

        <?php if ($currentDoc): ?>
          <div class="space-y-6">
            <nav class="flex items-center gap-2 text-sm">
              <a href="index.php?page=docs" class="text-gray-400 hover:text-gray-200 transition-colors">Documentation</a>
              <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
              <span class="text-gray-300"><?= htmlspecialchars($currentDoc['title']) ?></span>
            </nav>

            <div class="lg:sticky lg:top-20 lg:z-20 lg:self-start">
              <article class="bg-[#1f2937] border border-gray-700 rounded-lg overflow-hidden flex flex-col shadow-xl" style="max-height: calc(100vh - 7rem);">
                <div id="docHeader" class="px-8 py-6 border-b border-gray-700 sticky top-0 z-30 bg-[#1f2937] transition-all duration-200 flex-shrink-0">
                  <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[#5865F2]/10 flex items-center justify-center border border-[#5865F2]/20 flex-shrink-0">
                      <svg class="w-6 h-6 text-[#5865F2]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                      </svg>
                    </div>

                    <div class="flex-1">
                      <h1 class="text-3xl font-bold text-gray-100 mb-2"><?= htmlspecialchars($currentDoc['title']) ?></h1>

                      <?php if (!empty($currentDoc['description'])): ?>
                        <p class="text-base text-gray-400"><?= htmlspecialchars($currentDoc['description']) ?></p>
                      <?php endif; ?>

                      <div class="flex items-center gap-4 mt-4 pt-4 border-t border-gray-700">
                        <?php if (!empty($currentDoc['author'])): ?>
                          <div class="flex items-center gap-2 text-sm text-gray-400">
                            <div class="w-6 h-6 rounded-full bg-gradient-to-br from-[#5865F2] to-[#4752C4] flex items-center justify-center">
                              <span class="text-xs font-semibold text-white"><?= strtoupper(substr($currentDoc['author'], 0, 1)) ?></span>
                            </div>
                            <span><?= htmlspecialchars($currentDoc['author']) ?></span>
                          </div>
                        <?php endif; ?>

                        <?php if (!empty($currentDoc['last_updated'])): ?>
                          <div class="flex items-center gap-2 text-sm text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Updated <?= htmlspecialchars($currentDoc['last_updated']) ?></span>
                          </div>
                        <?php endif; ?>

                        <?php if (!empty($currentDoc['read_time'])): ?>
                          <div class="flex items-center gap-2 text-sm text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <span><?= htmlspecialchars($currentDoc['read_time']) ?> min read</span>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </div>

                <div id="docContent" class="px-8 py-10 overflow-y-auto flex-1">
                  <div class="prose prose-invert prose-lg max-w-none">
                    <div class="text-gray-300 leading-relaxed space-y-6" style="white-space: pre-wrap; line-height: 1.8;">
                      <?= nl2br(htmlspecialchars($currentDoc['content'])) ?>
                    </div>
                  </div>
                </div>

                <div class="px-8 py-6 border-t border-gray-700 bg-[#111827] flex-shrink-0">
                  <div class="flex items-center justify-between">
                    <a href="index.php?page=docs" class="inline-flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:text-gray-100 hover:bg-gray-800 rounded-md transition-colors">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                      </svg>
                      Back to documentation
                    </a>

                    <div class="flex items-center gap-3">
                      <button onclick="copyDocUrl(event)" class="inline-flex items-center gap-2 px-4 py-2 text-sm text-gray-400 hover:text-gray-200 hover:bg-gray-800 rounded-md transition-colors" title="Copy link">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <span class="hidden sm:inline">Copy link</span>
                      </button>

                      <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 text-sm text-gray-400 hover:text-gray-200 hover:bg-gray-800 rounded-md transition-colors" title="Print">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        <span class="hidden sm:inline">Print</span>
                      </button>
                    </div>
                  </div>
                </div>
              </article>
            </div>
          </div>

        <?php else: ?>
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
                              <h3 class="text-base font-semibold text-white group-hover:text-white"><?= htmlspecialchars($doc['title']) ?></h3>
                              <p class="mt-1 text-sm text-gray-400 line-clamp-2"><?= htmlspecialchars($doc['description']) ?></p>
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
            <?php endif; ?>
          </div>
        <?php endif; ?>

<?php if (!$ajax): ?>
      </main>
    </div>
  </div>

  <script>
    function copyDocUrl(e) {
      if (!navigator.clipboard) return;
      navigator.clipboard.writeText(window.location.href).then(() => {
        const btn = (e && e.currentTarget) || (e && e.target) || document.activeElement;
        const originalHTML = btn && btn.innerHTML ? btn.innerHTML : '';
        if (btn) {
          btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="hidden sm:inline">Copied!</span>';
          btn.classList.add('text-emerald-400');
          setTimeout(() => { btn.innerHTML = originalHTML; btn.classList.remove('text-emerald-400'); }, 2000);
        }
      }).catch(() => {});
    }

    (function() {
      const header = document.getElementById('docHeader');
      if (!header) return;
      const SMALL_PAD = 'py-3';
      const LARGE_PAD = 'py-6';
      header.classList.add(LARGE_PAD);
      window.addEventListener('scroll', function() {
        const y = window.scrollY || window.pageYOffset;
        if (y > 80) {
          header.classList.add('shadow-lg');
          header.classList.remove(LARGE_PAD);
          header.classList.add(SMALL_PAD);
        } else {
          header.classList.remove('shadow-lg');
          header.classList.remove(SMALL_PAD);
          header.classList.add(LARGE_PAD);
        }
      }, { passive: true });
    })();

    (function() {
      const pageInput = document.querySelector('input[name="page"][value="docs"]');
      const form = pageInput ? pageInput.closest('form') : null;
      const contentEl = document.getElementById('docsContent');
      if (!form || !contentEl) return;
      const qInput = form.querySelector('input[name="q"]');
      const catSelect = form.querySelector('select[name="cat"]');
      let timer = null;

      function serializeState() {
        return { q: (qInput && qInput.value) || '', cat: (catSelect && catSelect.value) || '' };
      }

      function load(state, push = true) {
        const params = new URLSearchParams();
        params.set('page', 'docs');
        if (state.q) params.set('q', state.q);
        if (state.cat) params.set('cat', state.cat);
        params.set('ajax', '1');

        const url = 'index.php?' + params.toString();
        fetch(url, { credentials: 'same-origin' }).then(r => r.text()).then(html => {
          contentEl.innerHTML = html;
          // Execute script tags inside returned HTML
          const scripts = contentEl.querySelectorAll('script');
          scripts.forEach(s => {
            const ns = document.createElement('script');
            if (s.src) ns.src = s.src;
            else ns.textContent = s.textContent;
            document.body.appendChild(ns);
            s.remove();
          });
          if (push) {
            const friendly = 'index.php?page=docs' + (state.q ? '&q=' + encodeURIComponent(state.q) : '') + (state.cat ? '&cat=' + encodeURIComponent(state.cat) : '');
            try { history.pushState(state, '', friendly); } catch (e) {}
          }
        }).catch(() => {});
      }

      form.addEventListener('submit', function(e) {
        e.preventDefault();
        clearTimeout(timer);
        load(serializeState());
      });

      if (qInput) qInput.addEventListener('input', function() {
        clearTimeout(timer);
        timer = setTimeout(function() { load(serializeState()); }, 300);
      }, { passive: true });

      if (catSelect) catSelect.addEventListener('change', function() {
        clearTimeout(timer);
        load(serializeState());
      });

      window.addEventListener('popstate', function(ev) {
        const state = ev.state || {};
        if (qInput) qInput.value = state.q || '';
        if (catSelect) catSelect.value = state.cat || '';
        load(state, false);
      });
    })();
  </script>

</body>
</html>
<?php endif; ?>
