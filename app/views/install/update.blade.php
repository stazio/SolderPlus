<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
        <title>Update SolderPlus</title>
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
</head>
<body>
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <div class="navbar-brand">
            <a class="navbar-brand"><img src="{{ URL::asset('img/solderplus.png') }}" height="41px"> {{ SOLDER_VERSION }}</a>
        </div>
    </div>
    <!-- /.navbar-top-links -->
</nav>
<!-- /.navbar-static-top -->
<div id="page-wrapper" style="margin-right:250px">
    <div class="row">
        <h1>Updating SolderPlus</h1>
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
            <button class="btn btn-primary" id="update">Click here to update</button>
            <div id="output"></div>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->

<script>
    $('#update').click(function() {
        $(this).css('display', 'none');
        $.ajax({
            type: "POST",
            url: "{{ URL::current() }}",
            success: function (data) {
                $.jGrowl('Success!', {group: 'alert-primary'})
            },
            error: function (xhr, textStatus, errorThrown) {
                $("#importing").html('Checking log status failed.<br>' + textStatus + ': ' + errorThrown);
                $.jGrowl(textStatus + ': ' + errorThrown, {group: 'alert-danger'});
            }
        });
        checkState();
    });

    function checkState() {
        $.ajax({
            type: "GET",
            url: "{{ URL::to('update_log.txt') }}",
            success: function (data) {
                $("#output").text(data);
            },
            error: function (xhr, textStatus, errorThrown) {
                $("#importing").html('Checking log status failed.<br>' + textStatus + ': ' + errorThrown);
                $.jGrowl(textStatus + ': ' + errorThrown, {group: 'alert-danger'});
            }
        });
        setTimeout(checkState, 500);
    }
</script>

<script type="text/javascript">
    (function($){
        $(function(){
            $.jGrowl.defaults.closerTemplate = '<div class="alert alert-info">Close All</div>';
        });
    })(jQuery);
</script>
</body>
</html>
