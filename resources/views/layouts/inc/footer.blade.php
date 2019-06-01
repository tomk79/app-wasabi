<div class="container-fluid">
<hr />
<p>{{ config('app.name') }}</p>
</div>

<script>
window.addEventListener('load', function(){
	px2style.header.init({
		"current": @if(isset($current)) <?php var_export( $current ); ?> @else "" @endif
	});
});
</script>
