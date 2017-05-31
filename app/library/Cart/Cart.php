<?php
namespace RW\Cart;

use Phalcon\Mvc\User\Component;

class Cart extends Component
{

    private $cart;

    public function __construct()
    {
        $this->cart = $this->session->has('cart') ? $this->session->get('cart') : [];
        if (empty($this->cart)) {
            $this->cart = $this->getDefaul();
        }
    }

    private function getDefaul()
    {
        $company_default = \RW\Models\JTCompany::getCompanyDefault();
        return [
                'items' => [],
                'total' => 0,
                'quantity' => 0,
                'taxper' => $company_default['tax'],
                'taxkey' => $company_default['taxkey'],
                'tax_no' => $company_default['tax_no'],
                'delivery_cost' => 0,
                'tax' => 0,
                'main_total' => 0,
                // 'quantity_promo'=>0,
                'note'  => '',
                'order_id'  => '',
                'order_type'  => 0,
                'use_combo' => 0,
                'update_combo_id' => '',
                'combo_step' => 0,
                'combo_id' => '',
                'combo_sales' => 100,
                'combo_list'=>[],
                'use_group'=>0,
                'group_order'=>[],
                'payment_method' =>array(),
                'amount_tendered'=>0,
                'last_your_order_code' =>'',
                'voucher_code' =>'',
                'promo_code' =>'',
                'voucher_value' =>0,
                'voucher_type' =>'$',
                'discount_total' =>0,
                'finalize'=>'',
                'datetime_pickup'=> new \MongoDate(),            
                'datetime_delivery'=> new \MongoDate()            
            ];
    }

    public function buildItems($items)
    {
        if (!is_array($items)) {
            return false;
        }
        $this->cart['items'] = $items;
        return $this->rebuild();
    }

    public function add($data)
    {
        $data = array_merge(['_id' => '', 'options' => []], $data);
        $user_id = '';
        if( $this->cart['use_group'] == 1){
            $user_id = 'g'.$this->cart['group_order']['step']['user_id'];
        }
        $cartKey = md5((string)$data['_id'].serialize($data['options']).(string)$this->cart['combo_id'].$user_id);
        if (isset($this->cart['items'][$cartKey])) {
            $quantity = $this->cart['items'][$cartKey]['quantity'] + $data['quantity'];
            $priceData = \RW\Models\JTProduct::getPrice([
                                            '_id'   => $data['_id'],
                                            'sizew' => 0,
                                            'sizeh' => 0,
                                            'quantity'  => $quantity,
                                            'companyId' => '',
                                            'options'   => $data['options'],
                                            'fields'    => ['name', 'description']
                                        ]);
            $this->update($cartKey, [
                            '_id'       => new \MongoId($data['_id']),
                            'name'      => $priceData['name'],
                            'description'=> $priceData['description'],
                            'note'      => $data['note'],
                            'image'     => $data['image'],
                            'quantity'  => $quantity,
                            'options'   => $data['options'],
                            'total' => $priceData['sub_total']
                        ]);
        } else {
            if($this->cart['use_combo'] == 1){
                $data['combo_id'] = $this->cart['combo_id'];
                $data['combo_step'] = $this->cart['combo_step'];
                $this->cart['combo_step'] = (int)$this->cart['combo_step']+1;
            }
            if($this->cart['use_group'] == 1){
                $data['user_id'] = $this->cart['group_order']['step']['user_id'];
            }
            $data['discount'] = isset($this->cart['combo_sales'])?($this->cart['combo_sales']/100):0;

            if(isset($data['user_id']))
            {

            }

            $this->cart['items'][$cartKey] = $data;
            if($this->cart['update_combo_id']!='' && isset($this->cart['items'][$this->cart['update_combo_id']])){
                unset($this->cart['items'][$this->cart['update_combo_id']]);
                $this->cart['use_combo'] = 0;
                $this->cart['update_combo_id'] = '';
                $this->cart['combo_id'] = '';
                $this->cart['combo_sales'] = 100;
                $this->cart['combo_step'] = 0;
            }

            //re-sort the cart items, by user_id
            uasort($this->cart['items'], function($a, $b) {
                if(!isset($a['user_id'])) $a['user_id'] = -1;                
                if(!isset($b['user_id'])) $b['user_id'] = -1;
                return intval($a['user_id']) - intval($b['user_id']);
            });
            
            //re-sort the cart items, by combo_id
            uasort($this->cart['items'], function($a, $b) {
                if(!isset($a['combo_id'])) $a['combo_id'] = -1;                
                if(!isset($b['combo_id'])) $b['combo_id'] = -1;
                return intval($a['combo_id']) - intval($b['combo_id']);
            });
        }
        
        $this->rebuild();
        return $cartKey;
    }

    public function update($cartKey, $data)
    {
        if (!isset($this->cart['items'][$cartKey])) {
            throw new Exception("The item \"{$cartKey}\" did not exist.");
        }
        $this->cart['items'][$cartKey] = array_merge($this->cart['items'][$cartKey], $data);
        $this->rebuild();
        return $this->get();
    }

    public function updateTotalQuantity()
    {
        $this->cart['total'] = $this->cart['quantity'] = 0;
        $tmp = array();

        //Get total of every user group
        /*$group_total = array();
        foreach ($this->cart['items'] as $item) {                        
            if(isset($item['user_id']))
            {
                if(!isset($group_total[$item['user_id']])) $group_total[$item['user_id']] = 0;
                $group_total[$item['user_id']] += $item['total'];
            }
        }*/
        $cart_total = 0;
        foreach ($this->cart['items'] as $item) {                        
            $cart_total += $item['total'];
        }


        foreach ($this->cart['items'] as $item) {
            //tinh total cua combo_list
            if(isset($item['combo_id'])){
                if(!isset($tmp[$item['combo_id']])){
                    $tmp[$item['combo_id']]['total'] = $tmp[$item['combo_id']]['item_qty'] = 0;
                    $tmp[$item['combo_id']]['quantity'] = 0;
                }
                $tmp[$item['combo_id']]['total'] += $item['total'] - $item['discount']*$item['total'];
                $tmp[$item['combo_id']]['item_qty'] += (int)$item['quantity'];
            //tinh toan total group
            }else if(isset($item['user_id'])){
                if(!isset($gtmp[$item['user_id']])){
                    $gtmp[$item['user_id']]['total'] = $gtmp[$item['user_id']]['item_qty'] = 0;
                    $gtmp[$item['user_id']]['quantity'] = 0;
                }
                if(!isset($item['quantity_promo'])){
                    $item['quantity_promo'] = 0;
                }
                $group_discount = 0;
                // if($group_total[$item['user_id']] >= ORDER_GROUP_DISCOUNT_CONDITION) $group_discount = ORDER_GROUP_DISCOUNT;
                if($cart_total >= ORDER_GROUP_DISCOUNT_CONDITION) $group_discount = ORDER_GROUP_DISCOUNT;
                // total tru di tien item promo
                $gtmp[$item['user_id']]['total'] += $item['total'] - $group_discount*$item['total'] - (float)$item['sell_price']*(int)$item['quantity_promo'];
                // quantity tru di quantity promo
                $gtmp[$item['user_id']]['quantity'] += (int)$item['quantity'] - $item['quantity_promo'];
            }else{
                $this->cart['total'] += $item['total']  - (float)$item['sell_price']*(int)$item['quantity_promo'];
                $this->cart['quantity'] += $item['quantity'] - $item['quantity_promo'];
            }
        }
        foreach ($this->cart['combo_list'] as $key => $value) {
            if(isset($tmp[$key])){
                $this->cart['combo_list'][$key]['item_qty'] = $tmp[$key]['item_qty'];
                $this->cart['combo_list'][$key]['total'] = round((float)($tmp[$key]['total']*$this->cart['combo_list'][$key]['quantity']),2);
                $this->cart['quantity'] += $this->cart['combo_list'][$key]['item_qty'];
                $this->cart['total'] += $this->cart['combo_list'][$key]['total'];
            }
        }
        if(isset($this->cart['group_order']['list']))
        {
            foreach ($this->cart['group_order']['list'] as $key => $value) {
                if(isset($gtmp[$key])){
                    $this->cart['group_order']['list'][$key]['quantity'] = $gtmp[$key]['quantity'];
                    $this->cart['group_order']['list'][$key]['total'] = round((float)($gtmp[$key]['total']), 2);
                    $group_discount = 0;
                    // if($group_total[$key] >= ORDER_GROUP_DISCOUNT_CONDITION) $group_discount = ORDER_GROUP_DISCOUNT;                    
                    if($cart_total >= ORDER_GROUP_DISCOUNT_CONDITION) $group_discount = ORDER_GROUP_DISCOUNT;                    
                    $this->cart['group_order']['list'][$key]['discount'] = $group_discount;
                    $this->cart['quantity'] += $gtmp[$key]['quantity'];
                    $this->cart['total'] += $gtmp[$key]['total'];
                }
            }
        }
        $this->cart['total'] =  round((float)$this->cart['total'],2);
        if(!isset($this->cart['taxper'])){
            $this->cart['taxper'] = 5;
        }
        if(isset($this->cart['voucher_type']) && $this->cart['voucher_type']=='%'){
            $this->cart['discount_total'] = round((float)$this->cart['total']*$this->cart['voucher_value']/100,2);
        }else if(isset($this->cart['voucher_type']) && $this->cart['voucher_type']!=''){
            $this->cart['discount_total'] = round((float)$this->cart['voucher_value'],2);
        }
        else{
            $this->cart['discount_total'] = 0.00;   
        }

        $this->cart['tax'] = round((float)(($this->cart['total'] - $this->cart['discount_total'])*$this->cart['taxper']/100),2);
        $this->cart['main_total'] = $this->cart['total'] - $this->cart['discount_total'] + $this->cart['tax']  +$this->cart['delivery_cost'];
        // $arr_temp = $this->get();
        // rebuild($arr_temp);
    }

    private function rebuild($cart = [])
    {
        if (!empty($cart)) {
            $this->cart = $cart;
        }
        $this->updateTotalQuantity();
        return $this->session->set('cart', $this->cart);
    }

    public function get($cartKey = '')
    {
        if (!empty($cartKey)) {
            if (!isset($this->cart['items'][$cartKey])) {
                throw new Exception("The item \"{$cartKey}\" did not exist.");
            }
            return $this->cart['items'][$cartKey];
        }
        return $this->cart;
    }

    public function combo_list()
    {
        return $this->cart['combo_list'];
    }

    public function getItems()
    {
        return $this->cart['items'];
    }

    public function getTotal()
    {
        return $this->cart['total'];
    }

    public function getQuantity()
    {
        return $this->cart['quantity'];
    }

    public function getDetailTotal()
    {
        return array(   'total' => $this->cart['total'],
                        'taxper' => $this->cart['taxper'],
                        'tax' => $this->cart['tax'],
                        'main_total' => $this->cart['main_total']
                    );
    }

    public function updateNote($note = '')
    {
        $this->cart['note'] = $note;
        return $this->rebuild();
    }

    public function updateDeliveryTime($datetime = '')
    {
        $this->cart['datetime_delivery'] = new \MongoDate(strtotime($datetime));
        $this->cart['datetime_pickup'] = new \MongoDate(strtotime($datetime));
        return $this->rebuild();
    }
    public function updateDeliveryCost($price = 0)
    {
        $this->cart['delivery_cost'] = $price;
        return $this->rebuild();
    }
    public function getDeliveryCost()
    {
        return $this->cart['delivery_cost'];
    }

    public function updateTax($taxper=0){
        $this->cart['taxper'] = $taxper;
        return $this->rebuild();
    }
    public function setCart($data){
        $this->rebuild($data);
    }
    public function setOrderType($type=0){
        $this->cart['order_type'] = $type;
        return $this->rebuild();
    }
    public function remove($cartKey)
    {
        if (isset($this->cart['items'][$cartKey])) {
            unset($this->cart['items'][$cartKey]);
            return $this->rebuild();
        }
        throw new Exception("The item \"{$cartKey}\" did not exist.");
    }

    public function destroy()
    {
        return $this->rebuild($this->getDefaul());
    }

    public function setcombo($value,$product_id='',$combo_sales=1)
    {
        
        if($value==1){
            $this->cart['combo_id'] = count($this->cart['combo_list']);
            $this->cart['combo_sales'] = $combo_sales;
            $this->cart['combo_step'] = 1;
            $tmp = array();
            $priceData = \RW\Models\JTProduct::getPrice([
                                            '_id'   => $product_id,
                                            'sizew' => 0,
                                            'sizeh' => 0,
                                            'quantity'  => 1,
                                            'companyId' => '',
                                            'options'   => array(),
                                            'fields'    => ['name', 'description']
                                        ]);
            $tmp['product_id']  = $product_id;
            $tmp['name']        = $priceData['name'];
            $tmp['description'] = $priceData['description'];
            $tmp['image'] = '';
            $tmp['quantity'] = 1;
            $tmp['item_qty'] = 0;
            $tmp['combo_sales'] = $combo_sales;
            $tmp['total'] = 0;          
            $this->cart['combo_list'][] = $tmp;
        }
        $this->cart['use_combo'] = $value;
        return $this->rebuild();
        
    }
    public function cancelcombo($combo_id='')
    {
        //xoa cac item product
        if($combo_id=='')
            $combo_id = $this->cart['combo_id'];
        foreach ($this->cart['items'] as $key => $value){
            if(isset($value['combo_id']) && $value['combo_id']==$this->cart['combo_id']){
                unset($this->cart['items'][$key]);
            }
        }
        if(isset($this->cart['combo_list'][$combo_id])){
            unset($this->cart['combo_list'][$combo_id]);
        }
        $this->cart['use_combo'] = 0;
        $this->cart['combo_id'] = '';
        $this->cart['combo_sales'] = 100;
        $this->cart['combo_step'] = 0;
        return $this->rebuild();
    }
    public function changeqtyCombo($combo_id='',$newqty=''){
        if($combo_id!='' && $newqty!='' && isset($this->cart['combo_list'][$combo_id])){
            $this->cart['combo_list'][$combo_id]['quantity']=$newqty;
        }
        return $this->rebuild();
    }
    public function checkcombo()
    {
        return $this->cart['use_combo'];
    }
    public function combostep()
    {
        return $this->cart['combo_step'];
    }
    public function getUpdateComboId()
    {
        return $this->cart['update_combo_id'];
    }
    public function returnstep($cartKey='')
    {
        if (isset($this->cart['items'][$cartKey])){
            $this->cart['use_combo'] = 1;
            $this->cart['update_combo_id']= $cartKey;
            $this->cart['combo_id'] = $this->cart['items'][$cartKey]['combo_id'];
            $this->cart['combo_step']= $this->cart['items'][$cartKey]['combo_step'];
            $this->cart['combo_sales'] = 100*$this->cart['items'][$cartKey]['discount'];
        }
        return $this->rebuild();
    }
    public function cancelUserGroup($user_id='')
    {
        //xoa cac item product
        // if($user_id=='')
        //     $user_id = $this->cart['user_id'];
        foreach ($this->cart['items'] as $key => $value){
            if(isset($value['user_id']) && $value['user_id']==$user_id){
                unset($this->cart['items'][$key]);
            }
        }

        foreach ($this->cart['group_order']['list'] as $key => $value){
            if(isset($value['user_id']) && $value['user_id']==$user_id){
                unset($this->cart['group_order']['list'][$key]);
            }
        }

        return $this->rebuild();
    }

    public function returnUserGroup($cartKey='', $data)
    {
        if (isset($this->cart['items'][$cartKey])){
            $this->cart['use_group'] = 1;

            $group = $this->cart['group_order'];
            $group['step']['group_product_id'] = $data['group_product_id'];
            $group['step']['user_name'] = $data['user_name'];
            $group['step']['user_id'] = $data['next_uid'];
            $group['step']['quantity'] = 0;
            $group['step']['total'] = 0;
            
            $this->cart['group_order'] = $group;

        }
        return $this->rebuild();
    }
    function getSepUserGroup()
    {
        $arr_return = array();
        if($this->cart['use_group'] == 1)   
        {
            $arr_return = isset($this->cart['group_order']['step']) ? $this->cart['group_order']['step'] : array();
        }
        return $arr_return;
    }  
    public function setUseGroup($val=0,$product_id='',$username=''){
        $this->cart['use_group'] = $val;
        if($val==1){
            $group = $this->cart['group_order'];
            if(!isset($group['list']))
                $group['list'] = array();
            $group['step']['group_product_id'] = $product_id;
            $group['step']['user_name'] = $username;
            $group['step']['user_id'] = count($group['list']);
            $group['step']['quantity'] = 0;
            $group['step']['total'] = 0;

            
            $group['list'][] = $group['step'];
            $this->cart['group_order'] = $group;
        }
        return $this->rebuild();
    }
    public function checkgroup(){
        if(isset($this->cart['use_group']))
            return $this->cart['use_group'];
        else
            return '';
    }
    public function group_list(){
        if(isset($this->cart['group_order']))
            return $this->cart['group_order'];
        else
            return array();
    }
    public function user_group_list(){
        if(!isset($this->cart['group_order']['list']))
            return array();
        $arr_return = array();
        foreach ($this->cart['group_order']['list'] as $key => $value) {
            $arr_return[$value['user_id']] = $value['user_name'];
        }
        return $arr_return;
    }
    public function user_group_now(){
        if(isset($this->cart['group_order']['list'])){
            $idkey = count($this->cart['group_order']['list'])-1;
            if(isset($this->cart['group_order']['list'][$idkey]['user_name'])){
                return $this->cart['group_order']['list'][$idkey]['user_name'];
            }
        }
        return '';
    }    
    public function next_uid($user_id=''){
        if(!isset($this->cart['group_order']['list']))
            return 1;
        elseif($user_id != ''){
            $group = $this->cart['group_order'];
            $next_uid = 0;
            $user_name = '';
            $group_product_id = '';
            foreach ($group['list'] as $key => $value) {
                if($value['user_id'] == $user_id)
                {
                    $user_name = $value['user_name'];
                    $group_product_id = $value['group_product_id'];
                    break;
                }
            }
            return ['next_uid'=>$user_id, 'user_name'=>$user_name, 'group_product_id'=>$group_product_id];
        }
        else
            return count($this->cart['group_order']['list'])+1;
    }
    public function user_data_list(){
        if(!isset($this->cart['group_order']['list']))
            return array();
        return $this->cart['group_order']['list'];
    }

    public function update_payment_mt($payment_method=array(),$amount_tendered=0){
        $change = 0;
        if(!empty($payment_method)){
            $this->cart['payment_method'] = $payment_method;
            $change = 1;
        }
        if($amount_tendered>0){
            $this->cart['amount_tendered'] = $amount_tendered;
            $change = 1;
        }
        if($change == 1)
            return $this->rebuild();
    }
    public function update_promo($coupon){
        $arr_return = array();
        $arr_items = $this->cart['items'];
        $arr_total = array();
        $v_total_quantity = 0;
        $check = false;
        foreach($arr_items as $key => $val){            
            if( ($val['is_11_inch'] && $val['is_banhmisub_category']) || $val['_id']== new \MongoId('5789a88b124dcacd2c965269') ){

                $check = true;
                $arr_total[] = array(
                    'sell_price'=>(float)$val['sell_price'],
                    '_cart_key'=>$key,
                    'quantity'=>(int)$val['quantity'],
                    'is_banhmisub_category'=>(int)$val['is_banhmisub_category'],
                    'is_11_inch'=>(int)$val['is_11_inch'],
                    'quantity_promo'=>0,
                    'name'=>$val['name'],
                    'image'=>$val['image'],
                    'description'=>$val['description']
                );
                $v_total_quantity += (int)$val['quantity'];
            }            
        }
        
        usort($arr_total, function($a, $b) {
            return $a['sell_price'] > $b['sell_price'];
        });
        
        $total_quantity_promo = floor((int) $v_total_quantity/(int)PROMOTED_BUY_SELL);
        $_quantity = 0;

        if($total_quantity_promo>0){
            for($i=0;$i<count($arr_total);$i++){
                $current_quantity = $arr_total[$i]['quantity'];
                $remaining_quantity = $current_quantity-$total_quantity_promo;               
                if($remaining_quantity >=0 && $_quantity==0) {
                    // da~ tru het trong lan dau tien
                    $arr_items[$arr_total[$i]['_cart_key']]['quantity_promo'] = $total_quantity_promo;
                    $arr_return [] = array(
                        'image'=>$arr_total[$i]['image'],
                        'name'=>$arr_total[$i]['name'],
                        'description'=>$arr_total[$i]['description'],
                        'quantity'=>$total_quantity_promo,
                        'sell_price'=>$arr_total[$i]['sell_price'],
                    );
                    break;
                }else{
                    if($_quantity==0){
                        // echo $remaining_quantity;die;
                        // tru ko het tron lan dau
                        // chi chay toi day trong lan dau tien                        
                        if($remaining_quantity<0) $_quantity = -1*$remaining_quantity;
                        else $_quantity = $remaining_quantity;
                        $arr_return [] = array(
                            'image'=>$arr_total[$i]['image'],
                            'name'=>$arr_total[$i]['name'],
                            'description'=>$arr_total[$i]['description'],
                            'quantity'=>$current_quantity
                        );
                        $arr_items[$arr_total[$i]['_cart_key']]['quantity_promo'] = $current_quantity;
                    }else{
                        // tu lan thu 2 chay o day
                        $last_quantity = $_quantity;
                        $remaining_quantity = $current_quantity - $_quantity;
                        // pr($remaining_quantity);
                        if($remaining_quantity>0){
                            $arr_items[$arr_total[$i]['_cart_key']]['quantity_promo'] = $last_quantity;
                            $arr_return [] = array(
                                'image'=>$arr_total[$i]['image'],
                                'name'=>$arr_total[$i]['name'],
                                'description'=>$arr_total[$i]['description'],
                                'quantity'=>$last_quantity
                            );
                            break;
                        }else{
                            pr($remaining_quantity);
                            $arr_return [] = array(
                                'image'=>$arr_total[$i]['image'],
                                'name'=>$arr_total[$i]['name'],
                                'description'=>$arr_total[$i]['description'],
                                'quantity'=>($_quantity)
                            );
                            $_quantity = -1*$remaining_quantity;
                            $arr_items[$arr_total[$i]['_cart_key']]['quantity_promo'] = $_quantity;
                        }
                    }
                }
            }
        }else{
            foreach($arr_items as $key => $val){
                $arr_items[$key]['quantity_promo'] = 0;            
            }
        }
        // update lai item
        $this->cart['items'] = $arr_items;
        // update promo code
        $this->cart['promo_code'] = $coupon;
        //update lai gia
        $this->rebuild();
        // tra ve danh sach item dc promo
        return array(
            'plist'=>$arr_return,
            'new_total'=>$this->cart['main_total'],
            'new_tax'=>$this->cart['tax'],
            'total'=>$this->cart['total'],
            'check'=>$check
        );
    }
    public function update_vouchers($arr_voucher=array()){
        $arr =array();
        if(isset($arr_voucher['type']) && isset($arr_voucher['value']) && isset($arr_voucher['name'])){
            if($arr_voucher['type']=='%')
                $arr['value'] = $this->cart['total']*$arr_voucher['value']/100;
            else
                $arr['value'] = $arr_voucher['value'];

            $this->cart['voucher_code']  = $arr_voucher['name'];
            $this->cart['voucher_value'] = $arr_voucher['value'];
            $this->cart['voucher_type']  = $arr_voucher['type'];
            // $cartKey = $this->add([
            //     '_id'       => new \MongoId('574506db124dca181c7e409c'),
            //     'name'      => 'Discount',
            //     'description'=> 'Voucher discount',
            //     'note'      => '',
            //     'image'     => '',
            //     'quantity'  => 1,
            //     'options'   => array(),
            //     'sell_price' => -1*$arr['value'],
            //     'total'     => -1*$arr['value']
            // ]);
            $this->rebuild();
            $arr['voucher_main_total'] = $this->cart['main_total'];
            $arr['tax'] = $this->cart['tax'];
        }
        return $arr;
    }
    public function get_voucher(){
        return isset($this->cart['voucher_code'])?$this->cart['voucher_code']:"";
    }
     public function get_promo(){
        return isset($this->cart['promo_code'])?$this->cart['promo_code']:"";
    }
    public function remove_vouchers(){
        $arr =array();
        $this->cart['voucher_code']  = '';
        $this->cart['voucher_value'] = 0;
        $this->cart['voucher_type']  = '$';
        $this->rebuild();
        $arr['new_main_total'] = $this->cart['main_total'];
        $arr['tax'] = $this->cart['tax'];

        return $arr;
    }
    public function remove_promo(){
        $arr =array();
        $this->cart['promo_code']  = '';
        $arr_item = $this->cart['items'];
        foreach($arr_item as $key => $val){
            $arr_item[$key]['quantity_promo'] = 0;            
        }
        $this->cart['items'] = $arr_item;
        $this->rebuild();
        $arr['new_main_total'] = $this->cart['main_total'];
        $arr['new_sub_total'] = $this->cart['total'];
        $arr['tax'] = $this->cart['tax'];

        return $arr;
    }
}
