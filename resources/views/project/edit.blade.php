@extends('../layouts/default')

@section('title', 'Edit &quot;'.$project->name.'&quot;')
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

{!! Form::open(['method'=>'put', 'action' => ['ProjectController@update', $project->account]]) !!}
    <div class="form-group">
        <label>プロジェクト名</label>
        {!! Form::input('text', 'name', $project->name, ['required', 'class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        <label>物理名</label>
        {!! Form::input('text', 'account', $project->account, ['required', 'class' => 'form-control']) !!}
    </div>
    <div class="text-center">
        <button type="submit" class="btn btn-primary">編集内容を保存する</button>
    </div>
{!! Form::close() !!}

<hr />
<div class="text-right">
    <a href="{{ url('/project/'.$project->account) }}" class="btn btn-default">キャンセル</a>
</div>

@endsection
