<?php

class AuthController extends BaseController {

  private $UserModel;

  public function __construct(){
    $this->loadModel('UserModel');
    $this->UserModel = new UserModel;
  }

  public function login($success = "") {
    if (!isset($_SESSION['id'])){
      return $this->view(['Auth.login']);
    } else {
      header('Location: ./?controller=User&action=profile&id='.$_SESSION['id']);
    }
  }

  public function dologin($success = "") {
    if (!isset($_SESSION['id'])){
      $result = $this->UserModel->checkCredentials($_POST['username'], $_POST['password']);
      if ($result){
        session_regenerate_id(true);
        $user = $this->UserModel->read(0,0,['username' => $_POST['username']])[0]; 
        $_SESSION['id'] = $user['id']; 
        $_SESSION['avatar'] = $user['avt_url'];
        $_SESSION['role'] = $user['role']; 
        $_SESSION['full_name'] = $user['full_name'];
        header("Location: ./?controller=User&action=profile&id=" . $_SESSION['id']);
      } else {
        header('Location: ./?controller=Auth&action=login');
      }
    } else {
      header('Location: ./?controller=User&action=profile&id=' . $_SESSION['id']);
    }
  }

  public function logout(){
    session_unset();
    session_destroy();  
    header('Location: ./?controller=Auth&action=login');
  }
}

?>