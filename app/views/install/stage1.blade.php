@extends('install.master')
@section('title')
    <title>Install Stage 1 - SolderPlus</title>
@stop

@section('stage-num')
    1
@stop

@section('form-data')
        <div class="form-group">
            <label for="driver">
                Database
            </label>
            <select class="form-control" id="driver" name="driver">
                <option value="sqlite" id="sqlite-select" selected>sqlite (Built-In)</option>
                <option value="mysql">mysql</option>
                <option value="pgsql">pgsql</option>
                <option value="sqlsrv">sqlsrv</option>
            </select>
        </div>

        <div class="not-sqlite">
        <div class="form-group" id="host-group">
            <label for="host">
                Database Host
            </label>
            <input class="form-control" name="host" id="host">
        </div>

        <div class="form-group" id="database-group">
            <label for="database">
                Database Name
            </label>
            <input class="form-control" name="database" id="database">
        </div>

        <div class="form-group" id="username-group">
            <label for="username">
                Database Username
            </label>
            <input class="form-control" name="username" id="username">
        </div>

        <div class="form-group" id="password-group">
            <label for="password">
                Database Password
            </label>
            <input class="form-control" name="password" id="password">
        </div>

        <div class="form-group" id="prefix-group">
            <label for="prefix">
                Database Prefix
            </label>
            <input class="form-control" name="prefix" id="prefix" value="solder_">
        </div>
        </div>
@stop

@section('after-form')
    <script>
        $("form").change(onChange);

        function onChange() {
            $('#sqlite-select').is(":selected") ?
                $(".not-sqlite").addClass('hidden') : $(".not-sqlite").removeClass('hidden');
        }
        onChange();
    </script>
@stop