<?php

class RecordController extends BaseController {

  private $recordModel;

  public function __construct(){
    $this->loadModel('RecordModel');
    $this->recordModel = new RecordModel;
  }

  public function index($success = "") {
    if (isset($_SESSION['id'])){
      if (isset($_GET["page"])) {
        $page = $_GET["page"];
      } else {
        $page = 1;
      }
  
      $recordPerPage = 10;
  
      //Select Filter Field for option
      $typeOption = $this->recordModel->selectFilter("type");
      $typeOptionData = [];
      foreach ($typeOption as $row){
        $typeOptionData[] = $row["type"];
      }
  
      $platformOption = $this->recordModel->selectFilter("platform");
      $platformOptionData = [];
      foreach ($platformOption as $row){
        $platformOptionData[] = $row["platform"];
      }
  
      // Filter Field
      $filterCondition = [];
      $filterField = ["vendor", "type", "platform", "Verified", "Mestasploit"];
      foreach ($filterField as $filterValue){
        if (isset($_GET[$filterValue])){
          $filterCondition += [$filterValue => $_GET[$filterValue]];
        }
      }
  
      $searchKey = (isset($_GET["search_input"]) ? $_GET["search_input"] : "");
  
      $result = $this->recordModel->read(0, 0, $filterCondition, $searchKey); // Read all record
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
      $main_record = $this->recordModel->read($page, $recordPerPage, $filterCondition, $searchKey);
      return $this->view('Records.index', [
        "record" => $main_record,
        "num_page" => ceil($total_record / $recordPerPage),
        "page" => $page,
        "success" => $success,
        "typeOption" => $typeOptionData,
        "platformOption" => $platformOptionData
      ]);
    } else {
      header('Location: ./?controller=Auth&action=login');
    }
    
  }


  public function add(){
    if (isset($_SESSION['id'])){
      return $this->view('Records.addForm');
    } else {
      header('Location: ./?controller=Auth&action=login');
    }
    
  }

  public function doAdd(){
    if (isset($_SESSION['id'])){
      $result = $this->recordModel->create($_POST);
      return $this->view('Records.addForm', [
        "msg" => $result,
        "method" => "Add"
      ]);
    } else {
      header('Location: ./?controller=Auth&action=login');
    }
    
  }


  public function update(){ 
    if (isset($_SESSION['id'])){
      if (isset($_GET['id'])){
        $record = $this->recordModel->read(0, 0, ['CVE_ID' => $_GET['id']]);
        return $this->view('Records.addForm',[
          "record" => (isset($record) ? ($record[0] ?? []) : [])
        ]);
      } 
    } else {
      header('Location: ./?controller=Auth&action=login');
    } 
  }

  public function doUpdate(){
    if (isset($_SESSION['id'])){
      $result = $this->recordModel->update($_GET["id"], $_POST);
      if ($result == "Success"){
        $record = $this->recordModel->read(0, 0, ['CVE_ID' => $_POST['CVE_ID']]);
      } else {
        $record = $this->recordModel->read(0, 0, ['CVE_ID' => $_GET['id']]);
      }
      
      return $this->view('Records.addForm', [
        "record" => $record[0],
        "msg" => $result,
        "method" => "Update"
      ]);
    } else {
      header('Location: ./?controller=Auth&action=login');
    }
    
  }

  public function delete(){
    if (isset($_SESSION['id'])){
      if (isset($_GET['id'])){
        $existingRecord = $this->recordModel->read(0, 0, ['CVE_ID' => $_GET['id']]);
        if ($existingRecord){
          if ($this->recordModel->delete($_GET['id'])){
            return $this->index("true");
          }
        }
      } 
      return $this->index("false");
    } else {
      header('Location: ./?controller=Auth&action=login');
    } 
    
    
  }

  public function download(){
    if (isset($_SESSION['id'])){
      if (isset($_GET['id'])){
        $record = $this->recordModel->read(0, 0, ['CVE_ID' => $_GET['id']]);
        $payload = $record[0]["payload"];
        $fileName = $_GET['id'] . ".txt";
        echo "$fileName";
  
        $isSaved = file_put_contents($fileName, $payload);
  
        if ($isSaved !== false) {
          // Set the appropriate headers for file download
          header("Content-Type: application/octet-stream");
          header("Content-Disposition: attachment; filename=" . $fileName);
          header('Expires: 0');
          header('Cache-Control: must-revalidate');
          header('Pragma: public');
          header("Content-Length: " . filesize($fileName));
      
          // Read the file and output it to the browser
          readfile($fileName);
      
          // Delete the file after download
          unlink($fileName);
          exit;
        } else {
          echo "Failed to save the content to a file.";
        }
      }
    } else {
      header('Location: ./?controller=Auth&action=login');
    }  
  }


  
}

?>