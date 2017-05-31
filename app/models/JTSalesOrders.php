<?php
namespace RW\Models;

class JTSalesOrders extends MongoBase {
    
    public $code = '';
    public $name = '';

    public function getSource(){
        return 'tb_salesaccount';
    }
}