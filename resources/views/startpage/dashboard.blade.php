@extends('layouts.app')
@section('title') {{ config('app.name') }} @endsection

@section('content')
<div class="container">
	<p>ようこそ {{ config('app.name') }}</p>
</div>
@endsection
