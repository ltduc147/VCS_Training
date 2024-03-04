<!DOCTYPE html>
<html lang="en">
<head>
  <link type="text/css" rel="stylesheet" href="views/css/Users/student_management.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
  
  <div class="container">
    <div class="wrapper">
      <div class="content">
        <div class="student_form"></div>
        <div class="top_bar">
          <div class="page_title">STUDENT</div>
          <button class="add_record">
            <i class="material-icons button">person_add_alt</i> &nbsp;
            Add student
          </button>
        </div>
        <div class="main_content">
          <!-- <?php
            $success = $success;
            if ($success != ""){
              echo "<div id='msg' class='delete_message " . ($success == "true" ? "success" : "fail") . "'>";
              echo ($success == "true" ? "<div class='material-icons'>task_alt</div>" : "<div class='material-icons'>error_outline</div>");
              echo "<div class='msg'>" . ($success == "true" ? "Delete success" : "Delete failed") . "</div>";
              echo "<div class='close'><span class='material-icons'>close</span></div>";  
              echo "</div>";
            }
          ?>  -->
          <div class="page">
            <table>
              <tr class='row'>
                <th class="td_user">Username</th>
                <th class="td_pass">Password</th>
                <th class="td_name">Full name</th>
                <th class="td_email">Email</th>
                <th class="td_phnum">Phone number</th>
                <th class="edit"></th>
                <th class="update"></th>
              </tr>
            <?php
              if ($record){
                foreach ($record as $row){
                  echo '<tr class="row">';
                    echo "<td class='td_user'>". $row["username"] ."</td>";
                    echo "<td class='td_pass'>". $row["password"] ."</td>";
                    echo "<td class='td_name'>". $row["full_name"] ."</td>";
                    echo "<td class='td_email'>". $row["email"] ."</td>";
                    echo "<td class='td_phnum'>". $row["phone_number"] ."</td>";
                    echo "<td class='edit'> <i class='material-icons action icon_edit' data-id='". $row['id'] ."'>border_color</i></div></td>";
                    echo "<td class='delete'><i class='material-icons action icon_delete' data-id='".$row['id'] . "'>&#xe872;</i></div></td>";
                  echo '</tr>';
                }
              }
            ?>
            </table>
            <div class="paglink">
              <?php
                echo "<a class='link_button first " . ($page <= 1 ? "disable" : ""). " ' href='?controller=User&action=student_management&page=1"
                . "'>FIRST</a>";

                echo "<a class='link_button pre ". ($page <= 1 ? "disable" : "") ."' href='?controller=User&action=student_management&page=". ($page - 1)
                ."'>PREVIOUS</a>";

                for ($i = 1; $i <= $num_page; $i++){
                  if ($i == $page){
                    echo "<a class='isset link' href = '?controller=User&action=student_management&page=". "$i"."'>". $i  ."</a>";
                  } else {
                    echo "<a class='link' href = '?controller=User&action=student_management&page=". "$i" ."'>". $i . "</a>";
                  }
                }
                echo "<a class='link_button next ".($page >= $num_page ? "disable" : "") ."' href='?controller=User&action=student_management&page=".($page +1)
                . "'>NEXT</a>";
                
                echo "<a class='link_button last ".($page >= $num_page ? "disable" : "") ."' href='?controller=User&action=student_management&page=". ($num_page)
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
        // Handle add record function
        $('.add_record').click(function(){
            // Send AJAX request to viewForm endpoint
            $.ajax({
                url: './?controller=User&action=viewForm',
                type: 'GET',
                success: function(response) {
                    // Display the edit form
                    $('.student_form').html(response);

                    $('.close, .cancel_button').click(function(){
                      $('.student_form').html('');
                      $('.submit_button').off();
                    });

                    $('.submit_button').click(function(){
                      event.preventDefault();
                      $.ajax({
                        url : './?controller=User&action=add',
                        type: 'POST',
                        data : $('.record_form').serialize() ,
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


        // Handle update message function
        function handleEditClick(studentId){
          // Send AJAX request to viewForm endpoint
        
          $.ajax({
              url: `./?controller=User&action=viewForm&id=${studentId}`,
              type: 'GET',
              success: function(response) {
                  // Display the edit form
                  $('.student_form').html(response);

                  // Hide the edit form
                  $('.close, .cancel_button').click(function(){
                    location.reload();
                  });

                  $('.submit_button').click(function(){
                    // Submit form and reload new data
                    event.preventDefault();
                    $.ajax({
                      url : `./?controller=User&action=update&id=${studentId}`,
                      type: 'POST',
                      data : $('.record_form').serialize() ,
                      success: function(response) {
                        
                      },
                      error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        // Handle error
                      }
                    });

                    handleEditClick(studentId);
                  });

              },
              error: function(xhr, status, error) {
                  console.error(xhr.responseText);
                  // Handle error
              }
          });
        }

        $('.icon_edit').click(function(){
          var studentId = $(this).data('id');
          handleEditClick(studentId);
        });

        // Handle delete function
        $('.icon_delete').click(function(){
            var studentId = $(this).data('id');
            // Send AJAX request to viewForm endpoint
            $.ajax({
                url : `./?controller=User&action=delete&id=${studentId}`,
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