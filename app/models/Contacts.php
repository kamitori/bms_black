<?php
namespace RW\Models;

use Phalcon\Mvc\Model\Validator\PresenceOf;

class Contacts extends ModelBase {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $contact_name;

    /**
     *
     * @var string
     */
    public $contact_email;
    

    public function getSource()
    {
        return 'contacts';
    }

    public function validation()
    {
        $this->validate(
            new PresenceOf(
                array(
                    'field'    => 'contact_name',
                    'message'  => 'Name is required.'
                )
            )
        );

        if ($this->validationHasFailed() == true) {
            return false;
        }

        return true;
    }
}
