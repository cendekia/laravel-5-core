@extends('admin.layouts.master')

@section('content')
	<div class="center-block w-xxl w-auto-xs p-v-md">
		<div class="navbar">
			<div class="navbar-brand m-t-lg text-center">
			<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve" style="
			    width: 24px; height: 24px;">
			<path d="M 50 0 L 100 14 L 92 80 Z" fill="rgba(139, 195, 74, 0.5)"></path>
			<path d="M 92 80 L 50 0 L 50 100 Z" fill="rgba(139, 195, 74, 0.8)"></path>
			<path d="M 8 80 L 50 0 L 50 100 Z" fill="#fff"></path>
			<path d="M 50 0 L 8 80 L 0 14 Z" fill="rgba(255, 255, 255, 0.6)"></path>
			</svg>
			<span class="m-l inline">Materil</span>
			</div>
		</div>

		<div class="p-lg panel md-whiteframe-z1 text-color m">
			<div class="m-b text-sm">
				Sign in with your Materil Account
			</div>

            {!! Form::open(['url' => 'admin/signin']) !!}

                @include('admin.layouts.error_and_message')

				<div class="md-form-group">
					<input type="email" name="email" class="md-input" ng-model="user.email" required>
					<label>Email</label>
				</div>
				<div class="md-form-group">
					<input type="password" name="password" class="md-input" ng-model="user.password" required>
					<label>Password</label>
				</div>
				<div class="m-b-md">
					<label class="md-check">
    					<input type="checkbox" name="remember" value="1"><i class="indigo"></i> Keep me signed in
    				</label>
				</div>
				<button md-ink-ripple type="submit" class="md-btn md-raised pink btn-block p-h-md">Sign in</button>
			{!! Form::close() !!}

        </div>

		<div class="p-v-lg text-center">
			<div class="m-b"><button ui-sref="access.forgot-password" class="md-btn">Forgot password?</button></div>
			<div>Do not have an account? <button ui-sref="access.signup" class="md-btn">Create an account</button></div>
		</div>
	</div>
@endsection
