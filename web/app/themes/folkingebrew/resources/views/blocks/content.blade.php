<x-section :backgroundColor="$backgroundColor">
  <x-container :classes="'grid grid-cols-1 gap-8 md:gap-16 ' . ($image || ($contentType === 'info' && $companyDetails['company_google_maps']) ? 'md:grid-cols-2' : 'md:place-items-center')">
    <div class="order-1 {{ $textRight ? 'md:order-2' : 'md:order-1' }} md:flex md:flex-col md:justify-center {{ !$image ? 'md:max-w-2xl' : '' }}">
      @if ($title)
        <h2 class="text-2xl font-bold mb-2">{!! $title !!}</h2>
      @endif

      @if ($contentType === 'opening_hours')
        <div class="text-body mb-6">
          @if(!empty($openingHours))
            <div class="overflow-x-auto">
              <table class="w-full border-collapse" data-opening-hours-table>
                <thead>
                  <tr class="border-b border-b-1 border-neutral-gray">
                    <th class="text-left py-3 pr-8 font-semibold text-body">Day</th>
                    <th class="text-left py-3 pr-8 font-semibold text-body">Opening Hours</th>
                    <th class="text-left py-3 font-semibold text-body">Kitchen Hours</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($openingHours as $hour)
                    @if(isset($hour['is_closed']) && !$hour['is_closed'])
                      <tr class="border-b border-neutral-gray transition-colors duration-200" data-day="{{ strtolower($hour['day'] ?? 'unknown') }}">
                        <td class="py-3 pr-8 text-body transition-colors duration-200">{{ $hour['day'] ?? 'Unknown' }}</td>
                        <td class="py-3 pr-8 text-body">
                          @if(isset($hour['time_open']) && isset($hour['time_close']) && $hour['time_open'] && $hour['time_close'])
                            {{ $hour['time_open'] }} - {{ $hour['time_close'] }}
                          @else
                            <span class="text-body">Not available</span>
                          @endif
                        </td>
                        <td class="py-3 text-body">
                          @if(isset($hour['kitchen_open']) && isset($hour['kitchen_close']) && $hour['kitchen_open'] && $hour['kitchen_close'])
                            {{ $hour['kitchen_open'] }} - {{ $hour['kitchen_close'] }}
                          @else
                            <span class="text-body">Not available</span>
                          @endif
                        </td>
                      </tr>
                    @endif
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <p class="text-gray-500">No opening hours available.</p>
          @endif
        </div>
      @endif

      @if ($contentType === 'text')
        @if ($text)
          <div class="text-body mb-6 text-lg prose prose-a:text-primary">
            {!! $text !!}
          </div>
        @endif
        @if ($link)
          <div class="self-start">
            <x-button :link="$link" type="outline-primary" />
          </div>
        @endif
      @endif

      @if ($contentType === 'info')
        <div class="flex flex-col gap-4">
          <h3 class="text-2xl font-bold mb-2">{{ __('Address', 'folkingebrew') }}</h3>
          <address class="not-italic text-lg">
            {!! $companyDetails['company_address'] !!}<br>
            {!! $companyDetails['company_zipcode'] !!}<br>
            {!! $companyDetails['company_city'] !!}
          </address>
        </div>
        <div class="flex flex-col gap-2 mb-4">
          <a class="text-primary" href="tel:{{ $companyDetails['company_phone'] }}">{{ $companyDetails['company_phone'] }}</a>
          <a class="text-primary" href="mailto:{{ $companyDetails['company_email'] }}">{{ $companyDetails['company_email'] }}</a>
          <a class="text-primary" href="https://www.google.com/maps/search/?api=1&query={{ $companyDetails['company_address'] }},{{ $companyDetails['company_zipcode'] }},{{ $companyDetails['company_city'] }}" target="_blank">Google Maps</a>
        </div>
        <div class="flex flex-col gap-4">
          <h3 class="text-2xl font-bold mb-1">{{ __('Directions', 'folkingebrew') }}</h3>
          <h4 class="text-lg font-bold mb-1">{{ __('Public Transport', 'folkingebrew') }}</h4>
          {!! $directions['public_transport'] !!}
          <h4 class="text-lg font-bold mb-1">{{ __('Car', 'folkingebrew') }}</h4>
          {!! $directions['car'] !!}
        </div>

      @endif
    </div>
    @if ($image && $contentType !== 'info')
      <div class="order-2 {{ $textRight ? 'md:order-1' : 'md:order-2' }}">
        <img src="{{ $image['url'] }}" alt="{{ $image['alt'] }}" class="w-full h-auto">
      </div>
    @endif
    @if ($contentType === 'info')
      <div class="order-2 h-[500px] {{ $textRight ? 'md:order-1' : 'md:order-2' }}">
        <iframe 
          src="{{ $companyDetails['company_google_maps'] }}"
          width="100%" 
          height="100%" 
          style="border:0;" 
          allowfullscreen="" 
          loading="lazy" 
          referrerpolicy="no-referrer-when-downgrade"
          class="w-full h-full">
        </iframe>
      </div>
    @endif
  </x-container>
</x-section>
