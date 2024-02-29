<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="views/css/Messages/popup_form.css"/>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
<div class="popup">
  
  <div class="popup_content">
    <div class="header">
      <div class="title">Edit message</div>
      <div class="close"><span class="material-symbols-outlined">close_small</span></div>
    </div>
    <form class="record_form" enctype="multipart/form-data" method="post">
      <div class="input_item username">
        <label for="username">Message *</label>
        <textarea name="message_content" col='30', row='5'><?php echo (isset($message) ? $message['message_content']: "")?></textarea>
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
