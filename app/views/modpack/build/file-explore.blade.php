@extends('layouts/master')
@section('title')
    <title>{{ $build->version }} - {{ $build->modpack->name }} - SolderPlus</title>
@stop
@section('content')
    <div class="page-header">
        <h1>File Explorer</h1>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            {{$build->modpack->name}} - Build {{ $build->version }}

            <div class="pull-right">
                <a class="btn btn-xs btn-primary" href="{{URL::to('modpack/build/' . $build->id)}}">
                    Return to build
                </a>
                <a class="btn btn-xs btn-warning" href="{{URL::to('modpack/view/' . $build->modpack->id)}}">
                    Return to modpack
                </a>
            </div>

        </div>
        <div class="panel-body">
            @if ($errors->all())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br/>
                    @endforeach
                </div>
            @endif

            <div class="head" style="font-size: 1.5em;">
                <a href="{{URL::action("ModpackController@getFileExplorer",$build->id)}}">
                <i class="fa fa-home"></i>
                </a>
                    @for ($i = 1; $i < count($pathSplit); $i++)
                    ->
                <a href="{{URL::action("ModpackController@getFileExplorer",$build->id) .
						"?path=" . $pathSplit[$i][1]}}">
                         {{$pathSplit[$i][0]}}
                </a>

                @endfor
            </div>

            <table class="table table-striped table-hover" id="dataTables">
                <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="5%"></th>
                    <th>Name</th>
                    <th width="30%">Owning Mod(s)</th>
                    <th width="10%">Size</th>
                    <th width="8%">Actions</th>
                </tr>
                </thead>

                <tbody>
				<?php $i = 1; ?>
                @foreach ($files as $name => $file)
                    <tr>
                        @if (isset($file['size']))
                            <td>{{$i++}}</td>
                            <td>
                                <span class="hidden">File</span>
                                <i class="fa fa-file"></i>
                            </td>
                            <td>
                                {{$file['name']}}</td>
                            <td>
                                <a href="{{URL::to('/mod/view', [$file['modversion']->mod->id])}}">
                                    {{$file['modversion']->mod->pretty_name}}</a>
                            </td>
                            <td>{{$file['size']}}</td><td><a onclick="removeModversion({{$file['modversion']->id}}, this);" class="btn btn-xs btn-danger"> Remove Mod</a></td>
                        @else
                            <td>{{$i++}}</td>
                            <td>
                                <span class="hidden">Folder</span>
                                <i class="fa fa-folder"></i>
                            </td>
                            <td><a href="{{URL::action("ModpackController@getFileExplorer",$build->id) .
						"?path=$path/$name"}}">
                                    {{$name}}</a></td>
                            <td>N/A</td>
                            <td>N/A</td>
                            <td></td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('bottom')
    <script type="text/javascript">
        var build = "{{$build->id}}";
        function removeModversion(id, td) {
            $.ajax({
                type: "POST",
                url: "{{ URL::to('modpack/modify/delete') }}",
                data: {
                    'build_id': build,
                    'modversion_id' : id
                },
                success: function (data) {
                    console.log(data);
                    if (data.status === 'success') {
                        $.jGrowl("Mod removed", {group: 'alert-success'});
                        location.reload();
                    } else if (data.status === 'failed') {
                        $.jGrowl("Unable to remove mod", {group: 'alert-warning'});
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    $.jGrowl(textStatus + ': ' + errorThrown, {group: 'alert-danger'});
                }
            });
        }

        $("#dataTables").dataTable({
            'paging': false,
            'order' : [2, 'asc']
        });
    </script>
@endsection
