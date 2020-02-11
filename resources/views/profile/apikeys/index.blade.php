@extends('layouts.app')
@section('title', 'API Key 一覧')

@section('content')
<div class="container">

	<p>
		API Key は、外部のアプリケーションから {{ env('APP_NAME') }} の API にアクセスする認証方法の1つです。<br />
	</p>

	<div class="text-right mb-3">
		<a href="{{ url('settings/profile/apikeys/create') }}" class="btn btn-primary">新しい API Key を登録</a>
	</div>

	@empty($apikeys)

	<p>API Key は登録されていません。</p>

	@else

	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>名前</th>
					<th>説明</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			@foreach ($apikeys as $apikey)
				<tr>
					<td><a href="{{ url('settings/profile/apikeys/'.urlencode($apikey->id)) }}">{{ $apikey->name }}</a></td>
					<td>{{ $apikey->description }}</td>
					<td>
						<a href="{{ url('settings/profile/apikeys/'.urlencode($apikey->id).'/edit') }}" class="btn btn-primary">編集</a>
					</td>
					<td>
						<form action="{{ url('settings/profile/apikeys/'.urlencode($apikey->id)) }}" method="post">
							@csrf
							@method('DELETE')
							<button type="submit" name="submit" class="btn btn-danger">削除する</button>
						</form>
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
	{{ $apikeys->links() }}

	@endempty

	<hr />
	<div class="text-right">
		<a href="{{ url('settings/profile') }}" class="btn btn-default">プロフィール へ戻る</a>
	</div>

</div>
@endsection
