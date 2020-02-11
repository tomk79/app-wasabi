@extends('layouts.app')
@section('title', $project->name)

@section('content')
<div class="container">

	<table class="table table__dd">
		<tbody>
			<tr>
				<th><label for="group_name">グループ名</label></th>
				<td>
					<p><a href="{{ url('g/'.urlencode($group->account)) }}">{{ $group->name }}</a></p>
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
						<li><a href="{{ url(urlencode($member->account)) }}">{!! App\Helpers\wasabiHelper::icon_img($member->icon, null, '2em') !!} {{ $member->name }}</a> ({{ App\Helpers\wasabiHelper::roleLabel($member->role) }})</li>
					@endforeach
					</ul>
				</td>
			</tr>
		</tbody>
	</table>

</div>
@endsection
