<header class="banner">
  <a class="brand" href="{{ home_url('/') }}">
    <img src="{{ Vite::asset('resources/images/icon-folkingebrew.svg') }}" alt="{{ $siteName }}" class="h-8 w-auto">
  </a>

  @if (has_nav_menu('primary_navigation'))
    <nav class="nav-primary" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
      {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav', 'echo' => false]) !!}
    </nav>
  @endif
</header>
