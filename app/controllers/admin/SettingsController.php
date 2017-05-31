<?php
namespace RW\Controllers\Admin;

class SettingsController extends ControllerBase {

    protected $notFoundMessage = 'setting did not exist.';

    public function listAction(){
        $arr_data = array(
            'site_title'=>SITE_TITLE,
            'meta_title'=>META_TITLE,
            'meta_description'=>META_DESCRIPTION,
            'keywords'=>KEYWORDS,
            'site_301_url'=>SITE_301_URL,
            'site_404_url'=>SITE_404_URL,
            'site_500_url'=>SITE_500_URL ,
            'from_hours'=>FROM_HOURS,
            'to_hours'=>TO_HOURS,           
            'merchant_id'=>MERCHANT_ID,           
            'beanstream_api_key'=>BEANSTREAM_API_KEY,
            'mailchimp_api_key'=>MAILCHIMP_API_KEY,

        );
        return $this->response(['error' => 0, 'data' => $arr_data]);
    }

    public function updateAction()
    {
        $filter = new \Phalcon\Filter;
        $data = $this->getPost();
        $v_str = "<?php \r\n";
        
        foreach($data as $key => $val) {
            $v_str .= "\t defined('".strtoupper($key)."') || define('".strtoupper($key)."', '".$val."'); \r\n";
        }

        @chmod(APP_PATH . "/app/system_defined.php",0777);
        $fp = fopen(APP_PATH . "/app/system_defined.php",'w');
        fwrite($fp, $v_str);
        fclose($fp);
        $arrReturn = ['error' => 0, 'message' => 'Save successful'];
        return $this->response($arrReturn);
    }


}
