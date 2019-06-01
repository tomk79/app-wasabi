@extends('layouts.app')
@section('title', 'グループ一覧')

@section('content')
<div class="container">

	<p>あなたが所属しているグループを管理します。</p>
	<div class="text-right mb-3">
		<a href="{{ url('/settings/groups/create') }}" class="btn btn-primary">グループを作成する</a>
	</div>

	@empty($groups)

	<p>グループはまだ作成されていません。</p>

	@else

	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>グループ名</th>
					<th>アカウント</th>
					<th>あなたの役割</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			@foreach ($groups as $group)
				<tr>
					<td>{{ $group->name }}</td>
					<td>@if($group->account) <a href="{{ url('g/'.urlencode($group->account)) }}">{{ $group->account }}</a> @else --- @endif</td>
					<td>{{ $group->role }}</td>
					<td><a href="{{ url('settings/groups/'.urlencode($group->id)) }}" class="btn btn-primary">詳細</a></td>
					<td><a href="{{ url('settings/groups/'.urlencode($group->id).'/edit') }}" class="btn btn-primary">編集</a></td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
	{{ $groups->links() }}

	@endempty

</div>
@endsection
