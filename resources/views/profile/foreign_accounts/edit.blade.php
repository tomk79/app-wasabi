@extends('layouts.default')
@section('title', 'アカウント 情報を更新する')

@section('content')
<div class="container">

	<form action="{{ url('settings/profile/foreign_accounts/'.urlencode($foreign_account->id)) }}" method="post">
		@csrf
		@method('PUT')

		<table class="table table__dd">
			<tbody>
				<tr>
					<th><label for="foreign_service_id">サービス名</label></th>
					<td>
						<select name="foreign_service_id" class="form-control{{ $errors->has('foreign_service_id') ? ' is-invalid' : '' }}" required autofocus>
							<option value="backlog" @if ( old('foreign_service_id', $foreign_account->foreign_service_id) == 'backlog') selected @endif>Backlog</option>
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
						<input id="space" type="text" class="form-control @if ($errors->has('space')) is-invalid @endif" name="space" value="{{ old('space', $foreign_account->space) }}" autofocus>
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
						<input id="backlog-apikey" type="text" class="form-control{{ $errors->has('backlog-apikey') ? ' is-invalid' : '' }}" name="backlog-apikey" value="{{ old('backlog-apikey', $foreign_account->{'backlog-apikey'}) }}" required autofocus>

							@if ($errors->has('backlog-apikey'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('backlog-apikey') }}
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
		<a href="{{ url('settings/profile/foreign_accounts/'.urlencode($foreign_account->id)) }}" class="btn btn-default">キャンセル</a>
	</div>

</div>
@endsection
