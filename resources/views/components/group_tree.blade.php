<div class="group-tree">
@if( $group_tree->id == $group_id )
<span>{{ $group_tree->name }}</span>
@else
<a href="{{ url('/settings/groups/'.urlencode($group_tree->id)) }}">{{ $group_tree->name }}</a>
@endif
@if(count($group_tree->children))
<ul>
@foreach( $group_tree->children as $child )
	<li>
	@component('components.group_tree')
		@slot('group_id', $group_id)
		@slot('group_tree', $child)
	@endcomponent
	</li>
@endforeach
</ul>
@endif
</div>
