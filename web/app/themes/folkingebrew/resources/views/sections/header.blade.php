<header class="bg-black px-2 md:px-6 h-16 md:h-20 flex items-center justify-between" id="site-header">
  <div class="md:hidden text-white text-left w-15 shrink-0 bg-blue-500">
    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white cursor-pointer" aria-controls="mobile-menu" aria-expanded="false" id="menu-toggle">
      <span class="sr-only">{{ __('Open main menu', 'folkingebrew') }}</span>
      <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#FFFFFF" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
      <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#FFFFFF" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>
  <div class="flex items-center w-full justify-center md:w-auto">
    <a class="inline-block md:w-15 md:shrink-0" href="{{ home_url('/') }}">
      <img src="{{ Vite::asset('resources/images/logo-folkingebrew-hop.svg') }}" alt="{{ $siteName }}" class="h-12 w-auto">
    </a>
  </div>
  <div class="hidden md:block flex-1 text-white text-center">
    <nav class="nav-primary" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
      @foreach ($primaryNavigation as $item)
        <a href="{{ $item->url }}" class="text-white no-underline hover:underline hover:text-primary px-2 text-md">{{ $item->label }}</a>
      @endforeach
    </nav>
  </div>
  <div class="text-white text-right bg-blue-500 w-15 shrink-0">Cart</div>
</header>
