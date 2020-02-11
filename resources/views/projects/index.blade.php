@extends('layouts.app')
@section('title', 'プロジェクト一覧')

@section('content')
<div class="container">

	<p>あなたが所属しているプロジェクトを管理します。</p>
	<div class="text-right mb-3">
		<a href="{{ url('/settings/projects/create') }}" class="btn btn-primary">プロジェクトを作成する</a>
	</div>

	@empty($projects)

	<p>プロジェクトはまだ作成されていません。</p>

	@else

	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>プロジェクト名</th>
					<th>あなたの役割</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			@foreach ($projects as $project)
				<tr>
					<td>{{ $project->name }}</td>
					<td>{{ App\Helpers\wasabiHelper::roleLabel($project->role) }}</td>
					<td><a href="{{ url('settings/projects/'.urlencode($project->id)) }}" class="btn btn-primary">詳細</a></td>
					<td><a href="{{ url('settings/projects/'.urlencode($project->id).'/edit') }}" class="btn btn-primary">編集</a></td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
	{{ $projects->links() }}

	@endempty

</div>
@endsection
