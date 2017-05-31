<?php
namespace RW\Controllers\Services;

use RW\Models\Products;

class IndexController extends ControllerBase
{

    public function indexAction()
    {

    }

    public function listProductsAction()
    {
        $products = Products::find();

        $arr_result = [];
        $arr_result['last_update'] = '';
        $arr_result['mac_address'] = '';
        $arr_result['mode'] = '';
        $arr_result['type'] = '';
        $data = [];
        
        foreach ($products as $key => $value) 
        {
        	$product = [];
        	$product['short_name'] = $value->short_name;
        	$product['extension'] = substr($value->image, strrpos($value->image, '.', -1)+1);
        	$product['image'] = URL.'/'.$value->image;
        	$product['price'] = $value->price;
        	$product['id'] = $value->id;
        	$product['name'] = $value->name;
            $product['category_id'] = $value->category_id;
            $cat = $value->categories;
            $product['category_name'] = $cat->name;            
        	$data[$value->id] = $product;
            
        }
        //pr($data);
        $arr_result['datasupdate'] = $data;
        
        //Write to products.json file
        $myFile = "datas/products.json";
		$fh = fopen($myFile, 'w') or die("can't open file");
		fwrite($fh, json_encode($arr_result));
		fclose($fh);

        $this->view->disable();

        $callback = $this->request->get('callback');
        if($callback != null)
        {
	        header("Content-Type: application/json; charset=utf-8");
	        echo $callback.'('.json_encode($arr_result).')';
	        exit;
        }

        //Create a response instance
        $response = new \Phalcon\Http\Response();
        $response->setContentType('application/json', 'UTF-8');

    	//Set the content of the response
        $response->setContent(json_encode($arr_result));

        //return $response;    	
        $response->send();
    }
}

