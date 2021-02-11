@extends('layouts.default')
@section('title', '退会')

@section('content')
<div class="container">

	<form action="{{ url('settings/withdraw') }}" method="post">
		@csrf
		@method('DELETE')
		<div class="text-left">
			<p>
				サービスを退会しようとしています。<br />
			</p>
			<p>
				この処理は、あなたに関連するすべてのデータをサービスから削除します。<br />
				一度退会すると、データを復元することはできません。<br />
			</p>
			<p>
				本当に退会してもよろしいですか？<br />
			</p>
		</div>
		<div class="text-center">
			<button type="submit" class="btn btn-danger">退会する</button>
		</div>
	</form>

	<hr />
	<div class="text-right">
		<a href="{{ url('/settings/profile/') }}" class="btn btn-default">キャンセル</a>
	</div>

</div>
@endsection
