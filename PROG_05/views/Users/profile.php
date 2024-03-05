<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link type="text/css" rel="stylesheet" href="views/css/Users/profile.css">
</head>
<body>
    <?php
      if (isset($msg)){
        echo "<div id='msg' class='message " . ($msg != "Success" ? "fail" : "success") . "'>";
        echo ($msg != "Success" ? "<div class='material-icons'>error_outline</div>" : "<div class='material-icons'>task_alt</div>");
        echo "<div class='msg'>";
        if ($msg == "ID existed") {
          echo "ID existed";
        } else {
          echo ($method) ." ". ($msg);
        }
        echo "</div>";
        echo "<div class='close'><span class='material-icons'>close</span></div>";  
        echo "</div>";
      }
    ?> 
    <div class="Profile_container">
      <div class="Profile_wrapper">
        <div class="tab_frame">
          <div class="frame_container">
            <div
              <?php echo (!isset($record["id"]) || $record["id"] !== $_SESSION["id"]) ? "style='height:250px; grid-template-rows: 2fr 2.5fr;'" : ""?>
              class="Information_frame"
            >
              <div class="img">
                <img src="https://gust.com/assets/blank_slate/Gust_Profile_CoverPhoto_Blank-21edf1e2890708d5a507204f49afc10b7dc58eb7baea100b68a1bc2c96948297.png" alt="" />
              </div>
              <div class="profile_avatar">
                <img class='avatar_img' src='<?php echo (isset($record) ? $record["avt_url"] ?? "" : "")?>' alt="">
                <button class="upload_avt" for="avt"><span class="material-icons" style="font-size:18px">photo_camera</span></button>
              </div>
              <div class="infor">
                <div class="infor_content">
                  <span><?php echo (isset($record) ? $record["full_name"] ?? "" : "") ?></span><br>
                  Age: 25
                </div>
              </div>

              <?php if (!isset($record["id"]) || $record["id"] === $_SESSION["id"]):?>
              <div
                
                class="Profile_setting tab"
                aria-selected="<?php echo ($tab === "info" ) ?"true" : "false" ?>"
                onclick="showProfile()"
              >
                PROFILE SETTING
              </div>

              <div
                class="change_pass tab"
                aria-selected="<?php echo ($tab === "pass" ) ?"true" : "false" ?>"
                onclick="showChangeForm()"
              >
                CHANGE PASSWORD
              </div>
              <?php endif;?>

            </div>
          </div>

          <div class="tab_panel">

            <form class="Update_form" method="post"
              style="display:<?php echo ($tab === "info" ) ?"grid" : "none" ?>"
            >
              <div class="lable_text">
                <label for="username">USERNAME</label>
                <input disabled type="text" name="username" value='<?php echo (isset($record) ? $record["username"] ?? "" : "") ?>'/>
              </div>
              <div class="lable_text">
                <label for="full_name">FULL NAME</label>
                <input disabled type="text" name="full_name" value='<?php echo (isset($record) ? $record["full_name"] ?? "" : "") ?>'/>
              </div>
              <div class="lable_text">
                <label for="email">EMAIL ADDRESS</label>
                <input <?php echo ((!isset($record["id"]) || $record["id"] !== $_SESSION["id"]) ? "disabled" : "") ?> required type="text" name="email" value='<?php echo (isset($record) ? $record["email"] ?? "" : "") ?>'/>
              </div>
              <div class="lable_text">
                <label for="phone_number">PHONE NUMBER</label>
                <input <?php echo ((!isset($record["id"]) || $record["id"] !== $_SESSION["id"]) ? "disabled" : "") ?> required type="text" name="phone_number" value='<?php echo (isset($record) ? $record["phone_number"] ?? "" : "") ?>'/>
              </div>
              <button <?php echo ((!isset($record["id"]) || $record["id"] !== $_SESSION["id"]) ? "style='display:none;'" : "") ?> type='submit' class='update_button'>UPDATE PROFILE</button>
            </form>

            <div class="pass_form_wrapper" style="display:<?php echo ($tab === "pass" ) ? "flex" : "none" ?>">
              <form class="change_pass_form" name="change_pass_form" method="post">
                  <div class="pass_input">
                    <label for="old_pass">Old Password:</label>
                    <input required type="password" name="old_pass"/>
                  </div>
                  <div class="pass_input">
                    <label for="new_pass">New Password:</label>
                    <input required type="password" name="new_pass"/>
                  </div>
                  <div class="pass_input">
                    <label for="confirm_pass">Confirm Password:</label>
                    <div class="confirm_pass">
                      <input required type="password" name="confirm_pass"/>
                      <span id='error'></span>
                    </div>
                  </div>
                  <div class="pass_input">
                    <label for="change_button"></label>
                    <button type='submit' class='change_button'>CHANGE PASSWORD</button>
                  </div>
                  
                </form>
            </div>
          </div>
        </div>  
        <div class="message_container">
          <div class="message_tab_title">
            Messages
          </div>
          
          <div class="message_tab_content">
            <div class="message_quantity">
                <?php echo (isset($messages) ? count($messages) : 0) ?> message for <?php echo (isset($record) && ($record['id'] === $_SESSION['id'])) ? "You" : (isset($record) ? $record["full_name"] ?? "" : "")?>
            </div>
            <?php if (isset($messages)) foreach($messages as $row): ?>
              <div class="message_row">
                <div class="message_avt">
                  <?php
                    $words = explode(" ", $row['name']); 
                    echo mb_substr(end($words), 0, 1) ;
                  ?>
                </div>
                <div class="message_detail">
                  <div class="message_name">
                    <?php 
                      echo $row['name']?>
                  </div>
                  <div class="message_content">
                    <?php echo $row['message_content']?>
                  </div>
                </div>
                <div class="message_action">
                  <?php if ($row['sender_id'] === $_SESSION['id']):?>
                    <i class='material-icons icon_edit' data-id='<?php echo $row['id']?>'>border_color</i>
                    <i class='material-icons icon_delete' data-id='<?php echo $row['id']?>'>&#xe872;</i>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <?php if (isset($record) && $record['id'] !== $_SESSION['id']):?>
          <div class="message_add_form">
            <div class="form_title">
              Add a message
            </div>
            <form class='add_form'>
              <input type="hidden" name="receiver_id" value='<?php echo (isset($record) ? $record["id"] : "") ?>'>
              <input type="hidden" name="sender_id" value='<?php echo $_SESSION['id'] ?>' >
              <label class='label' for="message_content">Your message *</label>
              <textarea name="message_content" col='30', row='5' placeholder="Write a message..."></textarea>
              <button type="submit" class='_submit_button'>Submit</button>
            </form>
          </div>
          <?php endif;?>
        </div> 
        <div class="message_popup_form"></div>
        <div class="avatar_popup_form"></div>
      </div>       
    </div>
    
    <script>
      function showProfile(){
        document.querySelector(".Profile_setting").setAttribute("aria-selected", true);
        document.querySelector(".change_pass").setAttribute("aria-selected", false);
        document.querySelector(".Update_form").style.display = "grid";
        document.querySelector(".pass_form_wrapper").style.display = "none";
      }

      function showChangeForm(){
        document.querySelector(".Profile_setting").setAttribute("aria-selected", false);
        document.querySelector(".change_pass").setAttribute("aria-selected", true);
        document.querySelector(".Update_form").style.display = "none";
        document.querySelector(".pass_form_wrapper").style.display = "flex";
      }

      function passwordValidation(event){
        event.preventDefault();
        let newPass = document.change_pass_form.new_pass.value;
        let confirmPass = document.change_pass_form.confirm_pass.value;
        if (newPass === confirmPass){
          document.change_pass_form.submit();
        } else {
          document.getElementById("error").innerHTML = "*Confirm password must match";
        }
      }
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
      $(document).ready(function(){
        // Handle add message function
        $('._submit_button').click(function(){
          // Submit form and reload new data
          event.preventDefault();
          $.ajax({
            url : `./?controller=Message&action=add`,
            type: 'POST',
            data : $('.add_form').serialize() ,
            success: function(response) {
              location.reload()
            },
            error: function(xhr, status, error) {
              console.error(xhr.responseText);
              // Handle error
            }
          });
        });
        // Handle update message function
        function handleEditClick(messageId){
          // Send AJAX request to showEditForm endpoint
        
          $.ajax({
              url: `./?controller=Message&action=viewForm&id=${messageId}`,
              type: 'GET',
              success: function(response) {
                  // Display the edit form
                  $('.message_popup_form').html(response);

                  // Hide the edit form
                  $('.close, .cancel_button').click(function(){
                    location.reload();
                  });

                  $('.submit_button').click(function(){
                    // Submit form and reload new data
                    event.preventDefault();
                    $.ajax({
                      url : `./?controller=Message&action=update&id=${messageId}`,
                      type: 'POST',
                      data : $('.record_form').serialize() ,
                      success: function(response) {
                        
                      },
                      error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        // Handle error
                      }
                    });

                    handleEditClick(messageId);
                  });

              },
              error: function(xhr, status, error) {
                  console.error(xhr.responseText);
                  // Handle error
              }
          });
        }

        $('.icon_edit').click(function(){
          var messageId = $(this).data('id');
          handleEditClick(messageId);
        });

        // Handle delete function
        $('.icon_delete').click(function(){
            var messageId = $(this).data('id');
            // Send AJAX request to viewForm endpoint
            $.ajax({
                url : `./?controller=Message&action=delete&id=${messageId}`,
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

        // Handle update profile
        $('.change_button').click(function(){
            event.preventDefault();
            let newPass = $('input[name="new_pass"]').val();
            let confirmPass = $('input[name="confirm_pass"]').val();
            if (newPass === confirmPass){
              // Send AJAX request to changePass endpoint
              $.ajax({
                url : `./?controller=User&action=changePass`,
                type: 'POST',
                data : $('.change_pass_form').serialize() ,
                success: function(response) {
                  $('.change_pass_form')[0].reset();
                  $("#error").html("");
                },
                error: function(xhr, status, error) {
                  console.error(xhr.responseText);
                  // Handle error
                }
              });
            } else {
              $("#error").html("*Confirm password must match");
            }
            
        });

        $('.update_button').click(function(){
          event.preventDefault();
          // Send AJAX request to changePass endpoint
          $.ajax({
            url : `./?controller=User&action=updateProfile`,
            type: 'POST',
            data : $('.Update_form').serialize() ,
            success: function(response) {
              location.reload();
            },
            error: function(xhr, status, error) {
              console.error(xhr.responseText);
              // Handle error
            }
          });
        });

        $('.upload_avt').click(function(){
            // Send AJAX request to viewForm endpoint
            $.ajax({
                url: './?controller=User&action=view_avatar_form',
                type: 'GET',
                success: function(response) {
                    // Display the edit form
                    $('.avatar_popup_form').html(response);

                    $('.close, .cancel_button').click(function(){
                      $('.avatar_popup_form').html('');
                      $('.submit_button').off();
                    });
                    
                    $('.submit_button').click(function(){
                      event.preventDefault();
                      var formData = new FormData($(".avatar_form")[0]);
                      $.ajax({
                        url : './?controller=User&action=updateProfile',
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
      });
    </script>
</body>
</html>