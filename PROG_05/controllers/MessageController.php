<?php

class MessageController extends BaseController {

  private $MessageModel;

  public function __construct(){
    $this->loadModel('MessageModel');
    $this->MessageModel = new MessageModel;
  }


  public function viewForm(){
    if (isset($_SESSION['id'])){
        if (isset($_GET['id'])) {
          $message = $this->MessageModel->read(0, 0, ['id' => $_GET['id']]);
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
      return $result = $this->MessageModel->create($_POST);
    } else {
      header('Location: ./?controller=Auth&action=login');
    }
    
  }

  public function update(){
    if (isset($_SESSION['id'])){
      if (isset($_GET['id'])){
        $message = $this->MessageModel->read(0,0, ['id' => $_GET["id"]]);
        if ($_SESSION['id'] === $message[0]['sender_id']) {
          $result = $this->MessageModel->update($_GET["id"], $_POST);
          return $result;
        } else {
          return false;
        }
      }  
    } else {
      header('Location: ./?controller=Auth&action=login');
    }
    
  }

  public function delete(){
    if (isset($_SESSION['id'])){
        if (isset($_GET['id'])){
          $message = $this->MessageModel->read(0, 0, ['id' => $_GET['id']]);
          if ($message){
            if ($_SESSION['id'] === $message[0]['sender_id']){
              return $this->MessageModel->delete($_GET['id']); 
            } else {
              return false;
            }
          } else {
            return false;
          }
          
        }
    } else {
      header('Location: ./?controller=Auth&action=login');
    }
     
  }

}

?>