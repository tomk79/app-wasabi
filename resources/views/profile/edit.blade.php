@extends('../layouts/default')

@section('title', 'Edit Profile')
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

{!! Form::open(['method'=>'post', 'action' => ['ProfileController@update']]) !!}
    <div class="form-group">
        <label>name</label>
        {!! Form::input('text', 'name', $profile->name, ['required', 'class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        <label>E-mail</label>
        {!! Form::input('text', 'email', $profile->email, ['required', 'class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        <label>Password</label>
        {!! Form::input('password', 'password', '', ['class' => 'form-control']) !!}
    </div>
    <div class="text-center">
        <button type="submit" class="btn btn-primary">編集内容を保存する</button>
    </div>
{!! Form::close() !!}

<hr />
<div class="text-right">
    <a href="{{ url('/profile/') }}" class="btn btn-default">キャンセル</a>
</div>

@endsection
