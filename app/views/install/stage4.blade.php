@extends('install.master')
@section('title')
    <title>Install Stage 3 - SolderPlus</title>
@stop

@section('stage-num')
    4
@stop

@section('form-data')

    <h3>
        The API key is necessary in order for the TechnicPlatform to hook into SolderPlus.
    </h3>
    <h5>
        Login to the Technic Platform <a href="https://www.technicpack.net/login">here</a>.<br>
        You must then click on your name in the top right -> Edit My Profile -> Solder Configuration.
    </h5>
    <div class="form-group">
        <label for="key">
            Enter the API key found on that page.
        </label>
        <input class="form-control" name="key" id="key" required>
    </div>
    <div class="form-group">
        <label for="name">
            Enter a name for this API key.
        </label>
        <input class="form-control" name="name" id="name" required>
    </div>
@stop