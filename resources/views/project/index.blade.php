@extends('../layouts/default')

@section('title', 'Projects')
@section('content')

<div class="text-right">
    <a href="{{ url('/project/create') }}" class="btn btn-primary">新規プロジェクト作成</a>
</div>

<table class="table table-striped table-hover">
    <colgroup width="30%" />
    <colgroup width="50%" />
    <colgroup width="20%" />
    <thead>
        <tr>
            <th>ID</th>
            <th>プロジェクト名</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($projects as $project)
        <tr>
            <td>{{{ $project->account }}}</td>
            <td>{{{ $project->name }}}</td>
            <td style="text-align: right;">
                <a href="{{ url('/project') }}/{{{ $project->account }}}" class="btn btn-default">詳細</a>
                <a href="{{ url('/project') }}/{{{ $project->account }}}/edit" class="btn btn-primary">編集</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
