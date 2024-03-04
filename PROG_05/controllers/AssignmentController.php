<?php

class AssignmentController extends BaseController {

  private $UserModel;
  private $AssignmentModel;
  private $SubmissionModel;

  public function __construct(){
    $this->loadModel('UserModel');
    $this->loadModel('AssignmentModel');
    $this->loadModel('SubmissionModel');
    $this->UserModel = new UserModel;
    $this->AssignmentModel = new AssignmentModel;
    $this->SubmissionModel = new SubmissionModel;
  }


  public function assignment_list(){
    if (isset($_SESSION['id'])){
      if (isset($_GET["page"])) {
        $page = $_GET["page"];
      } else {
        $page = 1;
      }
  
      $recordPerPage = 8;
  
  
      $result = $this->AssignmentModel->read(0, 0, []); // Read all record
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
      $main_record = $this->AssignmentModel->read($page, $recordPerPage, []);
      
      if (isset($main_record)){
        for($i = 0; $i < count($main_record); $i++){
          $user = $this->UserModel->read(0,0, ['id' => $main_record[$i]['teacher_id']]);
          $main_record[$i]['name'] = $user[0]['full_name'];
        }
      }

      $data = [
        'Assignments.assignment_list' => [
          "assignments" => $main_record,
          "num_page" => ceil($total_record / $recordPerPage),
          "page" => $page
        ]
      ];
      return $this->view(['Assignments.assignment_list', 'application'], $data );

    } else {
      header('Location: ./?controller=Auth&action=login');
    }
  }

  public function assignment_detail(){
    if (isset($_SESSION['id'])){
        if (isset($_GET['id'])) {
          $assignment = $this->AssignmentModel->read(0, 0, ['id' => $_GET['id']]);
          if (isset($assignment)){
            $user = $this->UserModel->read(0,0, ['id' => $assignment[0]['teacher_id']]);
            $assignment[0]['name'] = $user[0]['full_name'];
          }

          $file_types = array(
            'txt' => 'Text',
            'doc' => 'Word',
            'docx' => 'Word',
            'xlsx' => 'Excel',
            'pptx' => 'PowerPoint',
            'pdf' => 'PDF',
            'jpg' => 'JPEG Image',
            'png' => 'PNG Image'
          );

          $assignment[0]['file_name'] = basename($assignment[0]['file_url']);
          $file_extension = pathinfo($assignment[0]['file_url'], PATHINFO_EXTENSION);
          $assignment[0]['file_type'] = isset($file_types[$file_extension]) ? $file_types[$file_extension] : 'Unknown';
          $assignment[0]['date'] = date('d/m/Y',strtotime($assignment[0]['created_time']));
          
          $submission = $this->SubmissionModel->read(0 , 0, [
            'assignment_id' => $_GET['id'],
            'student_id' => $_SESSION['id']
          ]);

          if ($submission) {
            $submission[0]['file_name'] = basename($submission[0]['file_url']);
            $file_extension = pathinfo($submission[0]['file_url'], PATHINFO_EXTENSION);
            $submission[0]['file_type'] = isset($file_types[$file_extension]) ? $file_types[$file_extension] : 'Unknown';
          }

          $submission_list = $this->SubmissionModel->read(0, 0, ['assignment_id' => $_GET['id']]);
          if ($submission_list) {
            for($i = 0; $i < count($submission_list); $i++){
              $user = $this->UserModel->read(0,0, ['id' => $submission_list[$i]['student_id']]);
              $submission_list[$i]['name'] = $user[0]['full_name'];

              $submission_list[$i]['file_name'] = basename($submission_list[$i]['file_url']);
              $file_extension = pathinfo($submission_list[$i]['file_url'], PATHINFO_EXTENSION);
              $submission_list[$i]['file_type'] = isset($file_types[$file_extension]) ? $file_types[$file_extension] : 'Unknown';
              $submission_list[$i]['date'] = date('d/m/Y',strtotime($submission_list[$i]['submitted_time']));
            }
          }
          $data = [
            'Assignments.assignment_detail' => [
              "assignment" => (isset($assignment) ? ($assignment[0] ?? []) : []),
              "submission" => (isset($submission) ? $submission[0] : null),
              "submission_list" => $submission_list
            ]
          ];
        } else {
          $data = [];
        }
        return $this->view(['Assignments.assignment_detail', 'application'], $data);

    } else {
      header('Location: ./?controller=Auth&action=login');
    } 
  }

  public function assignment_form(){
    if (isset($_SESSION['id'])){
      if (isset($_SESSION['role']) && $_SESSION['role'] === 'teacher'){
        if (isset($_GET['id'])) {
          $assignment = $this->AssignmentModel->read(0, 0, ['id' => $_GET['id']]);
          $data = [
            'Assignments.popup_form' => [
              "assignment" => (isset($assignment) ? ($assignment[0] ?? []) : [])
            ]
          ];
        } else {
          $data = [];
        }
        return $this->view(['Assignments.popup_form'], $data);
      }
      
    } else {
      header('Location: ./?controller=Auth&action=login');
    } 
  }


  public function add(){
    if (isset($_SESSION['id'])){
      if (isset($_SESSION['role']) && $_SESSION['role'] === 'teacher'){
        if (isset($_FILES['assignment_file']) && $_FILES['assignment_file']['error'] === UPLOAD_ERR_OK){
          $assignment_directory = "uploads/assignments/";
          $fileType = strtolower(pathinfo($_FILES['assignment_file']['name'], PATHINFO_EXTENSION));
          
          $file_types = array('txt', 'doc', 'docx', 'xlsx', 'pptx', 'pdf', 'jpg', 'png');
          //Check file formats
          if (in_array($fileType, $file_types)){

            // create new avatar file and store the path in DB
            $target = $assignment_directory . $_FILES['assignment_file']['name'];
            if (move_uploaded_file($_FILES["assignment_file"]["tmp_name"], $target)){
              
            }
            $_POST['file_url'] = $target;
            return $this->AssignmentModel->create($_POST); 
          } else {
            return false;
          }
        }
         
      } else {
        header('Location: ./?controller=Auth&action=login');
      }
    } else {
      header('Location: ./?controller=Auth&action=login');
    }
    
  }

  public function update(){
    if (isset($_SESSION['id'])){
      if (isset($_SESSION['role']) && $_SESSION['role'] === 'teacher'){
        if (isset($_GET['id'])){
          $assignment = $this->AssignmentModel->read(0,0, ['id' => $_GET["id"]]);
          if ($assignment){
            if ($_SESSION['id'] === $assignment[0]['teacher_id']){

              if (isset($_FILES['assignment_file']) && $_FILES['assignment_file']['error'] === UPLOAD_ERR_OK){
                $assignment_directory = "uploads/assignments/";
                $fileType = strtolower(pathinfo($_FILES['assignment_file']['name'], PATHINFO_EXTENSION));
                
                $file_types = array('txt', 'doc', 'docx', 'xlsx', 'pptx', 'pdf', 'jpg', 'png');
                //Check file formats
                if (in_array($fileType, $file_types)){
                  unlink($assignment[0]['file_url']);
                  // create new avatar file and store the path in DB
                  $target = $assignment_directory . $_FILES['assignment_file']['name'];
                  if (move_uploaded_file($_FILES["assignment_file"]["tmp_name"], $target)){
                    
                  }
                  
                  $_POST['file_url'] = $target;
                   
                } else {
                  return false;
                }
              }
              return $this->AssignmentModel->update($_GET['id'], $_POST);

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
    } else {
      header('Location: ./?controller=Auth&action=login');
    }
    
  }

  public function delete(){
    if (isset($_SESSION['id'])){
        if (isset($_GET['id'])){
          $assignment = $this->AssignmentModel->read(0, 0, ['id' => $_GET['id']]);
          if ($assignment){
            if ($_SESSION['id'] === $assignment[0]['teacher_id']){
              unlink($assignment[0]['file_url']);
              return $this->AssignmentModel->delete($_GET['id']); 
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