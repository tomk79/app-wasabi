<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name') }}</title>

	<!-- Scripts -->
	<script src="{{ asset('js/app.js') }}" defer></script>

	<!-- Styles -->
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">

	<!-- px2style -->
	<script src="{{ asset('common/px2style/dist/scripts.js') }}" defer></script>
	<link href="{{ asset('common/px2style/dist/styles.css') }}" rel="stylesheet">

	@yield('head')
</head>
<body class="theme-layout-top">

	@include("layouts.inc.header")


	{{-- main column --}}
	<main class="theme-main">
		@if (session('flash_message'))
			<div class="alert alert-success" role="alert">
				{{ session('flash_message') }}
			</div>
		@endif

		@yield('content')
	</main>

	@include("layouts.inc.footer")

	@yield('foot')
</body>
</html>
