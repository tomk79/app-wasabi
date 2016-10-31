@extends('../layouts/app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Projects - Edit</div>

                <div class="panel-body">

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{!! Form::open(['method'=>'put', 'action' => ['ProjectController@update', $project->id]]) !!}
    <div class="form-group">
        <label>プロジェクト名</label>
        {!! Form::input('text', 'name', $project->name, ['required', 'class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        <label>物理名</label>
        {!! Form::input('text', 'account', $project->account, ['required', 'class' => 'form-control']) !!}
    </div>
    <button type="submit" class="btn btn-primary">編集内容を保存する</button>
{!! Form::close() !!}

<div>
    <a href="{{ url('/project') }}" class="btn btn-default">キャンセル</a>
</div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
