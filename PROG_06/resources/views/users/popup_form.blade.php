<head>
    <link rel="stylesheet" href="{{ asset('css/users/popup_form.css')}}">
</head>

<div class="popup">
  <div class="popup_content">
    <div class="header">
      <div class="title"> {{ $user ? "Update" : 'Add'}} student</div>
      <div class="close"><span class="material-symbols-outlined">close_small</span></div>
    </div>
    <form class="record_form" enctype="multipart/form-data" method="post">
      <div class="input_item username">
        <label for="username">Username</label>
        <input required type="text" name="username" value='{{ isset($user) ? $user["username"] ?? "" : "" }}' >
      </div>
      <div class="input_item password">
        <label for="password">Password</label>
        <input required type="text" name="password" value='{{ isset($user) ? $user["password"] ?? "" : "" }}' >
      </div>
      <div class="input_item fname">
        <label for="full_name">Full name</label>
        <input required type="text" name="full_name" value='{{ isset($user) ? $user["full_name"] ?? "" : "" }}' >
      </div>
      <div class="input_item email">
        <label for="email">Email</label>
        <input required type="text" name="email" value='{{isset($user) ? $user["email"] ?? "" : "" }}' >
      </div>
      <div class="input_item phnum">
        <label for="phone_number">Phone number</label>
        <input required type="text" name="phone_number" value='{{ isset($user) ? $user["phone_number"] ?? "" : ""}}''  >
      </div>
      <div class="button">
        <div class="cancel_button">Cancel</div>
        <button type="submit" class="submit_button">OK</button>
      </div>
    </form>
  </div>
</div>
