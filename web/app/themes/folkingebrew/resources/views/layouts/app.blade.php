<!doctype html>
<html @php(language_attributes())>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <link rel="shortcut icon" href="{{ Vite::asset('resources/images/favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ Vite::asset('resources/images/apple-touch-icon.png') }}">
    <link rel="icon" href="{{ Vite::asset('resources/images/favicon-32x32.png') }}">
    <link rel="icon" href="{{ Vite::asset('resources/images/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ Vite::asset('resources/images/site.webmanifest') }}">
    @php(do_action('get_header'))
    @php(wp_head())

    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body @php(body_class())>
    @php(wp_body_open())

    <div id="app" class="flex flex-col font-sans min-h-screen has-adminbar:min-h-screen-minus-admin-bar">
      <a class="sr-only focus:not-sr-only" href="#main">
        {{ __('Skip to content', 'folkingebrew') }}
      </a>

      @include('sections.header')

      <main id="main" class="flex-grow -mt-16 md:-mt-20 pt-32 md:pt-40">
        @yield('content')
      </main>

      @include('sections.footer')
    </div>

    @php(do_action('get_footer'))
    @php(wp_footer())

    @if(isset($showZenChefWidget) && $showZenChefWidget)
      <script>
        ;(function (d, s, id) {
          const el = d.getElementsByTagName(s)[0]
          if (d.getElementById(id) || el.parentNode == null) return
          var js = d.createElement(s)
          js.id = id
          js.src = 'https://sdk.zenchef.com/v1/sdk.min.js'
          el.parentNode.insertBefore(js, el)
        })(document, 'script', 'zenchef-sdk')
      </script>
      <div class="zc-widget-config"
        data-restaurant="375897"
        data-open=""></div>
    @endif
  </body>
</html>
