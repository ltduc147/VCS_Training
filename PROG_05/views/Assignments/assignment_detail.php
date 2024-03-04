<!DOCTYPE html>
<html lang="en">
<head>
  <link type="text/css" rel="stylesheet" href="views/css/Assignments/assignment_detail.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
  
  <div class="container">
    <div class="wrapper">
      <div class="content">
        <div class="main_content">

          <div class="assignment_detail_container">
            <div class="assignment_detail">
              <div class="assignment_title"><?php echo (isset($assignment) ? $assignment['title']: "")?></div>
              <div class="assignment_info"><?php echo (isset($assignment) ? $assignment['name']: "")?> &bull; <?php echo (isset($assignment) ? $assignment['date'] : "")?></div>
              <div class="assignment_description"><?php echo (isset($assignment) ? $assignment['description']: "")?></div>
              <a class="file_info" href="<?php echo (isset($assignment) ? $assignment['file_url']: "")?>" download="<?php echo (isset($assignment) ? $assignment['file_name']: "")?>">
                <div class="file_name"><?php echo (isset($assignment) ? $assignment['file_name'] : "")?></div>
                <div class="file_type"><?php echo (isset($assignment) ? $assignment['file_type'] : "")?></div>
              </a>
            </div>
            <?php if($_SESSION['role'] !== 'teacher'): ?>
            <div class="submission_tab">
              <div class="submission_title">Submission</div>
              <?php if (isset($submission)):?>
                <div class="submission_info" >
                  <a class="submission_detail" href="<?php echo $submission['file_url']?>" download="<?php echo $submission['file_name']?>">
                    <div class="file_name"><?php echo $submission['file_name']?></div>
                    <div class="file_type"><?php echo $submission['file_type']?></div>
                  </a>
                  <i class="material-icons submission_delete" data-id='<?php echo $submission['id']?>'>close</i>
                </div>
              <?php endif;?>
              <div class="add_button"><i class="material-icons button">upload_file</i> &nbsp; <?php echo (isset($submission) ? "Change" : "Add")?> submission</div>
              <form action="" class="submission_form">
                <input type="file" name="submission_file" id="file_input">
                <input type="hidden" name="assignment_id" value='<?php echo (isset($assignment) ? $assignment['id']: "")?>'>
              </form>
            </div>
            <?php endif; ?>
          </div>
          <?php if(isset($submission_list) && $_SESSION['role'] === 'teacher'):  ?>
            <div class="submission_list">
              <div class="list_title">Submissions</div>
              <?php foreach($submission_list as $row):  ?>
                <a class="submission_row" href="<?php echo $row['file_url']?>" download="<?php echo $row['file_name']?>">
                  <div class="submission_info">
                    <div class="submission_name"><?php echo $row['name']?></div>
                    <div class="submission_detail"><?php echo $row['file_name'] . ' - ' . $row['file_type'] . ' - ' . $row['date']?></div>
                  </div>
                </a>
              
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    $(document).ready(function(){
      $('.add_button').click(function(){
        $('#file_input').click();
      });

      $('#file_input').change(function(){
        var formData = new FormData($(".submission_form")[0]);
        console.log(formData);
        $.ajax({
          url : './?controller=Submission&action=add_update',
          type: 'POST',
          data : formData,
          contentType: false,
          processData: false,
          success: function(response) {
            location.reload();
          },
          error: function(xhr, status, error) {
            console.error(xhr.responseText);
          }
        });
      });

      $('.submission_delete').click(function(){
        var submissionId = $(this).data('id');
        $.ajax({
          url : `./?controller=Submission&action=delete&id=${submissionId}`,
          type: 'GET',
          success: function(response) {
            location.reload();
          },
          error: function(xhr, status, error) {
            console.error(xhr.responseText);
          }
        });
      });
    });
  </script>
  
</body>
</html>