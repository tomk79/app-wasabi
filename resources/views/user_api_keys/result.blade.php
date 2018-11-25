@extends('../layouts/default')

@section('title', 'Create NEW API Key')
@section('content')

<p>
    API認証キー <code>{{{ session('authname') }}}</code> が発行されました。この文字列をコピーして安全な場所に保存してください。この文字列は再表示することができません。<br />
</p>
<pre><code>{{{ session('authkey') }}}</code></pre>

<div class="text-center">
    <a href="{{ url('/userApiKey') }}" class="btn btn-default">一覧へ戻る</a>
</div>
@endsection
