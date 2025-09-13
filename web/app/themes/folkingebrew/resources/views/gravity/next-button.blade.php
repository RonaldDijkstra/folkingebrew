<button
  type="button"
  id="gform_next_button_{{ $form['id'] }}_{{ $current_page }}"
  class="ml-auto bg-primary text-white px-6 py-3 font-medium hover:bg-primary-dark cursor-pointer transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
  tabindex="{{ $form['tabindex'] ?? 0 }}"
  onclick="if(window[&quot;gf_submitting_{{ $form['id'] }}&quot;]){return false;} if(!jQuery(&quot;#gform_{{ $form['id'] }}&quot;)[0].checkValidity || jQuery(&quot;#gform_{{ $form['id'] }}&quot;)[0].checkValidity()){ jQuery(&quot;#gform_target_page_number_{{ $form['id'] }}&quot;).val(&quot;{{ $current_page + 1 }}&quot;); } jQuery(&quot;#gform_{{ $form['id'] }}&quot;).trigger(&quot;submit&quot;,[true]); ">
  <span class="flex items-center gap-2">
    {{ $next_button_text ?? __('Next', 'folkingebrew') }}
    <x-icon name="chevron-right" classes="w-4 h-4 text-inherit" />
  </span>
</button>
