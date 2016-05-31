@extends('admin.layouts.master')

@section('content')
	<div class="padding-out">
		<div class="p-h-md p-v bg-white box-shadow pos-rlt">
			<h3 class="no-margin">Settings</h3>
		</div>
	  	<div class="box">
			<div class="col-md-3">
				<div style="background:url({{ asset('admin_assets')  }}/images/a1.jpg) center center; background-size:cover">
					<div class="p-lg bg-white-overlay text-center">
						<a href class="w-xs inline">
							<img src="{{ asset('admin_assets') }}/images/a1.jpg" class="img-circle img-responsive">
						</a>
						<div class="m-b m-t-sm h2">
							<span class="text-black">Mike</span>
						</div>
						<p>
						Great day, Great life
						</p>
					</div>
				</div>
				<ul class="nav nav-lists b-t" ui-nav>
					<li class="{{ ($activeSubMenu == 'account') ? 'active' : '' }}">
						<a href>Account Settings</a>
					</li>
					<li>
						<a href>Notifications</a>
					</li>
					<li>
						<a href>Security</a>
					</li>
					<li>
						<a href>Organizations</a>
					</li>
					<li>
						<a href>Members</a>
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