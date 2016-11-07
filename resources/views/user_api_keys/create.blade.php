@extends('../layouts/default')

@section('title', 'Create NEW API Key')
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

{!! Form::open(['method'=>'post', 'action' => ['UserApiKeyController@store']]) !!}
    <div class="form-group">
        <label>名前</label>
        {!! Form::input('text', 'name', null, ['required', 'class' => 'form-control']) !!}
    </div>
    <div class="text-center">
        <button type="submit" class="btn btn-primary">APIキー作成</button>
    </div>
{!! Form::close() !!}

<hr />
<div class="text-right">
    <a href="{{ url('/userApiKey') }}" class="btn btn-default">キャンセル</a>
</div>

@endsection
