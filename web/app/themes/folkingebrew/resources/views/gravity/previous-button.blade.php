@unless($is_first_page)
  <button
    type="button"
    id="gform_previous_button_{{ $form['id'] }}_{{ $current_page }}"
    class="text-primary border-2 border-primary hover:bg-primary-dark hover:text-white px-6 py-3 cursor-pointer font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
    tabindex="{{ $form['tabindex'] ?? 0 }}"
    onclick="jQuery(&quot;#gform_target_page_number_{{ $form['id'] }}&quot;).val(&quot;{{ $current_page - 1 }}&quot;);jQuery(&quot;#gform_{{ $form['id'] }}&quot;).trigger(&quot;submit&quot;,[true]); return false;">
    <span class="flex items-center gap-2">
      <x-icon name="chevron-left" classes="w-4 h-4 text-inherit" />
      {{ $previous_button_text ?? __('Previous', 'folkingebrew') }}
    </span>
  </button>
@endunless
