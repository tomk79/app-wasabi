@extends('layouts.default')
@section('title', 'グループの編集')

@section('content')
<div class="container">

	<form action="{{ url('/settings/groups/'.urlencode($group->id).'/edit') }}" method="post" enctype="multipart/form-data">
		@csrf
		@method('POST')

		<table class="table table__dd">
			<tbody>
				@if ($group->parent_group_id)
				<tr>
					<th><label for="parent_group_id">親グループ</label></th>
					<td>
						<select name="parent_group_id" class="form-control">
							<option value="{{ $group->root_group_id }}" @if(old('parent_group_id', $group->parent_group_id) == $group->root_group_id) selected @endif>{{ $root_group->name }}</option>
						@foreach( $sub_groups as $sub_group )
							<option value="{{ $sub_group['group']->id }}" @if(old('parent_group_id', $group->parent_group_id) == $sub_group['group']->id) selected @endif>{{ $root_group->name }} &gt; {{ $sub_group['fullname'] }}</option>
						@endforeach
						</select>
							@if ($errors->has('parent_group_id'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('parent_group_id') }}
								</span>
							@endif
					</td>
				</tr>
				@endif
				<tr>
					<th><label for="name">グループ名</label></th>
					<td>
						<input id="name" type="text" class="form-control @if ($errors->has('name')) is-invalid @endif" name="name" value="{{ old('name', $group->name) }}" required autofocus>
							@if ($errors->has('name'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('name') }}
								</span>
							@endif
					</td>
				</tr>
				<tr>
					<th><label for="account">アカウント名</label></th>
					<td>
						<p>アカウント名を設定すると、グループのホームページが作成され、プロフィールを共有できます。</p>
						<input id="account" type="text" class="form-control @if ($errors->has('account')) is-invalid @endif" name="account" value="{{ old('account', $group->account) }}" autofocus>
							@if ($errors->has('account'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('account') }}
								</span>
							@endif
					</td>
				</tr>
				<tr>
					<th><label for="description">説明</label></th>
					<td>
						<input id="description" type="text" class="form-control @if ($errors->has('description')) is-invalid @endif" name="description" value="{{ old('description', $group->description) }}" autofocus>
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
						<p><img src="{{ old('icon', $group->icon) }}" alt="グループアイコン" style="width: 200px; height: 200px;" class="group-icon cont-account-icon-preview" /></p>
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
		<a href="{{ url('/settings/groups/'.urlencode($group->id)) }}" class="btn btn-default">キャンセル</a>
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
