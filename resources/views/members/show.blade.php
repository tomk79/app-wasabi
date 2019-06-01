@extends('layouts.app')
@section('title', 'メンバー '.$user->name)

@section('content')
<div class="container">

	<table class="table table__dd">
		<tbody>
			<tr>
				<th><label for="name">名前</label></th>
				<td>
					<p>{{ $user->name }}</p>
				</td>
			</tr>
			<tr>
				<th><label for="email">メールアドレス</label></th>
				<td>
					<p>{{ $user->email }}</p>
				</td>
			</tr>
			<tr>
				<th><label for="role">役割</label></th>
				<td>
					<p>{{ $relation->role }}</p>
				</td>
			</tr>
		</tbody>
	</table>

	<hr />
	<div class="text-right">
		<a href="{{ url('/'.urlencode($group->account).'/members') }}" class="btn btn-default">一覧へ戻る</a>
		<a href="{{ url('/'.urlencode($group->account).'/members/'.urlencode($user->email).'/edit') }}" class="btn btn-primary">編集する</a>
	</div>

</div>
@endsection
