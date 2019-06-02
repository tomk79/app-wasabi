<header class="px2-header">
	<div class="px2-header__inner">
		<div class="px2-header__px2logo">
			<a href="{{ url('/') }}"><img src="/common/images/logo.svg" alt="" /></a>
		</div>
		<div class="px2-header__block">
			<div class="px2-header__id">
				<span>{{ config('app.name') }}</span>
			</div>
			<div class="px2-header__global-menu">
				<ul>
					<li><a href="{{ url('/') }}" data-name="">Dashboard</a></li>
					@guest
						<li><a href="{{ route('login') }}" data-name="login">ログイン</a></li>
						<li><a href="{{ route('register') }}" data-name="signup">新規ユーザー登録</a></li>
					@else
						<li>
							<a href="javascript:;" data-name="user"><img src="{{ $global->user->icon }}" alt="" style="height: 1em; vertical-align: bottom;" /> {{ Auth::user()->name }}</a>
							<ul>
								<li><a href="{{ url('settings/profile') }}" data-name="profile">プロフィール</a></li>
								<li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a>
									<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
										@csrf
									</form>
								</li>
							</ul>
						</li>
					@endguest
				</ul>
			</div>
		</div>
		<div class="px2-header__shoulder-menu">
			<button>≡</button>
			<ul>
				<li><a href="{{ url('/') }}" data-name="">ダッシュボード</a></li>
				@guest
					<li><a href="{{ route('login') }}" data-name="login">ログイン</a></li>
					<li><a href="{{ route('register') }}" data-name="signup">新規ユーザー登録</a></li>
				@else
					<li><a href="{{ url('/settings/groups') }}" data-name="groups">グループ</a></li>
					<li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a>
						<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
							@csrf
						</form>
					</li>
				@endguest
			</ul>
		</div>
	</div>
</header>
