@extends('../layouts/default')

@section('title', $project->name)
@section('content')

<?php
$authority_master = array(5=>'User', 10=>'Administrator');
?>


<h2>Project data</h2>
<table class="table table-striped">
    <colgroup width="30%" />
    <colgroup width="70%" />
    <tbody>
        <tr>
            <th>ID</th>
            <td>{{{ $project->account }}}</td>
        </tr>
        <tr>
            <th>プロジェクト名</th>
            <td>{{{ $project->name }}}</td>
        </tr>
        <tr>
            <th>オーナー</th>
            <td>{{{ $owner_user->name }}}</td>
        </tr>
    </tbody>
</table>

<div class="text-center">
    <a href="{{ url('/project/'.$project->account.'/edit') }}" class="btn btn-primary">このプロジェクトを編集する</a>
    <a href="{{ url('/project') }}" class="btn btn-default">一覧へ戻る</a>
</div>

<h2>API URL</h2>

<p><input type="text" value="{{ url('/api/'.$project->account) }}" class="form-control" readonly="readonly" /></p>

<h2>Member list</h2>
<div class="text-right">
    <a href="{{ url('/projectMember/create') }}?project_account={{{ $project->account }}}" class="btn btn-primary">追加する</a>
</div>
@if (count($members) > 0)
    <table class="table table-striped">
        <colgroup width="30%" />
        <colgroup width="30%" />
        <colgroup width="20%" />
        <colgroup width="20%" />
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
                    <td>{{ @$authority_master[$user->authority] ? @$authority_master[$user->authority] : $user->authority }}</td>
                    <td style="text-align: right;">
                        {!! Form::open(['url'=>url('/projectMember/'.$project->account), 'method'=>'delete']) !!}
                        <input type="hidden" name="user_id" value="{{{ $user->user_id }}}" />
                        <input type="hidden" name="project_account" value="{{{ $project->account }}}" />
                        <button type="submit" class="btn btn-danger" onclick="return confirm('realy!?');">削除する</button>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

<hr />

<div class="text-center">
    <a href="{{ url('/project') }}" class="btn btn-default">一覧へ戻る</a>
    {!! Form::open(['method'=>'delete', 'style'=>'display:inline;']) !!}
    <button type="submit" class="btn btn-danger" onclick="return confirm('realy!?');">このプロジェクトを削除する</button>
    {!! Form::close() !!}
</div>

@endsection


@section('foot')
@endsection
