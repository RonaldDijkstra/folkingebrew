<header class="bg-black md:px-6 h-16 md:h-20 flex items-center justify-between w-full left-0 top-0 z-30 fixed has-adminbar:top-[46px] wp:has-adminbar:top-[32px] md:transparent-header:bg-transparent" id="site-header">
  <div class="text-white text-left w-15 shrink-0 md:hidden">
    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white cursor-pointer" aria-controls="mobile-menu" aria-expanded="false" id="menu-toggle">
      <span class="sr-only">{{ __('Open main menu', 'folkingebrew') }}</span>
      <svg class="block h-6 w-6 menu-open:hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#FFFFFF" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
      <svg class="hidden h-6 w-6 menu-open:block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#FFFFFF" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>
  <div class="flex items-center w-full justify-center md:w-auto">
    <a class="inline-block md:w-15 md:shrink-0" href="{{ home_url('/') }}">
      <img src="{{ Vite::asset('resources/images/logo-folkingebrew-hop.svg') }}" alt="{{ $siteName }}" class="h-12 w-auto">
    </a>
  </div>
  <div class="hidden bg-black md:bg-transparent flex-1 text-white text-center md:block
              max-md:menu-open:block max-md:menu-open:flex max-md:menu-open:min-h-screen max-md:menu-open:absolute max-md:menu-open:top-16 max-md:menu-open:w-full">
    <nav class="nav-primary w-full max-md:menu-open:flex max-md:menu-open:flex-col max-md:menu-open:items-center max-md:menu-open:justify-center" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
      @foreach ($primaryNavigation as $item)
        <a href="{{ $item->url }}" class="no-underline hover:text-primary px-2 text-md max-md:text-lg max-md:font-bold max-md:mb-5 {{ $item->active ? 'text-primary' : 'text-white' }}">{{ $item->label }}</a>
      @endforeach
    </nav>
  </div>
  <div class="text-white text-right w-15 shrink-0 pr-2 md:pr-0">Cart</div>
</header>
