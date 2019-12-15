@extends('layouts.app')
@section('title', 'API Key '.$apikey->name)

@section('content')
<div class="container">

	<table class="table table__dd">
		<tbody>
			<tr>
				<th><label for="name">名前</label></th>
				<td>
					<p>{{ $apikey->name }}</p>
				</td>
			</tr>
			<tr>
				<th><label for="description">説明</label></th>
				<td>
					<p>{{ $apikey->description }}</p>
				</td>
			</tr>
			<tr>
				<th><label for="apikey">API Key</label></th>
				<td>
					<p><input type="text" name="apikey" value="{{ \Crypt::decryptString( $apikey->apikey ) }}" readonly="readonly" class="form-control" /></p>
				</td>
			</tr>
		</tbody>
	</table>

	<hr />
	<div class="text-right">
		<a href="{{ url('settings/profile/apikeys') }}" class="btn btn-default">一覧へ戻る</a>
		<a href="{{ url('settings/profile/apikeys/'.urlencode($apikey->id).'/edit') }}" class="btn btn-primary">編集する</a>
	</div>

	<hr />
	<div class="text-right">
		<form action="{{ url('settings/profile/apikeys/'.urlencode($apikey->id)) }}" method="post">
			@csrf
			@method('DELETE')
			<button type="submit" name="submit" class="btn btn-danger">削除する</button>
		</form>
	</div>

</div>
@endsection
