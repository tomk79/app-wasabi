@extends('layouts.default')
@section('title', 'API Key '.$foreign_account->foreign_service_id)

@section('content')
<div class="container">

	<table class="table table__dd">
		<tbody>
			<tr>
				<th><label for="name">名前</label></th>
				<td>
					<p>{{ $foreign_account->foreign_service_id }}</p>
				</td>
			</tr>
			<tr>
				<th><label for="space">スペース</label></th>
				<td>
					<p>{{ $foreign_account->space }}</p>
				</td>
			</tr>
		</tbody>
	</table>

	<hr />
	<div class="text-right">
		<a href="{{ url('settings/profile/foreign_accounts') }}" class="btn btn-default">一覧へ戻る</a>
		<a href="{{ url('settings/profile/foreign_accounts/'.urlencode($foreign_account->id).'/edit') }}" class="btn btn-primary">編集する</a>
	</div>

	<hr />
	<div class="text-right">
		<form action="{{ url('settings/profile/foreign_accounts/'.urlencode($foreign_account->id)) }}" method="post">
			@csrf
			@method('DELETE')
			<button type="submit" name="submit" class="btn btn-danger">削除する</button>
		</form>
	</div>

</div>
@endsection
