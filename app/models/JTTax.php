<?php
namespace RW\Models;

class JTTax extends MongoBase {

    public function getSource()
    {
        return 'tb_tax';
    }

    public static function selectList()
    {
        $arrTax =  self::find([
                'conditions' => [
                    'deleted' => false,
                ],
                'fields' => ['hst_tax', 'province_key', 'province', 'fed_tax'],
                'sort'   => ['_id' => -1]
            ]);
        $arrReturn = [
            '' => '0% (No tax)'
        ];
        foreach ($arrTax as $tax){
            if (isset($tax->hst_tax) && $tax->hst_tax == 'H'){
                $taxType = 'HST';
            } else {
                $taxType = 'GST';
            }
            if (isset($tax->province_key) && isset($tax->fed_tax) && isset($tax->province)) {
                $arrReturn[$tax->province_key] = $tax->fed_tax.'% ('.$tax->province.') '.$taxType;
            }
        }
        return $arrReturn;
    }
}
