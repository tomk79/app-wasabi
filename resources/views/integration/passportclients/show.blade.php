@extends('layouts.app')
@section('title', '外部アプリケーション '.$client->name)

@section('content')
<div class="container">

	<table class="table table__dd">
		<tbody>
			<tr>
				<th><label for="name">名前</label></th>
				<td>
					<p>{{ $client->name }}</p>
				</td>
			</tr>
			<tr>
				<th><label for="redirect">リダイレクト先</label></th>
				<td>
					<p>{{ $client->redirect }}</p>
				</td>
			</tr>
			<tr>
				<th><label for="name">Client ID</label></th>
				<td>
					<p>{{ $client->id }}</p>
				</td>
			</tr>
			<tr>
				<th><label for="secret">Client Secret</label></th>
				<td>
					<p>{{ $client->secret }}</p>
				</td>
			</tr>
		</tbody>
	</table>

	<hr />
	<div class="text-right">
		<a href="{{ url('settings/profile/integration/oauth_apps') }}" class="btn btn-default">一覧へ戻る</a>
		<a href="{{ url('settings/profile/integration/oauth_apps/'.urlencode($client->id).'/edit') }}" class="btn btn-primary">編集する</a>
	</div>

</div>
@endsection
