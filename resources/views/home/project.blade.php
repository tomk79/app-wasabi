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
					@empty ($members)
						<p>メンバーはいません。</p>
					@else
						<ul>
						@foreach( $members as $member )
							<li><a href="{{ url(urlencode($member->account)) }}">{!! App\Helpers\wasabiHelper::icon_img($member->icon, null, '2em') !!} {{ $member->name }}</a> ({{ App\Helpers\wasabiHelper::roleLabel($member->role) }})</li>
						@endforeach
						</ul>
					@endempty
				</td>
			</tr>
			<tr>
				<th>アプリケーション</th>
				<td>
					@empty ($wasabiApps)
						<p>アプリケーションは登録されていません。</p>
					@else
						<ul>
						@foreach( $wasabiApps as $wasabiApp )
							@php
							$wasabiApp = (object) $wasabiApp;
							@endphp
							<li><a href="{{ url('pj/'.urlencode($project->id).'/app/'.$wasabiApp->id) }}">{{ $wasabiApp->name }}</a></li>
						@endforeach
						</ul>
					@endempty
				</td>
			</tr>
		</tbody>
	</table>

</div>
@endsection
