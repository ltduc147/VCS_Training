<?php

class ChallengeController extends BaseController {

  private $UserModel;
  private $ChallengeModel;

  public function __construct(){
    $this->loadModel('UserModel');
    $this->loadModel('ChallengeModel');
    $this->UserModel = new UserModel;
    $this->ChallengeModel = new ChallengeModel;
  }


  public function challenge_list(){
    if (isset($_SESSION['id'])){
      if (isset($_GET["page"])) {
        $page = $_GET["page"];
      } else {
        $page = 1;
      }
  
      $recordPerPage = 8;
  
  
      $result = $this->ChallengeModel->read(0, 0, []); // Read all record
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
      $main_record = $this->ChallengeModel->read($page, $recordPerPage, []);
      
      if (isset($main_record)){
        for($i = 0; $i < count($main_record); $i++){
          $user = $this->UserModel->read(0,0, ['id' => $main_record[$i]['teacher_id']]);
          $main_record[$i]['name'] = $user[0]['full_name'];
        }
      }

      $data = [
        'Challenges.challenge_list' => [
          "challenges" => $main_record,
          "num_page" => ceil($total_record / $recordPerPage),
          "page" => $page
        ]
      ];
      return $this->view(['Challenges.challenge_list', 'application'], $data );

    } else {
      header('Location: ./?controller=Auth&action=login');
    }
  }

  public function challenge_detail(){
    if (isset($_SESSION['id'])){
        if (isset($_GET['id'])) {
          $challenge = $this->ChallengeModel->read(0, 0, ['id' => $_GET['id']]);
          if (isset($challenge)){
            $user = $this->UserModel->read(0,0, ['id' => $challenge[0]['teacher_id']]);
            $challenge[0]['name'] = $user[0]['full_name'];
          }

          $file_types = array(
            'txt' => 'Text'
          );

          $challenge[0]['file_name'] = basename($challenge[0]['file_url']);
          $file_extension = pathinfo($challenge[0]['file_url'], PATHINFO_EXTENSION);
          $challenge[0]['file_type'] = isset($file_types[$file_extension]) ? $file_types[$file_extension] : 'Unknown';
          $challenge[0]['date'] = date('d/m/Y',strtotime($challenge[0]['created_time']));
          
          
          $data = [
            'Challenges.challenge_detail' => [
              "challenge" => (isset($challenge) ? ($challenge[0] ?? []) : [])
            ]
          ];
        } else {
          $data = [];
        }
        return $this->view(['Challenges.challenge_detail', 'application'], $data);

    } else {
      header('Location: ./?controller=Auth&action=login');
    } 
  }

  public function challenge_form(){
    if (isset($_SESSION['id'])){
      if (isset($_SESSION['role']) && $_SESSION['role'] === 'teacher'){
        if (isset($_GET['id'])) {
          $challenge = $this->ChallengeModel->read(0, 0, ['id' => $_GET['id']]);
          $data = [
            'Challenges.popup_form' => [
              "challenge" => (isset($challenge) ? ($challenge[0] ?? []) : [])
            ]
          ];
        } else {
          $data = [];
        }
        return $this->view(['Challenges.popup_form'], $data);
      }
      
    } else {
      header('Location: ./?controller=Auth&action=login');
    } 
  }

  public function check_answer(){
    if (isset($_SESSION['id'])){
        if (isset($_GET['id'])) {
          $challenge = $this->ChallengeModel->read(0, 0, ['id' => $_GET['id']]);
          if ($challenge){
            $filename = pathinfo($challenge[0]['file_url'], PATHINFO_FILENAME);
            
            if ($_POST['answer'] === $filename){
              $file_content = file_get_contents('./' . $challenge[0]['file_url']);  
              //$file_content = str_replace(array("\r\n", "\r"), "\n", $file_content);
              echo $file_content;
              
            }
          }
        }
      
    } else {
      header('Location: ./?controller=Auth&action=login');
    } 
  }


  public function add(){
    if (isset($_SESSION['id'])){
      if (isset($_SESSION['role']) && $_SESSION['role'] === 'teacher'){
        if (isset($_FILES['challenge_file']) && $_FILES['challenge_file']['error'] === UPLOAD_ERR_OK){
          $challenge_directory = "uploads/challenges/";
          $fileType = strtolower(pathinfo($_FILES['challenge_file']['name'], PATHINFO_EXTENSION));
          
          $file_types = array('txt', 'doc', 'docx', 'xlsx', 'pptx', 'pdf');
          //Check file formats
          if (in_array($fileType, $file_types)){

            // create new avatar file and store the path in DB
            $target = $challenge_directory . $_FILES['challenge_file']['name'];
            if (move_uploaded_file($_FILES["challenge_file"]["tmp_name"], $target)){
              
            }
            $_POST['file_url'] = $target;
            return $this->ChallengeModel->create($_POST); 
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
          $challenge = $this->ChallengeModel->read(0,0, ['id' => $_GET["id"]]);
          if ($challenge){
            if ($_SESSION['id'] === $challenge[0]['teacher_id']){

              if (isset($_FILES['challenge_file']) && $_FILES['challenge_file']['error'] === UPLOAD_ERR_OK){
                $assignment_directory = "uploads/challenges/";
                $fileType = strtolower(pathinfo($_FILES['challenge_file']['name'], PATHINFO_EXTENSION));
                
                $file_types = array('txt', 'doc', 'docx', 'xlsx', 'pptx', 'pdf', 'jpg', 'png');
                //Check file formats
                if (in_array($fileType, $file_types)){
                  unlink($challenge[0]['file_url']);
                  // create new avatar file and store the path in DB
                  $target = $assignment_directory . $_FILES['challenge_file']['name'];
                  if (move_uploaded_file($_FILES["challenge_file"]["tmp_name"], $target)){
                    
                  }
                  
                  $_POST['file_url'] = $target;
                   
                } else {
                  return false;
                }
              }
              return $this->ChallengeModel->update($_GET['id'], $_POST);

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
          $challenge = $this->ChallengeModel->read(0, 0, ['id' => $_GET['id']]);
          if ($challenge){
            if ($_SESSION['id'] === $challenge[0]['teacher_id']){
              unlink($challenge[0]['file_url']);
              return $this->ChallengeModel->delete($_GET['id']); 
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