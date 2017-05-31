<?php
namespace RW\Models;

use Phalcon\Mvc\Model\Validator\PresenceOf;

class ProductCategories extends ModelBase {

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
     *
     * @var string
     */
    public $short_name;

    /**
     *
     * @var string
     */
    public $desciption;

    /**
     *
     * @var string
     */
    public $image;

    /**
     *
     * @var string
     */
    public $meta_title;

    /**
     *
     * @var string
     */
    public $meta_desciption;

    /**
     *
     * @var string
     */
    public $parent_id;

    /**
     *
     * @var string
     */
    public $position;

    /**
     *
     * @var string
     */
    public $order_no;
    

    public function getSource()
    {
        return 'product_categories';
    }

    public function beforeValidation()
    {
        $this->short_name = slug($this->name);
    }

    public function validation()
    {
        $this->validate(
            new PresenceOf(
                array(
                    'field'    => 'name',
                    'message'  => 'Name is required.'
                )
            )
        );

        // $this->validate(
        //     new Uniqueness(
        //         array(
        //             'field'    => 'name',
        //             'message'  => 'Value of field "name" is already present in another record'
        //         )
        //     )
        // );

        if ($this->validationHasFailed() == true) {
            return false;
        }

        return true;
    }
    // su dung khi can filter field
    public function getProductList($parameters = null) {
        return $this->getRelated('Products', $parameters);
    }
    public function getOptions($exid='')
    {
        $arrReturn = [];
        $extcond = "id != '".$exid."'";
        $categories = $this->find([
                'columns'   => 'id, name',
                'conditions'=> $extcond,
                'order'     => 'name ASC'
            ]);
        if ($categories) {
            foreach ($categories as $category) {
                $arrReturn[] = ['text' => $category->name, 'value' => $category->id];
            }
        }

        return $arrReturn;
    }
}
