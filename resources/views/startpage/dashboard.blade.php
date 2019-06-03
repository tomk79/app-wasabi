@extends('layouts.app')
@section('title') {{ config('app.name') }} @endsection

@section('head')
<style>
.cont-account-icon{
	width: 100%;
}
</style>
@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-4">
			<p><img src="{{ $profile->icon }}" class="account-icon cont-account-icon" /></p>
		</div>
		<div class="col">
			<table class="table table__dd">
				<tbody>
					<tr>
						<th>ユーザー名</th>
						<td>{{ $profile->name }}</td>
					</tr>
					<tr>
						<th>アカウント名</th>
						<td>@if($profile->account) <a href="{{ url('/'.$profile->account) }}">{{ $profile->account }}</a> @else --- @endif</td>
					</tr>
					<tr>
						<th>パスワード</th>
						<td>********</td>
					</tr>
				</tbody>
			</table>
			<div class="text-right">
				<p><a href="{{ url('/settings/profile/edit') }}" class="btn btn-primary">プロフィールを編集する</a></p>
			</div>

			<table class="table table__dd">
				<tbody>
					<tr>
						<th>メールアドレス</th>
						<td>
							<div>{{ $profile->email }}</div>
							@foreach($sub_emails as $email)
							<div>{{ $email->email }}</div>
							@endforeach
						</td>
					</tr>
				</tbody>
			</table>
			<div class="text-right">
				<p><a href="{{ url('/settings/profile/edit_email') }}" class="btn btn-primary">メールアドレスを変更する</a></p>
			</div>

		</div>
	</div>

</div>
@endsection
