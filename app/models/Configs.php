<?php
namespace RW\Models;

use Phalcon\Mvc\Model\Validator\PresenceOf;

class Configs extends ModelBase {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $cf_key;

    /**
     *
     * @var string
     */
    public $cf_value;

    public $status;
    

    public function getSource()
    {
        return 'configs';
    }

    public function validation()
    {
        $this->validate(
            new PresenceOf(
                array(
                    'field'    => 'cf_key',
                    'message'  => 'Key is required.'
                )
            )
        );

        if ($this->validationHasFailed() == true) {
            return false;
        }

        return true;
    }
}
