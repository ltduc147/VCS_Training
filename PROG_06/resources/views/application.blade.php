<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PROG 06</title>
  <link rel="stylesheet" type="text/css" href="{{ asset('css/application.css') }}">
  <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  @stack('scripts')
  @stack('styles')
</head>
<body>
    <div class="app_main_container">
      <div class="nav_bar">
        <a href="./?controller=Record" class="home_link"><span class="orange">PROG</span> 06</a>
        <img src="{{ asset(session('user')['avt_url'])}}" alt="" class="avatar" onclick="dropdownDisplay()">
        <div class="dropdown_menu">
          <div class="dropdown_content">
            <div class="user_info">
              <img src="{{ asset(session('user')['avt_url'])}}" alt="" class="user_avatar">
              <div class="user_name">{{ session('user')['full_name']}}</div>
            </div>
            <a href="/user/{{session('user')["id"]}}" class="profile_link navigation_link">
              <div class="circle">
                <span class="material-icons" style="font-size:18px;">person</span>
              </div>
              <div class="text">Profile</div>
              <span class="material-icons forward" style="font-size:12px;">arrow_forward_ios</span>
            </a>
            <a href="{{ route('users') }}" class="logout_link navigation_link">
              <div class="circle">
                <span class="material-icons" style="font-size:18px;">group</span>
              </div>
              <div class="text">List user</div>
              <span class="material-icons forward" style="font-size:12px;">arrow_forward_ios</span>
            </a>
            @if (session('user')['role'] === 'teacher')
                <a href="{{ route('students') }}" class="logout_link navigation_link">
                  <div class="circle">
                    <span class="material-icons" style="font-size:18px;">manage_accounts</span>
                  </div>
                  <div class="text">Student Management</div>
                  <span class="material-icons forward" style="font-size:12px;">arrow_forward_ios</span>
                </a>
            @endif
            <a href="{{ route('assignments') }}" class="logout_link navigation_link">
              <div class="circle">
                <span class="material-icons" style="font-size:18px;">assignment</span>
              </div>
              <div class="text">Assignment</div>
              <span class="material-icons forward" style="font-size:12px;">arrow_forward_ios</span>
            </a>
            <a href="{{ route('challenges') }}" class="logout_link navigation_link">
              <div class="circle">
                <span class="material-icons" style="font-size:18px;">extension</span>
              </div>
              <div class="text">Challenge</div>
              <span class="material-icons forward" style="font-size:12px;">arrow_forward_ios</span>
            </a>

            <a href="{{ route('logout')}}" class="logout_link navigation_link">
              <div class="circle">
                <span class="material-icons" style="font-size:18px;">logout</span>
              </div>
              <div class="text">Logout</div>
              <span class="material-icons forward" style="font-size:12px;">arrow_forward_ios</span>
            </a>
          </div>
        </div>
      </div>
      <div class="app_main_wrapper">
        <div class="app_main_content">
          @yield('content')
        </div>
      </div>
    </div>
    <script>
      function dropdownDisplay(){
        document.getElementsByClassName("dropdown_menu")[0].classList.toggle("appear");
      }
    </script>
</body>
</html>
