@extends('layouts.app')

@section('content')
  <h1 class="text-4xl font-normal text-white text-center">{{ $title }}</h1>
  @if(empty($products))
    <p>{{ $notFoundText }}</p>
  @else
    @foreach($products as $product)
      @dump($product)
      <h2>{{ $product->post_title }}</h2>
    @endforeach
  @endif
@endsection
