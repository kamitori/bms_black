<?php
namespace RW\Models;
use RW\Models\Vouchers;
class FacebookLikeVoucher extends MongoBase {

    public function getSource()
    {
        return 'tb_facebbok_like_users';
    }
    public function getVoucher($id,$first_name,$last_name,$email){
        $arr = self::findFirst([
                        'conditions' => ['facebook_id' => $id],
                        'fields'     => ['_id','user_coupon']
                    ]);
        $coupon = '';
        if(!empty($arr)) $coupon['voucher'] = $arr['user_coupon'];
        else{
            $Vouchers = new Vouchers;
            $coupon = $Vouchers->generator();
            $arrSave['user_coupon'] = $coupon['voucher'];
            $arrSave['facebook_id'] = $id;
            $arrSave['email'] = $email;
            $arrSave['first_name'] = $first_name;
            $arrSave['last_name'] = $last_name;
            $arrSave['create_at'] = new \MongoDate(strtotime(date('Y-m-d 00:00:00')));
            $this->getConnection()->{$this->getSource()}->insert($arrSave);
        }
        return $coupon['voucher'];
    }   
}