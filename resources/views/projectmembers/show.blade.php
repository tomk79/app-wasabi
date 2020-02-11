@extends('layouts.app')
@section('title', 'プロジェクトメンバー '.$user->name)

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
				<th><label for="icon">アイコン</label></th>
				<td>
					<p><img src="{{ $user->icon ? $user->icon : url('/common/images/nophoto.png') }}" alt="" class="account-icon" style="width:2em; height:2em;" /></p>
				</td>
			</tr>
			<tr>
				<th><label for="account">アカウント</label></th>
				<td>
					<p>@if($user->account) <a href="{{ url($user->account) }}">{{ $user->account }}</a> @else --- @endif</p>
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
					<p>{{ App\Helpers\wasabiHelper::roleLabel($relation->role) }}</p>
				</td>
			</tr>
		</tbody>
	</table>

	<hr />
	<div class="text-right">
		<a href="{{ url('settings/projects/'.urlencode($project->id).'/members') }}" class="btn btn-default">一覧へ戻る</a>
		<a href="{{ url('settings/projects/'.urlencode($project->id).'/members/'.urlencode($user->id).'/edit') }}" class="btn btn-primary">編集する</a>
	</div>

</div>
@endsection
