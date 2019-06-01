@extends('layouts.app')
@section('title', 'プロフィールを編集する')

@section('content')
<div class="container">

	<form action="{{ url('settings/profile/edit') }}" method="post" enctype="multipart/form-data">
		@csrf
		@method('POST')

		<table class="table table__dd">
			<tbody>
				<tr>
					<th><label for="icon">アイコン</label></th>
					<td>
						<p><img src="{{ old('icon', $profile->icon) }}" alt="プロフィールアイコン" style="width: 200px; height: 200px;" class="account-icon cont-account-icon-preview" /></p>
						<input id="icon" type="file" class="@if ($errors->has('icon')) is-invalid @endif" name="icon" value="" autofocus>
							@if ($errors->has('icon'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('icon') }}
								</span>
							@endif
					</td>
				</tr>
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
@section('foot')
<script>
window.addEventListener('load', function(){
	var $input = document.querySelector('input[name=icon][type=file]');
	$input.addEventListener('change', function(e){
		var fileInfo = e.target.files[0];
		// console.log(fileInfo);
		if( fileInfo.size > 200000 ){
			alert( '200KB以下の画像を選択してください。' );
			return;
		}
		if( !fileInfo.type.match(/^image\/(?:png|jpeg|gif)$/) ){
			alert( 'この種類のファイルは選択できません。' );
			return;
		}
		var reader = new FileReader();
		reader.onload = function(evt) {
			var images = document.querySelectorAll('.cont-account-icon-preview');
			for( var idx in images ){
				images[idx].src = evt.target.result;
			}
		}
		reader.readAsDataURL(fileInfo);
	});
});
</script>
@endsection
