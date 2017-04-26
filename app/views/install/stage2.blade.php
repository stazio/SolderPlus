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
        <p class="help-block">
            This should be the URL of where Solder is at.
        </p>
    </div>
    <div class="form-group">
        <label for="mod_uri">
            Mod Repository Location
        </label>
        <input class="form-control" name="mod_uri" id="mod_uri" value="">
        <p class="help-block">
            Where are we storing the mod files relative to the "public" folder?<br>
            Keep in mind this will automatically append "/mods/".
        </p>
    </div>

    <div class="form-group">
        <label for="mirror_url">
            Mirror Location
        </label>
        <input class="form-control" name="mirror_url" id="mirror_url" value="http://localhost/">
        <p class="help-block">
            Where is the afore-mentioned "mods" folder located as a URL?
        </p>
    </div>
@stop