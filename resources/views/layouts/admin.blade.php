<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ config('app.name', 'Noox') }} Admin</title>

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link href="{{ asset('admin/css/app.css') }}" rel="stylesheet">
  <!-- page specific styles -->
  @yield('pagespecificstyles')

  <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};

        window.Noox = {!! json_encode([
            'jwt' => session('JWTToken'),
        ]) !!};
  </script>
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <div class="col-md-3 left_col">
        <div class="left_col scroll-view">
          <div class="navbar nav_title" style="border: 0;">
            <a href="{{ url('admin') }}" class="site_title"><span>{{ config('app.name', 'Noox') }}</span></a>
          </div>

          <div class="clearfix"></div>

          <!-- menu profile quick info -->
          <!-- <div class="profile">
            <div class="profile_pic">
              <img src="images/img.jpg" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
              <span>Welcome,</span>
              <h2>John Doe</h2>
            </div>
          </div> -->
          <!-- /menu profile quick info -->

          <br />

          <!-- sidebar menu -->
          @include('layouts.adminsidebar')
          <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Logout" href="{{ route('admin.logout.submit') }}" onclick="event.preventDefault();
                 document.getElementById('logout-form').submit();">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav class="" role="navigation">
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    {{ Auth::user()->name }}
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="javascript:;"> Profile</a></li>
                    <li>
                      <a href="javascript:;">
                        <span>Settings</span>
                      </a>
                    </li>
                    <li><a href="{{ route('admin.logout.submit') }}" onclick="event.preventDefault();
                 document.getElementById('logout-form').submit();"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                  </ul>
                </li>

                <li role="presentation" class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-envelope-o"></i>
                    <span class="badge bg-green" id="noox-notification-badge">{{ Auth::user()->unreadNotifications()->count() ?: '' }}</span>
                  </a>
                  <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                    @foreach (Auth::user()->unreadNotifications()->take(5)->get() as $notification)
                    <li>
                      <a href="{{ $notification->data['target_url'] }}">
                        <span class="image"><img src="{{ asset('admin/images/user.png') }}" alt="Profile Image" /></span>
                        <span>
                          <span><strong>{{ $notification->data['title'] }}</strong></span>
                          <span class="time">{{ $notification->created_at->diffForHumans() }}</span>
                        </span>
                        <span class="message">
                          {{ $notification->data['text'] }}
                        </span>
                      </a>
                    </li>
                    @endforeach
                    <li>
                      <div class="text-center">
                        <a href="{{ url('cms/notifications') }}">
                          <strong>See All Alerts</strong>
                          <i class="fa fa-angle-right"></i>
                        </a>
                      </div>
                    </li>
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        @yield('content')
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-right">
            © Noox {{ Carbon\Carbon::now()->year }}
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>
    <form id="logout-form" action="{{ route('admin.logout.submit') }}" method="POST" style="display: none;">
      {{ csrf_field() }}
    </form>

    <script type="text/javascript" src="//{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script>
    <script src="{{ asset('admin/js/app.js') }}"></script>
    <!-- page specific scripts -->
    @yield('pagespecificscripts')

    <script type="text/javascript">
      if (typeof Echo !== "undefined") {
            Echo.private("Noox.Models.Admin.{{Auth::user()->id}}")
            .notification((notification) => {
                handleNotification(notification);
            });
        }
    </script>
  </body>
  </html>