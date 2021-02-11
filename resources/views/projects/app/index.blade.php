@extends('layouts.default')
@section('title', (strlen($app_name) ? $app_name : 'Wasabi App: '.$app_id))

@section('content')
<div class="container">
{!! $main !!}
</div>
@endsection
