@extends('layouts.default')
@section('title', 'プロジェクトの編集')

@section('content')
<div class="container">

	<form action="{{ url('/settings/projects/'.urlencode($project->id).'/edit') }}" method="post" enctype="multipart/form-data">
		@csrf
		@method('POST')

		<table class="table table__dd">
			<tbody>
				<tr>
					<th><label for="group_id">グループ名</label></th>
					<td>
						<select class="form-control @if ($errors->has('name')) is-invalid @endif" name="group_id" required>
							<option value="{{ $group->id }}">{{ $group->name }}</option>
						@foreach( $groups as $tmp_group )
							<option value="{{ $tmp_group->id }}">{{ $tmp_group->name }}</option>
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
						<input id="name" type="text" class="form-control @if ($errors->has('name')) is-invalid @endif" name="name" value="{{ old('name', $project->name) }}" required autofocus>
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
						<input id="description" type="text" class="form-control @if ($errors->has('description')) is-invalid @endif" name="description" value="{{ old('description', $project->description) }}" autofocus>
							@if ($errors->has('description'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('description') }}
								</span>
							@endif
					</td>
				</tr>
				<tr>
					<th><label for="icon">アイコン</label></th>
					<td>
						<p><img src="{{ old('icon', $project->icon) }}" alt="プロジェクトアイコン" style="width: 200px; height: 200px;" class="project-icon cont-account-icon-preview" /></p>
						<input id="icon" type="file" class="@if ($errors->has('icon')) is-invalid @endif" name="icon" value="" autofocus>
							@if ($errors->has('icon'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('icon') }}
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
		<a href="{{ url('/settings/projects/'.urlencode($project->id)) }}" class="btn btn-default">キャンセル</a>
	</div>

</div>
@endsection
@section('foot')
<script>
window.addEventListener('load', function(){
	var $input = document.querySelector('input[name=icon][type=file]');
	$input.addEventListener('change', function(e){
		var fileInfo = e.target.files[0];
		// console.log(fileInfo);
		if( fileInfo.size > 200000 ){
			alert( '200KB以下の画像を選択してください。' );
			return;
		}
		if( !fileInfo.type.match(/^image\/(?:png|jpeg|gif)$/) ){
			alert( 'この種類のファイルは選択できません。' );
			return;
		}
		var reader = new FileReader();
		reader.onload = function(evt) {
			var images = document.querySelectorAll('.cont-account-icon-preview');
			for( var idx in images ){
				images[idx].src = evt.target.result;
			}
		}
		reader.readAsDataURL(fileInfo);
	});
});
</script>
@endsection
