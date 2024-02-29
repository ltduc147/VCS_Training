<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="views/css/Users/popup_form.css"/>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
<div class="popup">
  
  <div class="popup_content">
    <div class="header">
      <div class="title"><?php echo (isset($record)) ? "Update" : 'Add'?> student</div>
      <div class="close"><span class="material-symbols-outlined">close_small</span></div>
    </div>
    <form class="record_form" enctype="multipart/form-data" method="post">
      <div class="input_item username">
        <label for="username">Username</label>
        <input required type="text" name="username" value='<?php echo (isset($record) ? $record["username"] ?? "" : "") ?>' >
      </div>
      <div class="input_item password">
        <label for="password">Password</label>
        <input required type="text" name="password" value='<?php echo (isset($record) ? $record["password"] ?? "" : "") ?>' >
      </div>
      <div class="input_item fname">
        <label for="full_name">Full name</label>
        <input required type="text" name="full_name" value='<?php echo (isset($record) ? $record["full_name"] ?? "" : "") ?>' >
      </div>
      <div class="input_item email">
        <label for="email">Email</label>
        <input required type="text" name="email" value='<?php echo (isset($record) ? $record["email"] ?? "" : "") ?>' >
      </div>
      <div class="input_item phnum">
        <label for="phone_number">Phone number</label>
        <input required type="text" name="phone_number" value=<?php echo (isset($record) ? $record["phone_number"] ?? "" : "") ?> >
      </div>
      <div class="button">
        <div class="cancel_button">Cancel</div>
        <button type="submit" class="submit_button">OK</button>
      </div>
    </form>
  </div>
</div>
</body>
</html>
