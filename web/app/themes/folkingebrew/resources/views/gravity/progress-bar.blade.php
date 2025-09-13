<div class="mb-4">
  <div class="mb-2 text-sm font-medium">
    @if($is_confirmation ?? false)
      {{ __('Completed', 'folkingebrew') }}
    @else
      {{ __('Step', 'folkingebrew') . ' ' . $current_page . ' ' . __('of', 'folkingebrew') . ' ' . $total_pages }}
    @endif
  </div>
  <div class="w-full bg-gray-300 rounded-full h-2.5">
    <div class="bg-primary h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
  </div>

  @if(($is_confirmation ?? false) && !empty($confirmation_message))
    <div class="mt-4 p-4">
      <div class="text-body">
        {{ $confirmation_message }}
      </div>
    </div>
  @endif
</div>
