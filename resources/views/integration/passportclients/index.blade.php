@extends('layouts.default')
@section('title', 'OAuthアプリケーション一覧')

@section('content')
<div class="container">

	<p>
		{{ env('APP_NAME') }} アカウントでOAuthログインできる外部のアプリケーションを管理します。<br />
	</p>
	<p>
		あなたが開発する外部のアプリケーションに、 {{ env('APP_NAME') }} アカウントでログインできるようにしたい場合は、ここでアプリケーションを登録してください。<br />
	</p>
	<p>
		ここで登録されたアプリケーションは、 OAuthログインしたユーザーの権限で {{ env('APP_NAME') }} API にアクセスできるようになります。<br />
	</p>

	<div class="text-right mb-3">
		<a href="{{ url('settings/profile/integration/oauth_apps/create') }}" class="btn btn-primary">新しいアプリケーションを登録</a>
	</div>

	@empty($clients)

	<p>アプリケーションは登録されていません。</p>

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
					<td><a href="{{ url('settings/profile/integration/oauth_apps/'.urlencode($client->id)) }}">{{ $client->name }}</a></td>
					<td>{{ $client->redirect }}</td>
					<td>
						@if (Auth::user()->email != $client->email)
						<a href="{{ url('settings/profile/integration/oauth_apps/'.urlencode($client->id).'/edit') }}" class="btn btn-primary">編集</a>
						@else
						---
						@endif
					</td>
					<td>
						@if (Auth::user()->email != $client->email)
						<form action="{{ url('settings/profile/integration/oauth_apps/'.urlencode($client->id)) }}" method="post">
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
