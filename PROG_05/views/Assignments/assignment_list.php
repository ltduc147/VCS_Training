<!DOCTYPE html>
<html lang="en">
<head>
  <link type="text/css" rel="stylesheet" href="views/css/Assignments/assignment_list.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
  
  <div class="container">
    <div class="wrapper">
      <div class="content">
        <div class="top_bar">
          <div class="page_title">ASSIGNMENT</div>
          <button class="add_record">
            <i class="material-icons button">assignment_add</i> &nbsp;
            Add assignment
          </button>
        </div>
        <div class="main_content">
          <div class="page">
            <div class="assignment_list_container">
              <?php if (isset($assignments)) foreach($assignments as $assignment):?>
                <div class="assignment_row">
                  <div class="assignment_content" data-id='<?php echo $assignment['id'];?>'>
                    <i class="material-symbols-outlined icon">assignment</i>
                    <div class="assignment_detail">
                      <div class="assignment_title"><?php echo $assignment['title'] ?></div>
                      <div class="assignment_info"><?php echo $assignment['name'] ?></div>
                    </div>
                  </div>
                  <div class="assignment_action">
                    <?php if ($assignment['teacher_id'] === $_SESSION['id']):?>
                      <i class='material-icons icon_edit' data-id='<?php echo $assignment['id']?>'>border_color</i>
                      <i class='material-icons icon_delete' data-id='<?php echo $assignment['id']?>'>&#xe872;</i>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="assignment_form"></div>
            <div class="paglink">
              <?php
                echo "<a class='link_button first " . ($page <= 1 ? "disable" : ""). " ' href='?controller=User&action=user_list&page=1"
                . "'>FIRST</a>";

                echo "<a class='link_button pre ". ($page <= 1 ? "disable" : "") ."' href='?controller=User&action=user_list&page=". ($page - 1)
                ."'>PREVIOUS</a>";

                for ($i = 1; $i <= $num_page; $i++){
                  if ($i == $page){
                    echo "<a class='isset link' href = '?controller=User&action=user_list&page=". "$i"."'>". $i  ."</a>";
                  } else {
                    echo "<a class='link' href = '?controller=User&action=user_list&page=". "$i" ."'>". $i . "</a>";
                  }
                }
                echo "<a class='link_button next ".($page >= $num_page ? "disable" : "") ."' href='?controller=User&action=user_list&page=".($page +1)
                . "'>NEXT</a>";
                
                echo "<a class='link_button last ".($page >= $num_page ? "disable" : "") ."' href='?controller=User&action=user_list&page=". ($num_page)
                . "'>LAST</a>";
              ?>
            </div>


          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    $(document).ready(function(){
      $('.assignment_content').click(function(){
        var user_id = $(this).data('id');
        location.href = `./?controller=Assignment&action=assignment_detail&id=${user_id}`;
      });


      $('.add_record').click(function(){
        // Send AJAX request to viewForm endpoint
        $.ajax({
            url: './?controller=Assignment&action=assignment_form',
            type: 'GET',
            success: function(response) {
                // Display the edit form
                $('.assignment_form').html(response);

                $('.close, .cancel_button').click(function(){
                  $('.assignment_form').html('');
                  $('.submit_button').off();
                });

                $('.submit_button').click(function(){
                  event.preventDefault();
                  var formData = new FormData($(".record_form")[0]);
                  $.ajax({
                    url : './?controller=Assignment&action=add',
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
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
      });

      function handleEditClick(assignmentId){
          // Send AJAX request to showEditForm endpoint
        
          $.ajax({
              url: `./?controller=Assignment&action=assignment_form&id=${assignmentId}`,
              type: 'GET',
              success: function(response) {
                  // Display the edit form
                  $('.assignment_form').html(response);

                  // Hide the edit form
                  $('.close, .cancel_button').click(function(){
                    location.reload();
                  });

                  $('.submit_button').click(function(){
                    // Submit form and reload new data
                    event.preventDefault();
                    var formData = new FormData($(".record_form")[0]);
                    $.ajax({
                      url : `./?controller=Assignment&action=update&id=${assignmentId}`,
                      type: 'POST',
                      data : formData,
                      contentType: false,
                      processData: false,
                      success: function(response) {
                        
                      },
                      error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        // Handle error
                      }
                    });

                    handleEditClick(assignmentId);
                  });

              },
              error: function(xhr, status, error) {
                  console.error(xhr.responseText);
                  // Handle error
              }
          });
        }

        $('.icon_edit').click(function(){
          var assignmentId = $(this).data('id');
          handleEditClick(assignmentId);
        });

        // Handle delete function
        $('.icon_delete').click(function(){
            var assignmentId = $(this).data('id');
            // Send AJAX request to viewForm endpoint
            $.ajax({
                url : `./?controller=Submission&action=delete&id=${assignmentId}`,
                type: 'GET',
                success: function(response) {
                    // Display the edit form
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