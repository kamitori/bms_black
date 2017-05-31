<?php
namespace RW\Controllers\Admin;

class ProductsController extends ControllerBase {

    protected $notFoundMessage = 'This category did not exist.';

    public function listAction()
    {
        $filter = new \Phalcon\Filter;
        $data = array_merge([
                'search'     => [],
                'pagination' => [
                    'pageNumber' => 1,
                    'pageSize'   => 100,
                    'sort'       => 'asc',
                    'sortName'   => 'id'
                ]
            ], $this->getPost());
        $columns = ['RW\Models\Products.id', 'RW\Models\Products.alt', 'RW\Models\Products.name', 'category_id', 'RW\Models\Categories.name as category_name', 'RW\Models\Products.image', 'RW\Models\Products.description','RW\Models\Products.price'];
        $conditions = [];
        $bind = [];

        $join = 'LEFT JOIN RW\Models\Categories ON RW\Models\Products.category_id = RW\Models\Categories.id';

        foreach($data['search'] as $fieldName => $value) {
            if (is_numeric($value)) {
                if (is_int($value)) {
                    $value = $filter->sanitize($value, 'int');
                } else if (is_float($value)) {
                    $value = $filter->sanitize($value, 'float');
                }
                $conditions[] = "{$fieldName}= :{$fieldName}:";
                $bind[$fieldName] = $value;
            } else if (is_string($value)) {
                $value = $filter->sanitize($value, 'string');
                if ($fieldName === 'category_name') {
                    $join .= ' AND RW\Models\Categories.name LIKE :{$fieldName}:';
                } else {
                    $conditions[] = "{$fieldName} LIKE :{$fieldName}:";
                }
                $bind[$fieldName] = '%'.$value.'%';
            }
        }
        $conditions = implode(' AND ', $conditions);
        if (is_string($data['pagination']['sortName'])) {
            $order = $data['pagination']['sortName'] .' '. $data['pagination']['sort'];
        } else {
            $order = 'id desc';
        }

        $limit = is_numeric($data['pagination']['pageSize']) ? $data['pagination']['pageSize'] : 100;
        $pageNumber = is_numeric($data['pagination']['pageNumber']) ? $data['pagination']['pageNumber'] : 1;
        $offset = ceil( ($pageNumber-1) * $limit);

        $where = !empty($conditions) ? 'WHERE '.$conditions : '';
        $total = $this->modelsManager->executeQuery('SELECT COUNT(RW\Models\Products.id) as total
                                                        FROM RW\Models\Products
                                                        '. $join .'
                                                          '.$where, $bind)->getFirst()->total;
        $data = $this->modelsManager->executeQuery('SELECT '. implode(', ', $columns) .'
                                                        FROM RW\Models\Products
                                                        '. $join .'
                                                        '.$where.'
                                                        ORDER BY RW\Models\Products.'.$order.'
                                                        LIMIT '.$limit.'
                                                        OFFSET '.$offset, $bind);

        if ($data) {
            $data = $data->toArray();
            foreach($data as $key => $value) {
                if (!is_null($value['image'])) {
                    $value['image'] = URL.'/'.$value['image'];
                } else {
                    $value['image'] = '';
                }
                $data[$key] = $value;
            }
        } else {
            $data = [];
        }

        return $this->response(['error' => 0, 'data' => $data, 'total' => $total]);
    }

    public function editAction($id = 0)
    {
        $filter = new \Phalcon\Filter;
        $product = $this->model->findFirst($filter->sanitize($id, 'int'));

        if ($product) {
            if (!is_null($product->image)) {
                $product->image = URL.'/'.$product->image;
            }
            $product = $product->toArray();
            $category = new \RW\Models\Categories;
            $product['categoryOptions'] = $category->getOptions();
            return $this->response(['error' => 0, 'data' => $product]);
        } else {
            return $this->error404($this->notFoundMessage);
        }
    }

    public function updateAction()
    {
        $filter = new \Phalcon\Filter;
        $data = $this->getPost();
        $data = array_merge(['name' => '', 'description' => '', 'category_id' => 0, 'meta_title' => '', 'meta_description' => ''], $data);
        if (isset($data['id'])) {
            $product = $this->model->findFirst($filter->sanitize($data['id'], 'int'));
            if ($product) {
                $message = 'has been updated';
            } else {
                return $this->error404($this->notFoundMessage);
            }
        } else {
            $product = new $this->model;
            $message = 'has been created';
        }
        $product->name = $filter->sanitize($data['name'], 'string');
        $product->description = $filter->sanitize($data['description'], 'string');
        $product->category_id = $filter->sanitize($data['category_id'], 'int');
        $product->price = $filter->sanitize($data['price'], 'float');
        $product->meta_title = $filter->sanitize($data['meta_title'], 'string');
        $product->meta_description = $filter->sanitize($data['meta_description'], 'string');
        $product->alt = $filter->sanitize($data['alt'], 'string');
        if ($this->request->hasFiles() == true) {
            $imagePath = PUBLIC_PATH . DS . 'images' . DS . 'products';
            if (!file_exists($imagePath)) {
                mkdir($imagePath, 0755, true);
            }
            foreach($this->request->getUploadedFiles() as $file) {
                if (isImage($file->getType())) {
                    $fileName = $file->getName();
                    $fileExt = $file->getExtension();

                    $fileName = str_replace('.'.$fileExt, '_'.time().'.'.$fileExt, \Phalcon\Text::uncamelize($fileName));

                    if ($file->moveTo($imagePath . DS . $fileName)) {
                        if (isset($product->image) && file_exists(PUBLIC_PATH . DS . $product->image)) {
                            unlink(PUBLIC_PATH . DS . $product->image);
                        }
                        $product->image = 'images/products/'.$fileName;
                    }
                    break;
                }
            }
        }
        if ($product->save() === true) {
            $arrReturn = ['error' => 0, 'message' => 'Product <b>'.$product->name.'</b> '.$message.' successful.', 'data' => ['id' => $product->getId()]];
        } else {
            $arrReturn = ['error' => 1, 'messages' => $product->getMessages()];
        }

        return $this->response($arrReturn);
    }

}
