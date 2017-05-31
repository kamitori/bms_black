<?php
namespace RW\Models;

class Vouchers extends MongoBase {

    public $id;
    public $name;
    public $value;
    public $expries;
    public $active;
    public $type;
    public $product_type;
    public $order_no;
    

    public function getSource()
    {
        return 'jt_voucher';
    }    
    public static function updateVoucher(){
        $arr_self = self::find(['conditions' => ['deleted'=>0]]);
        foreach($arr_self as $arr){
            $self = self::findFirst(['conditions' => ['_id' => $arr->_id]]);
            $self->deleted = false;
            if($self->save()===true){
                echo 'update ok';
            }else{
                echo 'update error';
            }
        }
    }    
    public function generator(){
        $arrReturn = ['error'=>1,'messages'=>''];
        $str = '';
        $start = 0;
        // do{
        //     $start++;
        //     $str = 'FV'.(string)sprintf("%04d", $start);
        //     echo $str;
        //     // $str = 'FV'.create_random_key(4,false,true);
        //     $test = self::findFirst(['name' => $str]);
        // }while(is_null($test));

        while(true){
            $start++;
            $str = 'FV'.(string)sprintf("%04d", $start);
            // $str = 'FV'.create_random_key(4,false,true);
            $test = self::findFirst(['conditions' => ['name'=>$str]]);
            if(is_null($test) || !$test){
                break;
            }
        };
        $Voucher = new Vouchers;
        $Voucher->name = $str;
        $Voucher->order_no = 1;
        $Voucher->value = 10;
        $Voucher->limited = 2;
        $Voucher->active = 1;
        $Voucher->deleted = false;
        $Voucher->product_type = 'all';
        $Voucher->type = '%';
        $Voucher->expries = date('Y-m-d H:i:s', strtotime('+1 month', strtotime( Date('Y-m-d H:i:s'))));
        if($Voucher->save()===true) return ['voucher'=>$str,'expries'=>$Voucher->expries];
        else return 'Error';
    }
}
