<?php
namespace RW\Controllers;

use RW\Models\Carts;
use RW\Models\Banners;
use RW\Models\JTProduct;
use RW\Models\JTCompany;
use RW\Models\JTProvince;
use RW\Models\JTOrder;
use RW\Models\JTContact;
use RW\Models\Vouchers;
use RW\Cart\Cart;  
class CartsController extends ControllerBase
{
    private $cart;

    public function initialize()
    {
        parent::initialize();
        $this->cart = new Cart;
    }
    public function viewOrderDetailAction($_id=''){
        if ($_id=='' && !$this->request->isAjax()) {
            return $this->error404();
        }
        $_id = $this->request->getPost('str');
        $arrReturn = ['error' => 1, 'message' => 'Not enough infomation to process order'];
        $html = '';
        $arr_order = JTOrder::findFirst(array(
            'conditions'=>array('_id'=>new \MongoId($_id))
        ));
        $arr_order = $arr_order->toArray();

        if(!empty($arr_order)){
            
            $html .='<div class="text-left col-md-6">Date of Order: '.date('m-d-Y',($arr_order['salesorder_date']->sec)).'</div>';
            $html .='<div class="text-right col-md-6">Delivery method: '.(isset($arr_order['delivery_method']) ? ucfirst($arr_order['delivery_method']) : '' ).'</div>';
            $html .='<div class="text-left col-md-6">Order code: '.$arr_order['code'].'</div>';
            $html .='<div class="text-right col-md-6">Paid By: '.$arr_order['paid_by'].'</div>';
            $html .='<div class="text-left col-md-6">Full name: '.(isset($arr_order['full_name'])?$arr_order['full_name']:$arr_order['contact_name']).'</div>';
            $html .='<div class="text-right col-md-6">Phone: '.(isset($arr_order['phones']) ? $arr_order['phones'] : $arr_order['phone'] ).'</div>';
            $html .='<div class="text-left col-md-6">Email: '.(isset($arr_order['emails']) ? $arr_order['emails'] : $arr_order['email']).'</div>';
            $html .='<div class="text-right col-md-6">Status: '.$arr_order['status'].'</div>';
            $html .='<p class="text-left col-md-12" style="border-bottom:1px dotted">&nbsp;</p>';

            $arr_products = $arr_order['products'];
            $arr_carts = isset($arr_order['cart']) ? $arr_order['cart'] : array();
            $arr_image_and_note = array();
            if(isset($arr_carts['items'])){
                $arr_carts_item = $arr_carts['items'];
                foreach($arr_carts_item as $item){
                    $arr_carts_item[(string)$item['_id']] = array(                        
                        'image'=>$item['image'],
                        'des'=>$item['description'],
                    );
                }
            }

            for($i=0;$i<count($arr_products);$i++){
                $v_p_id = (string) $arr_products[$i]['products_id'];
                if($arr_products[$i]['sell_price']<=0) continue;
                $html .= '<div class="col-md-12">';
                    $html .= '<div class="col-md-5">';
                        $html .= '<div class="col-md-6">';
                            $html .= '<img alt="'.$arr_carts_item[$v_p_id]['des'].'" style="max-width:150px;" src="'.$arr_carts_item[$v_p_id]['image'].'" />';
                        $html .= '</div>';
                        $html .= '<div class="col-md-6">';
                            $html .= $arr_products[$i]['products_name'];
                        $html .= '</div>';
                    $html .= '</div>';
                    $html .= '<div class="col-md-2 text-right">';
                        $html .= display_format_currency($arr_products[$i]['sell_price']);
                    $html .= '</div>';
                    $html .= '<div class="col-md-1 text-right">';
                        $html .= $arr_products[$i]['quantity'];
                    $html .= '</div>';
                    $html .= '<div class="col-md-2 text-right">';
                        $html .= display_format_currency((float)$arr_products[$i]['sell_price'] * (int)$arr_products[$i]['quantity']);
                    $html .= '</div>';
                    $html .= '<div class="col-md-2 text-right">';
                        $html .= '<input type="button" class="btn btn-primary" onclick=reorderThisItem("'.$_id.'","'.$v_p_id.'") value="Reorder" />';
                    $html .= '</div>';
                $html .= '</div>';
            }
            $html .='<p class="text-left col-md-12" style="border-bottom:1px dotted">&nbsp;</p>';
            $html .= '<div class="col-md-12" style="height:45px;">';
                $html .= '<div class="col-md-4 text-left">';
                    $html .= 'Sub Total:';
                $html .= '</div>';
                $html .= '<div class="col-md-8 text-right">';
                    $html .= display_format_currency($arr_order['sum_sub_total']);
                $html .= '</div>';
            $html .= '</div>';

            $html .= '<div class="col-md-12" style="height:45px;">';
                $html .= '<div class="col-md-4 text-left">';
                    $html .= 'Tax:';
                $html .= '</div>';
                $html .= '<div class="col-md-8 text-right">';
                    $html .= display_format_currency($arr_order['sum_tax']);
                $html .= '</div>';
            $html .= '</div>';

            $html .= '<div class="col-md-12" style="height:45px;">';
                $html .= '<div class="col-md-4 text-left">';
                    $html .= 'Delivery fee:';
                $html .= '</div>';
                $html .= '<div class="col-md-8 text-right">';
                    $html .= display_format_currency(isset($arr_order['delivery_cost'])?$arr_order['delivery_cost']:0);
                $html .= '</div>';
            $html .= '</div>';

            $html .= '<div class="col-md-12" style="height:45px;">';
                $html .= '<div class="col-md-4 text-left">';
                    $html .= 'Amount:';
                $html .= '</div>';
                $html .= '<div class="col-md-8 text-right">';
                    $html .= display_format_currency($arr_order['sum_amount']);
                $html .= '</div>';
            $html .= '</div>';

        }        
        if($html!='') $arrReturn = ['error' => 0, 'message' => $html];
        return $this->response($arrReturn);
    }
    public function DocheckoutAction(){

    }

    public function indexAction()
    {
        $this->assets
                ->collection('css')
                ->addCss('/bower_components/bootstrap/dist/css/bootstrap.min.css')
                ->addCss('/bower_components/font-awesome/css/font-awesome.css')
                ->addCss('/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css')
                ->addCss('/'.THEME.'css/font.css')
                ->addCss('/'.THEME.'css/main.css')
                ->addCss('/'.THEME.'css/custom.css')
                ->setSourcePath(PUBLIC_PATH)
                ->setTargetPath(PUBLIC_PATH.DS.THEME.'/css/app.min.css')
                ->setTargetUri('/'.THEME.'css/app.min.css')
                ->join(true)
                ->addFilter(new \Phalcon\Assets\Filters\Cssmin());
        $this->assets
                ->collection('js')
                ->addJs('/bower_components/jquery/dist/jquery.min.js')
                ->addJs('/bower_components/bootstrap/dist/js/bootstrap.min.js')
                ->addJs('/bower_components/iscroll/build/iscroll.js')
                ->addJs('/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')
                ->addJs('/'.THEME.'js/load.js')
                ->addJs('/'.THEME.'js/function.js')
                ->addJs('/'.THEME.'js/pricing.js')
                ->addJs('/'.THEME.'js/common.js')
                // ->addJs('/'.THEME.'js/facebook-sdk.js')
                // ->addJs('/'.THEME.'js/facebook_login.js')
                ->addJs('/'.THEME.'js/banner.js')
                ->setSourcePath(PUBLIC_PATH)
                ->setTargetPath(PUBLIC_PATH.DS.THEME.'/js/app.min.js')
                ->setTargetUri('/'.THEME.'js/app.min.js')
                ->join(true)
                ->addFilter(new \Phalcon\Assets\Filters\Jsmin());
        $this->assets->collection('pageJS');
        $this->view->baseURL = URL;
        $this->view->session_user = $this->session->has('user') ? $this->session->get('user') : false;
        $this->view->cart = $this->cart->get();
        $this->view->provinces = JTProvince::find(array(
                                                array("country_id" => "CA"),
                                                "sort" => array("name" => 1)
                                            ));       
        
        $this->view->session_user = $this->session->has('user')?$this->session->get('user'):false;
        $this->view->partial('frontend/Carts/index');
        $this->view->setViewsDir($this->view->getViewsDir() . '/frontend/');
    }
    public function reOrderItemAction(){
        if (!$this->request->isAjax()) {
            return $this->error404();
        }
        $_id = $this->request->getPost('str');
        $_pid = $this->request->getPost('pstr');
        $arr_order = JTOrder::findFirst(array(
            'conditions'=>array('_id'=>new \MongoId($_id))
        ));
        $arr_order = $arr_order->toArray();
        $arrReturn = ['error' => 1, 'message' => 'Not enough infomation to process order'];
        if(!empty($arr_order)){
            $arr_products = $arr_order['products'];
            $arr_carts = isset($arr_order['cart']) ? $arr_order['cart'] : array();
            $arr_image_and_note = array();
            if(isset($arr_carts['items'])){
                $arr_carts_item = $arr_carts['items'];
                foreach($arr_carts_item as $item){
                    if($_pid != (string)$item['_id']) continue;
                    $arr_carts_item[(string)$item['_id']] = array(
                        'note'=>$item['note'],
                        'image'=>$item['image'],
                        'des'=>$item['description'],
                        'options'=>$item['options'],
                    );
                }
            }            
            $arr_options = $arr_order['options'];            
            for($i=0;$i<count($arr_products);$i++){
                $v_product_id = $arr_products[$i]['products_id'];                                
                $v_str_product_id = (string) $v_product_id;
                if(!isset($arr_carts_item[$v_str_product_id])) continue;
                $v_quantity = $arr_products[$i]['quantity'];
                $v_product_name = $arr_products[$i]['products_name'];
                $v_sell_price = $arr_products[$i]['sell_price'];
                $v_sub_total = $arr_products[$i]['sub_total'];
                
                $v_image = isset($arr_carts_item[$v_str_product_id]) ? $arr_carts_item[$v_str_product_id]['image'] : '';
                $v_note = isset($arr_carts_item[$v_str_product_id]) ? $arr_carts_item[$v_str_product_id]['note'] : '';
                $v_des = isset($arr_carts_item[$v_str_product_id]) ? $arr_carts_item[$v_str_product_id]['des'] : '';
                $arr_option = isset($arr_carts_item[$v_str_product_id]) ? $arr_carts_item[$v_str_product_id]['options'] : array();
                $options = JTProduct::FilterOptions($arr_option,$v_product_id);
                $data = JTProduct::getPrice(
                    [
                        '_id'   => $v_str_product_id,
                        'sizew' => 0,
                        'sizeh' => 0,
                        'quantity'  => $v_quantity,
                        'note'      => '',
                        'companyId' => '',
                        'options'   => $options,
                        'fields'    => ['name', 'description']
                    ]
                );
                $cartKey = $this->cart->add(
                    [
                        '_id'       => $v_product_id,
                        'name'      => $v_product_name,
                        'description'=> $v_des,
                        'note'      => $v_note,
                        'image'     => $v_image,
                        'quantity'  => $v_quantity,
                        'options'   => $options,
                        'sell_price' => $v_sell_price,
                        'total'     => $v_sub_total
                    ]
                );
                $item = $this->cart->get($cartKey);
                $total = $this->cart->getDetailTotal();
                $arrReturn = [
                    'error' => 0,
                    'cart' => [
                        'quantity' => $this->cart->getQuantity(),
                        'main_total' => number_format($total['main_total'],2)
                    ]
                ];
            }
        }
        return $this->response($arrReturn);
    }
    public function reOrderAction(){
        if (!$this->request->isAjax()) {
            return $this->error404();
        }
        $arrReturn = ['error' => 1, 'message' => 'Not enough infomation to process order'];
        $_id = $this->request->getPost('str');

        $arr_order = JTOrder::findFirst(array(
            'conditions'=>array('_id'=>new \MongoId($_id))
        ));
        $arr_order = $arr_order->toArray();
        if(!empty($arr_order)){
            $arr_products = $arr_order['products'];
            $arr_carts = isset($arr_order['cart']) ? $arr_order['cart'] : array();
            $arr_image_and_note = array();
            if(isset($arr_carts['items'])){
                $arr_carts_item = $arr_carts['items'];
                foreach($arr_carts_item as $item){
                    $arr_carts_item[(string)$item['_id']] = array(
                        'note'=>$item['note'],
                        'image'=>$item['image'],
                        'des'=>$item['description'],
                        'options'=>$item['options'],
                    );
                }
            }
            $arr_options = $arr_order['options'];            
            for($i=0;$i<count($arr_products);$i++){
                $v_product_id = $arr_products[$i]['products_id'];
                $v_str_product_id = (string) $v_product_id;
                $v_quantity = $arr_products[$i]['quantity'];
                $v_product_name = $arr_products[$i]['products_name'];
                $v_sell_price = $arr_products[$i]['sell_price'];
                $v_sub_total = $arr_products[$i]['sub_total'];
                
                $v_image = isset($arr_carts_item[$v_str_product_id]) ? $arr_carts_item[$v_str_product_id]['image'] : '';
                $v_note = isset($arr_carts_item[$v_str_product_id]) ? $arr_carts_item[$v_str_product_id]['note'] : '';
                $v_des = isset($arr_carts_item[$v_str_product_id]) ? $arr_carts_item[$v_str_product_id]['des'] : '';
                $arr_option = isset($arr_carts_item[$v_str_product_id]) ? $arr_carts_item[$v_str_product_id]['options'] : array();
                $options = JTProduct::FilterOptions($arr_option,$v_product_id);
                $data = JTProduct::getPrice(
                    [
                        '_id'   => $v_str_product_id,
                        'sizew' => 0,
                        'sizeh' => 0,
                        'quantity'  => $v_quantity,
                        'note'      => '',
                        'companyId' => '',
                        'options'   => $options,
                        'fields'    => ['name', 'description']
                    ]
                );
                $cartKey = $this->cart->add(
                    [
                        '_id'       => $v_product_id,
                        'name'      => $v_product_name,
                        'description'=> $v_des,
                        'note'      => $v_note,
                        'image'     => $v_image,
                        'quantity'  => $v_quantity,
                        'options'   => $options,
                        'sell_price' => $v_sell_price,
                        'total'     => $v_sub_total
                    ]
                );
                $item = $this->cart->get($cartKey);
                $total = $this->cart->getDetailTotal();
                $arrReturn = [
                    'error' => 0,
                    'cart' => [
                        'quantity' => $this->cart->getQuantity(),
                        'main_total' => number_format($total['main_total'],2)
                    ]
                ];
            }
        }
        return $this->response($arrReturn);
    }
    public function addCartAction()
    {
        if (!$this->request->isAjax()) {
            return $this->error404();
        }
        $arrReturn = ['error' => 1, 'message' => 'Not enough infomation to process the calculation.'];
        $main = $this->request->getPost('main');
        if( $main ){
            $options = $this->request->getPost('options');

            $category_banhmisub = (int)$this->request->getPost('category_sub');
            $category_11_size = (int)$this->request->getPost('size');

            $options = JTProduct::FilterOptions($options,new \MongoId($main['_id']));
            $data = JTProduct::getPrice([
                                            '_id'   => $main['_id'],
                                            'sizew' => 0,
                                            'sizeh' => 0,
                                            'quantity'  => $main['quantity'],
                                            'note'      => $main['note'],
                                            'companyId' => '',
                                            'options'   => $options,
                                            'fields'    => ['name', 'description', 'category']
                                        ]);
            $cartKey = $this->cart->add([
                            '_id'       => new \MongoId($main['_id']),
                            'name'      => $data['name'],
                            'description'=> $data['description'],
                            'note'      => $main['note'],
                            'image'     => $main['image'],
                            'quantity'  => $main['quantity'],
                            'options'   => $options,
                            'sell_price' => $data['sell_price'],
                            'total'     => $data['sub_total'],
                            'category'     => $data['category'],
                            'is_banhmisub_category' => $category_banhmisub,
                            'is_11_inch'=> $category_11_size,
                            'quantity_promo'=>0
                        ]);
            $item = $this->cart->get($cartKey);
            $total = $this->cart->getDetailTotal();
            $arrReturn = [
                            'error' => 0,
                            'cart' => [
                                'quantity' => $this->cart->getQuantity(),
                                'total' => number_format($total['total'],2),
                                'taxper' => number_format($total['taxper'],2),
                                'tax' => number_format($total['tax'],2),
                                'main_total' => number_format($total['main_total'],2)
                            ],
                            'product' => [
                                'cartKey'       =>  $cartKey,
                                'name'          => $data['name'],
                                'description'   => $data['description'],
                                'note'          => isset($data['note']) ? $data['note'] : '',
                                'image'         => $main['image'],
                                'sell_price'    => number_format($data['sell_price'], 2),
                                'quantity'      => $item['quantity']
                            ],
                            'combo_step' => (new \RW\Cart\Cart)->combostep(),
                            'is_use_group' => (new \RW\Cart\Cart)->checkgroup()
                        ];
           // $cart_now = $this->cart->get();
           // $arr_cart= json_decode(file_get_contents("datacart.json"),true);
           // $arr_cart[session_id()] = $cart_now;
           // file_put_contents("datacart.json", json_encode($arr_cart));  
        }
        // update promo item
        $promo_code = $this->cart->get_promo();
        if($promo_code!='') $this->cart->update_promo($promo_code);
        return $this->response($arrReturn);
    }
    // create jt order
    public function processingAction(){
        
        if($this->request->isPost())
        {
            $data = $this->request->getPost();
            $items = (new \RW\Cart\Cart)->get();
            $method_pay = $data['method'];
            $location_address = $this->session->get('guest');
            
            $postalcode = $location_address['postalcode'];
            $address_1 = $location_address['address_1'];        
            $city = $location_address['city'];
            $province = $location_address['province'];        
            $postal_code = $location_address['postalcode'];

            $user_data = $this->session->get('user_data');

            $nameInfo = $user_data['name'];
            $emailInfo = trim($user_data['email']);
            $phone = $user_data['phone'];
            $notes = $user_data['note']; 
            $pickup_option = $user_data['pickup_option'];

            $this->cart->updateNote($notes);

            if($pickup_option=='pickup'){
                $postalcode = 'T2A 6K4';
                $address_1 = '3145 5th Ave NE';
                $city = 'Calgary';
                $province = 'AB';
                $postal_code = 'T2A 6K4';
            }

            $provinceId = '';
            if($method_pay!='cash') {
                $items = (new \RW\Cart\Cart)->get();
                $cash_tend = $items['main_total'];
            }
            else $cash_tend = $this->request->hasPost("cash_tend")?$this->request->getPost("cash_tend"):0;
            //Them delivery datetime va pickup date time
            $cart = (new \RW\Cart\Cart)->get();
            $datetime_pickup = $cart['datetime_pickup'];
            $datetime_delivery = $cart['datetime_delivery'];
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
            $items = $this->cart->update_payment_mt($payment_method,$cash_tend);

            $soStatus = 'New';
            //$orderType = $this->request->hasPost("orderType")?$this->request->getPost("orderType"):0;
            $orderType = 2; //Online
            $create_from = 'Online';
            $status = 'In production';            

            $pos_delay = 0;

            if($soStatus=='In production' && $orderType==0){
                $pos_delay = 1;
                $somongo = new \RW\Models\JTQuotation;
            }else{
                $pos_delay = 0;
                $somongo = new \RW\Models\JTOrder;
            }
            // echo $pos_delay;die;
            
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
                'shipping_address_1' => $address_1,
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
                'shipping_address_1' => $address_1,
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
            $payment_data = "";
            $had_paid = 0;
            $had_paid_amount = 0;
            if($method_pay=="card"){
                require APP_PATH.'/vendor/autoload.php';
                $merchant_id = MERCHANT_ID; //Official merchant id
                $api_key = BEANSTREAM_API_KEY; // Official key
                $api_version = 'v1'; //default
                $platform = 'www'; //default
                $beanstream = new \Beanstream\Gateway($merchant_id, $api_key, $platform, $api_version);
		        $items = (new \RW\Cart\Cart)->get();
                $order_id  = (new \RW\Models\JTOrder)->getCode();
                $payment_data = array(
                        'order_number' => $order_id,
                        'amount' =>$items['main_total'],
                        'payment_method' => 'card',
                        'card' => array(
                            'name' => (string) $data['nameoncard'],
                            'number' => (string) $data['cardnumber'],
                            'expiry_month' => (string) $data['expmonth'],
                            'expiry_year' => (string) $data['expyear'],
                            'cvd' => (string) $data['cvd']
                        ),
                        'billing' => array(
                            'name' =>(string) $nameInfo,
                            'email_address' =>(string) $emailInfo,
                            'phone_number' =>(string) str_replace("-","",$phone),
                            'address_line1' =>(string) $street_number . ' ' . $street_name,
                            'city' =>(string) $city,
                            'province' =>(string) "AB",
                            'postal_code' =>(string) $postalcode,
                            'country' =>(string) 'CA'
                        ),
                        'shipping' => array(
                            'name' =>(string) $nameInfo,
                            'email_address' =>(string) $emailInfo,
                            'phone_number' =>(string) str_replace("-","",$phone),
                            'address_line1' =>(string) $street_number . ' ' . $street_name,
                            'city' =>(string) $city,
                            'province' =>(string) "AB",
                            'postal_code' =>(string) $postalcode,
                            'country' =>(string) 'CA'
                        )
                );
                $complete = true;
                try {
                    $result = $beanstream->payments()->makeCardPayment($payment_data, $complete);
                    $had_paid = 1;
                    $had_paid_amount = $items['main_total'];
                    $Paidby = "Had paid";
                } catch (\Beanstream\Exception $e) {
                    //if($e->code!=7){
                        $this->session->set("error_payment",$e->getMessage());
                        return $this->response->redirect("/carts/pay-ment");
                    //}
                }
            }
            $deliveryMethod = $pickup_option;
            if ($this->request->getPost('chk_type_cart') == 'pickup') {
                $deliveryMethod = 'Call for Pick Up';
            }
            $voucher = $this->cart->get_voucher();
            if($contact){
                $order_id = $somongo->add($contact, [
                    'delivery_method' => $deliveryMethod,
                    'invoice_address'  => $invoiceAddress,
                    'shipping_address'  => $shippingAddress,
                    'cash_tend' => $cash_tend,
                    'paid_by' => $Paidby,
                    'had_paid'=>$had_paid,
                    'had_paid_amount'=>$had_paid_amount,
                    'pos_delay'=>$pos_delay,
                    'create_from'=>$create_from,
                    'datetime_pickup'=>$datetime_pickup,
                    'payment_due_date'=>$datetime_pickup,
                    'payment_data'=>$payment_data,
                    'datetime_delivery'=>$datetime_delivery,
                    'time_delivery'=>new \MongoDate(time()),
                    'status'=>$status,
                    'asset_status'=>$status,
                    'phones'=>$phone,
                    'emails'=>$emailInfo,
                    'full_name'=>$nameInfo,
                    'voucher'=>$voucher
                ]);

            }else{
                $user = array('_id'=>new \MongoId('564694b0124dca8603f4d46f'));
                $order_id = $somongo->add($user, [
                    'delivery_method' => $deliveryMethod,
                    'invoice_address'  => $invoiceAddress,
                    'shipping_address'  => $shippingAddress,
                    'paid_by' => $Paidby,
                    'had_paid'=>$had_paid,
                    'had_paid_amount'=>$had_paid_amount,
                    'cash_tend' => $cash_tend, //payment
                    'pos_delay'=>$pos_delay,
                    'create_from'=>$create_from,
                    'status'=>$status,
                    'payment_due_date'=>$datetime_pickup,
                    'payment_data'=>$payment_data,
                    'asset_status'=>$status,
                    'phones'=>$phone,
                    'emails'=>$emailInfo,
                    'full_name'=>$nameInfo,
                    'voucher'=>$voucher
                ]);

            }

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
                                'previous_order_status'=>$previous_order_status,
                                'datetime_pickup'=>date('D M d, Y g:i A',$datetime_pickup->sec),                                
                                ];
                    $html = $this->printCartTemplateAction($params);               

                    $params['baker'] = 1;
                    $html_notice_baker = $this->printCartTemplate2Action($params);

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
                      //restore

                    /*echo json_encode(['status'=>'ok', 
                                        'data'=>['code'=>$order_code],
                                        'message'=>'An email has been sent to you. Your order\'s number is <b>'.$order_code.'</b>'
                                    ]);*/                
                }
                (new \RW\Cart\Cart)->destroy();
                return $this->response->redirect(URL.'/carts/view-order/'.$order_id);
        
            }        
        }
        else
        {
            $sender_email = $this->config->smtp->from->email;
            $this->view->sender_email = $sender_email;
            $this->view->title = "Order Complete | Banh Mi SUB";
            return $this->view->partial('frontend/Carts/processing');
        }     
        
    }

    public function updateCartAction(){
        if (!$this->request->isAjax()) {
            return $this->error404();
        }
        $arrReturn = ['error' => 1, 'message' => 'Not enough infomation to process the calculation.'];
        $main = $this->request->getPost('main');
        $cart_id = $this->request->getPost('cart_id');
        if( $main ){
            $options = $this->request->getPost('options');
            $options = JTProduct::FilterOptions($options,new \MongoId($main['_id']));
            $data = JTProduct::getPrice([
                                            '_id'   => $main['_id'],
                                            'sizew' => 0,
                                            'sizeh' => 0,
                                            'quantity'  => $main['quantity'],
                                            'note'      => $main['note'],
                                            'companyId' => '',
                                            'options'   => $options,
                                            'fields'    => ['name', 'description']
                                        ]);
            $update_cart = $this->cart->update($cart_id,[
                            '_id'       => new \MongoId($main['_id']),
                            'name'      => $data['name'],
                            'description'=> $data['description'],
                            'note'      => $main['note'],
                            'image'     => $main['image'],
                            'quantity'  => $main['quantity'],
                            'options'   => $options,
                            'sell_price' => $data['sell_price'],
                            'total'     => $data['sub_total'],
                        ]);
            $item = $this->cart->get($cart_id);
            $total = $this->cart->getDetailTotal();
            $arrReturn = [
                            'error' => 0,
                            'cart' => [
                                'quantity' => $this->cart->getQuantity(),
                                'total' => number_format($total['total'],2),
                                'taxper' => number_format($total['taxper'],2),
                                'tax' => number_format($total['tax'],2),
                                'main_total' => number_format($total['main_total'],2)
                            ],
                            'product' => [
                                'cartKey'       =>  $cart_id,
                                'name'          => $data['name'],
                                'description'   => $data['description'],
                                'note'          => $data['note'],
                                'image'         => $main['image'],
                                'sell_price'    => number_format($data['sell_price'], 2),
                                'quantity'      => $item['quantity']
                            ]
                        ];
            $cart_now = $this->cart->get();
           $arr_cart= json_decode(file_get_contents("datacart.json"),true);
           $arr_cart[session_id()] = $cart_now;
           file_put_contents("datacart.json", json_encode($arr_cart));
        }
        return $this->response($arrReturn);
    }
    public function removeLinkItemAction($id){            
        try {
            $this->cart->remove($id);
            $total = $this->cart->getDetailTotal();
            $arrReturn = [
                            'error' => 0,
                            'cart' => [
                                'quantity' => $this->cart->getQuantity(),
                                'total' => number_format($total['total'],2),
                                'taxper' => number_format($total['taxper'],2),
                                'tax' => number_format($total['tax'],2),
                                'main_total' => number_format($total['main_total'],2)
                            ],
                        ];
            $cart_now = $this->cart->get();
            $arr_cart= json_decode(file_get_contents("datacart.json"),true);
            $arr_cart[session_id()] = $cart_now;
            file_put_contents("datacart.json", json_encode($arr_cart));
        } catch (\RW\Cart\Exception $e) {            
        }
        $response = new \Phalcon\Http\Response();
        // update promo item
        $promo_code = $this->cart->get_promo();
        if($promo_code!='') $this->cart->update_promo($promo_code);

        return $response->redirect("/carts/check-out");    
            
    }
    public function removeItemAction()
    {
        if (!$this->request->isAjax()) {
            return $this->error404();
        }
        $arrReturn = ['error' => 1, 'message' => 'Please refresh and try again.'];
        try {
            $this->cart->remove($this->request->getPost('cartKey'));
            $total = $this->cart->getDetailTotal();
            $arrReturn = [
                            'error' => 0,
                            'cart' => [
                                'quantity' => $this->cart->getQuantity(),
                                'total' => number_format($total['total'],2),
                                'taxper' => number_format($total['taxper'],2),
                                'tax' => number_format($total['tax'],2),
                                'main_total' => number_format($total['main_total'],2)
                            ],
                        ];
            $cart_now = $this->cart->get();
           $arr_cart= json_decode(file_get_contents("datacart.json"),true);
           $arr_cart[session_id()] = $cart_now;
           file_put_contents("datacart.json", json_encode($arr_cart));
        } catch (\RW\Cart\Exception $e) {
        }
        return $this->response($arrReturn);
    }
    public function payMentAction(){
        $items = (new \RW\Cart\Cart)->get();
        $this->view->title = "Order Payment | Banh Mi SUB";
        $this->view->error_payment = $this->session->has("error_payment")?$this->session->get("error_payment"):"";
        $this->session->remove("error_payment");
        $this->view->partial('frontend/Carts/payment', array('cart' => $items, 'baseURL' => URL));
    }
    public function saveAddressAction(){
        $data = $this->request->getPost();

        if(!empty($data)){            
            $guest['disposition'] = '';
            $guest['postalcode'] = isset($data['postal_code'])?$data['postal_code']:"";
            $guest['address_1'] = isset($data['address_1'])?$data['address_1']:"";
            $guest['city'] = isset($data['city'])?$data['city']:"";
            $guest['province'] = isset($data['province'])?$data['province']:"";
            $guest['housing_name'] = isset($data['postal_code'])?$data['postal_code']:"";
            $this->session->set('guest', $guest);
            $guest['key_address'] = isset($data['key_address'])?$data['key_address']:"";
            $key_address = isset($data['key_address'])?$data['key_address']:"";
            $user_data['name'] = isset($data['order_user_name'])?$data['order_user_name']:"";
            $user_data['email'] = isset($data['order_user_email'])?trim($data['order_user_email']):"";
            $user_data['phone'] = isset($data['order_phone'])?$data['order_phone']:"";
            $user_data['pickup_option'] = isset($data['pickup_option'])?$data['pickup_option']:"delivery";
            $user_data['note'] = isset($data['notes'])?$data['notes']:""; 


            $password = isset($data['password'])?$data['password']:'';

            if($password){

                $email = trim($data['order_user_email']);
                $user = JTContact::findFirst(array(array('email'=>$email)));
                
                if(!$user)
                {
                    $arr_name = explode(" ", $user_data['name']);
                    $first_name = $arr_name[0];
                    $last_name = '';
                    for($i=0;$i<count($arr_name);$i++){
                        if($i==0) continue;
                        $last_name = $arr_name[$i]. ' ';
                    }                     
                    $facebook_id = '';
                    $subscribe = 0;
                    $phone = $user_data['phone'];
                    $birthday = '';
                    $address = $guest['address_1'];
                    $town_city = $guest['city'];                    
                    $province_id = 0;
                    $province_name = $guest['province'];
                    $postcode = $guest['postalcode'];
                    
                    // Create JT contact
                    $contact = new JTContact();
                    $contact->first_name = $first_name;
                    $contact->last_name = $last_name;
                    $contact->fullname = $user_data['name'];
                    $contact->password = $this->security->hash($password);
                    $contact->email = $email;
                    $contact->mobile = $phone;
                    $contact->subscribe = $subscribe;
                    $contact->phone = $phone;
                    $contact->birthday = $birthday;
                    $contact->facebook_id = $facebook_id;
                    $contact->is_customer = 1;
                    $contact->is_employee = 0;

                    $company = JTCompany::findFirst(
                                                array(array(
                                                        'name' => 'Retail'
                                                    )
                                                )
                                            );
                    if($company){
                        $contact->company = $company->name;
                        $contact->company_id = $company->_id;
                    }

                    $contact_addresses = array();
                    $contact_addresses['country'] = 'Canada';
                    $contact_addresses['country_id'] = 'CA';
                    $contact_addresses['province_state'] = $province_name;
                    $contact_addresses['province_state_id'] = $province_id;
                    $contact_addresses['address_1'] = $address;
                    $contact_addresses['town_city'] = $town_city;
                    $contact_addresses['zip_postcode'] = $postcode;
                    $contact->addresses = array($contact_addresses);
                    
                    if($contact->save()){
                        $this->session->set('user',$contact->toArray());                        
                    }    

                }else{
                    // $this->session->set('user',$user->toArray());
                    if ($this->security->checkHash($password, $user->password)) {
                        $this->session->set('user',$user->toArray());                        
                    }else{
                        return $this->response(['error'=>'1','message'=>'Email exsited but password is wrong. Please try with different password or email']);            
                    }
                }
            }else{
                if($this->session->has('user')){
                    if($key_address==''){
                        $arr_user = $this->session->get('user');
                        $id = new \MongoId($arr_user['_id']);
                        $contact = JTContact::findFirst(array(array('_id'=>$id)));            
                        

                        $contact_addresses['country'] = 'Canada';
                        $contact_addresses['country_id'] = 'CA';
                        $contact_addresses['province_state'] = $guest['province'];
                        $contact_addresses['province_state_id'] = '';
                        $contact_addresses['address_1'] = $guest['address_1'];
                        $contact_addresses['town_city'] = $guest['city'];
                        $contact_addresses['zip_postcode'] = $guest['postalcode'];
                        $contact_addresses['phone'] = $user_data['phone'];
                        $contact_addresses['note'] = $user_data['note'];
                        $contact->addresses[] = $contact_addresses;
                        $contact->save();
                    }
                }
            }

            $delivery_date = isset($data['delivery_date'])?$data['delivery_date']:"";
            $this->cart->updateDeliveryTime($delivery_date);

            $this->session->set('user_data', $user_data);            
            return $this->response(['error'=>0]);
        }
        return $this->response(['error'=>1,'message'=>'Invalid data']);
    }
    public function detailAction(){
        // $location_address = $this->session->get('guest');
        // pr($location_address);die;
        $items = (new \RW\Cart\Cart)->get();
        if(isset($items['combo_step']) && ($items['combo_step']=='1' || $items['combo_step']=='2' || $items['combo_step']=='3'))
        {
            return $this->response->redirect('/sub_combo');   
        }
        $contact_addresses = array();
        if($this->session->has('user')){
            $arr_user = $this->session->get('user');
            $id = new \MongoId($arr_user['_id']);
            $contact = JTContact::findFirst(array(array('_id'=>$id)));
            $contact_addresses = $contact->addresses; 
            foreach($contact_addresses as $arr){
                if(isset($arr['deleted']) && $arr['deleted']) continue;
                $contact_addresses = $arr;
                break;
            }
            $contact_addresses['mobile'] = $contact->mobile?$contact->mobile:"";
            $contact_addresses['phone'] = $contact->phone?$contact->phone:"";
        }
        $items = (new \RW\Cart\Cart)->get();
        $total_discount = 0;
        if(isset($items['voucher_code']) && $items['voucher_code']!='') $total_discount += (float)$items['discount_total'];
        
        $contact_addresses['emails'] = $contact->email;
        $contact_addresses['full_name'] = isset($contact->fullname)?$contact->fullname:"";


        $is_login = $this->session->get('user') ? 1 : 0;
        $arr_province = array();
        $provinces = JTProvince::find(array(
            'conditions'=>array('deleted'=>false,'country_id'=>'CA')
        ));
        foreach($provinces as $province){
            $arr_province[] = $province->toArray();
        }
        $this->view->arr_province = $arr_province;
        $arr_user_address = array();
        foreach ($contact->addresses as $key => $value) {
            $arr_tmp['key'] = $key;
            $arr_tmp['text'] = $value['zip_postcode'].' - '.$value['address_1'];
            $arr_tmp['json'] = json_encode($value);
            $arr_user_address[] = $arr_tmp;
        }
        $this->view->arr_user_address = $arr_user_address;
        $this->view->datas = $this->session->get('guest');
        $this->view->is_login = $is_login;
        $this->view->data = $this->session->get('user_data');
        $this->view->contact_addresses = $contact_addresses;
        $this->view->total_discount = $total_discount;
        $this->view->title = "Order Detail | Banh Mi SUB";
        $this->view->partial('frontend/Carts/detail', array('cart' => $items, 'baseURL' => URL));
    }
    public function updateQuantityAction()
    {
        if (!$this->request->isAjax()) {
            return $this->error404();
        }
        $arrReturn = ['error' => 1, 'message' => 'Please refresh and try again.'];
        try {
            $cartKey = $this->request->getPost('cartKey');
            $item = $this->cart->get($cartKey);
            $item['quantity'] = $this->request->getPost('quantity');
            $data = JTProduct::getPrice([
                                            '_id'   => $item['_id'],
                                            'sizew' => 0,
                                            'sizeh' => 0,
                                            'quantity'  => $item['quantity'],
                                            'companyId' => '',
                                            'options'   => $item['options'],
                                        ]);
            $item['sell_price'] = $data['sell_price'];
            $item['total'] = $data['sub_total'];
            $this->cart->update($cartKey, $item);
            $promo_code = $this->cart->get_promo();
            $arr_promo_value = array();            
            if($promo_code!=''){
                $arr_promo_list = self::get_list_item($promo_code);
                                
                $arr_promo_value = array(
                    'message'=>$arr_promo_list['message'],
                    'coupon'=>$promo_code,
                    'new_tax'=>$arr_promo_list['new_tax'],
                    'new_val'=>$arr_promo_list['new_total'],
                    'total'=>$arr_promo_list['total']
                );
            }
            $total = $this->cart->getDetailTotal();
            $arrReturn = [
                            'error' => 0,                            
                            'cart' => [
                                'quantity' => $this->cart->getQuantity(),
                                'taxper' => number_format($total['taxper'],2),

                                'total' => ( isset($arr_promo_value['total']) ? number_format($arr_promo_value['total'],2) : number_format($total['total'],2) ),
                                'tax' => ( isset($arr_promo_value['new_tax']) ? number_format($arr_promo_value['new_tax'],2) : number_format($total['tax'],2) ),
                                'main_total' => ( isset($arr_promo_value['new_val']) ? number_format($arr_promo_value['new_val'],2) : number_format($total['main_total'],2) ),
                            ],
                            'product' => [
                                'total' => number_format($item['total'], 2)
                            ],
                            'data_promo'=>( isset($arr_promo_value['message']) ? $arr_promo_value['message']:'')
                        ];
            $cart_now = $this->cart->get();

            if(isset($item['user_id']))
            {
                $arrReturn['product']['group_total'] = $cart_now['group_order']['list'][$item['user_id']]['total'];
                $arrReturn['product']['user_id'] = $item['user_id'];
            }

            $arr_cart= json_decode(file_get_contents("datacart.json"),true);
            $arr_cart[session_id()] = $cart_now;
            file_put_contents("datacart.json", json_encode($arr_cart));
        } catch (\RW\Cart\Exception $e) {
        }
        return $this->response($arrReturn);
    }

    public function updateNoteAction()
    {
        if (!$this->request->isAjax()) {
            return $this->error404();
        }
        $this->cart->updateNote($this->request->getPost('note'));
        return $this->response(['error' => 0]);
    }

    public function updateItemAction()
    {
        if (!$this->request->isAjax()) {
            return $this->error404();
        }
        $arrReturn = ['error' => 1, 'message' => 'Please refresh and try again.'];
        try {
            $cartKey = $this->request->getPost('cartKey');
            $item = $this->cart->get($cartKey);
            $item['options'] = $this->request->getPost('options');
            $data = JTProduct::getPrice([
                                            '_id'   => $item['_id'],
                                            'sizew' => 0,
                                            'sizeh' => 0,
                                            'quantity'  => $item['quantity'],
                                            'companyId' => '',
                                            'options'   => $item['options'],
                                        ]);
            $item['sell_price'] = $data['sell_price'];
            $item['total'] = $data['sub_total'];
            $this->cart->update($cartKey, $item);
            $total = $this->cart->getDetailTotal();
            $arrReturn = [
                            'error' => 0,
                            'cart' => [
                                'quantity' => $this->cart->getQuantity(),
                                'total' => number_format($total['total'],2),
                                'taxper' => number_format($total['taxper'],2),
                                'tax' => number_format($total['tax'],2),
                                'main_total' => number_format($total['main_total'],2)
                            ],
                            'product' => [
                                'total' => number_format($item['total'], 2)
                            ]
                        ];
            $cart_now = $this->cart->get();
           $arr_cart= json_decode(file_get_contents("datacart.json"),true);
           $arr_cart[session_id()] = $cart_now;
           file_put_contents("datacart.json", json_encode($arr_cart));
        } catch (\RW\Cart\Exception $e) {
        }
        return $this->response($arrReturn);
    }
    public function taxlist(){
        return array(0=>'0%',5=>'5%',12=>'12%',13=>'13%',14=>'14%',15=>'15%');
    }
    public function dropCart()
    {
        if (!$this->request->isAjax()) {
            return $this->error404();
        }
        $this->cart->destroy();
       $arr_cart= json_decode(file_get_contents("datacart.json"),true);
       unset($arr_cart[session_id()]);
       file_put_contents("datacart.json", json_encode($arr_cart));
        return $this->response(['error' => 0]);
    }
    public function removePromoAction(){
        $arr_return = array('error'=>1,'message'=>'');
        $data = $this->cart->remove_promo();
        $arr_return['new_main_total'] = $data['new_main_total'];
        $arr_return['tax'] = $data['tax'];
        $arr_return['new_sub_total'] = $data['new_sub_total'];
        $this->view->disable();
        return $this->response($arr_return);
    }
    public function applyPromoAction(){
        $message = ''; $voucher_value = 0;
        $coupon = $this->request->hasPost('coupon')?$this->request->getPost('coupon'):'';
        $arr_return = array('error'=>1,'message'=>'');
        if($coupon == ''){
            $arr_return['message'] = 'Promo code is empty';
        }else{
              // $coupon = strtoupper($coupon);
              $voucher = Vouchers::findFirst(['conditions' =>["name"=>trim($coupon), "product_type"=>"promo"]]);

              if(!$voucher){
                $arr_return['message'] = 'Not found promo code';
              }else{
                $arr_promo_list = self::get_list_item($coupon);
                if(isset($arr_promo_list['error'])){
                    $arr_return = array(
                        'error'=>1,
                        'message'=>$arr_promo_list['message'],
                    );
                }else{
                    $arr_return = array(
                        'error'=>0,
                        'message'=>$arr_promo_list['message'],
                        'coupon'=>$coupon,
                        'new_tax'=>$arr_promo_list['new_tax'],
                        'new_val'=>$arr_promo_list['new_total'],
                        'total'=>$arr_promo_list['total']
                    );
                }                
              }
        }
        $this->view->disable();
        return $this->response($arr_return);
    }
    public function get_list_item($coupon){
        $arr_promo_list = $this->cart->update_promo($coupon);    
        $arr = $arr_promo_list['plist'];

        $message = 'Not enough item';
        if(!empty($arr)){
            $message = '<h2 style="margin-bottom:20px;">THE FOLLOWING ITEM IS FREE DUE TO PROMO EVENTS</h2>';
        
            for($i=0;$i<count($arr);$i++){
                if($arr[$i]['quantity']<=0) continue;
                $message .='<div class="item col-md-12 col-xs-12 col-lg-12">';
                    $message .='<div class="col-xs-2 image">';
                        $message .='<img class="img-thumb" src="'.$arr[$i]['image'].'" alt="'.$arr[$i]['description'].'">';
                    $message .='</div>';
                    $message .='<div class="col-xs-4">';
                        $message .='<p><span class="uppercase name" style="color:#fff">'.$arr[$i]['name'].'</span><span class="hidden description">'.$arr[$i]['description'].'</span></p>';
                        $message .='<div class="options"></div><p><span class="note"></span></p>';
                    $message .='</div>';
                    $message .='<div class="col-xs-2 text-left price_one price sell_price">'.$arr[$i]['sell_price'].'</div><div class="col-xs-2 quantity">';
                        $message .=$arr[$i]['quantity'];
                    $message .='</div>
                    <div class="col-xs-2 text-right price">
                        '.$arr[$i]['sell_price'].'                        </div>';
                $message .='</div>';
            }
        }else if(!$arr_promo_list['check']){
            $message ='This promo only apply for 11" size BanhMi Sub';
            $arr_promo_list['error'] = 1;
        }   
        $arr_promo_list['message'] = $message;
        return $arr_promo_list;
    }
    public function checkoutAction(){
        
        $arr_pro = array();
        $items = (new \RW\Cart\Cart)->get();      
        // echo (new \RW\Cart\Cart)->updateTotalQuantity();
        
        //echo(json_encode($items));exit;
        foreach ($items['items'] as $key => $value) {
            if(isset($value['options']))
                foreach ($value['options'] as $kk => $op) {
                    if($op['option_type']!='')
                    $arr_pro[$op['_id']] = $op['option_type'];
                }
        }
        $this->view->finish_option = (new \RW\Models\JTSettings)->getFinishOption($arr_pro);
        $this->view->combo_list = $this->cart->combo_list();
        $this->view->user_list = $this->cart->user_group_list();
        $this->view->user_data = $this->cart->user_data_list();
        $this->view->taxlist = self::taxlist();
        $this->view->items = $items;
        $this->view->title = "Order Summary | Banh Mi SUB";
        $this->view->partial('frontend/Carts/checkout', array('cart' => $items, 'baseURL' => URL));
    }

    public function viewCartAction(){
        if (!$this->request->isAjax()) {
            return $this->error404();
        }
        $arr_pro =array();
        $items = (new \RW\Cart\Cart)->get();
        foreach ($items['items'] as $key => $value) {
            if(isset($value['options']))
                foreach ($value['options'] as $kk => $op) {
                    if($op['option_type']!='')
                    $arr_pro[$op['_id']] = $op['option_type'];
                }
        }
        $this->view->disable();
        $this->view->finish_option = (new \RW\Models\JTSettings)->getFinishOption($arr_pro);
        $this->view->combo_list = $this->cart->combo_list();
        $this->view->user_list = $this->cart->user_group_list();
        $this->view->user_data = $this->cart->user_data_list();
        $this->view->taxlist = $this->taxlist();
        // pr($items);die;
        $this->view->partial('frontend/blocks/view_cart', array('cart' => $items, 'baseURL' => URL));
    }
    public function smallCartAction(){
        if (!$this->request->isAjax()) {
            return $this->error404();
        }
        $this->view->disable();
        $this->view->cart = (new \RW\Cart\Cart)->get();
        $this->view->baseURL = URL;
        $this->view->partial('frontend/blocks/small_cart');
    }

    function findLocationAction(){
        $this->view->disable();
        if($this->request->isAjax()){
            $arr_return = array(
                    'status' => 'error',
                    'message' => ''
                );
            $check_find = false;
            $postal_code = $this->request->hasPost('postal_code')?$this->request->getPost('postal_code'):'';
            $address = $this->request->hasPost('address')?$this->request->getPost('address'):'';

            $link_get_postal_code = 'http://geocoder.ca/?postal='.$postal_code.'&json=1&exact=1&showaddrs=1&topmatches=1';
            $link_get_address = 'http://geocoder.ca/?locate='.$address.'&json=1&exact=1&showaddrs=1&topmatches=1';

            if($postal_code != ''){
                $result_get_postal_code = json_decode(file_get_contents($link_get_postal_code));
                if(isset($result_get_postal_code->longt) && $result_get_postal_code->latt){
                    $longt = deg2rad($result_get_postal_code->longt);
                    $latt = deg2rad($result_get_postal_code->latt);
                    $check_find = true;
                }else{
                    $arr_return['message'] = 'We can not find your postal code';
                }
            }else{
                if($address!= ''){
                    $result_get_address = json_decode(file_get_contents($link_get_address));
                    if(isset($result_get_address->longt) && $result_get_address->latt){
                        $longt = deg2rad($result_get_address->longt);
                        $latt = deg2rad($result_get_address->latt);
                        $check_find = true;
                    }else{
                        $arr_return['message'] = 'We can not find your address';
                    }
                }else{
                    $arr_return['message'] = 'Please input postal code or address information';
                }
            }
            if($check_find){
                $company = JTCompany::findFirst(
                                            array(array(
                                                    'name' => 'Retail'
                                                )
                                            )
                                        )->toArray();
                $arr_compare = array();
                foreach ($company['addresses'] as $key => $address) {
                    $address_company = rawurlencode($address['address_1'].','.$address['town_city'].','.$address['province_state']);
                    $link_get_address_company = 'http://geocoder.ca/?locate='.$address_company.'&json=1&exact=1&showaddrs=1&topmatches=1';
                    $result_get_address_company = json_decode(file_get_contents($link_get_address_company));
                    if(!isset($result_get_address_company->error)){
                        $longt_company = deg2rad($result_get_address_company->longt);
                        $latt_company = deg2rad($result_get_address_company->latt);
                        $R = 6371000;
                        $x = ($longt_company - $longt)*cos(($latt_company + $latt) / 2);
                        $y = ($latt_company - $latt);
                        $d = sqrt($x*$x + $y*$y)*$R;
                        if($d<25000){
                            $arr_compare[$key] = $d;
                        }
                    }
                }
                $min = min($arr_compare);
                foreach ($arr_compare as $key => $value) {
                    if($min==$value){
                        $arr_return['address'] = $company['addresses'][$key];
                        $arr_return['status'] = 'success';
                    }
                }
                if($arr_return['status']=='success'){
                    $this->session->set('location',$arr_return['address']);
                }
            }
            echo json_encode($arr_return);
        }else{
            return $this->response->redirect('/');
        }
    }

    public function getOptionItemAction(){
        $this->view->disable();
        $productController = new \RW\Controllers\ProductsController();
        $cart = $this->cart->get($this->request->getPost('cartKey'));
        $arr_option =  $productController->optionAction((string)$cart['_id'],1);
        
        $this->view->arr_group = $arr_option['arr_group'];
        $this->view->option = $arr_option['option'];
        $this->view->option_price_total = $arr_option['option_price_total'];
        $this->view->baseURL = URL;
        $this->view->cart_id = $arr_option['cart_id'];
        $this->view->finish_option = $arr_option['finish_option'];
        $this->view->partial('frontend/Categories/option_product');
    }


    public function changeTaxAction(){
        if (!$this->request->isAjax()) {
            return $this->error404();
        }
        $arrReturn = ['error' => 1, 'message' => 'Please refresh and try again.'];
        try {
            $taxper = $this->request->getPost('taxper');
            $this->cart->updateTax($taxper);
            $total = $this->cart->getDetailTotal();
            $arrReturn = [
                    'error' => 0,
                    'cart' => [
                        'quantity' => $this->cart->getQuantity(),
                        'total' => number_format($total['total'],2),
                        'taxper' => number_format($total['taxper'],2),
                        'tax' => number_format($total['tax'],2),
                        'main_total' => number_format($total['main_total'],2)
                    ]
                ];
            $cart_now = $this->cart->get();
           $arr_cart= json_decode(file_get_contents("datacart.json"),true);
           $arr_cart[session_id()] = $cart_now;
           file_put_contents("datacart.json", json_encode($arr_cart));
        } catch (\RW\Cart\Exception $e) {
        }
        return $this->response($arrReturn);
    }

    public function printAction(){
        $user = $this->session->has('user') ? $this->session->get('user') : false;
        $arr_pro =array();
        $items = (new \RW\Cart\Cart)->get();
        foreach ($items['items'] as $key => $value) {
            if(isset($value['options']))
                foreach ($value['options'] as $kk => $op) {
                    if($op['option_type']!='')
                    $arr_pro[$op['_id']] = $op['option_type'];
                }
        }
        $this->view->disable();
        $this->view->finish_option = (new \RW\Models\JTSettings)->getFinishOption($arr_pro);
        $this->view->taxlist = $this->taxlist();
        $this->view->combo_list = (new \RW\Cart\Cart)->combo_list();
        $this->view->cart = $items;
        $this->view->baseURL = URL;
        $this->view->user = $user['full_name'];
        $this->view->time = date('D M d, Y g:i A');
        $this->view->partial('frontend/blocks/print');
    }
    public function printCartTemplate2Action($params=array()){

        $arr_pro =array();
        $items = (new \RW\Cart\Cart)->get();

        $payment_method = '';
        foreach ($items['payment_method'] as $key => $value) {
            $payment_method .= $key." ";
        }

        foreach ($items['items'] as $key => $value) {
            if(isset($value['options']))
                foreach ($value['options'] as $kk => $op) {
                    if($op['option_type']!='')
                    $arr_pro[$op['_id']] = $op['option_type'];
                }
        }
        // $this->view->disable();
        $pos_num = 0;
        if(file_exists("cookies_id.json")){
            $arr_cookies_id = json_decode(file_get_contents("cookies_id.json"),true);
            $cookies_order_pos = trim($this->cookies->get('cookies_order_pos')->getValue());
            if(count($arr_cookies_id)>0)
            foreach ($arr_cookies_id as $key => $value) {
                if(trim((string)$value)==$cookies_order_pos){
                    $pos_num = $key;
                    break;
                }
            }
        }

        $user_list = $this->cart->user_group_list();
        $user_data = $this->cart->user_data_list();
        $combo_list = (new \RW\Cart\Cart)->combo_list(); 

        $order_number = $items['last_your_order_code'];

        $vars = ['finish_option'=>(new \RW\Models\JTSettings)->getFinishOption($arr_pro),
                    'taxlist'=>$this->taxlist(),
                    'cart'=>$items,
                    'baseURL'=>URL,
                    'code'=>isset($params['code']) ? $params['code'] : '',
                    'name'=>isset($params['name']) ? $params['name'] : '',
                    'time'=>date('D M d, Y g:i A',time()),
                    'datetime_pickup'=>  (isset($params['datetime_pickup']) ? $params['datetime_pickup'] : ''),
                    'user_list'=>$user_list,
                    'user_data'=>$user_data,
                    'combo_list'=>$combo_list,
                    'amount_tendered'=>$items['amount_tendered'],
                    'main_total'=>$items['main_total'],
                    'change_due'=>$items['amount_tendered'] - $items['main_total'],
                    'tax_no'=>$items['tax_no'],
                    '_id'=>isset($params['order_id']) ? $params['order_id'] : '',
                    'order_id'=>substr($order_number, -2),
                    'discount_total'=>isset($items['discount_total'])?$items['discount_total']:0,
                    'session_order'=>$pos_num,
                    'payment_method'=>$payment_method,
                    'full_name'=>isset($params['full_name']) ? $params['full_name'] : '',
                    'emailInfo'=>isset($params['emailInfo']) ? $params['emailInfo'] : '',
                    'phone'=>isset($params['phone']) ? $params['phone'] : '',
                    'previous_order_status'=>isset($params['previous_order_status']) ? $params['previous_order_status'] : ''                    
                ];
        if(isset($params['baker']) && $params['baker']==1)
            $vars['baker'] = 1;
        // pr($items);die;
        // echo $this->view->getPartial("frontend/blocks/print_cart_template", $vars);exit;
        return $this->view->getPartial("frontend/blocks/print_cart_template2", $vars);

    }  
    public function printCartTemplateAction($params=array()){

        $arr_pro =array();
        $items = (new \RW\Cart\Cart)->get();

        $payment_method = '';
        foreach ($items['payment_method'] as $key => $value) {
            $payment_method .= $key." ";
        }

        foreach ($items['items'] as $key => $value) {
            if(isset($value['options']))
                foreach ($value['options'] as $kk => $op) {
                    if($op['option_type']!='')
                    $arr_pro[$op['_id']] = $op['option_type'];
                }
        }
        // $this->view->disable();
        $pos_num = 0;
        if(file_exists("cookies_id.json")){
            $arr_cookies_id = json_decode(file_get_contents("cookies_id.json"),true);
            $cookies_order_pos = trim($this->cookies->get('cookies_order_pos')->getValue());
            if(count($arr_cookies_id)>0)
            foreach ($arr_cookies_id as $key => $value) {
                if(trim((string)$value)==$cookies_order_pos){
                    $pos_num = $key;
                    break;
                }
            }
        }

        $user_list = (new \RW\Cart\Cart)->user_group_list();
        $user_data = (new \RW\Cart\Cart)->user_data_list();
        $combo_list = (new \RW\Cart\Cart)->combo_list(); 

        $order_number = $items['last_your_order_code'];

        $vars = ['finish_option'=>(new \RW\Models\JTSettings)->getFinishOption($arr_pro),
                    'taxlist'=>$this->taxlist(),
                    'cart'=>$items,
                    'baseURL'=>URL,
                    'code'=>isset($params['code']) ? $params['code'] : '',
                    'name'=>isset($params['name']) ? $params['name'] : '',
                    'time'=>date('D M d, Y g:i A',time()+12*60),
                    'datetime_pickup'=>  (isset($params['datetime_pickup']) ? $params['datetime_pickup'] : ''),
                    'user_list'=>$user_list,
                    'user_data'=>$user_data,
                    'combo_list'=>$combo_list,
                    'amount_tendered'=>$items['amount_tendered'],
                    'main_total'=>$items['main_total'],
                    'change_due'=>$items['amount_tendered'] - $items['main_total'],
                    'tax_no'=>$items['tax_no'],
                    'order_id'=>substr($order_number, -2),
                    'discount_total'=>isset($items['discount_total'])?$items['discount_total']:0,
                    'session_order'=>$pos_num,
                    'payment_method'=>$payment_method,
                    'full_name'=>isset($params['full_name']) ? $params['full_name'] : '',
                    'emailInfo'=>isset($params['emailInfo']) ? $params['emailInfo'] : '',
                    'phone'=>isset($params['phone']) ? $params['phone'] : '',
                    'previous_order_status'=>isset($params['previous_order_status']) ? $params['previous_order_status'] : ''                    
                ];
        if(isset($params['baker']) && $params['baker']==1)
            $vars['baker'] = 1;
        // pr($items);die;
        // echo $this->view->getPartial("frontend/blocks/print_cart_template", $vars);exit;
        return $this->view->getPartial("frontend/blocks/print_cart_template", $vars);

    }      

    public function paymentCalculationAction(){

        $arr_pro =array();
        $items = (new \RW\Cart\Cart)->get();
        foreach ($items['items'] as $key => $value) {
            if(isset($value['options']))
                foreach ($value['options'] as $kk => $op) {
                    if($op['option_type']!='')
                    $arr_pro[$op['_id']] = $op['option_type'];
                }
        }
        $this->view->disable();
        $this->view->finish_option = (new \RW\Models\JTSettings)->getFinishOption($arr_pro);
        $this->view->taxlist = $this->taxlist();
        $this->view->cart = $items;
        $this->view->baseURL = URL;

        $this->view->time = date('D M d, Y g:i A');
        $this->view->partial('frontend/blocks/payment_calculation');
    }    

    public function clearCartAction()
    {
        if (!$this->request->isAjax()) {
            return $this->error404();
        }
        $this->cart->destroy();
        $arr_cart= json_decode(file_get_contents("datacart.json"),true);
        unset($arr_cart[session_id()]);
        file_put_contents("datacart.json", json_encode($arr_cart));
        return $this->response(['error' => 0]);
    }
    public function idledisplayAction(){
        $arr_return = ['error'=>1,'image'=>''];
        if ($this->request->isAjax()) {
            $run = $this->request->hasPost('data_total')?$this->request->getPost('data_total'):0;
            settype($run,'int');            
            if($run<=0){
                $image = '';
                $arr_extra = (new \RW\Models\Banners)->getListBannersByType(4) ;
                $items = (new \RW\Cart\Cart)->get();
                if($items['total']==0){
                   if(!empty($arr_extra)){
                        $arr_return = ['error'=>0,'image'=>URL.'/'.rand_key ($arr_extra)];
                    }   
                }else{
                    $arr_return = ['error'=>1];
                }
                 
            }
        }        
        return $this->response($arr_return);
    }
    public function customerDisplayAction(){
        $arr_pro =array();
        $items = (new \RW\Cart\Cart)->get();
        // pr($items);die;
        foreach ($items['items'] as $key => $value) {
            if(isset($value['options']))
                foreach ($value['options'] as $kk => $op) {
                    if($op['option_type']!='')
                    $arr_pro[$op['_id']] = $op['option_type'];
                }
        }
        $this->view->disable();
        $this->view->finish_option = (new \RW\Models\JTSettings)->getFinishOption($arr_pro);
        $this->view->taxlist = $this->taxlist();
        $this->view->cart = $items;
        if($items['total']==0){
            $this->view->check = 0;
        }else{
             $this->view->check = 1;
        }
        
        $this->view->combo_list = (new \RW\Cart\Cart)->combo_list();
        $this->view->baseURL = URL;
        if(!$this->session->has('bannerList')){            
            $this->session->set('bannerList',rand_key ((new \RW\Models\Banners)->getListBannersByType(1) ));
        }
        if ($this->request->isAjax()) {
            $run = $this->request->hasPost('data_run')?$this->request->getPost('data_run'):0;
            if($run==10){
                $arr_extra = (new \RW\Models\Banners)->getListBannersByType(4) ;
                if(!empty($arr_extra)){
                    $this->session->set('bannerList',rand_key ($arr_extra));
                }                
            }
        }
        $this->view->bannerList = $this->session->get('bannerList');        
        $this->view->partial('frontend/blocks/customer_display');
    }
    public function customerDisplayViewAction(){
        $arr_pro =array();
        $order_session = $this->request->hasPost('id')?$this->request->getPost('id'):0;
        $arr_session= json_decode(file_get_contents("session_id.json"),true);
        $arr_cart= json_decode(file_get_contents("datacart.json"),true);
        $items = ['items'=>[]];
        if($order_session){
            $session_id = isset($arr_session[$order_session])?$arr_session[$order_session]:1;
            $items = isset($arr_cart[$session_id])?$arr_cart[$session_id]:array();
        }
        if(!isset($items['items']))
            $items['items'] =array();
        foreach ($items['items'] as $key => $value) {
            if(isset($value['options']))
                foreach ($value['options'] as $kk => $op) {
                    if($op['option_type']!='')
                    $arr_pro[$op['_id']] = $op['option_type'];
                }
        }
        $this->view->disable();
        $this->view->finish_option = (new \RW\Models\JTSettings)->getFinishOption($arr_pro);
        $this->view->taxlist = $this->taxlist();
        $this->view->cart = $items;
        
        $this->view->combo_list = (new \RW\Cart\Cart)->combo_list();
        $this->view->baseURL = URL;
        if(!$this->session->has('bannerList')){            
            $this->session->set('bannerList',rand_key ((new \RW\Models\Banners)->getListBannersByType(1) ));
        }
        if ($this->request->isAjax()) {
            $run = $this->request->hasPost('data_run')?$this->request->getPost('data_run'):0;
            if($run==10){
                $arr_extra = (new \RW\Models\Banners)->getListBannersByType(4) ;
                if(!empty($arr_extra)){
                    $this->session->set('bannerList',rand_key ($arr_extra));
                }                
            }
        }        
        $this->view->bannerList = $this->session->get('bannerList');        
        $this->view->partial('frontend/blocks/customer_display');
    }
    public function beginComboAction(){
        if (!$this->request->isAjax()) {
            return $this->error404();
        }
        $product_id = $this->request->getPost('product_id');
        $combo_sales = $this->request->getPost('combo_sales');
        $items = (new \RW\Cart\Cart)->setcombo(1,$product_id,$combo_sales);
        $this->view->disable();
        die;
    }
    public function stopComboAction(){
        $items = (new \RW\Cart\Cart)->setcombo(0);
        $cart_now = $this->cart->get();
           $arr_cart= json_decode(file_get_contents("datacart.json"),true);
           $arr_cart[session_id()] = $cart_now;
           file_put_contents("datacart.json", json_encode($arr_cart));
        $this->view->disable();
        die;
    }
    public function cancelComboAction(){
        $items = (new \RW\Cart\Cart)->cancelcombo();
        $cart_now = $this->cart->get();
           $arr_cart= json_decode(file_get_contents("datacart.json"),true);
           $arr_cart[session_id()] = $cart_now;
           file_put_contents("datacart.json", json_encode($arr_cart));
        $this->view->disable();
        die;
    }
    public function removeComboAction(){
        if (!$this->request->isAjax()) {
            return $this->error404();
        }
        $arrReturn = ['error' => 1, 'message' => 'Please refresh and try again.'];
        try {
            $this->cart->cancelcombo($this->request->getPost('combo_id'));
            $total = $this->cart->getDetailTotal();
            $arrReturn = [
                            'error' => 0,
                            'cart' => [
                                'quantity' => $this->cart->getQuantity(),
                                'total' => number_format($total['total'],2),
                                'taxper' => number_format($total['taxper'],2),
                                'tax' => number_format($total['tax'],2),
                                'main_total' => number_format($total['main_total'],2)
                            ],
                        ];
            $cart_now = $this->cart->get();
           $arr_cart= json_decode(file_get_contents("datacart.json"),true);
           $arr_cart[session_id()] = $cart_now;
           file_put_contents("datacart.json", json_encode($arr_cart));
        } catch (\RW\Cart\Exception $e) {
        }
        return $this->response($arrReturn);
    }
    public function changeComboAction(){
        if (!$this->request->isAjax()){
            return $this->error404();
        }
         $arrReturn = ['error' => 1, 'message' => 'Please refresh and try again.'];
        try {
            $cartKey = $this->request->getPost('cartKey');
            $this->cart->returnstep($cartKey);
            $arrReturn = ['error' => 0];
            $cart_now = $this->cart->get();
           $arr_cart= json_decode(file_get_contents("datacart.json"),true);
           $arr_cart[session_id()] = $cart_now;
           file_put_contents("datacart.json", json_encode($arr_cart));
        } catch (\RW\Cart\Exception $e) {
        }
        return $this->response($arrReturn);
    }
    public function addqtyComboAction(){
        if (!$this->request->isAjax()){
            return $this->error404();
        }
         $arrReturn = ['error' => 1, 'message' => 'Please refresh and try again.'];
        try {
            $cartKey = $this->request->getPost('cartKey');
            $comboqty = $this->request->getPost('comboqty');
            $this->cart->changeqtyCombo($cartKey,$comboqty);
            $cart_now = $this->cart->get();
           $arr_cart= json_decode(file_get_contents("datacart.json"),true);
           $arr_cart[session_id()] = $cart_now;
           file_put_contents("datacart.json", json_encode($arr_cart));
            $arrReturn = ['error' => 0];
        } catch (\RW\Cart\Exception $e){
        }
        return $this->response($arrReturn);
    }
    
    public function beginGroupAction(){
        if (!$this->request->isAjax()) {
            return $this->error404();
        }
        $product_id = $this->request->getPost('product_id');
        $username = $this->request->getPost('username');
        $items = $this->cart->setUseGroup(1,$product_id,$username);
        $this->view->disable();
        $arrReturn = array('next_uid'=>$this->cart->next_uid());
        return $this->response($arrReturn);
        die;
    }
    public function nextGroupAction(){
        $items = $this->cart->setUseGroup(0);
        $this->view->disable();
        $arrReturn = array('next_uid'=>$this->cart->next_uid());
        return $this->response($arrReturn);
        die;
    }
    public function endGroupAction(){
        $items = $this->cart->setUseGroup(0);
        $this->view->disable();
        die;
    }

    public function removeUserGroupAction(){
        if (!$this->request->isAjax()) {
            return $this->error404();
        }
        $arrReturn = ['error' => 1, 'message' => 'Please refresh and try again.'];
        try {
            $this->cart->cancelUserGroup($this->request->getPost('user_id'));

            $total = $this->cart->getDetailTotal();
            $arrReturn = [
                            'error' => 0,
                            'cart' => [
                                'quantity' => $this->cart->getQuantity(),
                                'total' => number_format($total['total'],2),
                                'taxper' => number_format($total['taxper'],2),
                                'tax' => number_format($total['tax'],2),
                                'main_total' => number_format($total['main_total'],2)
                            ],
                        ];
           
        } catch (\RW\Cart\Exception $e) {
        }
        return $this->response($arrReturn);
    }

    public function changeUserGroupAction(){
        if (!$this->request->isAjax()){
            return $this->error404();
        }
        $arrReturn = ['error' => 1, 'message' => 'Please refresh and try again.'];
        try {
            $cartKey = $this->request->getPost('cartKey');
            $user_id = $this->request->getPost('user_id');
            
            $data = $this->cart->next_uid($user_id);

            $this->cart->returnUserGroup($cartKey, $data);
            $this->view->disable();
            
            if(is_array($data))
                $arrReturn = array('error' => 0, 'next_uid'=>$data['next_uid'], 'user_name'=>$data['user_name']);

        } catch (\RW\Cart\Exception $e) {
        }
        return $this->response($arrReturn);
    }   
    public function TestCouponAction($coupon){
        $message = ''; $voucher_value = 0;
        $arr_return = array('error'=>1,'message'=>'');
        if($coupon == ''){
            $arr_return['message'] = 'Coupon is empty';
        }else{
              // $coupon = strtoupper($coupon);
              $voucher = Vouchers::findFirst(['conditions' =>["name"=>trim($coupon)]]);

              if(!$voucher){
                $arr_return['message'] = 'Not found voucher for this coupon';
              }else{
                // kiem tra loai voucher                
                // pr($voucher->limited);
                if($voucher->limited==2){ //loai chi dung 1 lan
                    // kiem tra tiep salesorder
                    $check = JTOrder::findFirst(['conditions' =>["voucher"=>trim($coupon)]]);
                    if(!empty($check)){
                        $arr_return['message'] = 'This voucher used';                        
                    }
                }
                // end
              }
        }
        $this->view->disable();
        // pr($arr_return);
    }
    public function applyCouponAction(){
        $message = ''; $voucher_value = 0;
        $coupon = $this->request->hasPost('coupon')?$this->request->getPost('coupon'):'';
        $arr_return = array('error'=>1,'message'=>'');
        if($coupon == ''){
            $arr_return['message'] = 'Coupon is empty';
        }else{
              // $coupon = strtoupper($coupon);
              $voucher = Vouchers::findFirst(['conditions' =>["name"=>trim($coupon)]]);

              if(!$voucher){
                $arr_return['message'] = 'Not found voucher for this coupon';
              }else{
                // kiem tra loai voucher                
                if($voucher->limited==2){ //loai chi dung 1 lan
                    // kiem tra tiep salesorder
                    $check = JTOrder::findFirst(['conditions' =>["voucher"=>trim($coupon)]]);
                    if(!empty($check)){
                        $arr_return['message'] = 'Voucher expired';
                        return $this->response($arr_return);
                    }
                }
                // end

                $voucher = $voucher->toArray();

                //voucher ap dung cho 1 category
                if(isset($voucher['category']) && $voucher['category']!=""){
                    $items = (new \RW\Cart\Cart)->get();
                    // pr($items);exit;
                    foreach ($items['items'] as $product) {
                        if($product['category'] != $voucher['category']){
                            $arr_return['message'] = 'This code is only used for category '.$voucher['category'].', Thank you.';
                            return $this->response($arr_return);
                        }
                    }
                }
                

                if($voucher['name']=='bms10' && $this->cart->getTotal() < 50){
                    $arr_return['message'] = 'bms10 code is only for any minimum order of $50cad. Please shop more and get the discount. Thank you!';
                } else {
                    $arr_voucher = $this->cart->update_vouchers($voucher);
                    $message = 'Discount ('.$voucher['value'].$voucher['type'].')';
                    $arr_return = array(
                            'error'=>0,
                            'message'=>$message,
                            'voucher_value'=>number_format((double)$arr_voucher['value'],2),
                            'new_main_total'=>$arr_voucher['voucher_main_total'],
                            'tax'=>$arr_voucher['tax'],
                            'coupon'=>$coupon
                    );
                }
              }
        }
        $this->view->disable();
        return $this->response($arr_return);
    }
    public function removeCouponAction(){
        $arr_return = array('error'=>1,'message'=>'');
        $data = $this->cart->remove_vouchers();
        $arr_return['new_main_total'] = $data['new_main_total'];
        $arr_return['tax'] = $data['tax'];
        $this->view->disable();
        return $this->response($arr_return);
    }

    public function getAddressAction(){
        if (!$this->request->isAjax()){
            return $this->error404();
        }
        $this->view->disable();
        // Get token from page search postcode
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL,"https://www.canadapost.ca/cpo/mc/personal/postalcode/fpc.jsf");
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec ($ch);
        curl_close ($ch);
        preg_match_all("/uk \= \'[a-zA-Z0-9\-]*\'/", $result, $match);
        $token = str_replace("uk = ","",$match[0][0]);
        $token = str_replace("'","",$token);
        
        //Get data by postcode
        $postcode = $this->request->hasPost('postcode')?$this->request->getPost('postcode'):'';

        if(strlen($postcode)>3){
                $url = "https://ws1.postescanada-canadapost.ca/AddressComplete/Interactive/Find/v2.10/json3ex.ws?Key=".$token."&Country=CAN&SearchTerm=".$postcode."&LanguagePreference=en&LastId=&SearchFor=Everything&OrderBy=UserLocation&$block=true&$cache=true&MaxSuggestions=7&MaxResults=20";
                $result = file_get_contents($url);
                return $this->response(['error'=>0,'addresses'=>json_decode($result, true)]);
        }else{
            return $this->response(['error'=>1,'message'=>$postcode.' is wrong Postal code']);
        }
    }

    public function updateShippingFeeAction(){
        $arr_return = array('message'=>'','error'=>1);
        $price = $this->request->hasPost('price')?$this->request->getPost('price'):0;
        $this->cart->updateDeliveryCost($price);
        $cost = $this->cart->getDeliveryCost();
        if($cost>0){
            $arr_return['error'] = 0;
            $arr_return['message'] = 'Shipping fee is 10$.';
        }   
        return $this->response($arr_return);
    }

    public function viewOrderAction($order_id){
        if($this->session->has('user')){
            $arr_user = $this->session->get('user');
            $order = JTOrder::findFirst(array(
                'conditions'=>array('_id'=> new \MongoId($order_id),'contact_id'=> new \MongoId($arr_user['_id']))
            ));
            $html='';
            if($order){
                $arr_order = $order->toArray();
                // pr($arr_order);die;
                $html .='<div class="text-left col-md-6">Date of Order: '.date('m-d-Y',($arr_order['salesorder_date']->sec)).'</div>';
                $html .='<div class="text-right col-md-6">Delivery method: '.(isset($arr_order['delivery_method']) ? ucfirst($arr_order['delivery_method']) : '' ).'</div>';
                $html .='<div class="text-left col-md-6">Order code: '.$arr_order['code'].'</div>';
                $html .='<div class="text-right col-md-6">Paid By: '.$arr_order['paid_by'].'</div>';
                $html .='<div class="text-left col-md-6">Full name: '.(isset($arr_order['full_name'])?$arr_order['full_name']:$arr_order['contact_name']).'</div>';
                $html .='<div class="text-right col-md-6">Phone: '.(isset($arr_order['phones']) ? $arr_order['phones'] : $arr_order['phone'] ).'</div>';
                $html .='<div class="text-left col-md-6">Email: '.(isset($arr_order['emails']) ? $arr_order['emails'] : $arr_order['email']).'</div>';
                $html .='<div class="text-right col-md-6">Status: '.$arr_order['status'].'</div>';
                $html .='<p class="text-left col-md-12" style="border-bottom:1px dotted">&nbsp;</p>';

                $arr_products = $arr_order['products'];
                $arr_carts = isset($arr_order['cart']) ? $arr_order['cart'] : array();
                $arr_image_and_note = array();
                if(isset($arr_carts['items'])){
                    $arr_carts_item = $arr_carts['items'];
                    foreach($arr_carts_item as $item){
                        $arr_carts_item[(string)$item['_id']] = array(                        
                            'image'=>$item['image'],
                            'des'=>$item['description'],
                        );
                    }
                }

                for($i=0;$i<count($arr_products);$i++){
                    $v_p_id = (string) $arr_products[$i]['products_id'];
                    if($arr_products[$i]['sell_price']<=0) continue;
                    $html .= '<div class="col-md-12">';
                        $html .= '<div class="col-md-5 col-xs-12">';
                            $html .= '<div class="col-xs-6">';
                                $html .= '<img alt="'.$arr_carts_item[$v_p_id]['des'].'" style="max-width:150px;" src="'.$arr_carts_item[$v_p_id]['image'].'" />';
                            $html .= '</div>';
                            $html .= '<div class="col-xs-6">';
                                $html .= $arr_products[$i]['products_name'];
                            $html .= '</div>';
                        $html .= '</div>';
                        $html .= '<div class="col-md-2 col-xs-3 text-right">';
                            $html .= display_format_currency($arr_products[$i]['sell_price']);
                        $html .= '</div>';
                        $html .= '<div class="col-md-1 col-xs-3 text-right">';
                            $html .= $arr_products[$i]['quantity'];
                        $html .= '</div>';
                        $html .= '<div class="col-md-4 col-xs-6 text-right">';
                            $html .= display_format_currency((float)$arr_products[$i]['sell_price'] * (int)$arr_products[$i]['quantity']);
                        $html .= '</div>';
                        // $html .= '<div class="col-md-2 text-right">';
                        //     $html .= '<input type="button" class="btn btn-primary" onclick=reorderThisItem("'.$_id.'","'.$v_p_id.'") value="Reorder" />';
                        // $html .= '</div>';
                    $html .= '</div>';
                }
                $html .='<p class="text-left col-md-12" style="border-bottom:1px dotted">&nbsp;</p>';
                $html .= '<div class="col-md-12" style="height:45px;">';
                    $html .= '<div class="col-md-4 text-left">';
                        $html .= 'Sub Total:';
                    $html .= '</div>';
                    $html .= '<div class="col-md-8 text-right">';
                        $html .= display_format_currency($arr_order['sum_sub_total']);
                    $html .= '</div>';
                $html .= '</div>';

                $html .= '<div class="col-md-12" style="height:45px;">';
                    $html .= '<div class="col-md-4 text-left">';
                        $html .= 'Tax:';
                    $html .= '</div>';
                    $html .= '<div class="col-md-8 text-right">';
                        $html .= display_format_currency($arr_order['sum_tax']);
                    $html .= '</div>';
                $html .= '</div>';

                $html .= '<div class="col-md-12" style="height:45px;">';
                    $html .= '<div class="col-md-4 text-left">';
                        $html .= 'Delivery fee:';
                    $html .= '</div>';
                    $html .= '<div class="col-md-8 text-right">';
                        $html .= display_format_currency(isset($arr_order['delivery_cost'])?$arr_order['delivery_cost']:0);
                    $html .= '</div>';
                $html .= '</div>';

                $html .= '<div class="col-md-12" style="height:45px;">';
                    $html .= '<div class="col-md-4 text-left">';
                        $html .= 'Amount:';
                    $html .= '</div>';
                    $html .= '<div class="col-md-8 text-right">';
                        $html .= display_format_currency($arr_order['sum_amount']);
                    $html .= '</div>';
                $html .= '</div>';
                
            }else{
                $html .= '<h2>Order not found</h2>';
            }
        $this->view->html_order = $html;
        }else{
            return $this->response->redirect('/users/sign-in');
        }
    }

}
