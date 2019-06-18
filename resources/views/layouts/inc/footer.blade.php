<div class="theme-footer">
	<div class="theme-footer__inner">
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
		<p><a href="{{ url('/') }}">{{ config('app.name') }}</a></p>
	</div>
</div>

<script>
window.addEventListener('load', function(){
	px2style.header.init({
		"current": @if(isset($current)) <?php var_export( $current ); ?> @else "" @endif
	});
});
</script>
