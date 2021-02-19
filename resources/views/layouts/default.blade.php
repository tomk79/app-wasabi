<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name') }} | @yield('title')</title>

	@include("layouts.inc.head")

	@yield('head')
</head>
<body id="app" class="@if ($content_fit_to_window) theme-content-fit-to-window @endif @if ($hide_h1_container) theme-hide-h1-container @endif">

	@include("layouts.inc.header")

	<div class="theme-h1-container">
		<div class="theme-h1-container__heading">
			@hasSection('title')
			<h1>@yield('title')</h1>
			@endif
		</div>
	</div>
	<div class="theme-main-container">

		<div class="theme-main-container__header-info">
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

			@if (session('flash_message'))
			<div class="alert alert-success" role="alert">
				{{ session('flash_message') }}
			</div>
			@endif
		</div>

		<div class="contents">
			@yield('content')
		</div>
	</div>

	@include("layouts.inc.footer")

	@yield('foot')
</body>
</html>
