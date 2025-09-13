<div class="sm:flex sm:items-center sm:space-x-4 sm:w-full sm:justify-center sm:mb-4">
  @foreach($steps as $step)
    <div class="flex items-center mb-2 sm:mb-0">
      <div
        class="flex h-10 w-10 items-center justify-center rounded-full
        {{ $step['is_completed'] ? 'bg-primary-dark text-white' : ($step['is_current'] ? 'bg-primary text-white' : 'bg-gray-300 text-gray-600') }}"
      >
        @if($step['is_completed'] && ($is_confirmation ?? false))
          <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
          </svg>
        @else
          {{ $step['number'] }}
        @endif
      </div>

      @if($step['title'])
        <span class="ml-2 text-sm font-medium {{ $step['is_completed'] ? 'text-primary-dark' : ($step['is_current'] ? 'text-primary' : 'text-gray-600') }}">{{ $step['title'] }}</span>
      @endif
    </div>
  @endforeach
</div>
