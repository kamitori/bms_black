<?php
namespace RW\Controllers\Admin;
use RW\Models\JTProduct;
class BannersController extends ControllerBase {

    protected $notFoundMessage = 'This banner did not exist.';

    public function saveOrdersAction(){
        $filter = new \Phalcon\Filter;
        $data = $this->request->getPost();
        $arrReturn = ['error' => 1, 'messages' => 'Invalid data'];
        if(!empty($data) && isset($data['id'])){
            $banner = $this->model->findFirst($filter->sanitize($data['id'], 'int'));
            if($banner){
                $banner->order_no = $data ['orders'];
            }
            if ($banner->save() === true) {
                $arrReturn = ['error' => 0, 'message' => 'Banner <b>'.$banner->id.'</b> updated successful.', 'data' => ['id' => $banner->getId()]];
            } else {
                $arrReturn = ['error' => 1, 'messages' => $banner->getMessages()];
            }
        }        

        return $this->response($arrReturn);
    }

    public function listAction()
    {
        return $this->listRecords(['id', 'image', 'link','order_no','b_position','alt','start_date','end_date'], function($array) {
            if (isset($array['image'])) {
                if (!is_null($array['image'])) {
                    $array['image'] = URL.'/'.$array['image'];
                } else {
                    $array['image'] = '';
                }
                $array['b_position'] = ucwords(str_replace('-', ' ', $array['b_position']));
                $array['order_no'] = (int) $array['order_no'];
                $array['id'] = (int) $array['id'];
                $array['start_date'] = $array['start_date']?date("d-m-Y",strtotime($array['start_date'])):'';
                $array['end_date'] = $array['end_date']?date("d-m-Y",strtotime($array['end_date'])):'';
            }
            return $array;
        });
    }

    public function getListsAction(){
        $arrReturn = ['error' => 0, 'data' => (new JTProduct)->getLists()];

        return $this->response($arrReturn);
    }

    public function editAction($id = 0)
    {
        return $this->editRecord($id, function($banner) {
            if (!is_null($banner->image)) {
                $banner->image = URL.'/'.$banner->image;
            }
            $banner->start_date = $banner->start_date?date('d-m-Y',strtotime($banner->start_date)):'';   
            $banner->end_date = $banner->end_date?date('d-m-Y',strtotime($banner->end_date)):'';   
            return $banner;
        });

    }

    public function updateAction()
    {
        $filter = new \Phalcon\Filter;
        $data = $this->getPost();
        $data = array_merge(['link' => ''], $data);
        if (isset($data['id'])) {
            $banner = $this->model->findFirst($filter->sanitize($data['id'], 'int'));
            if ($banner) {
                $message = 'has been updated';
            } else {
                return $this->error404($this->notFoundMessage);
            }
        } else {
            $banner = new $this->model;
            $message = 'has been created';
        }
        $banner->link = $filter->sanitize($data['link'], 'string');
        $banner->b_position = $filter->sanitize($data['b_position'], 'string');
        $banner->product_id = $filter->sanitize($data['product_id'], 'string');
        $banner->alt = $filter->sanitize($data['alt'], 'string');
        $banner->order_no = $filter->sanitize($data['order_no'], 'int');
        $time_start = 0;
        $time_end = 0;
        if(isset($data['start_date'])){
            if(strlen($data['start_date'])>10){
                $time_start = strtotime(substr($data['start_date'],0,10))+86400;
            }else{
                $time_start = strtotime(substr($data['start_date'],0,10));
            }
        }
        if(isset($data['end_date'])){
            if(strlen($data['end_date'])>10){
                $time_end = strtotime(substr($data['end_date'],0,10))+86400;
            }else{
                $time_end = strtotime(substr($data['end_date'],0,10));
            }
        }
        $banner->start_date = $time_start>0?date("Y-m-d",$time_start):null;
        $banner->end_date = $time_end>0?date("Y-m-d",$time_end):null;

        $jt_product = (new JTProduct)->getNameById($banner->product_id);        
        $banner->product_name = $jt_product;
        $banner->description = $data['description'];
        if ($this->request->hasFiles() == true) {
            $imagePath = PUBLIC_PATH . DS . 'images' . DS . 'banners';
            if (!file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }
            foreach($this->request->getUploadedFiles() as $file) {
                if (isImage($file->getType())) {
                    $fileName = $file->getName();
                    $fileExt = $file->getExtension();

                    $fileName = str_replace('.'.$fileExt, '.'.date('d-m-y').'.'.$fileExt, \Phalcon\Text::uncamelize($fileName));

                    if ($file->moveTo($imagePath . DS . $fileName)) {
                        if (isset($banner->image) && file_exists(PUBLIC_PATH . DS . $banner->image)) {
                            unlink(PUBLIC_PATH . DS . $banner->image);
                        }
                        $banner->image = 'images/banners/'.$fileName;
                    }
                    break;
                }
            }
        }
        if ($banner->save() === true) {
            $arrReturn = ['error' => 0, 'message' => 'Banner <b>'.$banner->name.'</b> '.$message.' successful.', 'data' => ['id' => $banner->getId()]];
        } else {
            $arrReturn = ['error' => 1, 'messages' => $banner->getMessages()];
        }

        return $this->response($arrReturn);
    }
}
