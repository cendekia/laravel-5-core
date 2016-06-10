<!DOCTYPE html>
<html lang="en">
    <head>

        @include('admin.layouts.head')

        @stack('elfinder_assets')

    </head>
    <body>
        <div class="app">

            @if (isset($isLogin) && $isLogin)
                @include('admin.layouts.sidebar_navigation')

                <div id="content" class="app-content" role="main">
                    <div class="box">

                        @include('admin.layouts.top_navigations')

                        <div class="box-row">
                            <div class="box-cell">
                                <div class="box-inner padding">

                                    @yield('content')

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                @include('admin.layouts.notifications')
            @else
                @yield('content')
            @endif

        </div>

        @include('admin.layouts.js')

        @stack('elfinder_call')

    </body>
</html>
