<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'Noox') }}</title>
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width">
    <link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('fonts/stylesheet.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/index.css') }}">

    <script type="text/javascript">
        var base_url = "{{ url('/') }}";
    </script>
</head>
<body>
    <div class="top-navbar">
        <div class="navbar-trigger">
            <span class="menu-trigger"></span>
        </div>
        <div class="navbar-title">
            <span class="project-logo" alt="noox"></span>
            <span class="vertical-mid-helper"></span>
        </div>
        <div class="navbar-right"></div>
        <div class="navbar-container-big">
            <ul>
                <li class="navbar-navigate" onclick="openAboutModal()">About</li>
                <!-- <li class="navbar-navigate" data-navigate="footer" style="border: 1px solid #ffffff;border-radius: 15px;padding: 0 15px;">Download App</li> -->
                <span class="vertical-mid-helper"></span>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="navbar-container">
        <ul>
            <li class="navbar-navigate" onclick="openAboutModal()">About</li>
            <!-- <li class="navbar-navigate" data-navigate="footer">Download App</li> -->
        </ul>
    </div>
    <!-- content start -->
    @yield('content')
    <!-- content end -->
    <div id="footer">
        <div class="text">© Noox {{ Carbon\Carbon::now()->year }}</div>
        <div class="vertical-mid-helper"></div>
        <a class="downloadLink" href="https://play.google.com/store" target="_blank"></a>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="aboutModal" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" style="font-family: dominebold;">About This Project</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" style="font-family: ralewaymedium; font-size: 14px;">
              <p>Noox Project is an undergradute college project that is handled by three youngsters.</p>
              <ul>
                <li><a href="https://www.linkedin.com" target="_blank">Anthony Prasetyo</a></li>
                <li><a href="https://www.linkedin.com" target="_blank">Raymond Haryanto</a></li>
                <li><a href="https://www.linkedin.com" target="_blank">Rahadian Adinugroho</a></li>
              </ul>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
    </div>
    <script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/index.js') }}"></script>
</body>
</html>