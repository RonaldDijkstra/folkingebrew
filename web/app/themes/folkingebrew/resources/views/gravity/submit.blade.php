<div class="{{ $containerClass }}">
  @if($buttonType === 'image' && !empty($imageUrl))
    <input
      type="image"
      src="{{ $imageUrl }}"
      id="gform_submit_button_{{ $form['id'] }}"
      class="gform_image_button {{ $widthClass }}"
      @if(isset($isPreview) && $isPreview)
        disabled
      @else
        onclick="gform.submission.handleButtonClick(this);"
        data-submission-type="submit"
      @endif
      alt="{{ $buttonText }}">
  @else
    <input
      type="submit"
      id="gform_submit_button_{{ $form['id'] }}"
      class="gform_button bg-primary text-white py-2 px-6 cursor-pointer hover:bg-primary-dark {{ $widthClass }}"
      @if(isset($isPreview) && $isPreview)
        disabled
      @else
        onclick="gform.submission.handleButtonClick(this);"
        data-submission-type="submit"
      @endif
      value="{{ $buttonText }}">
  @endif
</div>
