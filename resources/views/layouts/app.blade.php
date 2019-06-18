<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name') }} | @yield('title')</title>

	<!-- Scripts -->
	<script src="{{ asset('js/app.js') }}" defer></script>

	<!-- Styles -->
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">

	<!-- px2style -->
	<script src="{{ asset('common/px2style/dist/scripts.js') }}"></script>
	<link href="{{ asset('common/px2style/dist/styles.css') }}" rel="stylesheet">

	@yield('head')
</head>
<body>

	@include("layouts.inc.header")

	{{-- main column --}}
	<div class="theme-main-column">
		<div id="app">
			@if (count($global->breadcrumb) > 1)
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					@foreach($global->breadcrumb as $idx => $page_info)
						@if (!strlen($page_info->href) || count($global->breadcrumb)-1 <= $idx )
							<li class="breadcrumb-item">{{ $page_info->label }}</li>
						@else
							<li class="breadcrumb-item"><a href="{{ $page_info->href }}">{{ $page_info->label }}</a></li>
						@endif
					@endforeach
				</ol>
			</nav>
			@endif
			<main class="theme-main">
				<h1>@yield('title')</h1>

				@if (session('flash_message'))
					<div class="alert alert-success" role="alert">
						{{ session('flash_message') }}
					</div>
				@endif

				<div class="contents">
					@yield('content')
				</div>
			</main>

		</div>
	</div>

	@include("layouts.inc.footer")

	@yield('foot')
</body>
</html>
