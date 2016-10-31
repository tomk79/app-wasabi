@extends('../layouts/app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Projects - Edit</div>

                <div class="panel-body">

{!! Form::open(['method'=>'put', 'action' => ['ProjectController@update', $project->id]]) !!}
{!! Form::input('hidden', 'user_id', $project->user_id, ['required', 'class' => 'form-control']) !!}
    <div class="form-group">
        <label>プロジェクト名</label>
        {!! Form::input('text', 'name', $project->name, ['required', 'class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        <label>物理名</label>
        {!! Form::input('text', 'account', $project->account, ['required', 'class' => 'form-control']) !!}
    </div>
    <button type="submit" class="btn btn-default">編集</button>
{!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
