@extends('layouts.default')
@section('title', 'API Key 情報を更新する')

@section('content')
<div class="container">

	<form action="{{ url('settings/profile/apikeys/'.urlencode($apikey->id)) }}" method="post">
		@csrf
		@method('PUT')

		<table class="table table__dd">
			<tbody>
				<tr>
					<th><label for="name">名前</label></th>
					<td>
						<input id="name" type="text" class="form-control @if ($errors->has('name')) is-invalid @endif" name="name" value="{{ old('name', $apikey->name) }}" autofocus>
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
						<input id="description" type="text" class="form-control @if ($errors->has('description')) is-invalid @endif" name="description" value="{{ old('description', $apikey->description) }}" autofocus>
							@if ($errors->has('description'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('description') }}
								</span>
							@endif
					</td>
				</tr>
			</tbody>
		</table>

		<button type="submit" name="submit" class="btn btn-primary">変更を保存する</button>
	</form>

	<hr />
	<div class="text-right">
		<a href="{{ url('settings/profile/apikeys/'.urlencode($apikey->id)) }}" class="btn btn-default">キャンセル</a>
	</div>

</div>
@endsection
