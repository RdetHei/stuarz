<?php if (!isset($newsItem)) { die('News not found'); } ?>
<?php if (!isset($baseUrl)) { $baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/'); if ($baseUrl === '/') $baseUrl = ''; } ?>

<div class="bg-gray-900 min-h-screen">
  <header id="stickyHeader" class="bg-[#1f2937] border-b border-gray-700 sticky top-0 z-40 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-6 py-4">
      <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-4 min-w-0 flex-1">
          <a href="index.php?page=news" 
             class="flex-shrink-0 p-2 text-gray-400 hover:text-gray-200 hover:bg-gray-800 rounded-md transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
          </a>
          <div class="h-6 w-px bg-gray-700"></div>
          <h1 id="headerTitle" class="text-lg font-semibold text-gray-100">
            <?= htmlspecialchars($newsItem['title']) ?>
          </h1>
        </div>
        
        <div class="flex items-center gap-2 flex-shrink-0">
          <button id="shareBtn" 
                  class="p-2 text-gray-400 hover:text-[#5865F2] hover:bg-[#5865F2]/10 rounded-md transition-colors"
                  title="Bagikan artikel">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
            </svg>
          </button>
          <button id="bookmarkBtn" 
                  class="p-2 text-gray-400 hover:text-[#5865F2] hover:bg-[#5865F2]/10 rounded-md transition-colors"
                  title="Bookmark artikel">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
            </svg>
          </button>
          <button id="printBtn" 
                  class="p-2 text-gray-400 hover:text-[#5865F2] hover:bg-[#5865F2]/10 rounded-md transition-colors"
                  title="Cetak artikel">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="max-w-5xl mx-auto px-6 py-8">
    <!-- Article Header -->
    <article>
      <div class="mb-8" id="articleTop">
        <div class="mb-4">
          <span class="inline-block px-3 py-1.5 bg-[#5865F2]/10 text-[#5865F2] border border-[#5865F2]/20 rounded-md text-sm font-medium">
            <?= htmlspecialchars($newsItem['category']) ?>
          </span>
        </div>

        <h1 class="text-4xl md:text-5xl font-bold text-gray-100 mb-6 leading-tight">
          <?= htmlspecialchars($newsItem['title']) ?>
        </h1>

        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-400 pb-6 border-b border-gray-700">
          <div class="flex items-center gap-2">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#5865F2] to-[#4752C4] flex items-center justify-center">
              <span class="text-sm font-semibold text-white">
                <?= strtoupper(substr($newsItem['author'], 0, 1)) ?>
              </span>
            </div>
            <div>
              <div class="text-gray-200 font-medium"><?= htmlspecialchars($newsItem['author']) ?></div>
              <div class="text-xs text-gray-500">Author</div>
            </div>
          </div>
          
          <div class="h-8 w-px bg-gray-700"></div>
          
          <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span><?= htmlspecialchars(date('d F Y', strtotime($newsItem['created_at']))) ?></span>
          </div>

          <div class="h-8 w-px bg-gray-700"></div>
          
          <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span><?= ceil(str_word_count(strip_tags($newsItem['content'])) / 200) ?> min read</span>
          </div>
        </div>
      </div>

      <?php if (!empty($newsItem['thumbnail'])): ?>
      <div class="mb-8 rounded-lg overflow-hidden border border-gray-700">
        <img
          src="<?= htmlspecialchars(($baseUrl ? $baseUrl . '/' : '') . ltrim($newsItem['thumbnail'], '/')) ?>"
          alt="<?= htmlspecialchars($newsItem['title']) ?>"
          class="w-full h-auto"
          loading="eager"
          onerror="this.onerror=null;this.src='<?= htmlspecialchars(($baseUrl ? $baseUrl . '/' : '') . 'assets/default-thumb.png') ?>'">
      </div>
      <?php endif; ?>

      <!-- Article Content -->
      <div class="prose prose-invert prose-lg max-w-none">
        <div class="bg-[#1f2937] border border-gray-700 rounded-lg p-8 md:p-12">
          <div class="text-gray-300 leading-relaxed text-base md:text-lg space-y-6" style="white-space: pre-wrap;">
            <?= nl2br(htmlspecialchars($newsItem['content'])) ?>
          </div>
        </div>
      </div>

      <!-- Article Footer -->
      <div class="mt-8 pt-6 border-t border-gray-700">
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div class="flex items-center gap-3">
            <span class="text-sm text-gray-400">Share this article:</span>
            <div class="flex items-center gap-2">
              <button class="p-2 bg-[#1f2937] hover:bg-gray-700 border border-gray-700 text-gray-400 rounded-md transition-colors" title="Share on Twitter">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                </svg>
              </button>
              <button class="p-2 bg-[#1f2937] hover:bg-gray-700 border border-gray-700 text-gray-400 rounded-md transition-colors" title="Share on Facebook">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
              </button>
              <button class="p-2 bg-[#1f2937] hover:bg-gray-700 border border-gray-700 text-gray-400 rounded-md transition-colors" title="Copy link">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
              </button>
            </div>
          </div>
          
          <a href="index.php?page=news" 
             class="text-sm text-[#5865F2] hover:text-[#4752C4] font-medium transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to News
          </a>
        </div>
      </div>
    </article>
  </main>
</div>

<script>
// Sticky header title reveal on scroll
const header = document.getElementById('stickyHeader');
const headerTitle = document.getElementById('headerTitle');
const articleTop = document.getElementById('articleTop');

let lastScrollY = window.scrollY;
let ticking = false;



function onScroll() {
  lastScrollY = window.scrollY;
  
  if (!ticking) {
    window.requestAnimationFrame(() => {
      updateHeader();
    });
    ticking = true;
  }
}

window.addEventListener('scroll', onScroll, { passive: true });

// Share functionality
document.getElementById('shareBtn')?.addEventListener('click', function() {
  if (navigator.share) {
    navigator.share({
      title: '<?= htmlspecialchars($newsItem['title'], ENT_QUOTES) ?>',
      text: '<?= htmlspecialchars(substr(strip_tags($newsItem['content']), 0, 100), ENT_QUOTES) ?>',
      url: window.location.href
    }).catch(err => console.log('Error sharing', err));
  } else {
    // Fallback: copy to clipboard
    navigator.clipboard.writeText(window.location.href).then(() => {
      const originalHTML = this.innerHTML;
      this.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
      this.classList.add('text-emerald-400');
      setTimeout(() => {
        this.innerHTML = originalHTML;
        this.classList.remove('text-emerald-400');
      }, 2000);
    });
  }
});

// Bookmark functionality
let isBookmarked = localStorage.getItem('bookmark_<?= $newsItem['id'] ?>') === 'true';
const bookmarkBtn = document.getElementById('bookmarkBtn');
if (bookmarkBtn) {
  if (isBookmarked) {
    bookmarkBtn.classList.add('text-[#5865F2]');
    bookmarkBtn.querySelector('svg').setAttribute('fill', 'currentColor');
  }
  
  bookmarkBtn.addEventListener('click', function() {
    isBookmarked = !isBookmarked;
    if (isBookmarked) {
      localStorage.setItem('bookmark_<?= $newsItem['id'] ?>', 'true');
      this.classList.add('text-[#5865F2]');
      this.querySelector('svg').setAttribute('fill', 'currentColor');
    } else {
      localStorage.removeItem('bookmark_<?= $newsItem['id'] ?>');
      this.classList.remove('text-[#5865F2]');
      this.querySelector('svg').removeAttribute('fill');
    }
  });
}

// Print functionality
document.getElementById('printBtn')?.addEventListener('click', function() {
  window.print();
});

// Share functionality for footer buttons
document.querySelectorAll('button[title^="Share"]').forEach(button => {
  button.addEventListener('click', function() {
    const title = this.getAttribute('title');
    if (title.includes('Copy link')) {
      navigator.clipboard.writeText(window.location.href).then(() => {
        const originalHTML = this.innerHTML;
        this.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
        this.classList.add('text-emerald-400');
        setTimeout(() => {
          this.innerHTML = originalHTML;
          this.classList.remove('text-emerald-400');
        }, 2000);
      });
    }
  });
});
</script>