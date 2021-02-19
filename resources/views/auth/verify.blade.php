@extends('layouts.default')
@section('title', __('Verify Your Email Address'))

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">ご登録のメールアドレスをご確認ください。</div>

				<div class="card-body">
					@if (session('resent'))
						<div class="alert alert-success" role="alert">
							確認用の新しいリンクをご登録のメールアドレス宛にお送りしました。受信ボックスをご確認ください。
						</div>
					@endif

					<p>ユーザー登録はまだ完了していません。</p>
					<p>ご登録のメールアドレス宛に、メールアドレス確認用のリンクをお送りしております。ご確認の上、確認の操作を完了してください。</p>
					<p>もしもメールを受け取れない場合は、 <a href="{{ route('verification.resend') }}">ここをクリックして再送できます</a>。</p>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
