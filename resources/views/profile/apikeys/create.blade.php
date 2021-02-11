@extends('layouts.default')
@section('title', '新しい API Key を登録する')

@section('content')
<div class="container">

	<form action="{{ url('settings/profile/apikeys') }}" method="post">
		@csrf
		@method('POST')


		<table class="table table__dd">
			<tbody>
				<tr>
					<th><label for="name">名前</label></th>
					<td>
						<input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

							@if ($errors->has('name'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('name') }}
								</span>
							@endif
					</td>
				</tr>
				<tr>
					<th><label for="description">説明</label></th>
					<td>
						<input id="description" type="text" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" value="{{ old('description') }}" required autofocus>

							@if ($errors->has('description'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('description') }}
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
		<a href="{{ url('settings/profile/apikeys') }}" class="btn btn-default">キャンセル</a>
	</div>

</div>
@endsection
