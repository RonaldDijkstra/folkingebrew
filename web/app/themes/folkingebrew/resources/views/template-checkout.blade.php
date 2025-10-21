{{--
  Template Name: Checkout
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    <div class="w-full text-center py-8">
      <h1 class="text-4xl font-normal text-body">{{ get_the_title() }}</h1>
    </div>
    @include('partials.content-page')
  @endwhile
@endsection
