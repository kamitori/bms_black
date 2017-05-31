<?php
namespace RW\Models;
use RW\Models\Vouchers;
use RW\Models\JTOrder;
class FacebookAppUsers extends MongoBase {

    public function getSource()
    {
        return 'tb_facebbok_app_users';
    }
    public static function checkPlay($id){
        $arr = self::findFirst([
                        'conditions' => ['facebook_id' => $id],
                        'fields'     => ['_id','user_coupon','product_name','test_account','user_email']
                    ]);
        $arr_list_test_email = array(
            'poke.bald.1990@gmail.com','renos.phan@gmail.com','valerie@anvydigital.com'
        );
        // if(isset($arr['test_account']) && $arr['test_account']){
        if(in_array($arr['user_email'], $arr_list_test_email)){            
            return null;
        }
        return $arr;
    }
    public static function countList(){
        $arr_total = self::find(['conditions' => [],'fields' => ['product_id']]);
        if(!is_array($arr_total)) $arr_total = return_to_array($arr_total);
        $total = count($arr_total);
        $arr_return = array();
        $arr_count = [];
        foreach($arr_total as $arr){
            $arr_count [] = $arr['product_id'];
        }
        $arr_percent = [];
        $arr_count = array_count_values($arr_count);
        $run = 0;    
        $total_percent = 0;    
        foreach($arr_count as $key => $val){
            $arr_percent[$key]['draw'] = ( ( ( $val/$total )*100 ) * 60 )/100;
            $arr_percent[$key]['origin'] = round (( $val/$total )*100,2 );            
            if($run==( count($arr_count) -1 ) ){
                $arr_percent[$key]['origin'] = 100 - $total_percent;
            }else{
                $total_percent += $arr_percent[$key]['origin'];
            }
            $run++;
        }
        $arr_return['total'] = $total;
        $arr_return['percent'] = $arr_percent;
        return $arr_return;
    }
    public function createData($arrData = [])
    {
        //nano /etc/httpd/conf/httpd.conf
        ///home/bmsweb/domains/web.banhmisub.com/public_html/bms_black
    	$arr_return = [];
        $create_at = new \MongoDate(strtotime(date('Y-m-d 00:00:00')));
        $arrSave = [
            'create_at'=> $create_at,
            'user_first_name'  => $arrData['first_name'],
            'user_last_name'  => $arrData['last_name'],
            'facebook_id' => $arrData['facebook_id'],
            'user_email' => $arrData['user_email'],
            'product_id' => $arrData['product_id'],
            'product_name' => $arrData['product_name'],
            'deleted'=>false,
            'test_account'=>0
        ];        
        $check = self::findFirst([
                        'conditions' => ['facebook_id' => $arrData['facebook_id'],'deleted'=>false],
                        'fields'     => ['_id','user_coupon','expries','test_account','user_email']
                    ]);
        $arr_list_test_email = array(
            'poke.bald.1990@gmail.com','renos.phan@gmail.com','valerie@anvydigital.com'
        );
        // if (!isset($check->_id) || (isset($check->test_account) && $check->test_account) ) {
        if (!isset($check->_id) || in_array($check->user_email, $arr_list_test_email) ) {
            
        	$Vouchers = new Vouchers;
        	$coupon = $Vouchers->generator();
            $arrSave['user_coupon'] = $coupon['voucher'];
            $arrSave['expries'] = $coupon['expries'];
           
            // if((isset($check->test_acount) && $check->test_acount) ) {
            if(in_array($check->user_email, $arr_list_test_email) ) {
                $arrSave['test_acount'] = 1;
            }
            $this->getConnection()->{$this->getSource()}->insert($arrSave);
        }else{
            /*

            $check2 = JTOrder::findFirst(['conditions' =>["voucher"=>trim($check->user_coupon)]]);            
            if(!empty($check2)){
                // xai roi, tao code moi
                // update xoa code cu
                $arrSave['deleted'] = true;
                $this->getConnection()->{$this->getSource()}->update(array('_id'=>$check->_id), $arrSave, array('upsert' => true));
                // tao code moi
                $Vouchers = new Vouchers;
                $coupon = $Vouchers->generator();
                $arrSave['user_coupon'] = $coupon['voucher'];
                $arrSave['expries'] = $coupon['expries'];
                $this->getConnection()->{$this->getSource()}->insert($arrSave);
            }else{
                // chua xai
                $arrSave['user_coupon'] = $check->user_coupon;
                $arrSave['expries'] = $check->expries;
            }
            */

            $arrSave['user_coupon'] = $check->user_coupon;
            $arrSave['expries'] = $check->expries;
        }
        return $arrSave;
    }
}