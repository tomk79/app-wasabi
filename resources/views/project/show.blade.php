@extends('../layouts/default')

@section('title', $project->name)
@section('content')

<h2>Project data</h2>
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
            <th>オーナーユーザーID</th>
            <td>{{{ $project->user_id }}}</td>
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
    <a href="{{ url('/project/'.$project->id.'/edit') }}" class="btn btn-primary">編集する</a>
</div>

{!! Form::open(['method'=>'delete']) !!}
<button type="submit" class="btn btn-danger" onclick="return confirm('realy!?');">削除する</button>
{!! Form::close() !!}

<div>
    <a href="{{ url('/project') }}" class="btn btn-default">一覧へ戻る</a>
</div>


@if (count($members) > 0)
    <h2>Member list</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>name</th>
                <th>email</th>
                <th>authority</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($members as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->authority }}</td>
                    <td>
                        <!-- <button type="button" class="btn btn-danger" onclick="return confirm('realy!?');">削除する</button> -->
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif



@endsection
