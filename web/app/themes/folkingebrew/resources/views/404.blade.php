@extends('layouts.app')

@section('content')
  @include('partials.page-header')
  @if (!have_posts())
    <div class="w-full mx-auto max-w-2xl text-center">
      {{ __('Sorry, but the page you are trying to view does not exist.', 'folkingebrew') }}
    </div>
  @endif
@endsection
