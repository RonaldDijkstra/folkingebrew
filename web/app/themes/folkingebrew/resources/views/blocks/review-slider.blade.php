<x-section>
  <x-container>
    <h2 class="text-2xl md:text-3xl font-bold mb-3 text-center">{{ $title }}</h2>
    <p class="text-body mb-8 text-center text-lg">{{ $subtitle }}</p>
    <div class="review-slider swiper overflow-hidden">
      <div class="swiper-wrapper">
        @foreach ($reviews as $review)
          <div class="swiper-slide flex flex-col bg-neutral-light-brown p-6 rounded-lg text-center">
            @if(isset($review['rating']))
              <div class="flex justify-center gap-1 mb-4">
                @for ($i = 1; $i <= 5; $i++)
                  @if ($i <= ($review['rating'] ?? 5))
                    <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                  @else
                    <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                  @endif
                @endfor
              </div>
            @endif
            @if(isset($review['review']))
              <p class="text-body text-lg italic mb-4">
                "{{ $review['review'] }}"
              </p>
            @endif
            @if(isset($review['name']))
              <cite class="text-lg font-medium text-body not-italic">
                <div class="font-bold mb-0">{{ $review['name'] }}</div>
                @if(isset($review['source']))
                  <div class="text-body text-sm">{{ $review['source'] }}</div>
                @endif
              </cite>
            @endif
          </div>
        @endforeach
      </div>
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-pagination"></div>
    </div>
  </x-container>
</x-section>
