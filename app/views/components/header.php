<?php
require_once dirname(__DIR__) . '../../../app/config/config.php';
?>

<style>
.nav-link {
  position: relative;
  transition: color 0.2s ease;
}

.nav-link::after {
  content: '';
  position: absolute;
  bottom: -6px;
  left: 0;
  right: 0;
  height: 2px;
  background: #6366f1;
  transform: scaleX(0);
  transition: transform 0.2s ease;
}

.nav-link:hover::after {
  transform: scaleX(1);
}

.nav-link:hover {
  color: #e5e7eb;
}
</style>

<header class="bg-gray-900 sticky top-0 z-50 border-b border-gray-800">
  <nav aria-label="Global" class="mx-auto flex max-w-7xl items-center justify-between p-4 lg:px-8">
    <!-- Logo -->
    <div class="flex lg:flex-1">
      <a href="index.php?page=home" class="-m-1.5 p-1.5 hover:opacity-80 transition-opacity duration-200">
        <span class="sr-only">Your Company</span>
        <svg fill="#ffffff" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 293.538 293.538"
             xml:space="preserve" stroke="#ffffff" class="h-8 w-8">
          <g>
            <polygon points="210.084,88.631 146.622,284.844 81.491,88.631"></polygon>
            <polygon points="103.7,64.035 146.658,21.08 188.515,64.035"></polygon>
            <polygon points="55.581,88.631 107.681,245.608 0,88.631"></polygon>
            <polygon points="235.929,88.631 293.538,88.631 184.521,247.548"></polygon>
            <polygon points="283.648,64.035 222.851,64.035 168.938,8.695 219.079,8.695"></polygon>
            <polygon points="67.563,8.695 124.263,8.695 68.923,64.035 7.969,64.035"></polygon>
          </g>
        </svg>
      </a>
    </div>

    <!-- Mobile menu button -->
    <div class="flex lg:hidden">
      <button type="button" 
              command="show-modal" 
              commandfor="mobile-menu" 
              class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-400 hover:text-gray-300 hover:bg-gray-800 transition-colors duration-200">
        <span class="sr-only">Open main menu</span>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="size-6">
          <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </button>
    </div>

    <!-- Desktop Navigation -->
    <el-popover-group class="hidden lg:flex lg:gap-x-8">
      <a href="index.php?page=home" class="nav-link text-sm font-semibold text-gray-300 py-2">
        Home
      </a>
      <a href="index.php?page=news" class="nav-link text-sm font-semibold text-gray-300 py-2">
        News
      </a>
      <a href="index.php?page=docs" class="nav-link text-sm font-semibold text-gray-300 py-2">
        Documentation
      </a>
      <a href="index.php?page=company" class="nav-link text-sm font-semibold text-gray-300 py-2">
        Company
      </a>
    </el-popover-group>

    <!-- Login Button -->
    <div class="hidden lg:flex lg:flex-1 lg:justify-end">
      <a href="index.php?page=login" 
         class="text-sm font-semibold text-gray-300 hover:text-white transition-colors duration-200 flex items-center gap-1">
        Log in 
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
        </svg>
      </a>
    </div>
  </nav>

  <!-- Mobile Menu Dialog -->
  <el-dialog>
    <dialog id="mobile-menu" class="backdrop:bg-black/50 lg:hidden">
      <div tabindex="0" class="fixed inset-0 focus:outline-none">
        <el-dialog-panel class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-gray-900 p-6 sm:max-w-sm border-l border-gray-800">
          <!-- Mobile Header -->
          <div class="flex items-center justify-between">
            <a href="index.php?page=home" class="-m-1.5 p-1.5">
              <span class="sr-only">Your Company</span>
              <svg fill="#ffffff" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                   xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 293.538 293.538"
                   xml:space="preserve" stroke="#ffffff" class="h-8 w-8">
                <g>
                  <polygon points="210.084,88.631 146.622,284.844 81.491,88.631"></polygon>
                  <polygon points="103.7,64.035 146.658,21.08 188.515,64.035"></polygon>
                  <polygon points="55.581,88.631 107.681,245.608 0,88.631"></polygon>
                  <polygon points="235.929,88.631 293.538,88.631 184.521,247.548"></polygon>
                  <polygon points="283.648,64.035 222.851,64.035 168.938,8.695 219.079,8.695"></polygon>
                  <polygon points="67.563,8.695 124.263,8.695 68.923,64.035 7.969,64.035"></polygon>
                </g>
              </svg>
            </a>
            <button type="button" 
                    command="close" 
                    commandfor="mobile-menu" 
                    class="-m-2.5 rounded-md p-2.5 text-gray-400 hover:text-gray-300 hover:bg-gray-800 transition-colors duration-200">
              <span class="sr-only">Close menu</span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="size-6">
                <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </button>
          </div>

          <!-- Mobile Navigation -->
          <div class="mt-6 flow-root">
            <div class="-my-6 divide-y divide-gray-800">
              <div class="space-y-2 py-6">
                <a href="index.php?page=home" 
                   class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold text-gray-300 hover:text-white hover:bg-gray-800 transition-colors duration-200">
                  Home
                </a>
                <a href="index.php?page=news" 
                   class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold text-gray-300 hover:text-white hover:bg-gray-800 transition-colors duration-200">
                  News
                </a>
                <a href="index.php?page=docs" 
                   class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold text-gray-300 hover:text-white hover:bg-gray-800 transition-colors duration-200">
                  Documentation
                </a>
                <a href="index.php?page=company" 
                   class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold text-gray-300 hover:text-white hover:bg-gray-800 transition-colors duration-200">
                  Company
                </a>
              </div>
              <div class="py-6">
                <a href="index.php?page=login" 
                   class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold text-gray-300 hover:text-white hover:bg-gray-800 transition-colors duration-200">
                  Log in
                </a>
              </div>
            </div>
          </div>
        </el-dialog-panel>
      </div>
    </dialog>
  </el-dialog>
</header>

<script src="/js/header-scroll.js"></script>