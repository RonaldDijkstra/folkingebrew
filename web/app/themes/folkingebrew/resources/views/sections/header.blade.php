<header class="banner bg-black px-2 md:px-6 h-16 md:h-20 flex items-center justify-between">
  <a class="brand inline-block md:w-15 md:shrink-0" href="{{ home_url('/') }}">
    <img src="{{ Vite::asset('resources/images/logo-folkingebrew-hop.svg') }}" alt="{{ $siteName }}" class="h-12 w-auto">
  </a>
  <div class="flex-1 text-white text-center">Navigation</div>
  <div class="text-white text-right md:w-15 md:shrink-0">Cart</div>
  {{-- @if (has_nav_menu('primary_navigation'))
    <nav class="nav-primary" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
      {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav', 'echo' => false]) !!}
    </nav>
  @endif --}}
</header>
