<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PROG 05</title>
  <link rel="stylesheet" type="text/css" href="views/css/application.css">
  <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <div class="main_container">
      <div class="nav_bar">
        <a href="./?controller=Record" class="home_link"><span class="orange">PROG</span> 05</a>
        <img src="<?php echo $_SESSION['avatar']?>" alt="" class="avatar" onclick="dropdownDisplay()">
        <div class="dropdown_menu">
          <div class="dropdown_content">
            <div class="user_info">
              <img src="<?php echo $_SESSION['avatar']?>" alt="" class="user_avatar">
              <div class="user_name"><?php echo $_SESSION['full_name']?></div>
            </div>
            <a href="./?controller=User&action=profile&id=<?php echo $_SESSION['id']?>" class="profile_link navigation_link">
              <div class="circle">
                <span class="material-icons" style="font-size:18px;">person</span>
              </div>
              <div class="text">Profile</div>
              <span class="material-icons forward" style="font-size:12px;">arrow_forward_ios</span>
            </a>
            <a href="./?controller=User&action=user_list" class="logout_link navigation_link">
              <div class="circle">
                <span class="material-icons" style="font-size:18px;">group</span>
              </div>
              <div class="text">List user</div>
              <span class="material-icons forward" style="font-size:12px;">arrow_forward_ios</span>
            </a>
            <?php if ($_SESSION['role'] === 'teacher'): ?>
                <a href="./?controller=User&action=student_management" class="logout_link navigation_link">
                  <div class="circle">
                    <span class="material-icons" style="font-size:18px;">manage_accounts</span>
                  </div>
                  <div class="text">Student Management</div>
                  <span class="material-icons forward" style="font-size:12px;">arrow_forward_ios</span>
                </a>
            <?php endif;?> 
            <a href="./?controller=Assignment&action=assignment_list" class="logout_link navigation_link">
              <div class="circle">
                <span class="material-icons" style="font-size:18px;">assignment</span>
              </div>
              <div class="text">Assignment</div>
              <span class="material-icons forward" style="font-size:12px;">arrow_forward_ios</span>
            </a>
            <a href="./?controller=Challenge&action=challenge_list" class="logout_link navigation_link">
              <div class="circle">
                <span class="material-icons" style="font-size:18px;">extension</span>
              </div>
              <div class="text">Challenge</div>
              <span class="material-icons forward" style="font-size:12px;">arrow_forward_ios</span>
            </a>
            
            <a href="?controller=Auth&action=logout" class="logout_link navigation_link">
              <div class="circle">
                <span class="material-icons" style="font-size:18px;">logout</span>
              </div>
              <div class="text">Logout</div>
              <span class="material-icons forward" style="font-size:12px;">arrow_forward_ios</span>
            </a>
          </div>
        </div>
      </div>
      <div class="main_wrapper">
        <div class="content">
          <?= $content ?>
        </div>
      </div>
    </div>
    <script>
      function dropdownDisplay(){
        document.getElementsByClassName("dropdown_menu")[0].classList.toggle("appear");
      }
    </script>
</body>
</html>