@extends('layouts/master')
@section('title')
    <title>Auto Import</title>
@stop
@section('content')
    <div class="page-header">
        <h1>Automatic Modfile Importing</h1>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            Auto Import
        </div>
        <div class="panel-body">
        <h5>
            Import mods from the specified mods folder
        </h5>
        <div class="row">
            <div class="col-sm-offset-3 col-sm-6">
                <button class="btn btn-primary center-block"
                        style="min-height: 50px; min-width: 100px;"
                        id="begin">
                    Click to begin
                </button>
                <div id="importing" class="text-center" style="display: none;">Importing... Please wait...</div>
            </div>
        </div>

    <div class="row" style="padding-top: 30px;" id="table">
        <div class="col-sm-12">
            <table class="table table-striped table-bordered table-hover" style="display: none;">
                <thead class="header">
                <tr>
                    <th>Mod Name</th>
                    <th>Mod Slug</th>
                    <th>Author</th>
                    <th>Description</th>
                    <th>Mod Website</th>
                    <th>Author Donation Link</th>
                    <th>Mod Type</th>
                    <th>Versions</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
        </div>
    </div>

@endsection
@section('bottom')
    <script>
        $('#begin').click(function() {
            $(this).css('display', 'none');
            $("#importing").css('display', 'inherit');

            $.ajax({
                type: "POST",
                url: "{{ URL::to('mod/import') }}",
                success: function (data) {
                    if (data.status == 'success') {
                        $('table').dataTable({
                            data: data.data
                        }).css('display', '');

                        $("#importing").text('Import successful!');
                    }else if (data.status == 'warning') {
                        $.jGrowl('Warning' + ': ' + data.reason, {group: 'alert-danger'});
                    }else {
                        $.jGrowl('Error' + ': ' + data.reason, {group: 'alert-danger'});
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    $("#importing").html('Import failed.<br>' + textStatus + ': ' + errorThrown);
                    $.jGrowl(textStatus + ': ' + errorThrown, {group: 'alert-danger'});
                }
            });
        })
    </script>
@endsection
