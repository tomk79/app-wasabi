@extends('layouts.app')
@section('title', 'アプリケーション統合')

@section('content')
<div class="container">

	<form action="{{ url('/settings/projects/'.urlencode($project->id).'/wasabiapps/update') }}" method="post">
		@csrf
		@method('POST')

		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th></th>
						<th>アプリケーション名</th>
					</tr>
				</thead>
				<tbody>
				@foreach ($apps as $app)
					@php
					$app = (object) $app;
					@endphp
					<tr>
						<td><input type="checkbox" name="{{ $app->id }}" value="1" @if ( array_key_exists($app->id, $relations) && $relations[$app->id] ) checked="checked" @endif /></td>
						<td>{{ $app->name }}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>

		<button type="submit" name="submit" class="btn btn-primary">変更を保存する</button>
	</form>

	<hr />
	<div class="text-right">
		<a href="{{ url('settings/projects/'.urlencode($project->id)) }}" class="btn btn-default">プロジェクト詳細 へ戻る</a>
	</div>

</div>
@endsection
