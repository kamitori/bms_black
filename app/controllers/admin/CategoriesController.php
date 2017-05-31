<?php
namespace RW\Controllers\Admin;

class CategoriesController extends ControllerBase {
    protected $notFoundMessage = 'This category did not exist.';
    public function listAction()
    {
        return $this->listRecords(['id', 'name', 'image', 'description','order_no','alt'], function($array) {
            if (isset($array['image'])) {
                if (!is_null($array['image'])) {
                    $array['image'] = URL.'/'.$array['image'];
                } else {
                    $array['image'] = '';
                }
            }
            return $array;
       });
    }
    public function editAction($id = 0)
    {
        $filter = new \Phalcon\Filter;
        $category = $this->model->findFirst($filter->sanitize($id, 'int'));
        if ($category) {
            if (!is_null($category->image)) {
                $category->image = URL.'/'.$category->image;
            }
            $category = $category->toArray();
            $category['catlist'] = $this->model->getOptions($filter->sanitize($id, 'int'));
            return $this->response(['error' => 0, 'data' => $category]);
        } else {
            return $this->error404($this->notFoundMessage);
        }
    }
    public function updateAction()
    {
        $filter = new \Phalcon\Filter;
        $data = $this->getPost();
        $data = array_merge(['name' => '', 'description' => '', 'meta_title' => '', 'meta_description' => ''], $data);
        if (isset($data['id'])) {
            $category = $this->model->findFirst($filter->sanitize($data['id'], 'int'));
            if ($category) {
                $message = 'has been updated';
            } else {
                return $this->error404($this->notFoundMessage);
            }
        } else {
            $category = new $this->model;
            $message = 'has been created';
        }
        if(!isset($data['parent_id'])){
            $data['parent_id'] = 0;
        }
        $category->name = $filter->sanitize($data['name'], 'string');
        $category->description = $filter->sanitize($data['description'], 'string');
        $category->meta_title = $filter->sanitize($data['meta_title'], 'string');
        $category->meta_description = $filter->sanitize($data['meta_description'], 'string');
        $category->parent_id = $filter->sanitize($data['parent_id'], 'string');
        $category->position = $filter->sanitize($data['position'], 'string');
        $category->order_no = $filter->sanitize($data['order_no'], 'string');
        $category->alt = $filter->sanitize($data['alt'], 'string');

        if ($this->request->hasFiles() == true) {
            $imagePath = PUBLIC_PATH . DS . 'images' . DS . 'product-categories';
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
                        $category->image = 'images/product-categories/'.$fileName;
                    }
                    break;
                }
            }
        }
        if ($category->save() === true) {
            $arrReturn = ['error' => 0, 'message' => 'Category <b>'.$category->name.'</b> '.$message.' successful.', 'data' => ['id' => $category->getId()]];
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

}
