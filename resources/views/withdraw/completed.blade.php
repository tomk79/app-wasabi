@extends('../layouts/default')

@section('title', 'Withdraw')
@section('content')

@if (Auth::guest())
    <p>
        退会処理を完了しました。<br />
        ご利用ありがとうございました。<br />
    </p>
@else
    <p>
        退会処理を完了できませんでした。<br />
    </p>
@endif

<hr />
<div class="text-center">
    <a href="{{ url('/') }}" class="btn btn-default">トップページへ</a>
</div>

@endsection
