<div class="py-16">
  <h2 class="text-3xl font-normal mb-4 text-center mb-10">{{ __('You might also like', 'folkingebrew') }}</h2>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($relatedProducts as $product)
      <x-card-product :product="$product" />
    @endforeach
  </div>
</div>
