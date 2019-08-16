<?php
class ControllerImageImage extends Controller {
	public function index() {
	    $name=$this->request->get['name'];
	    if($name){

            $ext = $this->_getExtension($name);
	        $imageurl = 'http://static.icootoo.com/'.$name;
            if ($ext == 'jpg' || $ext == 'jpeg')
            {
                $content_type = 'image/jpeg';
            }
            if ($ext == 'png')
            {
                $content_type = 'image/png';
            }
            if ($ext == 'gif')
            {
                $content_type = 'image/gif';
            }
            header("content-type:$content_type");
            $contents=file_get_contents($imageurl);
            echo $contents;
        }else{
	        echo '';
        }
	}

    private function _getExtension($string){
        $ext    =   "";
        try{
            $parts  =   explode(".",$string);
            $ext        =   strtolower($parts[count($parts)-1]);
        }catch(Exception $c){
            $ext    =   "";
        }
        return $ext;
    }
	public  function test(){
	    print_r("test");
    }
}