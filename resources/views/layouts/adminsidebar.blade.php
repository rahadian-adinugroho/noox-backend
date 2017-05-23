<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
  <div class="menu_section">
    <h3>General</h3>
    <ul class="nav side-menu">
      <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i>Home</a></li>
      <li><a><i class="fa fa-newspaper-o"></i> News <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ route('cms.news') }}">All</a></li>
          <li><a href="{{ route('cms.news.reported') }}">Reported</a></li>
          <li><a href="{{ route('cms.news.deleted') }}">Deleted</a></li>
        </ul>
      </li>
      <li><a><i class="fa fa-users"></i> Users <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ route('cms.users') }}">All</a></li>
          <li><a href="{{ route('cms.users.reported') }}">Reported</a></li>
          <li><a href="{{ route('cms.users.ranking') }}">Ranking</a></li>
        </ul>
      </li>
      <li><a><i class="fa fa-exclamation-circle"></i> Reports <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="general_elements.html">All</a></li>
          <li><a href="media_gallery.html">News</a></li>
          <li><a href="typography.html">Users</a></li>
          <li><a href="typography.html">Comments</a></li>
        </ul>
      </li>
    </ul>
  </div>
  <div class="menu_section">
    <h3>Administrative</h3>
    <ul class="nav side-menu">
    @can('create', Noox\Models\Admin::class)
      <li><a href="javascript:;"><i class="fa fa-rebel"></i> Administrators </a></li>
    @endcan
      <li><a href="javascript:;"><i class="fa fa-list-ul"></i> System Logs </a></li>
  </div>

  </div>
