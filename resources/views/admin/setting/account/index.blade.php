@extends('admin.setting.index')

@section('sub_content')
	<div class="col-md-9 b-l bg-white bg-auto">
		<div class="p-md bg-light lt b-b font-bold">Account Setting</div>

        {!! Form::model($account, ['url' => url('admin/setting/account'), 'method' => 'post', 'class' => 'p-md col-md-6']) !!}

            @include('admin.layouts.error_and_message')

            <div class="form-group">
				<label>Profile picture</label>
				<div class="form-file">
					<input type="file">
					<button class="btn btn-default">Upload new picture</button>
				</div>
			</div>
			<div class="form-group">
				<label>Name</label>
				<input type="text" name="name" class="form-control" value="{{ $account->name }}">
			</div>
			<div class="form-group">
				<label>Email address</label>
				<input type="email" class="form-control" value="{{ $account->email }}" disabled>
			</div>
            <div class="form-group">
                <label>Current password</label>
                <input type="password" class="form-control" name="current_password">
            </div>
            <div class="form-group">
                <label>New password</label>
                <input type="password" class="form-control" name="password">
            </div>
            <div class="form-group">
                <label>Re-type password</label>
                <input type="password" class="form-control" name="password_confirmation">
            </div>
			<button type="submit" class="btn btn-info m-t">Submit</button>
		{!! Form::close() !!}

    </div>
@endsection
