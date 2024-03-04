<!DOCTYPE html>
<html lang="en">
<head>
  <link type="text/css" rel="stylesheet" href="views/css/Challenges/challenge_detail.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
  
  <div class="container">
    <div class="wrapper">
      <div class="content">
        <div class="main_content">

          <div class="assignment_detail_container">
            <div class="assignment_detail">
              <div class="assignment_title"><?php echo (isset($challenge) ? $challenge['title']: "")?></div>
              <div class="assignment_info"><?php echo (isset($challenge) ? $challenge['name']: "")?> &bull; <?php echo (isset($challenge) ? $challenge['date'] : "")?></div>
              <div class="assignment_description">Hint:</div>
              <div class="hint"><?php echo (isset($challenge) ? nl2br($challenge['hint']): "")?></div>
              <?php if($_SESSION['role'] === 'teacher'): ?>
                <a class="file_info" href="<?php echo (isset($challenge) ? $challenge['file_url']: "")?>" download="<?php echo (isset($challenge) ? $challenge['file_name']: "")?>">
                  <div class="file_name"><?php echo (isset($challenge) ? $challenge['file_name'] : "")?></div>
                  <div class="file_type"><?php echo (isset($challenge) ? $challenge['file_type'] : "")?></div>
                </a>
              <?php endif; ?>
             
            </div>
            <?php if($_SESSION['role'] !== 'teacher'): ?>
            <div class="answer_tab">
              <div class="answer_title">Answer</div>
              <form action="" class="answer_form">
                <input type="text" name="answer" >
                <button class="answer_button" data-id="<?php echo (isset($challenge) ? $challenge['id'] : '')?>">Submit</button>
              </form>
            </div>
            <?php endif; ?>
          </div>
          <div class="file_content"></div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    $(document).ready(function(){

      $('.answer_button').click(function(){
        event.preventDefault();
        var challengeId = $(this).data('id');

        $.ajax({
          url : `./?controller=Challenge&action=check_answer&id=${challengeId}`,
          type: 'POST',
          data : $(".answer_form").serialize(),
          success: function(response) {
            $('.file_content').html('<pre>' + response + '</pre>');
            if (response) {
              $('.file_content').css({
                'border-top': '1px dashed rgba(93, 92, 92,0.3)',
                'font-size': '1.3rem',
                'margin-top': '30px',
                'padding-top': '20px'
              })
            } else {
              $('.file_content').css({
                'border-top': ''
              });
            }
            
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