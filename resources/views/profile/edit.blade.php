@extends('layouts.app')
@section('title', 'プロフィールを編集する')

@section('content')
<div class="container">

	<form action="{{ url('settings/profile/edit') }}" method="post">
		@csrf
		@method('POST')

		<table class="table table__dd">
			<tbody>
				<tr>
					<th><label for="name">ユーザー名</label></th>
					<td>
						<input id="name" type="text" class="form-control @if ($errors->has('name')) is-invalid @endif" name="name" value="{{ old('name', $profile->name) }}" required autofocus>
							@if ($errors->has('name'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('name') }}
								</span>
							@endif
					</td>
				</tr>
				<tr>
					<th><label for="password">パスワード</label></th>
					<td>
						<p>パスワードを変更する場合のみ入力してください。</p>
						新パスワード：<input id="password" type="password" class="form-control @if ($errors->has('password')) is-invalid @endif" name="password" value="" autofocus><br />
						確認のため再入力：<input id="password-confirm" type="password" class="form-control @if ($errors->has('password')) is-invalid @endif" name="password-confirm" value="" autofocus><br />
							@if ($errors->has('password'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('password') }}
								</span>
							@endif
					</td>
				</tr>
			</tbody>
		</table>


		<button type="submit" name="submit" class="btn btn-primary">保存する</button>
	</form>

	<hr />
	<div class="text-right">
		<a href="{{ url('/settings/profile/') }}" class="btn btn-default">キャンセル</a>
	</div>

</div>
@endsection
