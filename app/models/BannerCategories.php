<?php
namespace RW\Models;

use Phalcon\Mvc\Model\Validator\PresenceOf;

class BannerCategories extends ModelBase {

    /**
     *
     * @var integer
     */
    public $id_category;

    /**
     *
     * @var string
     */
    public $image;

  

    public function getSource()
    {
        return 'banner_categories';
    }

    public function validation()
    {
        if ($this->validationHasFailed() == true) {
            return false;
        }

        return true;
    }
}
