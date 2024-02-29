<?php

class UserController extends BaseController {

  private $UserModel;
  private $MessageModel;

  public function __construct(){
    $this->loadModel('UserModel');
    $this->loadModel('MessageModel');
    $this->UserModel = new UserModel;
    $this->MessageModel = new MessageModel;
  }


  public function student_management() {
    if (isset($_SESSION['id'])){
      if (isset($_SESSION['role']) && $_SESSION['role'] === 'teacher'){
        if (isset($_GET["page"])) {
          $page = $_GET["page"];
        } else {
          $page = 1;
        }
    
        $recordPerPage = 10;
    
        $searchKey = (isset($_GET["search_input"]) ? $_GET["search_input"] : "");
    
        $result = $this->UserModel->read(0, 0, ['role' => 'student'], $searchKey); // Read all record
        if ($result){
          $total_record = count($result);
          
        } else {
          $total_record = 0;
        }
        
        if ($page > ceil($total_record / $recordPerPage) ){
          $page = ceil($total_record / $recordPerPage);
        }
        
        if ($page < 1){
          $page = 1;
        }
    
        if (!$total_record){
          $page = 0;
        }
    
        // page navigation
        $main_record = $this->UserModel->read($page, $recordPerPage, ['role' => 'student'], $searchKey);
        
        $data = [
          'Users.student_management' => [
            "record" => $main_record,
            "num_page" => ceil($total_record / $recordPerPage),
            "page" => $page
          ]
        ];
        return $this->view(['Users.student_management', 'application'], $data );
      } else {
        header('Location: ./?controller=User&action=profile'); // Update later
      }
    } else {
      header('Location: ./?controller=Auth&action=login');
    }
    
  }

  public function user_list() {
    if (isset($_SESSION['id'])){
      if (isset($_GET["page"])) {
        $page = $_GET["page"];
      } else {
        $page = 1;
      }
  
      $recordPerPage = 8;
  
  
      $result = $this->UserModel->read(0, 0, []); // Read all record
      if ($result){
        $total_record = count($result);
        
      } else {
        $total_record = 0;
      }
      
      if ($page > ceil($total_record / $recordPerPage) ){
        $page = ceil($total_record / $recordPerPage);
      }
      
      if ($page < 1){
        $page = 1;
      }
  
      if (!$total_record){
        $page = 0;
      }
  
      // page navigation
      $main_record = $this->UserModel->read($page, $recordPerPage, []);
      
      $data = [
        'Users.user_list' => [
          "record" => $main_record,
          "num_page" => ceil($total_record / $recordPerPage),
          "page" => $page
        ]
      ];
      return $this->view(['Users.user_list', 'application'], $data );

    } else {
      header('Location: ./?controller=Auth&action=login');
    }
    
  }

  public function viewForm(){
    if (isset($_SESSION['id'])){
      if (isset($_SESSION['role']) && $_SESSION['role'] === 'teacher'){
        if (isset($_GET['id'])) {
          $record = $this->UserModel->read(0, 0, ['id' => $_GET['id']]);
          $data = [
            'Users.popup_form' => [
              "record" => (isset($record) ? ($record[0] ?? []) : [])
            ]
          ];
        } else {
          $data = [];
        }
        return $this->view(['Users.popup_form'], $data);

      } else {
        header('Location: ./?controller=Record');
      }
    } else {
      header('Location: ./?controller=Auth&action=login');
    } 
  }


  public function add(){
    if (isset($_SESSION['id'])){
      if (isset($_SESSION['role']) && $_SESSION['role'] === 'teacher'){
        $result = $this->UserModel->create($_POST);

      } else {
        header('Location: ./?controller=Record');
      }
    } else {
      header('Location: ./?controller=Auth&action=login');
    }
    
  }

  public function update(){
    if (isset($_SESSION['id'])){
      if (isset($_SESSION['role']) && $_SESSION['role'] === 'teacher'){
        $result = $this->UserModel->update($_GET["id"], $_POST);
        
      } else {
        header('Location: ./?controller=Record');
      }
    } else {
      header('Location: ./?controller=Auth&action=login');
    }
    
  }

  public function delete(){
    if (isset($_SESSION['id'])){
      if (isset($_SESSION['role']) && $_SESSION['role'] === 'teacher'){

        if (isset($_GET['id'])){
          $existingRecord = $this->UserModel->read(0, 0, ['id' => $_GET['id']]);
          if ($existingRecord){
            return $this->UserModel->delete($_GET['id']);
          } else {
            return false;
          }
          
        } 

      } else {
        header('Location: ./?controller=Record');
      }
    } else {
      header('Location: ./?controller=Auth&action=login');
    }
     
  }

  public function profile(){
    
    if (isset($_SESSION['id'])){
      $record = $this->UserModel->read(0, 0, ['id' => $_GET['id']]);
      $messages = $this->MessageModel->read(0, 0, ['receiver_id' => $_GET['id']]);

      if (isset($messages)){
        for($i = 0; $i < count($messages); $i++){
          $user = $this->UserModel->read(0,0, ['id' => $messages[$i]['sender_id']]);
          $messages[$i]['name'] = $user[0]['full_name'];
        }
      }
  
      // print_r($messages);

      $data = [
        'Users.profile' => [
          "record" => (isset($record) ? ($record[0] ?? []) : []),
          "tab" => "info",
          "messages" => $messages
        ]
      ];
      return $this->view(['Users.profile', 'application'], $data);

    } else {
      header('Location: ./?controller=Auth&action=login');
    }
  }

  public function view_avatar_form(){
    return $this->view(['Users.avatar_form'], []);
  }

  public function updateProfile(){
    if (isset($_SESSION['id'])){
      // Handle avatar upload
      if (isset($_FILES['avt']) && $_FILES['avt']['error'] === UPLOAD_ERR_OK){
        $avt_directory = "uploads/avatars/";
        $fileType = strtolower(pathinfo($_FILES['avt']['name'], PATHINFO_EXTENSION));
        //Check file formats
        if ($fileType == "jpg" || $fileType == "png" || $fileType == "jpeg" || $fileType == "gif"){
          // Delete all old avatar of user
          foreach (glob($avt_directory . $_SESSION["id"] . ".*") as $filename){
            unlink($filename);
          }
          // create new avatar file and store the path in DB
          $target = $avt_directory . $_SESSION['id'] .'.'. $fileType;
          if (move_uploaded_file($_FILES["avt"]["tmp_name"], $target)){
            
          }
          $_POST['avt_url'] = $target;
        } else {
          return false;
        }
        
      }
      //Handle info update
      $result = $this->UserModel->update($_SESSION['id'], $_POST);
      if ($result){
        if (isset($_POST['avt_url'])){
          $_SESSION['avatar'] = $_POST['avt_url'];
        } 
      } 
    } else {
      header('Location: ./?controller=Auth&action=login');
    }
    
  }

  public function changePass(){
    if (isset($_SESSION['id'])){
      $user = $this->UserModel->read(0, 0, ['id' => $_SESSION['id']])[0];
      if ($user['password'] === $_POST["old_pass"]){
        return $this->UserModel->update($_SESSION['id'], ["password" => $_POST["new_pass"]]);
      } else {
        return false;
      }
    } else {
      header('Location: ./?controller=Auth&action=login');
    }
  }

  


  
}

?>