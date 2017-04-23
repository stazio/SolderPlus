@extends('layouts/master')
@section('title')
    <title>Create Mod - TechnicSolder</title>
@stop
@section('content')
<div class="page-header">
<h1>Mod Library</h1>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
	Add Mod
	</div>
	<div class="panel-body">
		@if ($errors->all())
            <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }}<br />
            @endforeach
            </div>
        @endif
		<form method="post" action="{{ URL::to('mod/create') }}">
		<div class="row">
			<div class="col-md-offset-3 col-md-6">
				<div class="form-group">
                    <label for="pretty_name">Mod Name</label>
                    <input type="text" class="form-control" name="pretty_name" id="pretty_name">
                </div>
                <div class="form-group">
                    <label for="name">Mod Slug</label>
                    <input type="text" class="form-control" name="name" id="name">
                </div>
                <div class="form-group">
                    <label for="author">Author</label>
                    <input type="text" class="form-control" name="author" id="author">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <label for="link">Mod Website</label>
                    <input type="text" class="form-control" name="link" id="link">
                </div>
                <div class="form-group">
                    <label for="donatelink">Author Donation Link</label>
                    <input type="text" class="form-control" name="donatelink" id="donatelink">
                    <span class="help-block">This is only used by the official Technic Solder</span>
                </div>

                {{ Form::submit('Add Mod', array('class' => 'btn btn-success')) }}
                {{ HTML::link('mod/list/', 'Go Back', array('class' => 'btn btn-primary')) }}
			</div>
		</div>
		</form>
	</div>
</div>
@endsection
@section('bottom')
<script type="text/javascript">
$("#name").slugify('#pretty_name');
$(".modslug").slugify("#pretty_name");
$("#name").keyup(function() {
	$(".modslug").html($(this).val());
});
</script>
@endsection