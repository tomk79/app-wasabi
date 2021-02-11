@extends('layouts.default')
@section('title', '新規プロジェクトを作成')

@section('content')
<div class="container">

	<form action="{{ url('settings/projects/create') }}" method="post">
		@csrf
		@method('POST')

		<table class="table table__dd">
			<tbody>
				<tr>
					<th><label for="group_id">グループ名</label></th>
					<td>
						<select class="form-control @if ($errors->has('name')) is-invalid @endif" name="group_id" required>
							<option value="">選択してください</option>
						@foreach( $groups as $group )
							<option value="{{ $group->id }}">{{ $group->name }}</option>
						@endforeach
						</select>
							@if ($errors->has('group_id'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('group_id') }}
								</span>
							@endif
					</td>
				</tr>
				<tr>
					<th><label for="name">プロジェクト名</label></th>
					<td>
						<input id="name" type="text" class="form-control @if ($errors->has('name')) is-invalid @endif" name="name" value="{{ old('name') }}" required autofocus>
							@if ($errors->has('name'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('name') }}
								</span>
							@endif
					</td>
				</tr>
				<tr>
					<th><label for="description">説明</label></th>
					<td>
						<input id="description" type="text" class="form-control @if ($errors->has('description')) is-invalid @endif" name="description" value="{{ old('description') }}" autofocus>
							@if ($errors->has('description'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('description') }}
								</span>
							@endif
					</td>
				</tr>
			</tbody>
		</table>

		<button type="submit" name="submit" class="btn btn-primary">プロジェクトを作成する</button>
	</form>

	<hr />
	<div class="text-right">
		<a href="{{ url('settings/projects/') }}" class="btn btn-default">キャンセル</a>
	</div>

</div>
@endsection
