<?php

class SubmissionController extends BaseController {

  private $SubmissionModel;

  public function __construct(){
    $this->loadModel('SubmissionModel');
    $this->SubmissionModel = new SubmissionModel;
  }


  public function viewForm(){
    if (isset($_SESSION['id'])){
        if (isset($_GET['id'])) {
          $message = $this->SubmissionModel->read(0, 0, ['id' => $_GET['id']]);
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

  public function add_update(){
    if (isset($_SESSION['id'])){
      $submission = $this->SubmissionModel->read(0 , 0, [
        'assignment_id' => $_POST['assignment_id'],
        'student_id' => $_SESSION['id']
      ]);

      if (!$submission) {
        if (isset($_FILES['submission_file']) && $_FILES['submission_file']['error'] === UPLOAD_ERR_OK){
          $submission_directory = "uploads/submissions/";
          $fileType = strtolower(pathinfo($_FILES['submission_file']['name'], PATHINFO_EXTENSION));
          
          $file_types = array('txt', 'doc', 'docx', 'xlsx', 'pptx', 'pdf', 'jpg', 'png');
          //Check file formats
          if (in_array($fileType, $file_types)){

            // create new avatar file and store the path in DB
            $target = $submission_directory . $_FILES['submission_file']['name'];
            if (move_uploaded_file($_FILES["submission_file"]["tmp_name"], $target)){
              
            }
            $_POST['file_url'] = $target;
            $_POST['student_id'] = $_SESSION['id'];
            return $this->SubmissionModel->create($_POST); 
          } else {
            return false;
          }
        }
      } else {
        if (isset($_FILES['submission_file']) && $_FILES['submission_file']['error'] === UPLOAD_ERR_OK){
          $submission_directory = "uploads/submissions/";
          $fileType = strtolower(pathinfo($_FILES['submission_file']['name'], PATHINFO_EXTENSION));
          
          $file_types = array('txt', 'doc', 'docx', 'xlsx', 'pptx', 'pdf', 'jpg', 'png');
          //Check file formats
          if (in_array($fileType, $file_types)){
            unlink($submission[0]['file_url']);
            // create new avatar file and store the path in DB
            $target = $submission_directory . $_FILES['submission_file']['name'];
            if (move_uploaded_file($_FILES["submission_file"]["tmp_name"], $target)){
              
            }
            $_POST['file_url'] = $target;
            return $this->SubmissionModel->update($submission[0]['id'], $_POST); 
          } else {
            return false;
          }
        }
      }
    } else {
      header('Location: ./?controller=Auth&action=login');
    }
  }

  public function add(){
    if (isset($_SESSION['id'])){
      return $result = $this->SubmissionModel->create($_POST);
    } else {
      header('Location: ./?controller=Auth&action=login');
    }
    
  }

  public function update(){
    if (isset($_SESSION['id'])){
      if (isset($_GET['id'])){
        $message = $this->SubmissionModel->read(0,0, ['id' => $_GET["id"]]);
        if ($_SESSION['id'] === $message[0]['sender_id']) {
          $result = $this->SubmissionModel->update($_GET["id"], $_POST);
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
          $submission = $this->SubmissionModel->read(0, 0, ['id' => $_GET['id']]);
          if ($submission){
            if ($_SESSION['id'] === $submission[0]['student_id']){
              return $this->SubmissionModel->delete($_GET['id']); 
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