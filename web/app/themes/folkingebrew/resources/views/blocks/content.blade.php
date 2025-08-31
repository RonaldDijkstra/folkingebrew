<x-section :backgroundColor="$backgroundColor">
  <x-container :classes="'grid grid-cols-1 gap-8 md:gap-16 ' . ($image ? 'md:grid-cols-2' : 'md:place-items-center')">
    <div class="order-1 {{ $textRight ? 'md:order-2' : 'md:order-1' }} md:flex md:flex-col md:justify-center {{ !$image ? 'md:max-w-2xl' : '' }}">
      @if ($title)
        <h2 class="text-2xl font-bold mb-2">{!! $title !!}</h2>
      @endif
      @if ($contentType === 'opening_hours')
        <div class="text-body mb-6">
          @if(!empty($openingHours))
            <div class="overflow-x-auto">
              <table class="w-full border-collapse">
                <thead>
                  <tr class="border-b-2 border-gray-200">
                    <th class="text-left py-3 pr-8 font-semibold text-gray-900">Day</th>
                    <th class="text-left py-3 pr-8 font-semibold text-gray-900">Opening Hours</th>
                    <th class="text-left py-3 font-semibold text-gray-900">Kitchen Hours</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($openingHours as $hour)
                    @if(isset($hour['is_closed']) && !$hour['is_closed'])
                      <tr class="border-b border-gray-100">
                        <td class="py-3 pr-8 font-medium text-gray-900">{{ $hour['day'] ?? 'Unknown' }}</td>
                        <td class="py-3 pr-8 text-gray-700">
                          @if(isset($hour['time_open']) && isset($hour['time_close']) && $hour['time_open'] && $hour['time_close'])
                            {{ $hour['time_open'] }} - {{ $hour['time_close'] }}
                          @else
                            <span class="text-gray-400">Not available</span>
                          @endif
                        </td>
                        <td class="py-3 text-gray-700">
                          @if(isset($hour['kitchen_open']) && isset($hour['kitchen_close']) && $hour['kitchen_open'] && $hour['kitchen_close'])
                            {{ $hour['kitchen_open'] }} - {{ $hour['kitchen_close'] }}
                          @else
                            <span class="text-gray-400">Not available</span>
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
      @elseif ($contentType === 'text')
        @if ($text)
          <div class="text-body mb-6 text-lg">
            {!! $text !!}
          </div>
        @endif
        @if ($link)
          <div class="self-start">
            <x-button :link="$link" type="outline-primary" />
          </div>
        @endif
      @endif
    </div>
    @if ($image)
      <div class="order-2 {{ $textRight ? 'md:order-1' : 'md:order-2' }}">
        <img src="{{ $image['url'] }}" alt="{{ $image['alt'] }}" class="w-full h-auto">
      </div>
    @endif
  </x-container>
</x-section>
