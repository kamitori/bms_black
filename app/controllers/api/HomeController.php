<?php
namespace RW\Controllers\Api;

use RW\Models\Categories;
use RW\Models\Banners;
use RW\Models\JTCategory;
use RW\Models\Pages;


class HomeController extends ControllerBase {
	public function indexAction(){
		echo "This is home";
		die;
	}
	public function testGetAction(){
		$arr_test = [
			"int"=>3,
			"float"=>3.3,
			"char"=>"N",
			"string"=>"Nguyễn Minh Trí",
			"array1"=>[1,2,3],
			"array2"=>["0"=>1,"a"=>2,"c"=>3],
			"array3"=>[
				"a"=>1,
				"b"=>"this is test !",
				"c"=>[
					"d"=>3.3,
					"e"=>"abc xyz"
				]
			]
		];
		return $this->response($arr_test);
	}

	public function testPostAction(){
		if(count($_POST)){
			return $this->response($_POST);
		}else{
			return $this->response(["message"=>"You don't post your data"]);
		}
	}

	public function getBannersAction(){
		$banners = Banners::find([
			'columns'   => 'id, image, link,b_position,description,product_id,product_name,alt',
			'order'     => 'order_no ASC',
			// 'conditions'=> 'b_position = "home-banner"'
		]);
		if(is_object($banners)){
			$banners = $banners->toArray();
		}
		return $this->response($banners);
	}

	public function getMenuAction(){
		$is_login = 1;
		$arr_return = [
			"categories"=>[],
			"submenufooter"=>[],
			"catefooter"=>[]
		];
		$categories = $this->JTCategory();
		foreach ($categories as $key => $value) {
			$categories[$key]['short_name'] = str_replace("_","-",$value['short_name']);
			if($value['short_name']=="others"){
				unset($categories[$key]);
			}
		}
		$arr_return["categories"] = $categories;
		$catefooter = Categories::find([
				'columns'   => 'id, name, short_name,order_no',
				'order'     => 'order_no ASC',
				'conditions' => "position = 2 "
		]);

		$arr_return['catefooter'] = $catefooter;
		$is_condition = " category_id IN(";
		foreach ($catefooter as $key => $value) {
			$is_condition .= $value->id.",";
		}
		$is_condition .= "0)";
		$pages = Pages::find([
			'columns'=>'id, name, short_name, category_id,order_no,type',
			'conditions' =>$is_condition,
			'order'=> 'order_no ASC'
		]);
		if(is_object($pages)){
			$pages = $pages->toArray();
		}
		$submenu=[];
		foreach ($pages as $key => $value) {
			if($value['short_name']=='sign-in' || $value['short_name'] =='create-account' || $value['short_name'] =='order-history' ){
				if($value['short_name'] =='order-history') {
					if($is_login) $submenu[$value['category_id']][$value['id']] = $value;
				}
				else {
					if(!$is_login) $submenu[$value['category_id']][$value['id']] = $value;
				}
			}else{
				$submenu[$value['category_id']][$value['id']] = $value;
			}
			
		}
		$arr_return['submenufooter'] = $submenu;
		return $this->response($arr_return);
	}
}
