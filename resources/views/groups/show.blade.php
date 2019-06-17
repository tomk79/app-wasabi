@extends('layouts.app')
@section('title', 'グループ '.$group->name)

@section('content')
<div class="container">

	@if( strlen( $group->parent_group_id ) )
	<ul class="breadcrumb">
	@foreach( $logical_path as $tmp_current_group )
		@if( $group->id != $tmp_current_group->id )
		<li class="breadcrumb-item"><a href="{{ url('settings/groups/'.urlencode($tmp_current_group->id)) }}">{{ $tmp_current_group->name }}</a></li>
		@else
		<li class="breadcrumb-item active"><span>{{ $tmp_current_group->name }}</span></li>
		@endif
	@endforeach
	</ul>
	@endif

	<table class="table table__dd">
		<tbody>
			@if ($parent_group)
			<tr>
				<th><label for="name">親グループ</label></th>
				<td>
					<p><a href="{{ url('settings/groups/'.urlencode($parent_group->id)) }}">{{ $parent_group->name }}</a></p>
				</td>
			</tr>
			@endif
			<tr>
				<th><label for="name">グループ名</label></th>
				<td>
					<p>{{ $group->name }}</p>
				</td>
			</tr>
			<tr>
				<th><label for="icon">アイコン</label></th>
				<td>
					<p><img src="{{ $group->icon }}" alt="" class="group-icon" style="width:1.5em; height: 1.5em;" /></p>
				</td>
			</tr>
			<tr>
				<th><label for="email">アカウント</label></th>
				<td>
					<p>@if($group->account) <a href="{{ url('g/'.urlencode($group->account)) }}">{{ $group->account }}</a> @else --- @endif</p>
				</td>
			</tr>
			<tr>
				<th><label for="role">あなたの役割</label></th>
				<td>
					<p>{{ $relation ? helpers\wasabiHelper::roleLabel($relation->role) : '---' }}</p>
				</td>
			</tr>
			<tr>
				<th>サブグループ</th>
				<td>
					<ul>
					@foreach( $children as $child )
						<li><a href="{{ url('settings/groups/'.urlencode($child->id)) }}">{{ $child->name }}</a></li>
					@endforeach
					</ul>
				</td>
			</tr>
			<tr>
				<th>プロジェクト</th>
				<td>
					<ul>
					@foreach( $projects as $project )
						<li><a href="{{ url('settings/projects/'.urlencode($project->id)) }}">{{ $project->name }}</a></li>
					@endforeach
					</ul>
				</td>
			</tr>
		</tbody>
	</table>

	<hr />
	<div class="text-right">
		<a href="{{ url('/settings/groups') }}" class="btn btn-default">一覧へ戻る</a>
		<a href="{{ url('/settings/groups/'.urlencode($group->id).'/members') }}" class="btn btn-default">メンバー一覧</a>
		<a href="{{ url('/settings/groups/create?parent='.urlencode($group->id)) }}" class="btn btn-default">新規サブグループ</a>
		<a href="{{ url('/settings/groups/'.urlencode($group->id).'/edit') }}" class="btn btn-primary">編集する</a>
	</div>

</div>
@endsection
