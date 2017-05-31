<?php
namespace RW\Controllers\Admin;
use \RW\Models\JTCategory;
use \RW\Models\BannerCategories;

class ProductCategoriesController extends ControllerBase {
    protected $notFoundMessage = 'This category did not exist.';
    public function listAction()
    {
        $JTCategory = new JTCategory;
        $categories = $JTCategory->get();
        $BannerCategories = new BannerCategories();
        $banner_categories = $BannerCategories->find()->toArray();
        foreach ($categories as $key => $value) {
                unset($categories[$key]['image']);
                foreach ($banner_categories as $key2 => $value2) {
                    if($value2['id_category']==$value['id']){
                        $categories[$key]['image'] =  URL.'/'.$value2['image'];
                        $categories[$key]['alt'] =  $value2['alt'];
                        break;
                    }
                }
        }        
        $arr_return = ['error'=>0,'data'=>$categories];
        return $this->response($arr_return);
    }
    public function editAction($id = 0)
    {
        $id=str_replace("/","",$id);
        $BannerCategories = new BannerCategories();
        $banner_categories = $BannerCategories->findFirst("id_category=".$id);
        if ($banner_categories) {
            if (!is_null($banner_categories->image)) {
                $banner_categories->image = URL.'/'.$banner_categories->image;
            }
            $banner_categories = $banner_categories->toArray();
            return $this->response(['error' => 0, 'data' => $banner_categories]);
        }else{
            return $this->response(['error' => 0, 'data' =>['id_category'=>$id]]);
        }
    }
    public function updateAction()
    {

        $filter = new \Phalcon\Filter;
        $data = $this->getPost();
        $data = array_merge([], $data);
        if (isset($data['id_category'])) {
            $BannerCategories = new BannerCategories();
            $category = $BannerCategories->findFirst("id_category=".$data['id_category']);
            if ($category) {
                $message = 'has been updated';
            } else {
                $category = new BannerCategories();
                $category->id_category = $data['id_category'];
                $message = 'has been created';
               
            }
        } else {
             return $this->error404($this->notFoundMessage);
        }

        if ($this->request->hasFiles() == true) {
            $imagePath = PUBLIC_PATH . DS . 'images' . DS . 'banner-categories';
            if (!file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }
            foreach($this->request->getUploadedFiles() as $file) {
                if (isImage($file->getType())) {
                    $fileName = $file->getName();
                    $fileExt = $file->getExtension();

                    $fileName = str_replace('.'.$fileExt, '.'.date('d-m-y').'.'.$fileExt, \Phalcon\Text::uncamelize($fileName));

                    if ($file->moveTo($imagePath . DS . $fileName)) {
                        if (isset($category->image) && file_exists(PUBLIC_PATH . DS . $category->image)) {
                            unlink(PUBLIC_PATH . DS . $category->image);
                        }
                        $category->image = 'images/banner-categories/'.$fileName;
                    }
                    break;
                }
            }
        }
        $category->description = $data['description'];
        $category->alt = $data['alt'];

        if ($category->save() === true) {
            $arrReturn = ['error' => 0, 'message' => 'Category <b>'.$category->name.'</b> '.$message.' successful.', 'data' => ['id' => $data['id_category']]];
        } else {
            $arrReturn = ['error' => 1, 'messages' => $category->getMessages()];
        }

        return $this->response($arrReturn);
    }

    public function getOptionsAction()
    {
        $arrReturn = ['error' => 0, 'data' => $this->model->getOptions()];

        return $this->response($arrReturn);
    }

    public function deleteBannerAction($id)
    {
        $id=str_replace("/","",$id);
        $filter = new \Phalcon\Filter;
        $arrReturn = ['error' => 1];
        $BannerCategories = new BannerCategories();
        $record = $BannerCategories->findFirst("id_category=".$id);

        if ($record) {
            $record->delete();
            $arrReturn = ['error' => 0, 'message' => 'This banner was deleted successful.'];
        } else {
            $arrReturn['message'] = 'This banner did not exist.';
        }

        return $this->response($arrReturn);
    }

}
