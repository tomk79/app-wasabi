<div>
	<form action="{{ url('pj/'.urlencode($project->id).'/app/AbstractTaskmanager/project_conf/edit') }}" method="post">
		@csrf
		@method('POST')

		<table class="table table__dd">
			<tbody>
				<tr>
					<th><label for="foreign_service_id">サービス名</label></th>
					<td>
						<select name="foreign_service_id" class="form-control{{ $errors->has('foreign_service_id') ? ' is-invalid' : '' }}" required autofocus>
							<option value="backlog" @if ( old('foreign_service_id', $pjconf->foreign_service_id) == 'backlog') selected @endif>Backlog</option>
						</select>
							@if ($errors->has('foreign_service_id'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('foreign_service_id') }}
								</span>
							@endif
					</td>
				</tr>
				<tr>
					<th><label for="space">スペース</label></th>
					<td>
						<input id="space" type="text" class="form-control @if ($errors->has('space')) is-invalid @endif" name="space" value="{{ old('space', $pjconf->space) }}" autofocus>
							@if ($errors->has('space'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('space') }}
								</span>
							@endif
					</td>
				</tr>
				<tr>
					<th><label for="foreign_project_id">外部サービス上のプロジェクトID</label></th>
					<td>
						<input id="foreign_project_id" type="text" class="form-control{{ $errors->has('foreign_project_id') ? ' is-invalid' : '' }}" name="foreign_project_id" value="{{ old('foreign_project_id', $pjconf->{'foreign_project_id'}) }}" required autofocus>

							@if ($errors->has('foreign_project_id'))
								<span class="invalid-feedback" role="alert">
									{{ $errors->first('foreign_project_id') }}
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
		<a href="{{ url('/pj/'.urlencode($project->id).'/app/AbstractTaskmanager') }}" class="btn btn-default">キャンセル</a>
	</div>
</div>
