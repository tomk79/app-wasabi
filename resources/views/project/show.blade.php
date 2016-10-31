@extends('../layouts/app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Projects - Property</div>

                <div class="panel-body">

<table class="table table-striped">
    <tbody>
        <tr>
            <th>プロジェクトID</th>
            <td>{{{ $project->id }}}</td>
        </tr>
        <tr>
            <th>プロジェクト名</th>
            <td>{{{ $project->name }}}</td>
        </tr>
        <tr>
            <th>物理名</th>
            <td>{{{ $project->account }}}</td>
        </tr>
        <tr>
            <th>作成日時</th>
            <td>{{{ $project->created_at }}}</td>
        </tr>
        <tr>
            <th>更新日時</th>
            <td>{{{ $project->updated_at }}}</td>
        </tr>
    </tbody>
</table>

<div>
    <a href="{{ url('/project') }}/{{{ $project->id }}}/edit" class="btn btn-primary">編集する</a>
</div>

{!! Form::open(['method'=>'delete']) !!}
<button type="submit" class="btn btn-danger">削除する</button>
{!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
