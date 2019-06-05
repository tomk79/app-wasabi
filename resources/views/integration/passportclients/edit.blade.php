@extends('layouts.app')
@section('title', '外部アプリケーション情報を更新する')

@section('content')
<div class="container">

	<form action="{{ url('settings/profile/integration/oauth_apps/'.urlencode($client->id)) }}" method="post">
		@csrf
		@method('PUT')

		<table class="table table__dd">
			<tbody>
				<tr>
					<th><label for="name">名前</label></th>
					<td>
						<input id="name" type="text" class="form-control @if ($errors->has('name')) is-invalid @endif" name="name" value="{{ old('name', $client->name) }}" autofocus>
							@if ($errors->has('name'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('name') }}
								</span>
							@endif
					</td>
				</tr>
				<tr>
					<th><label for="redirect">リダイレクト</label></th>
					<td>
						<input id="redirect" type="text" class="form-control @if ($errors->has('redirect')) is-invalid @endif" name="redirect" value="{{ old('redirect', $client->redirect) }}" autofocus>
							@if ($errors->has('redirect'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('redirect') }}
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
		<a href="{{ url('settings/profile/integration/oauth_apps/'.urlencode($client->id)) }}" class="btn btn-default">キャンセル</a>
	</div>

</div>
@endsection
