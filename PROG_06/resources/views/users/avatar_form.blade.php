<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="views/css/Users/avatar_form.css"/>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
<div class="popup">
  
  <div class="popup_content">
    <div class="header">
      <div class="title">Avatar</div>
      <div class="close"><span class="material-symbols-outlined">close_small</span></div>
    </div>
    <form class="avatar_form" enctype="multipart/form-data" method="post">
      <div class="input_item username">
        <label for="avt_url">Url</label>
        <input type="text" name="avt_url" class='url_input'  placeholder="Url of image ...">
      </div>
      <div class="input_item username">
        <label for="avt">File</label>
        <input type="file" name="avt" class='avt_input' >
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
