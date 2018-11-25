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

{!! Form::open(['method'=>'post', 'action' => ['UserApiKeyController@store'], 'id'=>'form_apikey_create']) !!}
    <div class="form-group">
        <label>Key Name</label>
        {!! Form::input('text', 'name', null, ['required', 'class' => 'form-control']) !!}
    </div>
    <div class="text-center">
        <button type="submit" class="btn btn-primary">API Key 作成</button>
    </div>
{!! Form::close() !!}

<hr />
<div class="text-right">
    <a href="{{ url('/userApiKey') }}" class="btn btn-default">キャンセル</a>
</div>
@endsection


@section('foot')

<script>
var $form = $('#form_apikey_create');
$form.submit(function(){
    $.ajax({
        'type': 'json',
        'method': $form.attr('method'),
        'url': $form.attr('action'),
        'data':$form.serialize(),
        'success': function(data){
            // alert('API認証キーが発行されました。この文字列をコピーして安全な場所に保存してください。この文字列は再表示することができません。'+"\n"+data.authkey);
            window.location.href = "{{ url('/userApiKey/result') }}";
        }
    });
    return false;
});
</script>

@endsection
