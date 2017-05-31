<?php
namespace RW\Controllers\Api;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use \RW\Models\BannerCategories;
use RW\Models\Categories;
use RW\Models\Configs;
use RW\Models\JTCategory;
use RW\Models\JTProvince;
use RW\Models\JTProduct;
use RW\Models\JTDocuse;
use RW\Models\JTDoc;
@require_once APP_PATH . "/app/system_defined.php";
class ControllerBase extends Controller
{
	protected $response;
	protected function fastCache()
	{	
		$ExpireDate = new \DateTime();
		$ExpireDate->modify('+1 week');
		$response = $this->response;
		$response->setExpires($ExpireDate);
		$response->setHeader('Cache-Control', 'public, max-age=604799, s-max=604799, must-revalidate, proxy-revalidate');
	}

	public function initialize()
	{
		$this->response = new \Phalcon\Http\Response;
		// set a quicker cache
			$this->fastCache();
	}

	public function beforeExecuteRoute(Dispatcher $dispatcher)
	{
	}

	public function afterExecuteRoute(Dispatcher $dispatcher)
	{
		$this->view->disable();
	}

	final function getPost()
	{
		$postJSON = $this->request->getRawBody();
		if (!empty($postJSON)) {
			$postJSON = json_decode($postJSON, true);
		} else {
			$postJSON = [];
		}        
		return array_merge($postJSON, $this->request->getPost());
	}

	final function abort($code = 404, $title = '', $message = '')
	{
		switch ($code) {
			case 404:
				if (empty($title)) {
					$title = 'Page not found';
					$message = 'This page did not exist. Please go back to homepage.';
				}
				$this->dispatcher->forward(array(
					'controller'    => 'Errors',
					'action'        => 'notFound',
				));
				break;
			case 301:
				return $this->response->redirect(SITE_301_URL);
			case 500:
				return $this->response->redirect(SITE_500_URL);
		}
		$data = [
			'title'     => $title,
			'message'   => $message
		];
		$this->dispatcher->setParams(['data' => $data]);
	}


	public function productByCategory($cate_name='', $arr_condition = array()){
		$where = array();

		if(isset($arr_condition['or']))
		{
			foreach($arr_condition as $key=>$value)
			{
				if($key == 'or') continue;
				$where['conditions']['$or'][] = array($key=>$value);
			}
		}
		else
		{
			foreach($arr_condition as $key=>$value)
			{
				$where['conditions'][$key] = $value;    
			}
		}
	
		if($cate_name!=''){
			$where['conditions']['$or'][] = array('category'=>$cate_name);
			$where['conditions']['$or'][] = array('cate_more'=>$cate_name);
		}
		$where['conditions']['assemply_item'] =1;
		$where['conditions']['deleted'] =false;
		$where['sort'] = array('name'=>1);
		$where['limit'] = 100;
		$jtproduct = JTProduct::find($where);
		$products = $product_id = $product_tag = $arr_have_price= $arr_opid = array();
		foreach ($jtproduct as $key => $value) {
			$products[$key]['name'] = $value->name;
			$products[$key]['description'] = isset($value->description)?$value->description:'';
			$products[$key]['product_desciption'] = isset($value->product_desciption)?$value->product_desciption:'';
			$products[$key]['id'] = (string)$value->_id;
			 //find option defaut price
			  if(isset($value->options)){
				foreach ($value->options as $kk => $vv){
					if($vv['deleted']==true)
						continue;
					$adjustment_amount = isset($vv['adjustment'])?(float)$vv['adjustment']:0;
					$unit_price = isset($vv['unit_price'])?$vv['unit_price']:0;

					if($vv['require']==1 && isset($vv['product_id']) && isset($vv['quantity'])){
						$arr_have_price[$key][] = array('product_id'=>$vv['product_id'],'quantity'=>$vv['quantity'],'adjustment'=>$vv['adjustment'],'unit_price'=>$unit_price);
						if(!in_array($vv['product_id'],$arr_opid))
							$arr_opid[] = $vv['product_id'];
					}else if(isset($vv['default']) && $vv['default'] == 1 && isset($vv['finish']) && $vv['finish']==1){
						$arr_have_price[$key][] = array('product_id'=>$vv['product_id'],'quantity'=>$vv['quantity'],'adjustment'=>$vv['adjustment'],'unit_price'=>$unit_price);
						if(!in_array($vv['product_id'],$arr_opid))
							$arr_opid[] = $vv['product_id'];
					}
				}
			}
			$products[$key]['price'] = round($value->sell_price,2);
			$products[$key]['image'] = '';
			$products[$key]['category_id'] = $value->category;
			
			// if($value->category=='sub_combo')
			if($value->category=='Sub Combo') //test
				$products[$key]['combo'] = 1;
			else
				$products[$key]['combo'] = 0;

			if(isset($value->use_group_order)) //test
				$products[$key]['use_group_order'] = $value->use_group_order;
			else
				$products[$key]['use_group_order'] = 0;
			
			$products[$key]['combo_sales'] = isset($value->combo_sales)?(float)$value->combo_sales:1;

			if(isset($value->product_base)&&!empty($value->product_base)){
				$products[$key]['tag'] = $value->product_base;
				$product_tag[$products[$key]['tag']] = $products[$key]['tag'];
			}else
				$products[$key]['tag'] = 'Other';

			$products[$key]['custom'] = 0;
			if((isset($value->pricebreaks) && count($value->pricebreaks)>0) || (isset($value->options) && count($value->options)>0)){
				$products[$key]['custom'] = 1;
			}

			$products[$key]['image'] = '';
			if(isset($value->products_upload)){
				$products[$key]['image'] = $value->products_upload;
				foreach ($value->products_upload as $kk => $vv) {
				   if($vv['deleted']==false && $vv['path']!='' ){
						$products[$key]['image'] = JT_URL.$vv['path'];
						// break;
				   }
				}
			}
		}
		$where = array();
		$where['conditions']['_id'] = array('$in'=>$arr_opid);
		$where['conditions']['deleted'] =false;
		$where['sort'] = array('_id'=>1);
		$where['field'] = array('_id','sell_price');
		$where['limit'] = 200;
		$opproduct = JTProduct::find($where);
		
		foreach ($opproduct as $key => $value) {
			$sell_price[(string)$value->_id] = (float)$value->sell_price;
		}
		 foreach ($arr_have_price as $key => $oplist) {
			foreach ($oplist as $kk => $value) {
				if(isset($sell_price[(string)$value['product_id']])){
					$op_price = ($value['unit_price']!=0)?$value['unit_price']:$sell_price[(string)$value['product_id']];
					$products[$key]['price'] = round($products[$key]['price'] + $op_price * $value['quantity'] + $value['adjustment'],2);
					$products[$key]['sell_price'] = $products[$key]['price'];
				}
			}
		}
		$product_tag['Other'] = 'Other';
		return array('product_list'=>$products,'tag_list'=>$product_tag);
	}
	public function JTCategorySimple(){
		$category = array();
		$query = JTCategory::findFirst(array(
			'conditions'=>array('setting_value'=>'product_category')
		));
		if(isset($query->option) && !empty($query->option))
			foreach ($query->option as $key => $value) {
				if (isset($value['hidden_on_website']) && $value['hidden_on_website']=="1") {
					continue;
				}				
				if(isset($value['pubblic']) && $value['pubblic']==1)
					$category[strtolower(str_replace(" ","_",$value['value']))] = $value['name'];
			}
	   return $category;
	}
	public function JTCategory(){
		$category = array();
		$query = JTCategory::findFirst(array(
			'conditions'=>array('setting_value'=>'product_category')
		));
		$BannerCategories = new BannerCategories();
		$banner_categories = $BannerCategories->find()->toArray();
		if(isset($query->option) && !empty($query->option)){
			foreach ($query->option as $key => $value) {				
				if (isset($value['hidden_on_website']) && $value['hidden_on_website']=="1") {
					continue;
				}
				if(isset($value['pubblic']) && $value['pubblic']==1 && isset($value['deleted']) && $value['deleted']==false){
					$short_name = strtolower(str_replace(" ","_",$value['value']));
					$category[$short_name]['name'] = $value['name'];
					$category[$short_name]['value'] = $value['value'];
					$category[$short_name]['image'] = '';
					$category[$short_name]['short_name'] = strtolower(str_replace(" ","_",$value['value']));

					if(isset($value['description']))
						$category[$short_name]['description'] = $value['description'];

					if(!isset($value['order_no']))
						$category[$short_name]['order_no'] = 1;
					else
						$category[$short_name]['order_no'] = (int)$value['order_no'];

					// if(isset($value['image']) && $value['image']!='')
					//     $category[$short_name]['image'] = POS_URL.'/'.$value['image'];
					// else
					//     $category[$short_name]['image'] = 'http://pos.banhmisub.com/images/product-categories/l_518856232_banh-mi-xa-xiu.18-10-15.png';
					foreach ($banner_categories as $key2 => $value2) {
						if($value2['id_category']==$value['id']){
							$category[$short_name]['image'] =  URL.'/'.$value2['image'];
							$category[$short_name]['alt'] =  isset($value2['alt'])?$value2['alt']:'';
							break;
						}
					}
				}
			}
			$category = aasort($category,'order_no',1);
		}
	   return $category;
	}

	final function response($responseData = [], $responseCode = 200, $responseMessage = '', $responseHeader= [])
	{
		$this->view->disable();
		$this->response->setContentType('application/json', 'UTF-8')
							->setStatusCode($responseCode, $responseMessage)
							->setJsonContent($responseData);
		if (!empty($responseHeader)) {
			foreach($responseHeader as $headerName => $headerValue) {
				$this->response->setHeader($headerName, $headerValue);
			}
		}
		return $this->response;
	}

	protected function error404($message = 'Page not found')
	{
		return $this->response(['error' => 1, 'message' => $message], 404, $message);
	}    

}
