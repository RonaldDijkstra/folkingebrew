<header class="banner bg-black px-2 md:px-6 h-16 md:h-20 flex items-center justify-between">
  <a class="brand inline-block md:w-15 md:shrink-0" href="{{ home_url('/') }}">
    <img src="{{ Vite::asset('resources/images/logo-folkingebrew-hop.svg') }}" alt="{{ $siteName }}" class="h-12 w-auto">
  </a>
  <div class="flex-1 text-white text-center">
    <nav class="nav-primary" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
      @foreach ($primaryNavigation as $item)
        <a href="{{ $item->url }}" class="text-white no-underline hover:underline hover:text-primary px-4 text-xl">{{ $item->label }}</a>
      @endforeach
    </nav>
  </div>
  <div class="text-white text-right md:w-15 md:shrink-0">Cart</div>
</header>
