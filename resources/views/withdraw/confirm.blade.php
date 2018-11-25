@extends('../layouts/default')

@section('title', 'Withdraw')
@section('content')

{!! Form::open(['method'=>'delete', 'action' => ['WithdrawController@withdraw']]) !!}
    <div class="text-left">
        <p>
            サービスを退会しようとしています。<br />
        </p>
        <p>
            この処理は、あなたに関連するすべてのデータをサービスから削除します。<br />
            一度退会すると、データを復元することはできません。<br />
        </p>
        <p>
            本当に退会してもよろしいですか？<br />
        </p>
    </div>
    <div class="text-center">
        <button type="submit" class="btn btn-danger">退会する</button>
    </div>
{!! Form::close() !!}

<hr />
<div class="text-right">
    <a href="{{ url('/profile/') }}" class="btn btn-default">キャンセル</a>
</div>

@endsection
