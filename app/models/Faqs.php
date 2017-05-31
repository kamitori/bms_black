<?php
namespace RW\Models;

use Phalcon\Mvc\Model\Validator\PresenceOf;

class Faqs extends ModelBase
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;

   

    

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'faqs';
    }

}
