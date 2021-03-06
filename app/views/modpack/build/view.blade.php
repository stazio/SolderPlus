@extends('layouts/master')
@section('title')
    <title>{{ $build->version }} - {{ $build->modpack->name }} - SolderPlus</title>
@stop
@section('top')
    <script src="{{{ asset('js/selectize.min.js') }}}"></script>
    <link href="{{{ asset('css/selectize.css') }}}" rel="stylesheet">
@endsection
@section('content')
<div class="page-header">
<h1>Build Management</h1>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
	<div class="pull-right">
		<a href="{{ URL::current() }}" class="btn btn-xs btn-warning">Refresh</a>
        <a href="{{ URL::to("modpack/file-explorer/".$build->id) }}" class="btn btn-xs btn-primary">File Explorer</a>
		<a href="{{ URL::to('modpack/build/' . $build->id . '?action=edit') }}" class="btn btn-xs btn-danger">Edit</a>
	    <a href="{{ URL::to('modpack/view/' . $build->modpack->id) }}" class="btn btn-xs btn-info">Back to Modpack</a>
        <a href="{{ URL::to("modpack/add-build/".$build->modpack->id."?action=clone&build_id=$build->id") }}" class="btn btn-xs btn-success">Clone</a>
    </div>
	Build Info: {{ $build->modpack->name }} - Build {{ $build->version }}
	</div>
	<div class="panel-body">
		<div class="col-md-6">
			<label>Build Version:
				<span class="label label-default">{{ $build->version }}
				</span></label><br>
			<label>Minecraft Version: <span class="label label-default">{{ $build->minecraft }}</span></label><br>
			<div class="form-inline">
				<label for="latest">
					Latest:
				</label>
				@if($build->modpack->latest == $build->version)
					<span class="label label-default">Yes</span>
				@else
					<select onchange="update('latest', $(this).val())" id="latest"
							class="form-control input-sm">
						<option>No</option>
						<option value="{{$build->version}}">Yes</option>
					</select>
				@endif
			</div>
			<div class="form-inline">
				<label for="published">
					Published:
				</label>
				<select onchange="update('published', $(this).val())" id="published" class="form-control input-sm keep-select">
					<option value="1"  {{$build->is_published == true ? "selected" : ""}}>Yes</option>
					<option value="0" {{$build->is_published != true ? "selected" : ""}}>No</option>
				</select>
			</div>
		</div>
		<div class="col-md-6">
			<label>Java Version: <span class="label label-default">{{ !empty($build->min_java) ? $build->min_java : 'Not Required'  }}</span></label><br>
			<label>Memory (<i>in MB</i>): <span class="label label-default">{{ $build->min_memory != 0 ? $build->min_memory : 'Not Required' }}</span></label>
			<div class="form-inline">
				<label for="recommended">
					Recommended:
				</label>
				@if($build->modpack->recommended == $build->version)
					<span class="label label-default">Yes</span>
				@else
				<select onchange="update('recommended', $(this).val())" id="recommended"
						class="form-control input-sm">
						<option>No</option>
						<option value="{{$build->version}}">Yes</option>
				</select>
				@endif
			</div>
		</div>

        <div class="col-xs-12">
            <div class="form-inline">
                <label for="server_pack">
                    Server Pack:
                </label>
                <select onchange="update('server_pack', $(this).val()); doServerPackCheck();" id="server_pack" name="server_pack"
                        class="form-control input-sm keep-select">
                    <option value="0" {{!$build->is_server_pack ? "selected" : ""}}>No</option>
                    <option value="1" {{$build->is_server_pack ? "selected" : ""}}>Yes</option>
                </select>

            </div>
            <div class="showIfServerPack">
                <div class="server-pack-options" style="display: none;">
                    <div class="option-0">
                        Server pack failed being build.
                        <a class="btn btn-primary btn-sm" href="#" onclick="buildServerPack();">Click</a> to try again.
                    </div>
                    <div class="option-1">
                        Server pack is being built. Please wait this may take a while...
                    </div>
                    <div class="option-2">
                        Server Pack generated. Follow this link to download: <a href="{{$build->server_pack_url}}">{{$build->server_pack_url}}</a>
                        <br><a class="btn btn-primary btn-sm" href="#" onclick="buildServerPack();">Click</a> to rebuild.
                    </div>
                    <div class="option-3">
                    Server Pack needs to be built.
                    <a class="btn btn-primary btn-sm" href="#" onclick="buildServerPack();">Click</a> to rebuild.
                    </div>
                </div>
            </div>
        </div>
	</div>

</div>

<div class="panel panel-default">
	<div class="panel-heading">
	Build Management: {{ $build->modpack->name }} - Build {{ $build->version }}
	</div>
	<div class="panel-body">
		<div class="table-responsive">
			<form method="post" action="{{ URL::to('modpack/build/modify') }}" class="mod-add">

			<table class="table">
			<thead>
				<tr>
				<th style="width: 60%">Add a Mod</th>
				<th></th>
				<th></th>
				</tr>
			</thead>
			<tbody>
			<input type="hidden" name="build" value="{{ $build->id }}">
			<input type="hidden" name="action" value="add">
			<tr id="mod-list-add">
				@if(Mod::all()->isEmpty())
					<td>
						No Mods Found.
						{{HTML::link('mod/create', 'Add A Mod', ['class' => 'btn btn-success btn-sm'])}}
					</td>

					@else
				<td>
					<i class="icon-plus"></i>
					<select class="form-control" name="mod-name" id="mod" placeholder="Select a Mod...">
                        <?php /** @var Build $build */ ?>
						@foreach (Mod::notInBuild($build) as $mod)
                                <option value="{{ $mod->name }}">{{ $mod->pretty_name }}</option>
						@endforeach
					</select>
				</td>
				<td>
					<select class="form-control" name="mod-version" id="mod-version" placeholder="Select a Modversion...">
					</select>
				</td>
				<td>
					<button type="submit" class="btn btn-success btn-small">Add Mod</button>
				</td>
				@endif
			</tr>
			</tbody>
		</table>
			</form>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
	Build Management: {{ $build->modpack->name }} - {{ $build->version }}
	</div>
	<div class="panel-body">
        <form class="form-group form-inline" onchange="$(this).submit();">
                <label class="radio-inline">
                    <input type="radio" name="filter" value=""
                            {{Input::get('filter', '') == "" ? "checked" : ""}}>
                    All Mods
                </label>

                <label class="radio-inline">
                <input type="radio" name="filter" value="updatable"
                        {{Input::get('filter', '') == "updatable" ? "checked" : ""}}>
                    Updatable Mods Only
                </label>
        </form>

		<div class="table-responsive">
		<table class="table" id="mod-list">
			<thead>
			<tr>
				<th id="mod-header" style="width: 60%">Mod Name</th>
				<th>Version</th>
				<th></th>
			</tr>
			</thead>
			<tbody>
				@foreach ($build->modversions->sortByDesc('build_id', SORT_NATURAL) as $ver)
                    @if(Input::get('filter', '') == 'updatable' &&
                        end($ver->mod->versions)[0]->id == $ver->id)
                        <?php continue; ?>
                    @endif
				<tr>
					<td>{{ HTML::link('mod/view/'.$ver->mod->id, $ver->mod->pretty_name) }} ({{ $ver->mod->name }})</td>
					<td>
						<form method="post" outdated=" ? "false" : "true"}}" action="{{ URL::to('modpack/build/modify') }}" style="margin-bottom: 0" class="mod-version">
							<input type="hidden" class="build-id" name="build_id" value="{{ $build->id }}">
							<input type="hidden" class="modversion-id" name="modversion_id" value="{{ $ver->pivot->modversion_id }}">
							<input type="hidden" name="action" value="version">
							<div class="form-group input-group">
								<select class="form-control" name="version">
									@foreach ($ver->mod->versions as $version)
									<option value="{{ $version->id }}"{{ $selected = ($ver->version == $version->version ? 'selected' : '') }}>{{ $version->version }}</option>
									@endforeach
								</select>
								<span class="input-group-btn">
									<button id="add-mod-submit" type="submit" class="btn btn-primary">Change</button>
								</span>
							</div>
						</form>
					</td>
					<td>
						<form method="post" action="{{ URL::to('modpack/build/modify') }}" style="margin-bottom: 0" class="mod-delete">
							<input type="hidden" name="build_id" value="{{ $build->id }}">
							<input type="hidden" class="modversion-id" name="modversion_id" value="{{ $ver->pivot->modversion_id }}">
							<input type="hidden" name="action" value="delete">
							<button type="submit" class="btn btn-danger btn-small">Remove</button>
						</form>
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
    function doServerPackCheck() {
        if ($("#server_pack :selected").val()== "0") {
            $(".showIfServerPack").css("display", "none");
        }else
            $(".showIfServerPack").css("display", "inherit");
    }
    $().ready(doServerPackCheck);

    function buildServerPack() {
        $.ajax({
            type: "GET",
            url: "{{URL::action('ModpackController@getBuildServerPack', [$build->id])}}",
            beforeSend: function() {
                showPackState(1);
            },
            success: function (data) {
                console.log(data);
                if ('success' in data) {
                    $.jGrowl("Modpack updated", {group: 'alert-success'});
                    showPackState(2);
                } else {
                    $.jGrowl("Unable to change settings", {group: 'alert-warning'});
                    showPackState(0);
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                $.jGrowl(textStatus + ': ' + errorThrown, {group: 'alert-danger'});
                showPackState(0);
            }
        });
    }

    // 0 - fail
    // 1 - generating
    // 2 - generated
    // 3 - needs
    function showPackState(state) {
        $(".server-pack-options").css('display', 'inherit');
        $(".server-pack-options").children().css('display', 'none');
        $(".server-pack-options .option-" + state).css('display', 'inherit');
    }showPackState({{$build->server_pack_is_built ? 2 : 3}});

if(
    $("#mod").length) {
    var mod = $("#mod").selectize({
        dropdownParent: "body",
        persist: false,
        maxItems: 1,
        sortField: {
            field: 'text',
            direction: 'asc'
        }
    })[0].selectize;



    var modversion = $("#mod-version").selectize({
        dropdownParent: "body",
        persist: false,
        maxItems: 1,
        sortField: {
            field: 'text',
            direction: 'asc'
        }
    })[0].selectize;

    function update(key, value) {
        var url = {
            "latest": "{{URL::to("modpack/modify/latest")}}",
            "recommended": "{{URL::to("modpack/modify/recommended")}}",
            "published": "{{URL::to("modpack/modify/published")}}",
            "server_pack" : "{{URL::to("modpack/modify/server_pack")}}"
        };

        var data = {
            "build": "{{ $build->id }}",
            "action": key,
            "modpack": "{{$build->modpack->id}}"
        };
        data[key] = value;
        $.ajax({
            type: "POST",
            url: url[key],
            data: data,
            success: function (data) {
                console.log(data);
                if ('success' in data) {
                    $.jGrowl("Modpack updated", {group: 'alert-success'});
                    if (!$("#" + key).hasClass('keep-select'))
                        document.getElementById(key).outerHTML = "<span class='label label-default'>Yes</span>";
                } else {
                    $.jGrowl("Unable to change settings", {group: 'alert-warning'});
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                $.jGrowl(textStatus + ': ' + errorThrown, {group: 'alert-danger'});
            }
        });
    }

    $(".mod-version").submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "{{ URL::to('modpack/modify/version') }}",
            data: $(this).serialize(),
            success: function (data) {
                console.log(data);
                if (data.status == 'success') {
                    $.jGrowl("Modversion Updated", {group: 'alert-success'});
                } else if (data.status == 'failed') {
                    $.jGrowl("Unable to update modversion", {group: 'alert-warning'});
                } else if (data.status == 'aborted') {
                    $.jGrowl("Mod was already set to that version", {group: 'alert-success'});
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                $.jGrowl(textStatus + ': ' + errorThrown, {group: 'alert-danger'});
            }
        });
    });

    $(".mod-delete").submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "{{ URL::to('modpack/modify/delete') }}",
            data: $(this).serialize(),
            success: function (data) {
                console.log(data.reason);
                if (data.status == 'success') {
                    $.jGrowl("Modversion Deleted", {group: 'alert-success'});
                } else {
                    $.jGrowl("Unable to delete modversion", {group: 'alert-warning'});
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                $.jGrowl(textStatus + ': ' + errorThrown, {group: 'alert-danger'});
            }
        });
        $(this).parent().parent().fadeOut();
    });

    $(".mod-add").submit(function (e) {
        e.preventDefault();
        if ($("#mod-version").val()) {
            $.ajax({
                type: "POST",
                url: "{{ URL::to('modpack/modify/add') }}",
                data: $(this).serialize(),
                success: function (data) {
                    if (data.status === 'success') {
                        $("#mod-list-add").after('<tr><td>' + data.pretty_name + '</td><td>' + data.version + '</td><td></td></tr>');
                        $.jGrowl("Mod " + data.pretty_name + " added at " + data.version, {group: 'alert-success'});
                        mod.removeOption(mod.items[0]);
                    } else {
                        $.jGrowl("Unable to add mod. Reason: " + data.reason, {group: 'alert-warning'});
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    $.jGrowl(textStatus + ': ' + errorThrown, {group: 'alert-danger'});
                }
            });

        } else {
            $.jGrowl("Please select a Modversion", {group: 'alert-warning'});
        }
    });

    function refreshModVersions() {
        modversion.disable();
        modversion.clearOptions();
        $.ajax({
            type: "GET",
            url: "{{ URL::to('api/mod/') }}/" + mod.getValue(),
            success: function (data) {
                if (data.versions.length === 0) {
                    $.jGrowl("No Modversions found for " + data.pretty_name, {group: 'alert-warning'});
                    $("#mod-version").attr("placeholder", "No Modversions found...");
                } else {
                    $(data.versions).each(function (e, m) {
                        modversion.addOption({value: m, text: m});
                        modversion.refreshOptions(false);
                        $("#mod-version").attr("placeholder", "Select a Modversion...");
                    });
                    modversion.addItem(modversion.objects[Object.keys(modversion.objects)[Object.keys(modversion.objects).length - 1]]);
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                $.jGrowl(textStatus + ': ' + errorThrown, {group: 'alert-danger'});
            }
        });
        modversion.enable();
    }

    mod.on('change', refreshModVersions);
    $(document).ready(function(){
        refreshModVersions();
	});
}
var $table;
$( document ).ready(function() {
	$table = $("#mod-list").DataTable({
    	"order": [[ 0, "asc" ]],
    	"autoWidth": false,
    	"columnDefs": [
			{ "width": "60%", "targets": 0 },
			{ "width": "30%", "targets": 1 }
		    ]
    });
});
</script>
@endsection
