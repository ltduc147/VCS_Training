<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="{{ asset('css/auth/login_page.css')}}"/>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
  <div class="container">
    <div class="wrapper">
      <div class="title">
        LOGIN
      </div>
      <form class="login_form" action="/login" method="post">
        @csrf
        <div class="input_item">
          <div class="icon">
            <span class="material-icons" >person</span>
          </div>
          <input type="text" name="username" id="username" placeholder="Username">
        </div>
        <div class="input_item">
          <div class="icon">
            <span class="material-icons" >lock</span>
          </div>
          <input type="password" name="password" id="password" placeholder="Password">
        </div>
        <button type="submit">LOGIN</button>
      </form>
    </div>
  </div>
</body>
</html>
