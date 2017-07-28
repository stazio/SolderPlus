<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    @section('title')
        <title>SolderPlus {{ SOLDER_VERSION }}</title>
    @show
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{{ asset('favicon.ico') }}}">
    <script src="{{{ asset('js/jquery-1.11.1.min.js') }}}"></script>
    <script src="{{{ asset('js/bootstrap.min.js') }}}"></script>
    <script src="{{{ asset('js/jquery.jgrowl.min.js') }}}"></script>
    <link href="{{{ asset('css/bootstrap.min.css') }}}" rel="stylesheet">
    <link href="{{{ asset('font-awesome/css/font-awesome.css') }}}" rel="stylesheet">
    <link href="{{{ asset('css/sb-admin.css') }}}" rel="stylesheet">
    <link href="{{{ asset('css/solder.css') }}}" rel="stylesheet">
    <script src="{{{ asset('js/plugins/metisMenu/jquery.metisMenu.js') }}}"></script>
    <script src="{{{ asset('js/sb-admin.js') }}}"></script>
    <script src="{{{ asset('js/plugins/dataTables/jquery.dataTables.js') }}}"></script>
    <script src="{{{ asset('js/plugins/dataTables/dataTables.bootstrap.js') }}}"></script>
    <link href="{{{ asset('css/dataTables.bootstrap.css') }}}" rel="stylesheet">
    <script src="{{{ asset('js/jquery.slugify.js') }}}"></script>
    <script src="{{{ asset('js/nav-float.js') }}}"></script>
    <link href="{{{ asset('css/OpenSansfont.css') }}}" rel="stylesheet">
    @yield('top')
</head>
<body>
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <div class="navbar-brand">
            <a class="navbar-brand"><img src="{{ URL::asset('img/solderplus.png') }}" height="41px"> {{ SOLDER_VERSION }}</a>
        </div>

    </div>
    <ul class="nav navbar-top-links navbar-left">
        @if (Cache::get('update'))
            <li>
                <div style="color:orangered;">
                    Update Available! <i class="fa fa-exclamation-triangle"></i>
                </div>
            </li>
        @endif
    </ul>
    <!-- /.navbar-top-links -->

    @section('nav')
</nav>
<!-- /.navbar-static-top -->

@show
<div id="page-wrapper" style="margin-right:250px">
    <div class="row">
        <h1>Installation - Stage @yield('stage-num') of 5</h1>
    </div>

    <div class="row">
        @if($errors->any())
            <div class="alert-warning">
            @foreach($errors->all() as $error)
                {{$error}}
            @endforeach
            </div>
        @endif
    </div>

    <div class="row">
        <div class="col-lg-12">
            @yield('before-form')
            <form id="form" method="post">
                {{Form::token()}}
                @yield('form-data')
                <input type="submit" class="btn btn-success" id="submit" value="Next">
            </form>
            @yield('after-form')
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->
<script type="text/javascript">
    (function($){
        $(function(){
            $.jGrowl.defaults.closerTemplate = '<div class="alert alert-info">Close All</div>';
        });
    })(jQuery);
</script>
@yield('bottom')
</body>
</html>
