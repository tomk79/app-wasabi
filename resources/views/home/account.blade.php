@extends('layouts.app')
@section('title', $account->name )

@section('head')
<style>
.cont-account-icon{
	width: 100%;
}
</style>
@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-4">
			<p><img src="{{ $account->icon }}" class="account-icon cont-account-icon" /></p>
		</div>
		<div class="col">
			<table class="table table__dd">
				<tbody>
					<tr>
						<th>ユーザー名</th>
						<td>{{ $account->name }}</td>
					</tr>
					<tr>
						<th>アカウント名</th>
						<td>{{ $account->account }}</td>
					</tr>
				</tbody>
			</table>

		</div>
	</div>

</div>
@endsection
