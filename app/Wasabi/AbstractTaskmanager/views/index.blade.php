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
@if ($foreign_space_info)
					<div><a href="{{ $foreign_space_info->url }}" target="_blank">{{ $foreign_space_info->name }} ({{ $pjconf->space }})</a></div>
@else
					<div>{{ $pjconf->space }}</div>
@endif
				</td>
			</tr>
			<tr>
				<th><label for="foreign_project_id">外部サービス上のプロジェクトID</label></th>
				<td>
@if ($foreign_project_info)
					<div><a href="{{ $foreign_project_info->url }}" target="_blank">{{ $pjconf->{'foreign_project_id'} }}</a></div>
@else
					<div>{{ $pjconf->{'foreign_project_id'} }}</div>
@endif
				</td>
			</tr>
		</tbody>
	</table>

	<div class="text-right">
		<a href="{{ url('pj/'.urlencode($project->id).'/app/AbstractTaskmanager/project_conf/edit') }}" class="btn btn-primary">編集する</a>
	</div>
</div>
