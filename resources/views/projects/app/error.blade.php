@extends('layouts.app')
@section('title', (strlen($app_name) ? $app_name : 'Wasabi App: '.$app_id))

@section('content')
<div class="container">
<p>{{ $error_message }}</p>
</div>
@endsection
