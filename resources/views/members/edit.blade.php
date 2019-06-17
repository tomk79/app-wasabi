@extends('layouts.app')
@section('title', 'メンバー情報を更新する')

@section('content')
<div class="container">

	<form action="{{ url('settings/groups/'.urlencode($group->id).'/members/'.urlencode($user->id)) }}" method="post">
		@csrf
		@method('PUT')

		<table class="table table__dd">
			<tbody>
				<tr>
					<th><label for="name">名前</label></th>
					<td>
						<p>{{ $user->name }}</p>
					</td>
				</tr>
				<tr>
					<th><label for="email">メールアドレス</label></th>
					<td>
						<p>{{ $user->email }}</p>
					</td>
				</tr>
				<tr>
					<th><label for="role">役割</label></th>
					<td>
						<select id="role" class="form-control{{ $errors->has('role') ? ' is-invalid' : '' }}" name="role" value="{{ old('role', $relation->role) }}" required>
							<?php $vals = helpers\wasabiHelper::getRoleList(); ?>
							@foreach ($vals as $val=>$label)
							<option value="{{ $val }}" @if (old('role', $relation->role)==$val) selected="selected"  @endif>{{ $label }}</option>
							@endforeach
						</select>

							@if ($errors->has('role'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('role') }}
								</span>
							@endif
					</td>
				</tr>
			</tbody>
		</table>

		<button type="submit" name="submit" class="btn btn-primary">変更を保存する</button>
	</form>

	<hr />
	<div class="text-right">
		<a href="{{ url('settings/groups/'.urlencode($group->id).'/members/'.urlencode($user->id)) }}" class="btn btn-default">キャンセル</a>
	</div>

</div>
@endsection
