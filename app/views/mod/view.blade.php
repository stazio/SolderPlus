@extends('layouts/master')
@section('title')
    <title>{{ empty($mod->pretty_name) ? $mod->name : $mod->pretty_name }} - SolderPlus</title>
@stop
@section('content')
    <div class="page-header">
        <h1>Mod Library</h1>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            @if (!empty($mod->pretty_name))
                {{ $mod->pretty_name }}
                <small>{{ $mod->name }}</small>
            @else
                {{ $mod->name }}
            @endif
        </div>
        <div class="panel-body">
            <ul class="nav nav-tabs" id="tabs">
                <li class="active"><a href="#versions" data-toggle="tab">Versions</a></li>
                <li><a href="#details" data-toggle="tab">Details</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade" id="details">
                    <br>
                    @if ($errors->all())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br/>
                            @endforeach
                        </div>
                    @endif
                    @if (Session::has('success'))
                        <div class="alert alert-success">
                            {{ Session::get('success') }}
                        </div>
                    @endif
                    <form method="post" action="{{ URL::to('mod/modify/'.$mod->id) }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pretty_name">Mod Name</label>
                                    <input type="text" class="form-control" name="pretty_name" id="pretty_name"
                                           value="{{ $mod->pretty_name }}">
                                </div>
                                <div class="form-group">
                                    <label for="name">Mod Slug</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                           value="{{ $mod->name }}">
                                </div>
                                <div class="form-group">
                                    <label for="author">Author</label>
                                    <input type="text" class="form-control" name="author" id="author"
                                           value="{{ $mod->author }}">
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control"
                                              rows="5">{{ $mod->description }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="link">Mod Website</label>
                                    <input type="text" class="form-control" name="link" id="link"
                                           value="{{ $mod->link }}">
                                </div>
                                <div class="form-group">
                                    <label for="donatelink">Author Donation Link</label>
                                    <input type="text" class="form-control" name="donatelink" id="donatelink"
                                           value="{{ $mod->donatelink }}">
                                    <span class="help-block">This is only used by the Technic Platform.</span>
                                </div>
                                <div class="form-group">
                                    <label for="mod_type">Mod Type</label>
                                    <select id=mod_type name="mod_type" class="form-control">
                                        <option value="{{Mod::MOD_TYPE_UNIVERSAL}}">Universal</option>
                                        <option value="{{Mod::MOD_TYPE_CLIENT}}">Client</option>
                                        <option value="{{Mod::MOD_TYPE_SERVER}}">Server</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        {{ Form::submit('Save Changes', array('class' => 'btn btn-success')) }}
                        {{ HTML::link('mod/delete/'.$mod->id, 'Delete Mod', array('class' => 'btn btn-danger')) }}
                        {{ HTML::link('mod/list/', 'Go Back', array('class' => 'btn btn-primary')) }}
                    </form>
                </div>
                <div class="tab-pane fade in active" id="versions">
                    <br>
                    <blockquote><strong>/mods/[modslug]/[modslug]-[version].zip</strong></blockquote>
                    <table class="table">
                        <thead>
                        <tr>
                            <th style="width: 1%"></th>
                            <th style="width: 15%">Version</th>
                            <th style="width: 22%">MD5</th>
                            <th style="width: 22%">Download URL</th>
                            <th style="width: 9%">Filesize</th>
                            <th style="width: 25%"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr id="add-row">
                            <form method="post" id="add" action="{{ URL::to('mod/add-version') }}">
                                <input type="hidden" name="mod-id" value="{{ $mod->id }}">
                                <td></td>
                                <td>
                                    <input type="text" name="add-version" id="add-version" class="form-control"></td>
                                <td>
                                    <input type="text" name="add-md5" id="add-md5" class="form-control"></td>
                                </td>
                                <td><span id="add-url">N/A</span></td>
                                <td>N/A</td>

                                <td>
                                    @if(ModController::canUpload())
                                    <label class="btn btn-primary btn-sm" for="file">
                                        <input name="modfile" id="file" type="file" style="display:none;">
                                        File
                                    </label>
                                    <button type="submit" class="btn btn-success btn-sm add">Add/Upload</button>
                                    <div class="row">
                                        <div class="col">
                                            <small class="text-muted" id="file-name"></small>
                                        </div>
                                    </div>
                                    @else
                                        <button type="submit" class="btn btn-success btn-sm add">Add</button>
                                    @endif
                                </td>
                            </form>
                        </tr>
                        @foreach ($mod->versions()->orderBy('id', 'desc')->get() as $ver)
                            <tr class="version" rel="{{ $ver->id }}">
                                <form method="post" id="rehash" action="{{ URL::to('mod/rehash/') }}">
                                    <input type="hidden" name="version-id" value="{{ $ver->id }}">
                                    <td><i class="version-icon fa fa-plus" rel="{{ $ver->id }}"></i></td>
                                    <td class="version" rel="{{ $ver->id }}">{{ $ver->version }}</td>
                                    <td><input type="text" class="md5 form-control" name="md5" id="md5"
                                               placeholder="{{ $ver->md5 }}" rel="{{ $ver->id }}"></td>
                                    <td class="url" rel="{{ $ver->id }}">
                                        <small>
                                            <a href="{{ Config::get('solder.mirror_url').'mods/'.$mod->name.'/'.$mod->name.'-'.$ver->version.'.zip' }}">{{ Config::get('solder.mirror_url').'mods/'.$mod->name.'/'.$mod->name.'-'.$ver->version.'.zip' }}</a>
                                        </small>
                                    </td>
                                    <td>{{ $ver->humanFilesize("MB") }}</td>

                                    <td>
                                        <button type="submit" class="btn btn-primary btn-xs rehash"
                                                rel="{{ $ver->id }}">Rehash
                                        </button>
                                        <button class="btn btn-danger btn-xs delete" rel="{{ $ver->id }}">Delete
                                        </button>
                                    </td>
                                </form>
                            </tr>
                            @if (count($ver->builds) > 0)
                                <tr class="version-details" rel="{{ $ver->id }}" >
                                    <td colspan="5">
                                        <h5>Builds Used In</h5>
                                        <ul>
                                            @foreach ($ver->builds as $build)
                                                <li>{{ HTML::link('modpack/view/'.$build->modpack->id,$build->modpack->name) }}
                                                    - {{ HTML::link('modpack/build/'.$build->id,$build->version) }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('bottom')
    <script type="text/javascript">

        var mirror_url = '{{ Modfile::getModFolderURL() }}';

        $('#add-version').keyup(function () {
            $("#add-url").html('<a href="' + mirror_url + '{{ $mod->name }}/{{ $mod->name }}-' + $(this).val() + '.zip" target="_blank">' + mirror_url + 'mods/{{ $mod->name }}/{{ $mod->name }}-' + $(this).val() + '.zip</a>');
        });

        $('#add').submit(function (e) {
            e.preventDefault();
            console.log('hello');
            var fd = new FormData(document.querySelector("#add"));
            console.log(document.getElementsByName("modfile")[0].files[0]);
            fd.append('modfile', document.getElementsByName("modfile")[0].files[0]);
            var xml = new XMLHttpRequest();
            xml.responseType = "json";
            xml.addEventListener("load", function () {
                console.log(xml);
                var data = xml.response;
                if (xml.status === 200) {
                    if (xml.response.status === "success") {
                        $("#add-row").after('<tr><td></td><td>' + data.version + '</td><td>' + data.md5 + '</td><td><a href="' + mirror_url + 'mods/{{ $mod->name }}/{{ $mod->name }}-' + data.version + '.zip" target="_blank">mods/{{ $mod->name }}/{{ $mod->name }}-' + data.version + '.zip</a></td><td>' + data.filesize + '</td><td></td></tr>');
                        $.jGrowl('Added mod version at ' + data.version, {group: 'alert-success'});
                    } else if (xml.response.status === "warning") {
                        $("#add-row").after('<tr><td></td><td>' + data.version + '</td><td>' + data.md5 + '</td><td><a href="' + mirror_url + 'mods/{{ $mod->name }}/{{ $mod->name }}-' + data.version + '.zip" target="_blank">' + mirror_url + 'mods/{{ $mod->name }}/{{ $mod->name }}-' + data.version + '.zip</a></td><td>' + data.filesize + '</td><td></td></tr>');
                        $.jGrowl('Added mod version at ' + data.version + ". " + data.reason, {group: 'alert-warning'});
                    } else {
                        if (data.reason)
                            $.jGrowl('Error: ' + data.reason, {group: 'alert-danger'});
                        else
                            $.jGrowl('Error: ' + data, {group: 'alert-danger'});
                    }
                } else {
                    if (data.reason)
                    $.jGrowl('Error: ' + data.reason, {group: 'alert-danger'});
                    else
                        $.jGrowl('Error: ' + data, {group: 'alert-danger'});
                }
            });
            xml.open('post', "{{ URL::to('mod/add-version/') }}", true);
            xml.send(fd);

            return false;
            console.log($("#add").serialize());
            if ($('#add-version').val() != "") {

                console.log(document.querySelector('#add'));
                var data = new FormData(document.querySelector('#add'));
                var modfile = document.getElementsByName("modfile")[0];
                //if (modfile.files.length > 0)
                //data.append('modfile', document.getElementsByName("modfile")[0].files[0]);
                //data.append('mod-id', $("#mod").val());
                //data.append('add-version', $("#add-version").val());
                //data.append('add-md5', $("#add-md5").val());
                console.log(data.keys());

                $.ajax({
                    type: "POST",
                    url: "{{ URL::to('mod/add-version/') }}",
                    data: data,
                    success: function (data) {
                        if (data.status == "success") {
                            $("#add-row").after('<tr><td></td><td>' + data.version + '</td><td>' + data.md5 + '</td><td><a href="' + mirror_url + 'mods/{{ $mod->name }}/{{ $mod->name }}-' + data.version + '.zip" target="_blank">{mods/{{ $mod->name }}/{{ $mod->name }}-' + data.version + '.zip</a></td><td>' + data.filesize + '</td><td></td></tr>');
                            $.jGrowl('Added mod version at ' + data.version, {group: 'alert-success'});
                        } else if (data.status == "warning") {
                            $("#add-row").after('<tr><td></td><td>' + data.version + '</td><td>' + data.md5 + '</td><td><a href="' + mirror_url + 'mods/{{ $mod->name }}/{{ $mod->name }}-' + data.version + '.zip" target="_blank">' + mirror_url + 'mods/{{ $mod->name }}/{{ $mod->name }}-' + data.version + '.zip</a></td><td>' + data.filesize + '</td><td></td></tr>');
                            $.jGrowl('Added mod version at ' + data.version + ". " + data.reason, {group: 'alert-warning'});
                        } else {
                            $.jGrowl('Error: ' + data.reason, {group: 'alert-danger'});
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        $.jGrowl(textStatus + ': ' + errorThrown, {group: 'alert-danger'});
                    }
                })
            }
        });

        $('.version-icon').click(function () {
            $('.version-details[rel=' + $(this).attr('rel') + "]").toggle(function () {
                $('.version-icon[rel=' + $(this).attr('rel') + "]").toggleClass("fa-minus");
            });
        });

        $('.rehash').click(function (e) {
            e.preventDefault();
            $(".md5[rel=" + $(this).attr('rel') + "]").fadeOut();

            console.log($("#rehash").serialize());
            $.ajax({
                type: "POST",
                url: "{{ URL::to('mod/rehash/') }}",
                data: $("#rehash").serialize(),
                success: function (data) {
                    if (data.status == "success") {
                        $.jGrowl('MD5 hashing complete.', {group: 'alert-success'});
                    } else if (data.status == "warning") {
                        $.jGrowl('MD5 hashing complete. ' + data.reason, {group: 'alert-warning'});
                    } else {
                        $.jGrowl('Error: ' + data.reason, {group: 'alert-danger'});
                    }
                    $(".md5[rel=" + data.version_id + "]").attr('placeholder', data.md5);
                    $(".md5[rel=" + data.version_id + "]").fadeIn();
                },
                error: function (xhr, textStatus, errorThrown) {
                    $.jGrowl(textStatus + ': ' + errorThrown, {group: 'alert-danger'});
                }
            });
        });

        $('.delete').click(function (e) {
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: "{{ URL::to('mod/delete-version') }}/" + $(this).attr('rel'),
                success: function (data) {
                    if (data.status == "success") {
                        $('.version[rel=' + data.version_id + ']').fadeOut();
                        $('.version-details[rel=' + data.version_id + ']').fadeOut();
                        $.jGrowl('Mod version ' + data.version + ' deleted.', {group: 'alert-success'});
                    } else {
                        $.jGrowl('Error: ' + data.reason, {group: 'alert-danger'});
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    $.jGrowl(textStatus + ': ' + errorThrown, {group: 'alert-danger'});
                }
            });
        });

        $('#file').on('change', function () {
            var val = $(this).val().split("\\");
            $("#file-name").text(val[val.length - 1]);
        });

    </script>
@endsection
