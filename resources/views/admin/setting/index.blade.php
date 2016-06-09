@extends('admin.layouts.master')

@section('content')
	<div class="padding-out">
		<div class="p-h-md p-v bg-white box-shadow pos-rlt">
			<h3 class="no-margin">{{ ucwords($activeSubMenu) }}</h3>
		</div>
	  	<div class="box">
			<div class="col-md-3">
				<div style="background:url({{ asset('contents/profile_pictures/'.@$admin->adminProfile->profile_picture) }}) center center; background-size:cover">
					<div class="p-lg bg-white-overlay text-center">
						<a href class="w-xs inline">
                            @if ($admin->adminProfile)
				                <img src="{{ asset('contents/profile_pictures/'.$admin->adminProfile->profile_picture) }}" class="img-circle img-responsive">
                            @else
                                <i class="glyphicon glyphicon-user" style="font-size: 70px;color: grey;"></i>
                            @endif
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
                    <li class="{{ Attr::isActive('account', $activeSubMenu) }}">
                        <a href="{{ url('admin/setting/account') }}">Account Settings</a>
                    </li>

                    <?php $urlList = \Admin::adminUrlList(); ?>

                    @foreach(\Admin::adminRouteList() as $nav => $routes)
                        <?php
                            $navCheck = explode('.', $nav);
                            $isNotMainNav = config('app.admin.not_main_route');
                            $activeSubMenu = 'setting.'.$activeSubMenu;
                        ?>
                        @if (in_array($navCheck[0], $isNotMainNav) && \Admin::isHasAccess($routes, $admin))
                            <li class="{{ Attr::isActive($nav, $activeSubMenu) }}">
                                <a md-ink-ripple href="{{ $urlList[$nav] }}">
                                    <span>{{ ucwords($navCheck[1]) }}</span>
                                </a>
                            </li>
                        @endif
                    @endforeach
				</ul>
			</div>

			@yield('sub_content')

	  	</div>
	</div>
@endsection
