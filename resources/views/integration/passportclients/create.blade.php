@extends('layouts.app')
@section('title', '新しいアプリケーションを登録する')

@section('content')
<div class="container">

	<form action="{{ url('settings/profile/integration/oauth_apps') }}" method="post">
		@csrf
		@method('POST')


		<table class="table table__dd">
			<tbody>
				<tr>
					<th><label for="name">名前</label></th>
					<td>
						<input id="name" type="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

							@if ($errors->has('name'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('name') }}
								</span>
							@endif
					</td>
				</tr>
				<tr>
					<th><label for="redirect">リダイレクト先</label></th>
					<td>
						<input id="redirect" type="redirect" class="form-control{{ $errors->has('redirect') ? ' is-invalid' : '' }}" name="redirect" value="{{ old('redirect') }}" required autofocus>

							@if ($errors->has('redirect'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('redirect') }}
								</span>
							@endif
					</td>
				</tr>
			</tbody>
		</table>


		<button type="submit" name="submit" class="btn btn-primary">登録する</button>
	</form>

	<hr />
	<div class="text-right">
		<a href="{{ url('settings/profile/integration/oauth_apps') }}" class="btn btn-default">キャンセル</a>
	</div>

</div>
@endsection
