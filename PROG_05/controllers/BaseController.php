<?php
class BaseController{

  const VIEW_FOLDER_NAME = 'views';
  const MODEL_FOLDER_NAME = 'models';

  protected function view($views = [], array $data = []){
    
    
    if (in_array('application', $views)) {
      ob_start();
    }
    
    foreach ($views as $view) {
      if ($view !== 'application') {
        $viewPath = self::VIEW_FOLDER_NAME . '/' . str_replace('.', '/', $view) . '.php';
        $viewData = isset($data[$view]) ? $data[$view] : [];
        extract($viewData);
        require_once($viewPath);
      }
    }
    
    if (in_array('application', $views)) {
      $content = ob_get_clean();
      require_once('views/application.php');
    } 
  }

  protected function loadModel($modelPath){
    $modelPath = self::MODEL_FOLDER_NAME . '/' . str_replace('.', '/', $modelPath) . '.php';
    require($modelPath);
  }
}



?>