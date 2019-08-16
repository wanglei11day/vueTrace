
<?php
require DIR_SYSTEM.'library/aws/aws-autoloader.php';
class ControllerApiDesign extends Controller {
    public $files = array();
    public function index() {
        $this->load->model('account/order');
        
        $json = array();
        $token = $this->request->post['token'];
        if($token != 'coto0421pa@ss'){
            $json['status'] = 0;
            $json['data'] = 'token 错误';
        }
        $config_download_status_id = $this->config->get('config_download_status_id');
        $start_order_id = $this->request->post['start_order_id'];
        $end_order_id = $this->request->post['end_order_id'];
        if(!$start_order_id||!$end_order_id)
        {
            $json['status'] = 0;
            $json['data'] = '请输入orderid';
        }
        if(!$json){
            $orders = $this->model_account_order->getTshirtOrders($start_order_id,$end_order_id,$config_download_status_id);
            $url = 'http://www.icootoo.com/tshirtecommerce/design.php?key=';
            $total = 0;
            $list = array();
            foreach ($orders as $order_info) {
                $order_list = array();
                $design_keys = array();
                $order_list['order_id'] = $order_info['order_id'];
                $order_list['username']=$order_info['firstname'];
                $order_list['filename']=$order_info['order_id'].' '.$order_info['firstname'].' '.$order_info['model'];
                if ($order_info)
                {
                    $data['products'] = array();
                    
                    $products = $this->model_account_order->getTshirtOrderProducts($order_info['order_id']);
                    if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
                    if (!defined('ROOT')) define('ROOT', dirname(DIR_SYSTEM) . DS . 'tshirtecommerce');
                    
                    if (file_exists(ROOT . DS . 'includes' . DS . 'functions.php')) {
                        include_once ROOT . DS . 'includes' . DS . 'functions.php';
                        
                        $dg = new dg();
                        $lang = $dg->lang();
                        
                    }
                    
                    $this->load->model('tshirtecommerce/order');
                    
                    
                    foreach ($products as $product) 
                    {
                        $pk = array();
                        $this->load->model('tshirtecommerce/order');
                        
                        $design = $this->model_tshirtecommerce_order->getItem($product['order_product_id'], $product['product_id']);
                        
                        if (isset($dg) && $design !== false && isset($design->rowid)) {
                            if (isset($design->images))
                            {
                                $images = json_decode(str_replace('&quot;', '"', $design->images), true);
                                if (count($images) > 0)
                                {
                                    foreach($images as $view => $src)
                                    {
                                        $pk[] = $url.$design->rowid.'&view='.$view;
                                        $design_keys[] = $url.$design->rowid.'&view='.$view;
                                        $total++;
                                    }
                                }
                            }
                            
                        }
                        if(!$pk){
                            $design_keys[] = $product['pdf'];
                        }

                    }
                    
                }
                $order_list['list'] = $design_keys;
                $list[] = $order_list;
            }
            
            if(!$json){
                $json['status'] = 1;
                $json['total'] = $total;
                $json['list'] = $list;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        echo json_encode($json);
    }


    public function lists() {
        $this->load->model('account/order');
        $url = S3_SERVER.'upload.creator5.permanent/cootoo/creations/';
        $json = array();
        $token = $this->request->post['token'];
        if($token != 'coto0421pa@ss'){
            $json['status'] = 0;
            $json['data'] = 'token 错误';
        }
        $config_download_status_id = $this->config->get('config_download_status_id');
        $start_order_id = $this->request->post['start_order_id'];
        $end_order_id = $this->request->post['end_order_id'];
        if(!$start_order_id||!$end_order_id)
        {
            $json['status'] = 0;
            $json['data'] = '请输入orderid';
        }

        if(!$json){
            $designs = $this->model_account_order->getKeditorOrderDesigns($start_order_id,$end_order_id,$config_download_status_id);

            $url = S3_SERVER.'upload.creator5.permanent/cootoo/creations/';
            $total = 0;
            $data = array();
            foreach ($designs as $design) {
                $orderdata = array();
                $orderdata['order_id'] = $design['order_id'];
                $this->load->model('account/order');
                $order_id = $design['order_id'];
                $content = file_get_contents($url.$design['save_key'].'.json');

                $json_content = json_decode($content,true);
                $pages = $json_content['pages'];
                foreach ($pages as $page)
                {

                    $objcts = $page['objects'];

                    foreach ($objcts as $objct)
                    {
                        $type = $objct['type'];
                        if($type=='photo')
                        {
                            $source_img = $objct['source']['online_url'];
                            if($source_img)
                            {
                                $orderdata['list'][] = $source_img;
                            }
                        }
                    }
                }
                $data[] = $orderdata;
            }
            if(!$json){
                $json['status'] = 1;
                $json['data'] = $data;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        echo json_encode($json);
    }


    public function validDesign() {
        $this->load->model('account/order');
        $url = S3_SERVER.'upload.creator5.permanent/cootoo/creations/';
        $json = array();

        $order_id = $this->request->get['order_id'];
        $order_product_id = $this->request->get['order_product_id'];

        if(!$order_id||!$order_product_id)
        {
            $json['status'] = 0;
            $json['data'] = '请输入orderid';
        }

        if(!$json){
            $design = $this->model_account_order->getKeditorOrderDesign($order_id,$order_product_id);

            $url = S3_SERVER.'upload.creator5.permanent/cootoo/creations/';
            $total = 0;
            $data = array();

            $orderdata = array();
            $orderdata['order_id'] = $design['order_id'];
            $this->load->model('account/order');
            $order_id = $design['order_id'];
            $content = file_get_contents($url.$design['save_key'].'.json');
            $html_image = '';
            $json_content = json_decode($content,true);
            $pages = $json_content['pages'];
            foreach ($pages as $page)
            {

                $objcts = $page['objects'];

                foreach ($objcts as $objct)
                {

                    $type = $objct['type'];
                    if($type=='photo')
                    {

                        $source_img = $objct['source']['online_url'];
                        if($source_img)
                        {
                            if(substr($source_img,0,4)=='http')
                            {
                                $res = $this->testExist($source_img);
                            }
                            else
                            {
                                $res = $this->testExist('http:'.$source_img);
                            }
                            if(!$res)
                            {
                                $json['status'] = 0;
                                $json['data'] = $source_img .' are not exist!!';
                                break;
                            }
                            $html_image .= '<img style="width:100px;height:100px;" src="'.$source_img.'"/>';

                        }

                        $thumb_imgs = $objct['source']['online_thumbs'];
                        foreach ($thumb_imgs as $img)
                        {
                            $thumb_src = $img['url'];
                            if($thumb_src)
                            {

                                if(substr($thumb_src,0,4)=='http')
                                {
                                    $res = $this->testExist($thumb_src);
                                }
                                else
                                {
                                    $res = $this->testExist('http:'.$thumb_src);
                                }
                                if(!$res)
                                {
                                    $json['status'] = 0;
                                    $json['data'] = $thumb_src .' are not exist!!';
                                    break;
                                }

                            }
                            $html_image .= '<img style="width:100px;height:100px;" src="'.$thumb_src.'"/>';
                        }

                    }
                }
            }
            if(!$json){
                $json['status'] = 1;
                $json['data'] = 'valid!!';
            }
        }

        echo json_encode($json);
        if($this->request->get['show'])
        {
            echo $html_image;
        }

    }

    public function testExist($imageurl){
        $http = curl_init($imageurl);
        curl_setopt($http, CURLOPT_HEADER, true);
        curl_setopt($http, CURLOPT_FILETIME, true);
        curl_setopt($http, CURLOPT_NOBODY, true);
        curl_setopt($http, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($http);
        $http_status = curl_getinfo($http, CURLINFO_HTTP_CODE);
        curl_close($http);

        if($http_status == 200)
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    public function upload() {
        $this->load->model('catalog/directory');
        $this->load->language('common/filemanager');

        $json = array();

        // Check user has permission


        // Make sure we have the correct directory
        $directory = 0;
        $cur_path  = 0;


        if (isset($this->request->post['token'])) {
            $token = $this->request->post['token'];
        } else {
            $json['info'] = '没有token';
        }
        // Make sure we have the correct directory
        if (isset($this->request->post['bucket'])) {
            $bucket = $this->request->post['bucket'];
        } else {
           $json['info'] = '没有bucket';
        }

        if (!$json) {
           $files =  $this->request->files;
            foreach ($files as $file) {
                $json = $this->upload_single($file,$bucket);
           }

        }else{
            $json['status'] = 0;
        }


        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    private function upload_single($file,$bucket){
        $json = array();
        $ext = '';
        $filename = $file['name'];
        if (!empty($file['name']) && is_file($file['tmp_name'])) {
            // Sanitize the filename

            // Allowed file extension types
            $allowed = array(
                'jpg',

                'jpeg',
                'gif',
                'png'
            );
            $ext = utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1));
            if (!in_array(utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1)), $allowed)) {
                $json['info'] = $this->language->get('error_filetype');
            }


            // Allowed file mime types
            $allowed = array(
                'image/jpeg',
                'image/pjpeg',
                'image/png',
                'image/x-png',
                'image/gif'
            );

            if (!in_array($file['type'], $allowed)) {
                $json['info'] = $this->language->get('error_filetype');
            }

            // Check to see if any PHP files are trying to be uploaded
            $content = file_get_contents($file['tmp_name']);

            if (preg_match('/\<\?php/i', $content)) {
                $json['info'] = $this->language->get('error_filetype');
            }

            // Return any upload error
            if ($file['error'] != UPLOAD_ERR_OK) {
                $json['info'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
            }
        } else {
            $json['info'] = $this->language->get('error_upload');

        }

        if(!$json)
        {

            $key = basename(html_entity_decode($filename, ENT_QUOTES, 'UTF-8'));
            $key = substr($key,0,strrpos($key,'.'));
            $filename = $key.'_original.'.$ext;
            if(!is_dir(DIR_UPLOAD.'buckets/'.$bucket))
            {
                mkdir(DIR_UPLOAD.'buckets/'.$bucket,0777,true);
            }
            move_uploaded_file($file['tmp_name'],DIR_UPLOAD.'buckets/'.$bucket.'/'.$filename);
            $json['status'] = 1;
            return $json;

        }else{
            $json['status'] = 0;
        }
        return $json;
    }

    public function syncFileJob()
    {

        set_time_limit(0);
        $dir = DIR_UPLOAD.'buckets/';
        $this->syncFile($dir,20,'');
        $success = 0;
        $failed = 0;
        $time = time();
        if(count($this->files)==0)
        {
            return;
        }
        file_put_contents($dir.'/uploading-'.$time.'.log',date('Y-m-d H:i:s').":".'begin --------------'.count($this->files).PHP_EOL,FILE_APPEND) ;
        foreach ($this->files as $file)
        {

            file_put_contents($dir.'/uploading-'.$time.'.log',date('Y-m-d H:i:s').":".'begin '.$file['path'].PHP_EOL,FILE_APPEND) ;

            $res = $this->doMove($file['bucket'],$file['path']);
            if($res)
            {
                $success++;
            }
            else
            {
                $failed++;
            }
            file_put_contents($dir.'/uploading-'.$time.'.log',date('Y-m-d H:i:s').":".'end '.$file['path'].PHP_EOL,FILE_APPEND) ;
        }
        file_put_contents($dir.'/uploading-'.$time.'.log',date('Y-m-d H:i:s').":".json_encode(array('success'=>$success,'failed'=>$failed)).PHP_EOL,FILE_APPEND) ;
    }






    public function syncFile($dir,$count,$bucket)
    {


        //打开目录

        $handle = opendir($dir);

        while (($file = readdir($handle)) !== false) {

            //排除掉当前目录和上一个目录

            if ($file == "." || $file == "..") {

                continue;

            }


            $filepath = $dir . DIRECTORY_SEPARATOR . $file;

            //如果是文件就打印出来，否则递归调用

            if (is_file($filepath)) {
                if(strpos($file,'uploading-')!==false) continue;
                rename($filepath,dirname($filepath).'/uploading-'.basename($filepath));
                $this->files[] = array('path'=>'uploading-'.$file,'bucket'=>$bucket);
                $count--;
                if($count<0) return;

            } elseif (is_dir($filepath)) {

                $this->syncFile($filepath,$count,$file);

            }

        }

    }

    public function doMove($bucket,$filename)
    {

        $ext = utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1));

        $srcfile = DIR_UPLOAD.'buckets'.DIRECTORY_SEPARATOR.$bucket.DIRECTORY_SEPARATOR.$filename;
        $filename = str_replace('uploading-','',$filename);
        $key = substr($filename,0,strrpos($filename,'_'));
        $key = str_replace('_','/',$key);
        $orig_name = str_replace('_','/',$filename);
        $upload_status = $this->mv_file_to_s3($bucket,$srcfile , $orig_name,$ext);  //上传到亚马逊
        $im='';
        $width=0;
        $height = 0;
        if($upload_status){
            if ($ext == 'gif') {
                $im = imagecreatefromgif($srcfile);
            } elseif ($ext == 'png') {
                $im = imagecreatefrompng($srcfile);
                imagesavealpha($im, true);
            } elseif ($ext == 'jpeg'||$ext == 'jpg') {
                $im = imagecreatefromjpeg($srcfile);
            }
            if($im){
                $width=imagesx($im);
                $height = imagesy($im);
            }
            $new_filename = $this->changeSize($im,120,120,$ext);
            $filename = $key.'/120.'.$ext;
            $upload_status = $this->mv_file_to_s3($bucket,$new_filename , $filename,$ext);  //上传缩略图到亚马逊
            if($upload_status)
            {
                unlink($new_filename);
            }
            else
            {
                return 0;
            }
            $new_filename = $this->changeSize($im,960,960,$ext);

            $filename = $key.'/960.'.$ext;
            $upload_status = $this->mv_file_to_s3($bucket,$new_filename , $filename,$ext);  //上传缩略图到亚马逊

            imagedestroy($im);
            if($upload_status)
            {
                unlink($new_filename);
                unlink($srcfile);
            }
            else
            {
                return 0;
            }
            $json['info'] = $this->language->get('text_uploaded');
            $json['status'] = 1;
        }
        else
        {
            return 0;
        }
        return 1;
    }

    /**
     * 居中裁剪图片
     * @param string $source [原图路径]
     * @param int $width [设置宽度]
     * @param int $height [设置高度]
     * @param string $target [目标路径]
     * @return bool [裁剪结果]
     */
    function image_center_crop($image, $width, $height,$filetype)
    {
        $new_name = tempnam($this->config->get('DIR_UPLOAD'),'tmp_');
        $name = $new_name.'.'.$filetype;
        /* 获取图像尺寸信息 */
        $target_w = $width;
        $target_h = $height;
        $source_w = imagesx($image);
        $source_h = imagesy($image);
        /* 计算裁剪宽度和高度 */
        $judge = (($source_w / $source_h) > ($target_w / $target_h));
        $resize_w = $judge ? ($source_w * $target_h) / $source_h : $target_w;
        $resize_h = !$judge ? ($source_h * $target_w) / $source_w : $target_h;
        $start_x = $judge ? ($resize_w - $target_w) / 2 : 0;
        $start_y = !$judge ? ($resize_h - $target_h) / 2 : 0;
        /* 绘制居中缩放图像 */
        $resize_img = imagecreatetruecolor($resize_w, $resize_h);
        imagecopyresampled($resize_img, $image, 0, 0, 0, 0, $resize_w, $resize_h, $source_w, $source_h);
        $target_img = imagecreatetruecolor($target_w, $target_h);
        imagecopy($target_img, $resize_img, 0, 0, $start_x, $start_y, $resize_w, $resize_h);
        /* 将图片保存至文件 */
        switch ( $filetype ) {
            case 'gif':   imagegif($target_img, $name);    break;
            case 'jpg':  imagejpeg($target_img, $name, 100);   break;
            case 'jpeg':  imagejpeg($target_img, $name, 100);   break;
            case 'png':
                imagepng($target_img, $name, 9);
                break;
            default: return false;
        }
        imagedestroy($target_img);
        unlink($new_name);
        return $name;

    }


    //方法参数为图片名
    function changeSize($image, $width, $height,$filetype)
    {
        $new_name = tempnam($this->config->get('DIR_UPLOAD'),'tmp_');
        $name = $new_name.'.'.$filetype;
        /* 获取图像尺寸信息 */
        $target_w = $width;
        $target_h = $height;
        $source_w = imagesx($image);
        $source_h = imagesy($image);

        /* 计算裁剪宽度和高度 */


        //缩略图最大宽度与最大高度比
        $thcrown = $target_w/$target_h;
        //原图宽高比
        $crown = $source_w/$source_h;
        if($crown/$thcrown >= 1){
            $resize_w = $target_w;
            $resize_h = $target_w/$crown;
        } else {
            $resize_h = $target_h;
            $resize_w = $target_h*$crown;
        }
        imagesavealpha($image,true);
        if(function_exists("imagecopyresampled"))
        {
            $target_img = imagecreatetruecolor($resize_w,$resize_h);
            imagealphablending($target_img,false);//这里很重要,意思是不合并颜色,直接用$img图像颜色替换,包括透明色;
            imagesavealpha($target_img,true);//这里很重要,意思是不要丢了$thumb图像的透明色;
            imagecopyresampled($target_img,$image,0,0,0,0,$resize_w,$resize_h,$source_w,$source_h);//
        }
        else
        {
            $target_img = imagecreate($resize_w,$resize_h);
            imagecopyresized($target_img,$image,0,0,0,0,$resize_w,$resize_h,$source_w,$source_h);
        }

        /* 将图片保存至文件 */
        switch ( $filetype ) {
            case 'gif':   imagegif($target_img, $name);    break;
            case 'jpg':  imagejpeg($target_img, $name, 100);   break;
            case 'jpeg':  imagejpeg($target_img, $name, 100);   break;
            case 'png':

                imagepng($target_img, $name);
                break;
            default: return false;
        }
        imagedestroy($target_img);
        unlink($new_name);
        return $name;
    }

    private function mv_file_to_s3($bucket,$filename,$file,$ext){
        error_reporting(0);
        $content_type = '';
        if(!is_file($filename)) return false;

        $s3 = new Aws\S3\S3Client([
            'version' => 'latest',
            'region'  => 'cn-north-1',
            'ContentType'  => $content_type,
            'http'    => [
                'verify' => false
            ],
            'credentials' => [
                'key'    => 'AKIAOZKKEKG7GK5MPI6A',
                'secret' => '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'
            ]
        ]);


        $filebody = fopen($filename, 'r');

        $result = $s3->putObject([
            'Bucket' => $bucket,
            'Key'    => $file,
            'ACL'    => 'public-read',
            'Body'   => $filebody,
            'ContentType'  => $ext,
        ]);

        fclose($filebody);
        //print_r($result);
        return true;
    }
    public function finishDownload() {
        $json = array();
        $token = $this->request->get['token'];
        if($token != 'coto0421pa@ss'){
            $json['status'] = 0;
            $json['data'] = 'token 错误';
        }
        if(!$json){
            $this->load->model('account/order');
            $order_id = $this->request->get['order_id'];
            $status = $this->request->get['status'];

            $this->model_account_order->updateDownloadStatus($order_id,$status);
            $json['status'] = 1;
        }
        $this->response->addHeader('Content-Type: application/json');
        echo json_encode($json);
    }


}
