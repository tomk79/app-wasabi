@extends('layouts.app')
@section('title', 'API Key 一覧')

@section('content')
<div class="container">

	<div class="text-right mb-3">
		<a href="{{ url('settings/profile/apikeys/create') }}" class="btn btn-primary">新しい API Key を登録</a>
	</div>

	@empty($clients)

	<p>API Key は登録されていません。</p>

	@else

	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>名前</th>
					<th>リダイレクト先</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			@foreach ($clients as $client)
				<tr>
					<td><a href="{{ url('settings/profile/apikeys/'.urlencode($client->id)) }}">{{ $client->name }}</a></td>
					<td>{{ $client->redirect }}</td>
					<td>
						@if (Auth::user()->email != $client->email)
						<a href="{{ url('settings/profile/apikeys/'.urlencode($client->id).'/edit') }}" class="btn btn-primary">編集</a>
						@else
						---
						@endif
					</td>
					<td>
						@if (Auth::user()->email != $client->email)
						<form action="{{ url('settings/profile/apikeys/'.urlencode($client->id)) }}" method="post">
							@csrf
							@method('DELETE')
							<button type="submit" name="submit" class="btn btn-danger">削除する</button>
						</form>
						@else
						---
						@endif
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
	{{ $clients->links() }}

	@endempty

	<hr />
	<div class="text-right">
		<a href="{{ url('settings/profile') }}" class="btn btn-default">プロフィール へ戻る</a>
	</div>

</div>
@endsection
