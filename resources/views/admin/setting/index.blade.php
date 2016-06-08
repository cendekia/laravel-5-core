@extends('admin.layouts.master')

@section('content')
	<div class="padding-out">
		<div class="p-h-md p-v bg-white box-shadow pos-rlt">
			<h3 class="no-margin">{{ ucwords($activeSubMenu) }}</h3>
		</div>
	  	<div class="box">
			<div class="col-md-3">
				<div style="background:url({{ asset('contents/profile_pictures/'.$admin->adminProfile->profile_picture) }}) center center; background-size:cover">
					<div class="p-lg bg-white-overlay text-center">
						<a href class="w-xs inline">
							<img src="{{ asset('contents/profile_pictures/'.$admin->adminProfile->profile_picture) }}" class="img-circle img-responsive">
						</a>
						<div class="m-b m-t-sm h2">
							<span class="text-black">{{ $admin->name }}</span>
						</div>
						<p>
						{{ $admin->roles()->first()->name }}
						</p>
					</div>
				</div>
				<ul class="nav nav-lists b-t" ui-nav>
					<li class="{{ ($activeSubMenu == 'account') ? 'active' : '' }}">
						<a href="{{ url('admin/setting/account') }}">Account Settings</a>
					</li>
					<li class="{{ ($activeSubMenu == 'members') ? 'active' : '' }}">
						<a href="{{ url('admin/setting/members') }}">Members</a>
					</li>
					<li class="{{ ($activeSubMenu == 'roles') ? 'active' : '' }}">
						<a href="{{ url('admin/setting/roles') }}">Roles</a>
					</li>
				</ul>
			</div>

			@yield('sub_content')

	  	</div>
	</div>
@endsection
