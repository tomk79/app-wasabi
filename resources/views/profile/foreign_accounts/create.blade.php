@extends('layouts.default')
@section('title', '新しい アカウント を登録する')

@section('content')
<div class="container">

	<form action="{{ url('settings/profile/foreign_accounts') }}" method="post">
		@csrf
		@method('POST')


		<table class="table table__dd">
			<tbody>
				<tr>
					<th><label for="foreign_service_id">サービス名</label></th>
					<td>
						<select name="foreign_service_id" class="form-control{{ $errors->has('foreign_service_id') ? ' is-invalid' : '' }}" required autofocus>
							<option value="backlog">Backlog</option>
						</select>

							@if ($errors->has('foreign_service_id'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('foreign_service_id') }}
								</span>
							@endif
					</td>
				</tr>
				<tr>
					<th><label for="space">スペース</label></th>
					<td>
						<input id="space" type="text" class="form-control{{ $errors->has('space') ? ' is-invalid' : '' }}" name="space" value="{{ old('space') }}" required autofocus>

							@if ($errors->has('space'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('space') }}
								</span>
							@endif
					</td>
				</tr>
				<tr>
					<th><label for="backlog-apikey">APIキー</label></th>
					<td>
						<input id="backlog-apikey" type="text" class="form-control{{ $errors->has('backlog-apikey') ? ' is-invalid' : '' }}" name="backlog-apikey" value="{{ old('backlog-apikey') }}" required autofocus>

							@if ($errors->has('backlog-apikey'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('backlog-apikey') }}
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
		<a href="{{ url('settings/profile/foreign_accounts') }}" class="btn btn-default">キャンセル</a>
	</div>

</div>
@endsection
