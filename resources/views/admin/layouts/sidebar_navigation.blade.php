<aside id="aside" class="app-aside modal fade " role="menu">
    <div class="left">
        <div class="box bg-white">
            <div class="navbar md-whiteframe-z1 no-radius blue">

                <a class="navbar-brand">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve" style="
                width: 24px; height: 24px;">
                <path d="M 50 0 L 100 14 L 92 80 Z" fill="rgba(139, 195, 74, 0.5)"></path>
                <path d="M 92 80 L 50 0 L 50 100 Z" fill="rgba(139, 195, 74, 0.8)"></path>
                <path d="M 8 80 L 50 0 L 50 100 Z" fill="#f3f3f3"></path>
                <path d="M 50 0 L 8 80 L 0 14 Z" fill="rgba(220, 220, 220, 0.6)"></path>
                </svg>
                <img src="{{ asset('admin_assets') }}/images/logo.png" alt="." style="max-height: 36px; display:none">
                <span class="hidden-folded m-l inline">{{ config('app.name') }}</span>
                </a>

            </div>

            <div class="box-row">
                <div class="box-cell scrollable hover">
                    <div class="box-inner">
                        <div class="p hidden-folded blue-50" style="background-image:url({{ asset('admin_assets') }}/images/bg.png); background-size:cover">
                            <div class="rounded w-64 bg-white inline pos-rlt">
                                @if($admin->adminProfile)
                                    <img src="{{ asset('contents/profile_pictures/'.$admin->adminProfile->profile_picture) }}" class="img-responsive rounded">
                                @else
                                    <i class="glyphicon glyphicon-user" style="font-size: 45px;padding: 6px 5px 5px 9px;color: grey;"></i>
                                @endif
                            </div>
                            <a class="block m-t-sm" ui-toggle-class="hide, show" target="#nav, #account">
                                <span class="block font-bold">{{ $admin->name }}</span>
                                <span class="pull-right auto">
                                    <i class="fa inline fa-caret-down"></i>
                                    <i class="fa none fa-caret-up"></i>
                                </span>
                                {{ $admin->email }}
                            </a>
                        </div>


                        <div id="nav">
                            <nav ui-nav>
                                <ul class="nav">
                                    <?php
                                        $urlList = \Admin::adminUrlList();
                                        $nestedNav = [];
                                    ?>

                                    @foreach(\Admin::adminRouteList() as $nav => $routes)
                                        <?php
                                            $navSplit = explode('.', $nav);
                                            $isNotMainNav = config('app.admin.not_main_route');
                                        ?>

                                        @if (!in_array($navSplit[0], $isNotMainNav) && \Admin::isHasAccess($routes, $admin))
                                            @if(count($navSplit) == 2)
                                                <?php
                                                    $parent = $navSplit[0];
                                                    $child = $navSplit[1];
                                                    $nestedNav[$parent][$child] = $urlList[$nav];
                                                ?>
                                            @else
                                                <?php $active = Attr::isActive($nav, $activeMenu); ?>

                                                <li class="{{ $active }}">
                                                    <a md-ink-ripple href="{{ $urlList[$nav] }}">
                                                        <i class="icon mdi-toggle-radio-button-{{($active)?'on':'off'}} i-20"></i>
                                                        <span>{{ ucwords(str_replace('-', ' ', $nav)) }}</span>
                                                    </a>
                                                </li>
                                            @endif
                                        @endif
                                    @endforeach

                                    <!-- nested navigation -->
                                    @if (count($nestedNav) > 0)
                                        @foreach ($nestedNav as $mainNav => $subNav)
                                            <?php $active = Attr::isActive($mainNav, $activeMenu); ?>

                                            <li class="{{ $active }}">
                                                <a md-ink-ripple href>
                                                    <span class="pull-right text-muted">
                                                        <i class="fa fa-caret-down"></i>
                                                    </span>
                                                    <i class="icon mdi-content-sort i-20"></i>
                                                    <span>{{ ucwords(str_replace('-', ' ', $mainNav)) }}</span>
                                                </a>
                                                <ul class="nav nav-sub">
                                                    @foreach ($subNav as $nav => $link)
                                                        <?php $activeSub = Attr::isActive($nav, $activeSubMenu); ?>

                                                        <li class="{{ $activeSub }}"><a md-ink-ripple href="{{ $link }}">{{ ucwords(str_replace('-', ' ', $nav)) }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </nav>
                        </div>


                        <div id="account" class="hide m-v-xs">
                            <nav>
                                <ul class="nav">
                                    <li class="{{ Attr::isActive('setting', $activeMenu) }}">
                                        <a md-ink-ripple href="{{ url('admin/setting/account') }}">
                                            <i class="icon mdi-action-settings i-20"></i>
                                            <span>Settings</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a md-ink-ripple href="{{ url('admin/signout') }}">
                                            <i class="icon mdi-action-exit-to-app i-20"></i>
                                            <span>Logout</span>
                                        </a>
                                    </li>
                                    <li class="m-v-sm b-b b"></li>
                                    <li>
                                        <div class="nav-item" ui-toggle-class="folded" target="#aside">
                                            <label class="md-check">
                                                <input type="checkbox">
                                                <i class="purple no-icon"></i>
                                                <span class="hidden-folded">Folded aside</span>
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <nav>
                <ul class="nav b-t b">
                    <li>
                        <a href="mailto:{{ env('ADMIN_EMAIL', 'info@webarq.com') }}" target="_blank" md-ink-ripple>
                        <i class="icon mdi-action-help i-20"></i>
                        <span>Help &amp; Feedback</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</aside>
