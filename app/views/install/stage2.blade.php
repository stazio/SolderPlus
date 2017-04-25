@extends('install.master')
@section('title')
    <title>Install Stage 2 - SolderPlus</title>
@stop

@section('stage-num')
    2
@stop

@section('form-data')
    <div class="form-group">
        <label for="app_url">
            Application URL
        </label>
        <input class="form-control" type="url" name="app_url" id="app_url" value="http://localhost/">
    </div>
    <div class="form-group">
        <label for="mod_uri">
            Mod Repository Location
        </label>
        <input class="form-control" name="mod_uri" id="mod_uri" value="public/">
    </div>

    <div class="form-group">
        <label for="mirror_url">
            Mirror Location
        </label>
        <input class="form-control" name="mirror_url" id="mirror_url" value="http://localhost/">
    </div>
@stop