@extends('layouts.default')
@section('title', 'プロフィール')

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
						<td>@if($profile->account) <a href="{{ url($profile->account) }}">{{ $profile->account }}</a> @else --- @endif</td>
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
							<div>
								{{ $email->email }}
								<form action="{{ url('settings/profile/set_sub_email_as_primary') }}" method="post" style="display:inline;">
									@csrf
									<input type="hidden" name="email" value="{{ $email->email }}" />
									<button class="btn btn-primary" type="submit">✔</button>
								</form>

								<form action="{{ url('settings/profile/delete_sub_email') }}" method="post" style="display:inline;">
									@csrf
									@method('DELETE')
									<input type="hidden" name="email" value="{{ $email->email }}" />
									<button class="btn btn-danger" type="submit">✗</button>
								</form>
							</div>
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



	<hr />


	<h2>API Key</h2>
	<div class="text-center">
		<a href="{{ url('/settings/profile/apikeys') }}" class="btn btn-primary">API Key を管理する</a>
	</div>

	<h2>外部アプリケーション連携</h2>
	<div class="text-center">
		<a href="{{ url('/settings/profile/integration/oauth_apps') }}" class="btn btn-primary">OAuth アプリケーションを管理する</a>
	</div>

	<h2>外部アカウント連携</h2>
	<div class="text-center">
		<a href="{{ url('/settings/profile/foreign_accounts') }}" class="btn btn-primary">外部アカウント連携を管理する</a>
	</div>


	<hr />

	<h2>退会する</h2>
	<div class="text-center">
		<a href="{{ url('/settings/withdraw') }}" class="btn btn-danger">退会する</a>
	</div>

</div>
@endsection
