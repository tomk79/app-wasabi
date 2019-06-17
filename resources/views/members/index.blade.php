@extends('layouts.app')
@section('title', 'グループメンバー一覧')

@section('content')
<div class="container">

	<div class="text-right mb-3">
		<a href="{{ url('settings/groups/'.urlencode($group->id).'/members/create') }}" class="btn btn-primary">新しいメンバーを招待</a>
	</div>

	@empty($members)

	<p>メンバーはいません。</p>

	@else

	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th></th>
					<th>名前</th>
					<th>メールアドレス</th>
					<th>役割</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			@foreach ($members as $member)
				<tr>
					<td><img src="{{ $member->icon ? $member->icon : url('/common/images/nophoto.png') }}" alt="" style="width:1.5em; height:1.5em;" /></td>
					<td><a href="{{ url('settings/groups/'.urlencode($group->id).'/members/'.urlencode($member->id)) }}">{{ $member->name }}</a></td>
					<td>{{ $member->email }}</td>
					<td>{{ helpers\wasabiHelper::roleLabel($member->role) }}</td>
					<td>
						@if (Auth::user()->email != $member->email)
						<a href="{{ url('settings/groups/'.urlencode($group->id).'/members/'.urlencode($member->id).'/edit') }}" class="btn btn-primary">編集</a>
						@else
						---
						@endif
					</td>
					<td>
						@if (Auth::user()->email != $member->email)
						<form action="{{ url('settings/groups/'.urlencode($group->id).'/members/'.urlencode($member->id)) }}" method="post">
							@csrf
							@method('DELETE')
							<button type="submit" name="submit" class="btn btn-danger">外す</button>
						</form>
						@else
						---
						@endif
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
	{{ $members->links() }}

	@endempty

	<hr />
	<div class="text-right">
		<a href="{{ url('settings/groups/'.urlencode($group->id)) }}" class="btn btn-default">グループ詳細 へ戻る</a>
	</div>

</div>
@endsection
