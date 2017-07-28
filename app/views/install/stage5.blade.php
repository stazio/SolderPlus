@extends('install.master')
@section('title')
    <title>Install Stage 3 - SolderPlus</title>
@stop

@section('stage-num')
    5
@stop

@section('form-data')

    <h5>
        Then you must copy this url: <a href="#">{{URL::to('/')}}/api/</a> into the Solder URL box,
        and then press the Link Solder button.<br>
        If it does not work, make sure that you are viewing this page from the outside -- Meaning, if you are hosting
        this from home, you have port forwarded this, and that the URL is your
        <a href="https://www.google.com/search?q=what+is+my+ip">external IP address.</a>
    </h5>

@stop