@extends('layouts.app')
@section('title', '外部アカウント 一覧')

@section('content')
<div class="container">

	<p>
		外部アプリケーションのアカウントにログインするための認証情報を管理します。<br />
	</p>

	<div class="text-right mb-3">
		<a href="{{ url('settings/profile/foreign_accounts/create') }}" class="btn btn-primary">新しい連携先を登録</a>
	</div>

	@empty($foreign_accounts)

	<p>外部サービスは登録されていません。</p>

	@else

	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>サービス名</th>
					<th>スペース</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			@foreach ($foreign_accounts as $foreign_account)
				<tr>
					<td><a href="{{ url('settings/profile/foreign_accounts/'.urlencode($foreign_account->id)) }}">{{ $foreign_account->foreign_service_id }}</a></td>
					<td>{{ $foreign_account->space }}</td>
					<td>
						<a href="{{ url('settings/profile/foreign_accounts/'.urlencode($foreign_account->id).'/edit') }}" class="btn btn-primary">編集</a>
					</td>
					<td>
						<form action="{{ url('settings/profile/foreign_accounts/'.urlencode($foreign_account->id)) }}" method="post">
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
	{{ $foreign_accounts->links() }}

	@endempty

	<hr />
	<div class="text-right">
		<a href="{{ url('settings/profile') }}" class="btn btn-default">プロフィール へ戻る</a>
	</div>

</div>
@endsection
