@extends('../layouts/default')

@section('title', 'APIキー')
@section('content')

<div class="text-right">
    <a href="{{ url('/userApiKey/create') }}" class="btn btn-primary">新規APIキー作成</a>
</div>

<table class="table table-striped table-hover">
    <colgroup width="30%" />
    <colgroup width="50%" />
    <colgroup width="20%" />
    <thead>
        <tr>
            <th>名前</th>
            <th>作成日</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($user_api_keys as $user_api_key)
        <tr>
            <td>{{{ $user_api_key->name }}}</td>
            <td>{{{ $user_api_key->created_at }}}</td>
            <td style="text-align: right;">
                {!! Form::open(['url'=>url('/userApiKey/'.$user_api_key->hash), 'method'=>'delete']) !!}
                <input type="hidden" name="hash" value="{{{ $user_api_key->hash }}}" />
                <button type="submit" class="btn btn-danger" onclick="return confirm('realy!?');">削除</button>
                {!! Form::close() !!}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
