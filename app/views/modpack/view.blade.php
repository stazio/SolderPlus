@extends('layouts/master')
@section('title')
    <title>{{ $modpack->name }} - SolderPlus</title>
@stop
@section('content')
<h1>Modpack Management</h1>
<hr>
<div class="panel panel-default">
	<div class="panel-heading">
		<div class="pull-right">
			<a class="btn btn-primary btn-xs" href="{{ URL::to('modpack/add-build/'.$modpack->id) }}">Create New Build</a>
			<a class="btn btn-warning btn-xs" href="{{ URL::to('modpack/edit/'.$modpack->id) }}">Edit Modpack</a>
		</div>
		Managing modpack: {{ $modpack->name }}
	</div>
	<div class="panel-body">
		@if (Session::has('success'))
		<div class="alert alert-success">
			{{ Session::get('success') }}
		</div>
		@endif

		@if (!$modpack->is_on_platform)
			<div class="alert alert-danger">
				Warning! This modpack is not on the Technic Platform!
				It will not show up on the Technic Launcher!<br>

				To add it to the launcher first login <a href="https://www.technicpack.net/dashboard">here</a>.
				Then visit this <a href="https://www.technicpack.net/modpack/create/solder">page</a>
				and add this modpack ({{$modpack->name}}) to the Technic Platform.<br>

				Due to the way things work, this warning may be displayed even if you did everything correctly!
				The warning will disappear after a couple of minutes.
			</div>
		@endif

		<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover" id="dataTables">
			<thead>
				<tr>
					<th>#</th>
					<th>Build Number</th>
					<th>MC Version</th>
					<th>Mod Count</th>
					<th>Rec</th>
					<th>Latest</th>
					<th>Published</th>
					<th>Private</th>
					<th>Created on</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($modpack->builds as $build)
				<tr>
					<td>{{ $build->id }}</td>
					<td>{{ $build->version }}</td>
					<td>{{ $build->minecraft }}</td>
					<td>{{ count($build->modversions) }}</td>
					<td><input type="radio" name="recommended" value="{{ $build->version }}"{{ $checked = ($modpack->recommended == $build->version ? " checked" : "") }}></td>
					<td><input type="radio" name="latest" value="{{ $build->version }}"{{ $checked = ($modpack->latest == $build->version ? " checked" : "") }}></td>
					<td><input type="checkbox" name="published" value="1" class="published" rel="{{ $build->id }}"{{ ($build->is_published ? " checked" : "") }}></td>
					<td><input type="checkbox" name="private" value="1" class="private" rel="{{ $build->id }}"{{ ($build->private ? " checked" : "") }}></td>
					<td>{{ $build->created_at }}</td>
					<td>{{ HTML::link('modpack/build/'.$build->id, "Manage",'class="btn btn-xs btn-primary"') }}
						{{ HTML::link('modpack/build/'.$build->id.'?action=edit', "Settings",'class="btn btn-xs btn-warning"') }}
						{{ HTML::link('modpack/build/'.$build->id.'?action=delete', "Delete",'class="btn btn-xs btn-danger"') }}
						{{ HTML::link("modpack/add-build/$modpack->id?action=clone&build_id=$build->id", "Clone",'class="btn btn-xs btn-success"') }}
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
		</div>
	</div>
</div>
@endsection
@section('bottom')
<script type="text/javascript">

$("input[name=recommended]").change(function() {
	$.ajax({
		type: "GET",
		url: "{{ URL::to('modpack/modify/recommended?modpack='.$modpack->id) }}&recommended=" + encodeURIComponent($(this).val()),
		success: function (data) {
			$.jGrowl(data.success, { group: 'alert-success' });
		}
	});
});

$("input[name=latest]").change(function() {
	$.ajax({
		type: "GET",
		url: "{{ URL::to('modpack/modify/latest?modpack='.$modpack->id) }}&latest=" + encodeURIComponent($(this).val()),
		success: function (data) {
			$.jGrowl(data.success, { group: 'alert-success' });
		}
	});
});

$(".published").change(function() {
	var checked = 0;
	if (this.checked)
		checked = 1;
	$.ajax({
		type: "GET",
		url: "{{ URL::to('modpack/modify/published') }}?build=" + $(this).attr("rel") + "&published=" + checked,
		success: function (data) {
			$.jGrowl(data.success, { group: 'alert-success' });
		}
	})
});

$(".private").change(function() {
	var checked = 0;
	if (this.checked)
		checked = 1;
	$.ajax({
		type: "GET",
		url: "{{ URL::to('modpack/modify/private') }}?build=" + $(this).attr("rel") + "&private=" + checked,
		success: function (data) {
			$.jGrowl(data.success, { group: 'alert-success' });
		}
	})
});

$(document).ready(function() {
    $('#dataTables').dataTable({
    	"order": [[ 1, "desc" ]]
    });
});

</script>
@endsection
