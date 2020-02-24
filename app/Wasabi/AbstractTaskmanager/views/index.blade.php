<div>
	<table class="table table__dd">
		<tbody>
			<tr>
				<th><label for="foreign_service_id">サービス名</label></th>
				<td>
					<div>{{ $pjconf->foreign_service_id }}</div>
				</td>
			</tr>
			<tr>
				<th><label for="space">スペース</label></th>
				<td>
					<div>{{ $pjconf->space }}</div>
				</td>
			</tr>
			<tr>
				<th><label for="foreign_project_id">外部サービス上のプロジェクトID</label></th>
				<td>
					<div>{{ $pjconf->{'foreign_project_id'} }}</div>
				</td>
			</tr>
		</tbody>
	</table>

	<a href="{{ url('pj/'.urlencode($project->id).'/app/AbstractTaskmanager/project_conf/edit') }}">編集</a>
</div>
