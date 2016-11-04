@extends('../../layouts/default')

@section('title', 'Invite NEW Project Member')
@section('content')

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{!! Form::open(['method'=>'post', 'action' => ['ProjectMemberController@store']]) !!}
{!! Form::input('hidden', 'project_account', $project->account) !!}
    <div class="form-group">
        <label>ID</label>
        <p>{{ $project->account }}</p>
    <div class="form-group">
        <label>プロジェクト名</label>
        <p>{{ $project->name }}</p>
    </div>
    </div>
    <div class="form-group">
        <label>メールアドレス</label>
        <p>{!! Form::input('text', 'email', null, ['required', 'class' => 'form-control']) !!}</p>
    </div>
    <div class="form-group">
        <label>権限</label>
        <p>{!! Form::select('authority', [5=>'User',10=>'Administrator'], 5, ['required', 'class' => 'form-control']) !!}</p>
    </div>
    <button type="submit" class="btn btn-primary">メンバー追加</button>
{!! Form::close() !!}

<div>
    <a href="{{ url('/project/'.$project->account) }}" class="btn btn-default">キャンセル</a>
</div>

@endsection
