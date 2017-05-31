<?php
namespace RW\Controllers\Pos;
use RW\Models\Categories;
use RW\Models\Products;
class PosController extends ControllerBase
{
    public function indexAction()
    {       
    }
    public function menusAction(){        
        $this->view->ListProducts = $this->drawOneProduct();
    }
    public function getData(){
        // cho fix routter
        $categoryName = str_replace('/', '', $this->dispatcher->getParam('paramsList'));
        $category = Categories::findFirstByShortName($categoryName);

        $products = [];        
        if ($category) {
            $products =  $category->products;
            $category = $category->toArray();
            if ($category['image']) {
                $category['image'] = URL.'/'.$category['image'];
            } else {
                $category['image'] = '';
            }            

            if ($products) {                
                $products = $products->toArray();      
                foreach($products as $key => $product) {

                    if ($product['image']) {
                        $$products[ $key ]['image'] = URL.'/'.$product['image'];
                    } else {
                         $$products[ $key ]['image'] = '';
                    }
                }
            } else {
                $products = [];
            }
        }
        return $products;
    }
}
