<?php
namespace RW\Controllers\Api;

use RW\Models\Products;
use RW\Models\JTProduct;


class ProductController extends ControllerBase {
	
	public function getOptionAction($id,$return=0){
		$this->assets
		->collection('js')
		->addJs('/'.THEME.'js/app.min.js');

		$this->assets
		->collection('css')
		->addCss('/'.THEME.'/css/app.min.css');
		// Get Option Product
			if($id!=''){
				$id = str_replace("/","",$id);
				$where = $option= $arr_proid = $arr_group = $arr_pro = $is_default = array();
				$where['conditions']['_id'] = new \MongoId($id);
				$where['conditions']['assemply_item'] =1;
				$jtproduct = JTProduct::findFirst($where);
				$option_price_total = $max_choice = 0;
				$description = '';
				if(isset($jtproduct->maximum_option)){
					$max_choice = $jtproduct->maximum_option;
				}
				
				if(isset($jtproduct->options)){
					foreach ($jtproduct->options as $key => $value){
						if(!isset($value['hidden']))
							$value['hidden'] = 0;
						if(!isset($value['default']))
							$value['default'] = 0;
						if($value['deleted']==false && $value['hidden']!=1){
							$value['key'] = $key;
							$group = (isset($value['option_group']) && $value['option_group']!='')?$value['option_group']:'all';
							
							if(!isset($value['require']) || $value['require']==0)
								$value['quantity'] = 0;
							if(isset($value['finish'])){
								$value['quantity'] = (int)$value['finish'];
							}
							$value['default_qty'] = $value['quantity'];
							if(!isset($value['level']) || isset($value['level'])=='')
								$value['level']='Level 01';
							if(!isset($value['group_order']) || isset($value['group_order'])=='')
								$value['group_order']=1;
							
							$arr_group[$value['level']][GroupToKey($group)]['label'] = GroupToName($group);
							if(isset($arr_group[$value['level']][GroupToKey($group)]['order']) && (int)$value['group_order']<$arr_group[$value['level']][GroupToKey($group)]['order'])
								$arr_group[$value['level']][GroupToKey($group)]['order'] = (int)$value['group_order'];
							else
								$arr_group[$value['level']][GroupToKey($group)]['order'] = (int)$value['group_order'];

							$group = GroupToKey($group);
							$option[$value['level']][$group][(string)$value['product_id']] = $value;
							$option[$value['level']][$group][(string)$value['product_id']]['product_id'] = (string)$value['product_id'];
							
							$arr_proid[] = $value['product_id'];
							$arr_group_product[(string)$value['product_id']] = $group;
							if(isset($value['default']) && isset($value['finish']) && $value['default']==1 && $value['finish']==1)
								$is_default[(string)$value['product_id']] = 1;
						}

					}
					$query = JTProduct::find(array(
						'conditions'=>array('_id'=>array('$in'=>$arr_proid))
					));
					foreach ($query as $key => $value){
						$value->image = '';
						if(isset($value->products_upload) && !empty($value->products_upload)){
							foreach ($value->products_upload as $k => $v) {
								if($v['deleted']==false){
									$value->image = JT_URL.$v['path'];
								}
							}
						}
						$id = (string)$value->_id;
						if(isset($value->option_type))
							$arr_pro[$id] = $value->option_type;
						else
							$value->option_type = '';
						if(isset($is_default[$id])){
							$description .='<p id="od_'.$id.'">'.$value->name.' (<b>'.'Yes'.'</b>) </p>';
						}
						foreach ($option as $level => $arr_value) {
							if(isset($option[$level][$arr_group_product[$id]][$id])){
								$option[$level][$arr_group_product[$id]][$id] = $item = array_merge($value->toArray(),$option[$level][$arr_group_product[$id]][$id]);
								$adjustment = isset($item['adjustment'])?$item['adjustment']:0;
								$option_price_total += $item['quantity']*($item['sell_price']+$adjustment);
							}
						}
					}
				}
				//sort
				
				ksort($option);
				ksort($arr_group);
				$temp = $tmpgroup = array();
				// pr($option);die;
				foreach ($arr_group as $key => $value) {
					aasort($value,'order');
					$arr_group[$key] = $value;
					foreach ($value as $kk => $vv) {
						 // aasort($option[$key][$kk],'group_order');
						 uasort($option[$key][$kk], function ($a, $b) {
							return $b['name'] <= $a['name'];
							// return $b['group_order'] <= $a['group_order'];
						 });
						 $temp[$key][$kk] = $option[$key][$kk];
						 $tmpgroup[$key][$kk] = $vv['label'];
					}
				}
				$option = $temp;
				$arr_group = $tmpgroup;
				if(is_object($jtproduct)){
					$product = $jtproduct->toArray();
				}else{
					$product = $jtproduct;
				}
				$product['image'] = '';
					if(isset($product['products_upload'])){
						foreach ($product['products_upload'] as $kk => $vv) {
						if($vv['deleted']==false && $vv['path']!='' ){
							$product['image']= JT_URL.$vv['path'];
						}
					}
				}
				$this->view->disable();
				$this->view->banhmisub = "";
				$this->view->JT_URL = JT_URL;
				$this->view->POS_URL = POS_URL;
				$this->view->arr_group = $arr_group;
				$this->view->option = $option;
				$this->view->option_price_total = $option_price_total;
				$this->view->baseURL = URL;
				$this->view->cart_id = '';
				$this->view->product = $product;
				$this->view->description = $description;
				$this->view->choice_default_amout = count($is_default);
				$this->view->finish_option = (new \RW\Models\JTSettings)->getFinishOption($arr_pro);
				$this->view->partial('api/product_option');
			}
		//
	}

	public function updateOptionAction(){
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		$this->assets
		->collection('js')
		->addJs('/'.THEME.'js/app.min.js');

		$this->assets
		->collection('css')
		->addCss('/'.THEME.'/css/app.min.css');
		$json_item = $this->request->hasQuery('json_item')?$this->request->getQuery('json_item'):"123";
		$json_item = urldecode($json_item);
		$item_cart = json_decode($json_item,true);
		// echo "<pre>";
		// var_dump($item_cart);
		// echo "</pre>";
		$id = $item_cart['_id']['$id'];
		// Get Option Product

			if($id!=''){
				$id = str_replace("/","",$id);
				$where = $option= $arr_proid = $arr_group = $arr_pro = $is_default = array();
				$where['conditions']['_id'] = new \MongoId($id);
				$where['conditions']['assemply_item'] =1;
				$jtproduct = JTProduct::findFirst($where);
				$option_price_total = $max_choice = 0;
				$description = '';
				if(isset($jtproduct->maximum_option)){
					$max_choice = $jtproduct->maximum_option;
				}
				
				if(isset($jtproduct->options)){
					foreach ($jtproduct->options as $key => $value){

						// echo "<pre>";
						// var_dump($value);
						// echo "</pre>";

						if(!isset($value['hidden']))
							$value['hidden'] = 0;
						if(!isset($value['default']))
							$value['default'] = 0;
						if($value['deleted']==false && $value['hidden']!=1){
							$value['key'] = $key;
							$group = (isset($value['option_group']) && $value['option_group']!='')?$value['option_group']:'all';
							
							if(!isset($value['require']) || $value['require']==0)
								$value['quantity'] = 0;
							if(isset($value['finish'])){
								$value['quantity'] = (int)$value['finish'];
							}
							$product_id =(String) $value['product_id'];
							foreach (json_decode($item_cart['options'],true) as $k => $v) {
								if($product_id==$k){
									// $value['finish'] = $v['isfinish'];
									$value['quantity'] = $v['quantity'];
									if(isset($value['finish'])){
										$value['finish'] = $v['quantity'];
									}
								}
							}
							$value['default_qty'] = $value['quantity'];
							if(!isset($value['level']) || isset($value['level'])=='')
								$value['level']='Level 01';
							if(!isset($value['group_order']) || isset($value['group_order'])=='')
								$value['group_order']=1;
							
							$arr_group[$value['level']][GroupToKey($group)]['label'] = GroupToName($group);
							if(isset($arr_group[$value['level']][GroupToKey($group)]['order']) && (int)$value['group_order']<$arr_group[$value['level']][GroupToKey($group)]['order'])
								$arr_group[$value['level']][GroupToKey($group)]['order'] = (int)$value['group_order'];
							else
								$arr_group[$value['level']][GroupToKey($group)]['order'] = (int)$value['group_order'];

							$group = GroupToKey($group);
							$option[$value['level']][$group][(string)$value['product_id']] = $value;
							$option[$value['level']][$group][(string)$value['product_id']]['product_id'] = (string)$value['product_id'];
							
							$arr_proid[] = $value['product_id'];
							$arr_group_product[(string)$value['product_id']] = $group;
							if(isset($value['default']) && isset($value['finish']) && $value['default']==1 && $value['finish']==1)
								$is_default[(string)$value['product_id']] = 1;
						}

					}
					$query = JTProduct::find(array(
						'conditions'=>array('_id'=>array('$in'=>$arr_proid))
					));
					foreach ($query as $key => $value){
						$value->image = '';
						if(isset($value->products_upload) && !empty($value->products_upload)){
							foreach ($value->products_upload as $k => $v) {
								if($v['deleted']==false){
									$value->image = JT_URL.$v['path'];
								}
							}
						}
						$id = (string)$value->_id;
						if(isset($value->option_type))
							$arr_pro[$id] = $value->option_type;
						else
							$value->option_type = '';
						if(isset($is_default[$id])){
							$description .='<p id="od_'.$id.'">'.$value->name.' (<b>'.'Yes'.'</b>) </p>';
						}
						foreach ($option as $level => $arr_value) {
							if(isset($option[$level][$arr_group_product[$id]][$id])){
								$option[$level][$arr_group_product[$id]][$id] = $item = array_merge($value->toArray(),$option[$level][$arr_group_product[$id]][$id]);
								$adjustment = isset($item['adjustment'])?$item['adjustment']:0;
								$option_price_total += $item['quantity']*($item['sell_price']+$adjustment);
							}
						}
					}
				}
				//sort
				
				ksort($option);
				ksort($arr_group);
				$temp = $tmpgroup = array();
				// pr($option);die;
				foreach ($arr_group as $key => $value) {
					aasort($value,'order');
					$arr_group[$key] = $value;
					foreach ($value as $kk => $vv) {
						 // aasort($option[$key][$kk],'group_order');
						 uasort($option[$key][$kk], function ($a, $b) {
							return $b['name'] <= $a['name'];
							// return $b['group_order'] <= $a['group_order'];
						 });
						 $temp[$key][$kk] = $option[$key][$kk];
						 $tmpgroup[$key][$kk] = $vv['label'];
					}
				}
				$option = $temp;
				$arr_group = $tmpgroup;
				if(is_object($jtproduct)){
					$product = $jtproduct->toArray();
				}else{
					$product = $jtproduct;
				}
				$product['image'] = '';
					if(isset($product['products_upload'])){
						foreach ($product['products_upload'] as $kk => $vv) {
						if($vv['deleted']==false && $vv['path']!='' ){
							$product['image']= JT_URL.$vv['path'];
						}
					}
				}

				$this->view->disable();
				$this->view->banhmisub = "";
				$this->view->quantity = $item_cart['quantity'];
				$this->view->JT_URL = JT_URL;
				$this->view->POS_URL = POS_URL;
				$this->view->arr_group = $arr_group;
				$this->view->option = $option;
				$this->view->option_price_total = $option_price_total;
				$this->view->baseURL = URL;
				$this->view->cart_id = '';
				$this->view->product = $product;
				$this->view->description = $description;
				$this->view->choice_default_amout = count($is_default);
				$this->view->finish_option = (new \RW\Models\JTSettings)->getFinishOption($arr_pro);
				$this->view->partial('api/product_option');
			}
		//
	}
}
