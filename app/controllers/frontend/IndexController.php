<?php
namespace RW\Controllers;

use RW\Models\Categories;
use RW\Models\Banners;
use RW\Models\JTCompany;
use RW\Models\JTCategory;
use RW\Models\BannerCategories;
use RW\Models\JTProduct;

class IndexController extends ControllerBase
{
	public function indexAction()
	{		
		$this->view->banners = Banners::find([
			'columns'   => 'id, image, link,b_position,description,product_id,product_name,alt,start_date,end_date',
			'order'     => 'order_no ASC',
			// 'conditions'=> 'b_position = "home-banner"'
		]);
		$JTCompany = new JTCompany;
		$company = $JTCompany->findFirst(array("name"=>"Banh Mi Sub"));
		if($company){
			$company = $company->toArray();
			if(count($company['addresses'])){
				foreach ($company['addresses'] as $key => $value) {
					$this->session->set('store', $value);
					break;
				}
			}
			
		}

		$this->view->is_homepage = 1;
		
		
	}

	public function findStoresAction()
	{
		$this->view->disable();
		if($this->request->isPost()){
			$postalcode = $this->request->hasPost('postalcode') ? $this->request->getPost('postalcode') : '';
			$street_number = $this->request->hasPost('street_number') ? $this->request->getPost('street_number') : '';
			$street_name = $this->request->hasPost('street_name') ? $this->request->getPost('street_name') : '';
			$city = $this->request->hasPost('city') ? $this->request->getPost('city') : '';
			$province = $this->request->hasPost('province') ? $this->request->getPost('province') : '';
			$housing_type = $this->request->hasPost('housing_type') ? $this->request->getPost('housing_type') : '';
			$housing_name = $this->request->hasPost('housing_name') ? $this->request->getPost('housing_name') : '';
			$origin = 	($street_number!=''?urlencode($street_number):'').
					($street_name!=''?'+'.urlencode($street_name):'').
					($city!=''?'+'.urlencode($city):'').
					($province!=''?'+'.urlencode($province):'');
			$JTCompany = new JTCompany;
			$company = $JTCompany->findFirst(array("name"=>"Banh Mi Sub"));
			if($company){
				$company = $company->toArray();
				$list_store = [];
				foreach ($company['addresses'] as $key => $value) {
					if($value['deleted']!=1){
						$list_store[] = $value;
					}
				}
				// pr($list_store);

				if(count($list_store)){
					$destination='';
					foreach ($list_store as $key => $value) {
						if($key==0){
							$destination .= urlencode($value['address_1']).'+'.urlencode($value['town_city']).'+'.urlencode($value['province_state']);
						}else{
							$destination .= '|'.urlencode($value['address_1']).'+'.urlencode($value['town_city']).'+'.urlencode($value['province_state']);
						}
					}
					$data_location  = file_get_contents('http://maps.googleapis.com/maps/api/distancematrix/json?origins='.$origin.'&destinations='.$destination);
					$data_location = json_decode($data_location,true);
					$arr_sort_distance = [];
					foreach ($list_store as $key => $value) {
						if($data_location['rows'][0]['elements'][$key]['status']=="OK"){
							$list_store[$key]['distance'] = $data_location['rows'][0]['elements'][$key]['distance']['value'];
						}else{
							//unset($list_store[$key]);
						}
					}
					foreach ($list_store as $key => $value) {
						$arr_sort_distance[$key] = $value['distance'];
					}
					array_multisort($arr_sort_distance, SORT_ASC, $list_store);
					return $this->response(array("error"=>0,"data"=>$list_store));
				}else{
					return $this->response(array("error"=>1,"message"=>"Can not find store"));
				}
			}
			return $this->response(array("error"=>1,"message"=>"Can not find store"));
		}	
	}

	public function selectStoresAction()
	{
		if($this->request->isPost()){
			
			// Save as session guest
			$disposition = $this->request->hasPost('disposition') ? $this->request->getPost('disposition') : '';
			$postalcode = $this->request->hasPost('postalcode') ? $this->request->getPost('postalcode') : '';
			$street_number = $this->request->hasPost('street_number') ? $this->request->getPost('street_number') : '';
			$street_name = $this->request->hasPost('street_name') ? $this->request->getPost('street_name') : '';
			$city = $this->request->hasPost('city') ? $this->request->getPost('city') : '';
			$province = $this->request->hasPost('province') ? $this->request->getPost('province') : '';
			$housing_type = $this->request->hasPost('housing_type') ? $this->request->getPost('housing_type') : '';
			$housing_name = $this->request->hasPost('housing_name') ? $this->request->getPost('housing_name') : '';
			$guest = array();
			$guest['disposition'] = $disposition;
			$guest['postalcode'] = $postalcode;
			$guest['street_number'] = $street_number;
			$guest['street_name'] = $street_name;
			$guest['city'] = $city;
			$guest['province'] = $province;
			$guest['housing_type'] = $housing_type;
			$guest['housing_name'] = $housing_name;
			$this->session->set('guest', $guest);
			// End save as session guest

			$data =  $this->request->hasPost('data') ? $this->request->getPost('data') : '';
			if($data!='') {
				$data = json_decode($data);
			}
			$this->session->set('store', $data);
			if($this->request->isAjax())
			{
				$this->view->disable();
				return $this->response(array("error"=>0));
			}
		}	
	}

	public function getCategoryListAction(){
		// $JTCategory = new JTCategory;
		// $categories = $JTCategory->get();
		// $arr_return = array('error'=>1);
		$categories = $this->JTCategory();
		if($categories){
			$BannerCategories = new BannerCategories();
			$banner_categories = $BannerCategories->find()->toArray();
			foreach ($categories as $key => $value) {
				unset($categories[$key]['image']);
				foreach ($banner_categories as $key2 => $value2) {
					if($value2['id_category']==$value['id']){
						$categories[$key]['image'] =  URL.'/'.$value2['image'];
						break;
					}
				}
			}
			$arr_return = array('error'=>0,'data'=>$categories);
		}else{
			$arr_return['message'] = "No category";
		}
		return $this->response($arr_return);
	}

	public function getProductByCategoryAction(){
		$cate_name =  $this->request->hasPost('cate_name') ? $this->request->getPost('cate_name') : '';
		$product_list = $this->productByCategory($cate_name);
		return $this->response(array("error"=>0,"data"=>$product_list));
	}
}
