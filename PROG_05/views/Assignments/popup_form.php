<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="views/css/Assignments/popup_form.css"/>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
<div class="popup">
  
  <div class="popup_content">
    <div class="header">
      <div class="title"><?php echo (isset($assignment)) ? "Edit" : 'Add'?> assignment</div>
      <div class="close"><span class="material-symbols-outlined">close_small</span></div>
    </div>
    <form class="record_form" enctype="multipart/form-data" method="post">
      <div class="input_item">
        <label for="title">Title</label>
        <input type="text" name="title" class='assignment_title' value='<?php echo (isset($assignment) ? $assignment["title"] : "") ?>' placeholder="Assignment title ...">
      </div>
      <div class="input_item">
        <label for="description">Description </label>
        <textarea name="description" col='30', row='5' placeholder="Assignment description"><?php echo (isset($assignment) ? $assignment['description']: "")?></textarea>
      </div>
      <div class="input_item">
        <label for="assignment_file">File</label>
        <input type="file" name="assignment_file">
      </div>
      <input type="hidden" name="teacher_id" value='<?php echo $_SESSION['id']?>'>
      <div class="button">
        <div class="cancel_button">Cancel</div>
        <button type="submit" class="submit_button">OK</button>
      </div>
    </form>
  </div>
</div>
</body>
</html>
