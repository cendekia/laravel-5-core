@extends('admin.setting.index')

@section('sub_content')
	<div class="col-md-9 b-l bg-white bg-auto">
		<div class="p-md bg-light lt b-b font-bold">Public profile</div>
		<form role="form" class="p-md col-md-6">
			<div class="form-group">
				<label>Profile picture</label>
				<div class="form-file">
					<input type="file">
					<button class="btn btn-default">Upload new picture</button>
				</div>
			</div>
			<div class="form-group">
				<label>Name</label>
				<input type="text" class="form-control">
			</div>
			<div class="form-group">
				<label>Email address</label>
				<input type="email" class="form-control">
			</div>
			<div class="form-group">
				<label>URL</label>
				<input type="text" class="form-control">
			</div>
			<div class="form-group">
				<label>Company</label>
				<input type="text" class="form-control">
			</div>
			<div class="form-group">
				<label>Location</label>
				<input type="text" class="form-control">
			</div>
			<div class="checkbox">
				<label class="ui-checks">
				<input type="checkbox"><i></i> Available for hire
				</label>
			</div>
			<button type="submit" class="btn btn-info m-t">Submit</button>
		</form>
	</div>
@endsection