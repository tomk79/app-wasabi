@extends('layouts.app')
@section('title', 'プロジェクト '.$project->name)

@section('content')
<div class="container">

	<table class="table table__dd">
		<tbody>
			<tr>
				<th><label for="group_name">グループ名</label></th>
				<td>
					<p><a href="{{ url('settings/groups/'.urlencode($group->id)) }}">{{ $group->name }}</a></p>
				</td>
			</tr>
			<tr>
				<th><label for="name">プロジェクト名</label></th>
				<td>
					<p>{{ $project->name }}</p>
				</td>
			</tr>
			<tr>
				<th><label for="role">あなたの役割</label></th>
				<td>
					<p>{{ $relation ? App\Helpers\wasabiHelper::roleLabel($relation->role) : '---' }}</p>
				</td>
			</tr>
			<tr>
				<th>メンバー</th>
				<td>
					<ul>
					@foreach( $members as $member )
						<li><a href="{{ url('settings/projects/'.urlencode($project->id).'/members/'.urlencode($member->id)) }}">{!! App\Helpers\wasabiHelper::icon_img($member->icon, null, '2em') !!} {{ $member->name }}</a> ({{ App\Helpers\wasabiHelper::roleLabel($member->role) }})</li>
					@endforeach
					</ul>
				</td>
			</tr>
		</tbody>
	</table>

	<hr />
	<div class="text-center">
		<a href="{{ url('pj/'.urlencode($project->id)) }}" class="btn btn-primary">プロジェクトホームへ</a>
	</div>
	<div class="text-right">
		<a href="{{ url('/settings/projects') }}" class="btn btn-default">一覧へ戻る</a>
		<a href="{{ url('/settings/projects/'.urlencode($project->id).'/members') }}" class="btn btn-default">メンバー一覧</a>
		<a href="{{ url('/settings/projects/'.urlencode($project->id).'/wasabiapps') }}" class="btn btn-default">アプリケーション統合</a>
		<a href="{{ url('/settings/projects/'.urlencode($project->id).'/edit') }}" class="btn btn-primary">編集する</a>
	</div>

</div>
@endsection
