@extends('layouts.app')
@section('title', '新しいプロジェクトメンバーを招待する')

@section('content')
<div class="container">

	<form action="{{ url('settings/projects/'.urlencode($project_id).'/members') }}" method="post">
		@csrf
		@method('POST')


		<table class="table table__dd">
			<tbody>
				<tr>
					<th><label for="email">メールアドレス</label></th>
					<td>
						<input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

							@if ($errors->has('email'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('email') }}
								</span>
							@endif
					</td>
				</tr>
				<tr>
					<th><label for="role">役割</label></th>
					<td>
						<select id="role" class="form-control{{ $errors->has('role') ? ' is-invalid' : '' }}" name="role" value="{{ old('role') }}" required>
							<?php $vals = array( 'member', 'manager', 'owner' ); ?>
							@foreach ($vals as $val)
							<option value="{{ $val }}" @if (old('role')==$val) selected="selected"  @endif>{{ $val }}</option>
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


		<button type="submit" name="submit" class="btn btn-primary">招待する</button>
	</form>

	<hr />
	<div class="text-right">
		<a href="{{ url('settings/projects/'.urlencode($project_id).'/members') }}" class="btn btn-default">キャンセル</a>
	</div>

</div>
@endsection
