<!-- navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{url('/')}}"> <img
                src="{{ getFile(config('location.logoIcon.path') . 'logo.png') }}" alt="..."/>
        </a>
        <button class="sidebar-toggler" onclick="toggleSideMenu()">
            <i class="far fa-bars"></i>
        </button>
        <!-- navbar text -->
        <span class="navbar-text">
            <!-- notification panel -->
            @include($theme.'partials.pushNotify')

            <div class="user-panel">
               <span class="profile">
                  <img src="{{getFile(config('location.user.path').auth()->user()->image)}}" class="img-fluid"
                       alt="..."/>
               </span>
               <ul class="user-dropdown">
                  <li>
                     <a href="{{route('user.profile')}}"> <i class="fal fa-user"></i> @lang('Profile') </a>
                  </li>
                  <li>
                     <a href="{{route('user.password')}}"> <i class="fal fa-key"></i> @lang('Change Password') </a>
                  </li>
                  <li>
                     <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> <i
                             class="fal fa-sign-out-alt"></i> @lang('Sign Out') </a>
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                          @csrf
                      </form>
                  </li>
               </ul>
            </div>
        </span>
    </div>
</nav>
