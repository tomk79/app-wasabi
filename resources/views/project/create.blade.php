@extends('../layouts/app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Projects - Create</div>

                <div class="panel-body">

{!! Form::open() !!}
<div class="form-group">
    <label>プロジェクト名</label>
    {!! Form::input('text', 'name', null, ['required', 'class' => 'form-control']) !!}
</div>
<div class="form-group">
    <label>物理名</label>
    {!! Form::input('text', 'account', null, ['required', 'class' => 'form-control']) !!}
</div>
<button type="submit" class="btn btn-default">投稿</button>
{!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
