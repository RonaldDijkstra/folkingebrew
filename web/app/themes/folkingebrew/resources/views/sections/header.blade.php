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
              max-md:menu-open:block max-md:menu-open:flex max-md:menu-open:absolute max-md:menu-open:inset-x-0 max-md:menu-open:top-16 max-md:menu-open:w-full max-md:menu-open:max-h-[calc(100vh-4rem)] max-md:menu-open:overflow-y-auto">
    <nav class="nav-primary w-full max-md:menu-open:h-full max-md:menu-open:py-10 max-md:menu-open:flex max-md:menu-open:flex-col max-md:menu-open:items-center max-md:menu-open:justify-center" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
      @foreach ($primaryNavigation as $item)
        @if (!empty($item->children))
          <div class="nav-item-with-submenu inline-block relative group max-md:w-full max-md:mb-4">
            <a href="{{ $item->url }}" class="no-underline hover:text-primary px-2 text-md max-md:text-lg max-md:font-bold max-md:mb-2 inline-flex items-center justify-center max-md:justify-start {{ $item->active || $item->activeParent || $item->activeAncestor ? 'text-primary' : 'text-white' }}">
              {{ $item->label }}
              <svg class="submenu-chevron w-4 h-4 md:ml-1 transition-transform duration-200 max-md:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </a>
            <div class="submenu md:hidden md:group-hover:block touch-open:block md:absolute md:left-1/2 md:-translate-x-1/2 md:top-full md:bg-transparent md:min-w-[200px] max-md:w-full md:py-4">
              <div class="md:bg-gray-900 md:py-3 md:relative md:before:content-[''] md:before:absolute md:before:top-0 md:before:left-1/2 md:before:-translate-x-1/2 md:before:-translate-y-full md:before:border-8 md:before:border-transparent md:before:border-b-gray-900">
                @foreach ($item->children as $child)
                  <a href="{{ $child->url }}" class="block no-underline py-2 hover:text-primary px-4 text-md max-md:text-sm {{ $child->active ? 'text-primary' : 'text-white' }}">{{ $child->label }}</a>
                @endforeach
              </div>
            </div>
          </div>
        @else
          <a href="{{ $item->url }}" class="no-underline hover:text-primary px-2 text-md max-md:text-lg max-md:font-bold max-md:mb-5 {{ $item->active ? 'text-primary' : 'text-white' }}">{{ $item->label }}</a>
        @endif
      @endforeach
    </nav>
  </div>
  <div class="text-white text-right w-15 shrink-0 pr-2 md:pr-0">
    @include('webshop.header-cart')
  </div>
</header>
