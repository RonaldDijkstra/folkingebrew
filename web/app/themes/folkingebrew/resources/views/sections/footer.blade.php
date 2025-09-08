<footer class="bg-black text-gray-300">
  <x-container :classes="'py-16 grid md:grid-cols-3'">
    <div class="mb-6 md:mb-0 md:pr-4">
      <img src="{{ Vite::asset('resources/images/logo-folkingebrew-white.svg') }}" alt="{{ $siteName }}" class="max-w-[240px] mb-6">
      @if ($footerText)
        <p class="text-lg mb-6">
          {!! $footerText !!}
        </p>
      @endif
      <div class="flex items-center gap-4">
        <a href="https://www.untappd.com/Folkingebrew/" target="_blank">
          <img src="{{ Vite::asset('resources/images/icon-untappd.svg') }}" alt="Untappd" class="w-6">
        </a>
        <a href="https://www.instagram.com/folkingebrew" target="_blank">
          <img src="{{ Vite::asset('resources/images/icon-instagram.svg') }}" alt="Instagram" class="w-6">
        </a>
        <a href="https://www.facebook.com/Folkingebrew-167577637289407/" target="_blank">
          <img src="{{ Vite::asset('resources/images/icon-facebook.svg') }}" alt="Facebook" class="w-6">
        </a>
      </div>
    </div>
    @if ($footerNavigation)
      <nav class="md:px-4 md:pt-6">
        <ul class="md:space-x-4 grid xl:grid-cols-2">
          @foreach ($footerNavigation as $column)
            <li>
              <h3 class="text-xl font-bold uppercase mb-2">{{ $column->label }}</h3>
              @if (!empty($column->children))
                <ul class="mb-6 xl:mb-0">
                  @foreach ($column->children as $item)
                    <li>
                      <a
                        href="{{ $item->url }}"
                        @if($item->target)
                          target="{{ $item->target }}" rel="noopener noreferrer"
                        @endif
                        class="text-lg text-white hover:text-primary rounded-sm no-underline pb-2 block"
                      >
                        {{ $item->label }}
                      </a>
                    </li>
                  @endforeach
                </ul>
              @endif
            </li>
          @endforeach
        </ul>
      </nav>
    @endif
    <div class="md:pl-4 md:pt-6">
      <h3 class="text-xl font-bold uppercase mb-2">Contact</h3>
      <div class="mb-4">
        <h4 class="text-lg font-bold">Folkingebrew VOF</h4>
        <address class="not-italic">Olgerweg 2-7, 9723 ED Groningen, The Netherlands</address>
        <a href="mailto:info@folkingebrew.nl" class="text-white hover:text-primary focus:outline-none focus:ring-2 focus:ring-slate-500">info@folkingebrew.nl</a>
      </div>
      <div>
      <h4 class="text-lg font-bold">Folkingebrew The Pub</h4>
        <address class="not-italic">Boterdiep 75, 9712 LL Groningen, The Netherlands</address>
        <a href="mailto:brewpub@folkingebrew.nl" class="text-white hover:text-primary">brewpub@folkingebrew.nl</a><br>
        <a href="tel:+31628801883" class="text-white hover:text-primary">+31 (0)6 28 80 18 83</a>
      </div>
    </div>
  </x-container>
  <div class="bg-white text-sm">
    <x-container :classes="'py-2 flex flex-col md:flex-row md:justify-between'">
      <div class="text-body text-center md:text-left mb-2 md:mb-0 order-3 md:order-1">
          &copy; {{ date('Y') }} Folkingebrew
      </div>
      <div class="text-body mb-2 md:mb-0 order-1 md:order-2">
        <ul class="md:flex md:justify-center text-center md:text-left">
          @foreach ($bottomNavigation as $item)
            <li><a href="{{ $item->url }}" class="text-black px-2 hover:text-primary focus:outline-none focus:ring-2 focus:ring-slate-500">{{ $item->label }}</a></li>
          @endforeach
        </ul>
      </div>
      <div class="text-body text-center md:text-right mb-2 md:mb-0 order-2 md:order-3">
        <a href="https://www.nix18.nl/" class="inline-block" target="_blank">
          <img src="{{ Vite::asset('resources/images/nix18.svg') }}" alt="NIX18" class="w-16">
        </a>
      </div>
    </x-container>
  </div>
</footer>
