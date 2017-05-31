<?php
namespace RW\Controllers;

//use RW\Models\Categories;
//use RW\Models\Products;
use RW\Models\JTCategory;
use RW\Models\JTSettings;
use RW\Models\JTProduct;
class CategoriesController extends ControllerBase {

    public function indexAction(){
        
        $categories = $this->JTCategory();
        $categoryName = $this->dispatcher->getParam('categoryName');
        $categoryName = str_replace("-", "_", $categoryName);                
        if($this->request->isAjax()){

            $this->view->disable();

            if (isset($categories[$categoryName])) {                
                $data_product = $this->productByCategory($categories[$categoryName]['value']);                            
                $data = array('products' => $data_product['product_list'], 'tag_list'=>$data_product['tag_list'], 'baseURL' => URL);
                $data['category'] = $categories[$categoryName];
                $data['active_short_name'] = isset($category['short_name'])?$category['short_name']:'';
                $data['session_user'] = $this->session->has('user')?$this->session->get('user'):false;
                $data['session_store'] = $this->session->has('store')?$this->session->get('store'):false;
                $this->view->partial('frontend/Categories/index', $data);
            }
            else {
                return $this->abort(404);
            }

        }else{
            
            $iscombo = (new \RW\Cart\Cart)->checkcombo();
            $combostep = (new \RW\Cart\Cart)->combostep();
            if($iscombo==1 && $combostep==1){
                $categoryName = 'banh_mi_subs';
            }
            if($iscombo==1 && $combostep==2){
                $categoryName = 'appetizers';
            }
            if($iscombo==1 && $combostep==3){
                $categoryName = 'drinks';
            }

            if (isset($categories[$categoryName])) {
                $this->view->category = $categories[$categoryName];

                $this->view->active_short_name = isset($categories[$categoryName]['short_name'])?$categories[$categoryName]['short_name']:'';
                
                $arr_condition = array();
                $key = $this->request->get('key');
                if($key != null && trim($key) != '')
                {                    
                    $arr_condition['or'] = true;
                    $arr_condition['name'] = array('$regex'=>$key, '$options'=>'-i');
                    $arr_condition['description'] = array('$regex'=>$key, '$options'=>'-i');                    
                }

                $data = $this->productByCategory($categories[$categoryName]['value'], $arr_condition); 

                $this->view->products = $data['product_list'];
                $this->view->tag_list = $data['tag_list'];
                $this->view->title = $this->view->category['name']." | Banh Mi SUB";                
                
                $this->view->description = $this->view->category['description'];
            } else {
                return $this->abort(404);
            }
        }
    }
    public function checkdateAction(){        
        if(file_exists(PUBLIC_PATH . "/appdatas.json")){
            $arr_datas = json_decode(file_get_contents(PUBLIC_PATH . "/appdatas.json"),true);
            echo strtotime($arr_datas['date']) - 86400;
        }else{
            echo -1;
        }
        die;
    }
    public function createNewFileNowAction(){
        self::create_json_file();
    }
    public function getLinkAction(){    
        $v_current_date =  $v_last_date = strtotime(date('Y-M-d'));
        if(file_exists(PUBLIC_PATH . "/appdatas.json")){
            $arr_datas = json_decode(file_get_contents(PUBLIC_PATH . "/appdatas.json"),true);
            $v_last_date = strtotime($arr_datas['date']) - 86400;            
            if($v_last_date >= $v_current_date){
                self::create_json_file();
            }
        }else{
            self::create_json_file();
        }
        die( URL . "/appdatas.json");
    }
    public function facebookAction(){        
        $arr_list = $this->productByCategory('Banh Mi SUBS');        
        $this->view->products = $arr_list['product_list'];
    }
    function create_json_file(){
        $arr_list = $this->productByCategory('Banh Mi SUBS');        
        $arr_data['data'] = $arr_list['product_list'];        
        $arr_data['date'] = date('Y-M-d');
        // pr(PUBLIC_PATH . "/appdatas.json");die;
        $fp = fopen(PUBLIC_PATH . "/appdatas.json",'w');
        fwrite($fp, json_encode($arr_data));
        fclose($fp);
        @chmod(PUBLIC_PATH . "/appdatas.json",0777);
    }
}
