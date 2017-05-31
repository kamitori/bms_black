<?php
namespace RW\Controllers\Api;

use RW\Models\Categories;
use RW\Models\JTCategory;
use RW\Models\Carts;
use RW\Models\JTProduct;
use RW\Models\JTCompany;
use RW\Models\JTProvince;
use RW\Models\JTOrder;
use RW\Models\JTContact;
use RW\Models\Vouchers;
use RW\Cart\Cart;  

class ServicesController extends ControllerBase {
	
	public function getProductsByCategoryAction() {
        $categories = $this->JTCategory();
        // $categoryName = $this->dispatcher->getParam('categoryName');
        $categoryName = trim($this->request->get('categoryName'));
        // echo 'categoryName:'.$categoryName;exit;
        $categoryName = str_replace("-", "_", $categoryName);

        $this->view->disable();

        if (isset($categories[$categoryName])) {

            $data_product = $this->productByCategory($categories[$categoryName]['value']);            
            $data['products'] = $data_product['product_list'];
            $data['category'] = $categories[$categoryName];
            return $this->response($data);
        }
        else {
            return $this->abort(404);
        }
	}

	public function createOrderAction(){
        
        $this->view->disable();

        if($this->request->isPost())
        {
            $cart = new \RW\Cart\Cart();

            $data = $this->request->getPost();
            

            // return $this->response(['status'=>'vo post', 'data'=>$data['user']]);                 
            $data_cart_client = json_decode($data['cart'],true);
            foreach ($data_cart_client['items'] as $key => $value) {
                $data_cart_client['items'][$key]['_id'] = new \MongoId($value['_id']['$id']);
            }
            
            $user_data = json_decode($data['user']);

        	/*$result = array();
            foreach ($arr_products as $value) {
	            $result[] = $value->product->pName;
        	}
            return $this->response($result);*/                 
            
            $method_pay = '';
            
            // $location_address = $this->session->get('guest');
            $location_address = [
            						'postalcode' => '',
            						'street_number' => '',
            						'street_name' => '',
            						'city' => '',
            						'province' => ''
            					];
            $postalcode = $location_address['postalcode'];
            $street_number = $location_address['street_number'];        
            $street_name = $location_address['street_name'];
            $city = $location_address['city'];
            $province = $location_address['province'];        

            // $user_data = $this->session->get('user_data');
            /*$user_data = [
            						'name' => 'lam quang minh',
            						'email' => 'lqminhdev@yahoo.com',
            						'phone' => '382404856',
            						'note' => 'test test test test test test test test test test test test test test test',
            						'pickup_option' => 'pickup'
            					];*/

            $nameInfo = $user_data->uName;
            $emailInfo = trim($user_data->uEmail);
            $phone = $user_data->uPhone;
            $notes = $user_data->uNotes; 
            $pickup_option = 'pickup';
            $datetime_pickup = $user_data->uPickupDatetime;

            $cart->updateNote($notes);
            $cart->updateDeliveryTime($datetime_pickup);

            if($pickup_option=='pickup'){
                $postalcode = 'T2A 6K4';
                $street_number = '3145';
                $street_name = '5th Ave NE';
                $city = 'Calgary';
                $province = 'AB';
            }

           //  if(is_array($arr_products)) {
           //  	foreach ($arr_products as $item) {
		         //    $options = array();
           //          // $options = $item->options;
           //          $product = $item->product;
		         //    $options = JTProduct::FilterOptions($options,new \MongoId($product->pId));
		         //    $data_product = JTProduct::getPrice([
           //              '_id'   => $product->pId,
           //              'sizew' => 0,
           //              'sizeh' => 0,
           //              'quantity'  => $item->quantity,
           //              'note'      => '',
           //              'companyId' => '',
           //              'options'   => $options,
           //              'fields'    => ['name', 'description']
           //          ]);
        			// $cart->add([
           //              '_id'       => new \MongoId($product->pId),
           //              'name'      => $data_product['name'],
           //              'description'=> $data_product['description'],
           //              'note'      => '',
           //              'image'     => $product->pImageName,
           //              'quantity'  => $item->quantity,
           //              'options'   => $options,
           //              'sell_price' => $data_product['sell_price'],
           //              'total'     => $data_product['sub_total']
           //          ]);            		
           //  	}
           //  }

           $cart->buildItems($data_cart_client['items']);
           $data_cart = $cart->get();
            $provinceId = '';
            if($method_pay!='cash') {
                $cash_tend = $data_cart['main_total'];
            }
            else $cash_tend = $this->request->hasPost("cash_tend")?$this->request->getPost("cash_tend"):0;
            //Them delivery datetime va pickup date time
            // return $this->response(['data_cart datetime_pickup'=>$data_cart['datetime_pickup'], 'datetime_pickup'=>$datetime_pickup);        
            $datetime_pickup = $data_cart['datetime_pickup'];            
            $datetime_delivery = $data_cart['datetime_delivery'];


            // if($method_pay=='cash') $Paidby = 'Cash';
            if($method_pay=='cash') $Paidby = 'On Account';
            else{
                $arr_paidby = $this->request->hasPost("Paidby")?$this->request->getPost("Paidby"):array();
                if(count($arr_paidby)==1 && isset($arr_paidby))
                    $Paidby = $arr_paidby;
                else if(count($arr_paidby)>1)
                    $Paidby = 'Multipay';
                else
                    $Paidby = 'On Account';
            }

            $cash_tend = floatval($cash_tend);

            $payment_method = array('On Account'=>$cash_tend);
            $items = $cart->update_payment_mt($payment_method,$cash_tend);

            $soStatus = 'New';
            //$orderType = $this->request->hasPost("orderType")?$this->request->getPost("orderType"):0;
            $orderType = 2; //Online
            $create_from = 'Online';
            $status = 'In production';            

            $pos_delay = 0;
            $somongo = new \RW\Models\JTOrder;
            
             $arr_name = explode(' ', $nameInfo);
             $first_name = '';
             $last_name = '';
             foreach ($arr_name as $key => $value) {
                if($key==0){
                    $first_name = $value;
                }else{
                    $last_name .= $value.' ';
                }
             }

            $previous_order_status = 'No';

            $contact = (new \RW\Models\JTContact);
            $contact = $contact::findFirst(array(array('email'=>$emailInfo)));
            if(!$contact){
                $contact = new \RW\Models\JTContact;
                $contact->email = $emailInfo;
                $contact->fullname = $nameInfo;
                $contact->first_name = $first_name;
                $contact->last_name = $last_name;
                $contact->phone = $phone;
                try {
                    $contact->save();
                } catch (\RW\Cart\Exception $e) {
                }
            }
            else{
                $contact->email = $emailInfo;
                $contact->fullname = $nameInfo;
                $contact->first_name = $first_name;
                $contact->last_name = $last_name;
                $contact->phone = $phone;
                try {
                    $contact->save();
                } catch (\RW\Cart\Exception $e) {
                }
                
                $contact_id = $contact->_id;
                $previous_order = JTOrder::findFirst(array(
                    'conditions'=>array('contact_id'=>new \MongoId($contact_id),
                                        'deleted'=>false,
                                        '$or'=>array(array('status'=>'Completed'),
                                                    array('had_paid'=>1)
                                            )

                                        )
                ));
                if($previous_order) $previous_order_status = 'Yes';
            }

            $contact = $contact->toArray();
            $invoiceAddress =[[
                'deleted'   => false,
                'shipping_address_1' => $street_number . ' ' . $street_name,
                'shipping_address_2' => '',
                'shipping_address_3' => '',
                'shipping_town_city' => $city,
                'shipping_zip_postcode' => $postalcode,
                'shipping_province_state_id' => $provinceId,
                'shipping_province_state' => $province,
                'shipping_country'    => 'Canada',
                'shipping_country_id' => 'CA',
                'phones'=>$phone,
                'emails'=>$emailInfo,
                'full_name'=>$nameInfo,
                'notes'=>$notes
            ]];
            $shippingAddress =[[
                'deleted'   => false,
                'shipping_address_1' => $street_number . ' ' . $street_name,
                'shipping_address_2' => '',
                'shipping_address_3' => '',
                'shipping_town_city' => $city,
                'shipping_zip_postcode' => $postalcode,
                'shipping_province_state_id' => $provinceId,
                'shipping_province_state' => $province,
                'shipping_country'    => 'Canada',
                'shipping_country_id' => 'CA',
                'phones'=>$phone,
                'emails'=>$emailInfo,
                'full_name'=>$nameInfo,
                'notes'=>$notes
            ]];
            $deliveryMethod = $pickup_option;
            if ($this->request->getPost('chk_type_cart') == 'pickup') {
                $deliveryMethod = 'Call for Pick Up';
            }            
            if($contact){
                $order_id = $somongo->add($contact, [
                    'delivery_method' => $deliveryMethod,
                    'invoice_address'  => $invoiceAddress,
                    'shipping_address'  => $shippingAddress,
                    'cash_tend' => $cash_tend,
                    'paid_by' => $Paidby,
                    'pos_delay'=>$pos_delay,
                    'create_from'=>$create_from,
                    'datetime_pickup'=>$datetime_pickup,
                    'datetime_delivery'=>$datetime_delivery,
                    'time_delivery'=>new \MongoDate(time()),
                    'status'=>$status,
                    'asset_status'=>$status,
                    'phones'=>$phone,
                    'emails'=>$emailInfo,
                    'full_name'=>$nameInfo,
                ]);

            }else{
                $user = array('_id'=>new \MongoId('564694b0124dca8603f4d46f'));
                $order_id = $somongo->add($user, [
                    'delivery_method' => $deliveryMethod,
                    'invoice_address'  => $invoiceAddress,
                    'shipping_address'  => $shippingAddress,
                    'paid_by' => $Paidby,
                    'cash_tend' => $cash_tend, //payment
                    'pos_delay'=>$pos_delay,
                    'create_from'=>$create_from,
                    'status'=>$status,
                    'asset_status'=>$status,
                    'phones'=>$phone,
                    'emails'=>$emailInfo,
                    'full_name'=>$nameInfo,
                ]);

            }
            /*$cart->destroy();                

            $result = ['status'=>'ok', 
                                'message'=>'An email has been sent to you. Your order number is '
                            ];
            return $this->response($result); */            


            if ($order_id){

                $orderObj = JTOrder::findFirst(array(
                    'conditions'=>array('_id'=>new \MongoId($order_id))
                ));
                if($orderObj) $orderObj = $orderObj->toArray();
                
                $order_code = isset($orderObj['code']) ? $orderObj['code'] : '';                
                if($order_code != '')
                {                    
                    $params = ['order_id'=>$order_id, 
                                'code'=>$order_code, 
                                'name'=>$first_name,
                                'full_name'=>$nameInfo,
                                'emailInfo'=>$emailInfo,
                                'phone'=>$phone,
                                'previous_order_status'=>$previous_order_status
                                ];

                    $cartsController = new \RW\Controllers\CartsController();

                    $html = $cartsController->printCartTemplateAction($params);               

                    $params['baker'] = 1;
                    $html_notice_baker = $cartsController->printCartTemplateAction($params);

                    $arr_to = array(DEFAULT_EMAIL);

                    // Email notice to baker
                    $message = $this->mailer->message->setSubject('Order information from '.URL)
                      ->setFrom(array($this->config->smtp->from->email => $this->config->smtp->from->name))
                      ->setTo($arr_to)->setBcc(array(ADDITIONAL_EMAIL))
                      ->setBody($html_notice_baker, 'text/html', 'utf-8')
                      ;
                    $this->mailer->send($message);


                    //Email to customer
                    $arr_to = array();
                    if (!filter_var($emailInfo, FILTER_VALIDATE_EMAIL) === false) {
                        $arr_to = array($emailInfo);
                    }
                    // Create a message
                    $message = $this->mailer->message->setSubject('Order information from '.URL)
                      ->setFrom(array($this->config->smtp->from->email => $this->config->smtp->from->name))
                      ->setTo($arr_to)->setBcc(array())
                      ->setBody($html, 'text/html', 'utf-8')
                      ;
                    // Send the message
                    $this->mailer->send($message);
                    
                    $cart->destroy();                

                    $result = ['status'=>'ok', 
                                        'data'=>['code'=>$order_code],
                                        'message'=>'An email has been sent to you. Your order number is '.$order_code
                                    ];
                    return $this->response($result);             
                }
                
            }else{
                //
            }         
        }
        else
        {
            echo json_encode(['status'=>'fail']);
        }     
        
    }

    public function testAction(){
        $cart = new \RW\Cart\Cart();
        $emailInfo = 'lqminhdev@yahoo.com';
        $cartsController = new \RW\Controllers\CartsController();
        $params = ['order_id'=>'dsfasdfasdf', 
                    'code'=>'asdfadf', 
                    'name'=>'adfadsf',
                    'full_name'=>'adfasdf',
                    'emailInfo'=>$emailInfo,
                    'phone'=>'adfasdf',
                    'previous_order_status'=>''
                    ];
        
        $html = $cartsController->printCartTemplateAction($params);

        $params['baker'] = 1;
        $html_notice_baker = $cartsController->printCartTemplateAction($params);

        $arr_to = array(DEFAULT_EMAIL);

        // Email notice to baker
        $message = $this->mailer->message->setSubject('Order information from '.URL)
          ->setFrom(array($this->config->smtp->from->email => $this->config->smtp->from->name))
          ->setTo($arr_to)->setBcc(array(ADDITIONAL_EMAIL))
          ->setBody($html_notice_baker, 'text/html', 'utf-8')
          ;
        $this->mailer->send($message);


        //Email to customer
        $arr_to = array();
        if (!filter_var($emailInfo, FILTER_VALIDATE_EMAIL) === false) {
            $arr_to = array($emailInfo);
        }
        // Create a message
        $message = $this->mailer->message->setSubject('Order information from '.URL)
          ->setFrom(array($this->config->smtp->from->email => $this->config->smtp->from->name))
          ->setTo($arr_to)->setBcc(array())
          ->setBody($html, 'text/html', 'utf-8')
          ;
        // Send the message
        $this->mailer->send($message);
        
        $cart->destroy();                

        $result = ['status'=>'ok', 
                            'data'=>['code'=>'asdfasdf'],
                            'message'=>'An email has been sent to you. Your order number is '
                        ];        
        pr($result);exit;
    }    	
}
