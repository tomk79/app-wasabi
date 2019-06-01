@extends('layouts.app')
@section('title', 'メールアドレスを変更する')

@section('content')
<div class="container">

	<form action="{{ url('settings/profile/edit_email') }}" method="post">
		@csrf
		@method('POST')

		<table class="table table__dd">
			<tbody>
				<tr>
					<th><label for="email">新しいメールアドレス</label></th>
					<td>
						<input id="email" type="text" class="form-control @if ($errors->has('email')) is-invalid @endif" name="email" value="{{ old('email', $profile->email) }}" required autofocus>
							@if ($errors->has('email'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('email') }}
								</span>
							@endif
					</td>
				</tr>
			</tbody>
		</table>


		<button type="submit" name="submit" class="btn btn-primary">変更する</button>
	</form>

	<hr />
	<div class="text-right">
		<a href="{{ url('/settings/profile/') }}" class="btn btn-default">キャンセル</a>
	</div>

</div>
@endsection
