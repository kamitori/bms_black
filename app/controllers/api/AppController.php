<?php
namespace RW\Controllers\Api;
use RW\Models\FacebookAppUsers;
use RW\Models\JTProduct;
use RW\Models\Vouchers;
class AppController extends ControllerBase {
	public function indexAction(){
		return $this->response(['message' => 'Please login before continue!'], 401, 'Unauthorized');
	}
	public function updateVoucherAction(){
        Vouchers::updateVoucher();
    }
    public function TestAction(){
    	$Vouchers = new Vouchers;
    	$Vouchers->generator();
    }
    public function CheckPlayAction($id){
    	$filter = new \Phalcon\Filter;
    	$id = $filter->sanitize($id, 'string');
    	$arr_data = FacebookAppUsers::checkPlay($id);
    	$arr_count_list = FacebookAppUsers::countList();
    	$arr_return = ['error'=>1,'message'=>'Not login yet'];
    	if(!empty($arr_data)){
    		$arr_return = ['error'=>0,'message'=>'log in','voucher_code'=>$arr_data['user_coupon'],'product_name' => $arr_data['product_name'] , 'list_count'=>$arr_count_list];
    	}
    	return $this->response($arr_return);
    }
	public function getInformationAction($link){
		$arr_explode = explode('-', $link);
		if(isset($arr_explode[0]) && count($arr_explode)==5){
			$fb_id = $arr_explode[0];
			$email = $arr_explode[1];
			$item = $arr_explode[2];
			$first_name = $arr_explode[3];
        	$last_name = $arr_explode[4];        	

        	$JTProduct = new JTProduct;        	
	        $product = $JTProduct->getDataById($item);
	        if(empty($product)){
	        	$arr_return = ['error'=>1,'message'=>'Invalid item'];
	        }else{
	        	$arrData = [
		            'first_name'  => $first_name,
		            'last_name'  => $last_name,
		            'facebook_id' => $fb_id,
		            'user_email' => $email,
		            'product_id' => $item,
		            'product_name' => $product['name'],
		        ];
		        $FacebookAppUsers = new FacebookAppUsers;
		        $arr_data = $FacebookAppUsers->createData($arrData);
		        $arr_count_list = FacebookAppUsers::countList();
		        // $message = 'Thank you '.$arr_data['user_last_name']. ' '.$arr_data['user_first_name'];
		        $message = '<br/> We greatly appreciate your feedback, '.$arr_data['user_first_name'].'!';
		        $message .= '<br />Please use this coupon for a 10% discount on your next order.: <div class="voucher_style"><strong>'.$arr_data['user_coupon'].'</strong></div>';
		        // $message .= '<br />Your coupon expries at: '.$arr_data['expries'];
		        $arr_return = ['error'=>0,'message'=>$message,'product_name' => $product['name'], 'list_count'=>$arr_count_list];
	        }
			return $this->response($arr_return);

		}else{
	        return $this->response(['message' => 'Please login before continue!'], 401, 'Unauthorized');
		}
	}
	public function checkUserAction(){
		// if (!$this->request->isAjax()) {
  //           return $this->response(['message' => 'Please login before continue!'], 401, 'Unauthorized');
  //       }
        $email = $this->request->getPost('email');
        $fb_id = $this->request->getPost('fb_id');
        $first_name = $this->request->getPost('first_name');
        $last_name = $this->request->getPost('last_name');
        $item = $this->request->getPost('item');
        $JTProduct = new JTProduct;        
        $product = $JTProduct->getDataById($item);        
        if(empty($product)){
        	$arr_return = ['error'=>1,'message'=>'Invalid item'];
        }else{
        	$arrData = [
	            'user_first_name'  => $first_name,
	            'user_last_name'  => $last_name,
	            'facebook_id' => $fb_id,
	            'user_email' => $email,
	            'user_coupon' => $arrData['user_coupon'],
	            'product_id' => $item,
	            'product_name' => $product['name'],
	        ];
	        $FacebookAppUsers = new FacebookAppUsers;
	        $arr_data = $FacebookAppUsers->createData();
	        $arr_count_list = FacebookAppUsers::countList();
	        $message = 'We greatly appreciate your feedback, '.$arr_data['user_first_name'];
	        $message .= '<br />Please use this coupon for a 10% discount on your next order.: <div class="voucher_style"> <strong>'.$arr_data['user_coupon'].'</strong></div>';
	        // $message .= '<br />Expries at: '.$arr_data['expries'];
	        $arr_return = ['error'=>1,'message'=>$message,'product_name' => $product['name'], 'list_count'=>$arr_count_list];
        }
		return $this->response($arr_return);
	}
}
