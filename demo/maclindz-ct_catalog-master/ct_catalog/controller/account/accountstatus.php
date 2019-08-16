<?php 
class ControllerAccountAccountstatus extends Controller { 
    public function index() {
        $isLogined = $this->customer->isLogged()?true:false;
        
        $userid = $isLogined?$this->customer->getEmail():null;
        $username = $isLogined?$this->customer->getFirstName()." ".$this->customer->getLastName():null;
        $arr = array ('isLogined' => $isLogined, 'userid' => $userid, "username" => $username);
        
        $out = json_encode($arr);
        $this->response->setOutput($out);
      }
}
?>