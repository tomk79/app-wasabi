@extends('../layouts/app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Projects</div>

                <div class="panel-body">
<div>
    <a href="{{ url('/project/create') }}" class="btn btn-primary">新規プロジェクト作成</a>
</div>
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>プロジェクト名</th>
            <th>物理名</th>
            <th>作成日時</th>
            <th>更新日時</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($projects as $project)
        <tr>
            <td>{{{ $project->id }}}</td>
            <td>{{{ $project->name }}}</td>
            <td>{{{ $project->account }}}</td>
            <td>{{{ $project->created_at }}}</td>
            <td>{{{ $project->updated_at }}}</td>
            <td>
                <a href="{{ url('/project') }}/{{{ $project->id }}}" class="btn btn-default btn-xs">詳細</a>
                <a href="{{ url('/project') }}/{{{ $project->id }}}/edit" class="btn btn-success btn-xs">編集</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
