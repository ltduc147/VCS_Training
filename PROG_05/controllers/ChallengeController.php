<?php

class ChallengeController extends BaseController {

  private $ChallengeModel;

  public function __construct(){
    $this->loadModel('ChallengeModel');
    $this->ChallengeModel = new ChallengeModel;
  }


  public function viewForm(){
    if (isset($_SESSION['id'])){
        if (isset($_GET['id'])) {
          $message = $this->ChallengeModel->read(0, 0, ['id' => $_GET['id']]);
          $data = [
            'Messages.popup_form' => [
              "message" => (isset($message) ? ($message[0] ?? []) : [])
            ]
          ];
        } else {
          $data = [];
        }
        return $this->view(['Messages.popup_form'], $data);

    } else {
      header('Location: ./?controller=Auth&action=login');
    } 
  }


  public function add(){
    if (isset($_SESSION['id'])){
      if (isset($_SESSION['role']) && $_SESSION['role'] === 'teacher'){
        return $result = $this->ChallengeModel->create($_POST);
      } else {
        header('Location: ./?controller=Record');
      }
    } else {
      header('Location: ./?controller=Auth&action=login');
    }
    
  }

  public function update(){
    if (isset($_SESSION['id'])){
      if (isset($_SESSION['role']) && $_SESSION['role'] === 'teacher') {
        if (isset($_GET['id'])){
          $message = $this->ChallengeModel->read(0,0, ['id' => $_GET["id"]]);
          if ($_SESSION['id'] === $message[0]['sender_id']) {
            $result = $this->ChallengeModel->update($_GET["id"], $_POST);
            return $result;
          } else {
            return false;
          }
        }
      }  else {
        header('Location: ./?controller=Record');
      }
    } else {
      header('Location: ./?controller=Auth&action=login');
    }
    
  }

  public function delete(){
    if (isset($_SESSION['id'])){
      if (isset($_SESSION['role']) && $_SESSION['role'] === 'teacher') {
        if (isset($_GET['id'])){
          $message = $this->ChallengeModel->read(0, 0, ['id' => $_GET['id']]);
          if ($message){
            if ($_SESSION['id'] === $message[0]['sender_id']){
              return $this->ChallengeModel->delete($_GET['id']); 
            } else {
              return false;
            }
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

}

?>