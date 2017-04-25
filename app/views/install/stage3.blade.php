@extends('install.master')
@section('title')
    <title>Install Stage 3 - SolderPlus</title>
@stop

@section('stage-num')
    3
@stop

@section('form-data')
    <div class="form-group">
        <label for="email">
            Email Address
        </label>
        <input class="form-control" type="email" name="email" id="email" required>
    </div>
    <div class="form-group">
        <label for="username">
            Name
        </label>
        <input class="form-control" name="username" id="username" required>
    </div>
    <div class="form-group">
        <label for="password">
            Password
        </label>
        <input class="form-control" type="password" name="password" id="password" required>

    </div>

    <div class="form-group" id="password-confirm-group">
        <label for="password-confirm">
            Password Confirmation
        </label>
        <input class="form-control" type="password" id="password-confirm" required>
        <div class="control-label hidden">
            Passwords do not match.
        </div>
    </div>

@stop

@section('after-form')
    <script>
        $('form').submit(function(e) {
            if ($("#password").val() != $("#password-confirm").val()) {
                $("#password-confirm-group").addClass('has-error');
                $(".control-label").removeClass('hidden');
                e.preventDefault();
            }
        });
    </script>
    @stop