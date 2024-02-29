<!DOCTYPE html>
<html lang="en">
<head>
  <link type="text/css" rel="stylesheet" href="views/css/Users/user_list.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
  
  <div class="container">
    <div class="wrapper">
      <div class="content">
        <div class="main_content">

          <div class="page">
            <div class="user_list_container">
              <?php if (isset($record)) foreach($record as $row) :?>
              <div class="user_card_container" data-id='<?php echo $row['id'];?>'>
                <div class="user_card_content">
                  <img src='<?php echo $row['avt_url']?>' alt="" class="user_avt">
                  <div class="user_fullname"><?php echo $row['full_name']?></div>
                  <div class="user_role"><?php echo $row['role']?></div>
                  <div class="user_email"><?php echo $row['email']?></div>
                </div>
              </div>
              <?php endforeach;?>
            </div>
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
      $('.user_card_container').click(function(){
        var user_id = $(this).data('id');
        location.href = `./?controller=User&action=profile&id=${user_id}`;
      });
    });
  </script>
  
</body>
</html>