<?php
namespace RW\Controllers\Pos;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use RW\Models\Categories;
class ControllerBase extends Controller
{
    protected $response;
    public function indexAction(){
        
    }
    public function initialize()
    {
        $this->response = new \Phalcon\Http\Response;

        //Auto load Model class    
        $modelClass = 'RW\\Models\\Products';
        
        $this->model = new $modelClass;
        // default menu
        $this->view->menu = Categories::find([
            'columns'   => 'id, name, short_name',
            'order'     => 'order_no ASC',
            'conditions' => "position = 1 "
        ]);
        $this->view->baseURL = URL;
    }
    public function getData(){
        return $this->listRecords(false,['id', 'name', 'image','price']);
    }
    public function drawOneProduct(){
        $arr_product =  $this->getData();      
        $v_return = '';
        for($i=0;$i<count($arr_product);$i++){
            $v_return .='<div class="col-md-4 fixwidth">';
                $v_return .='<div class="pro-item">';
                    $v_return .='<a href="">';
                        $v_return .='<img src="'.URL.$arr_product[$i]['image'].'" />';
                        $v_return .= '<p>'.$arr_product[$i]['name'].'</p>';
                    $v_return .='</a>';
                $v_return .='</div>';
            $v_return .='</div>';
        }
        return $v_return;
    }
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
    }
    public function response($responseData = [], $responseCode = 200, $responseMessage = '', $responseHeader= [])
    {
        $this->response->setStatusCode($responseCode, $responseMessage)
                            ->setJsonContent($responseData);
        if (!empty($responseHeader)) {
            foreach($responseHeader as $headerName => $headerValue) {
                $this->response->setHeader($headerName, $headerValue);
            }
        }
        return $this->response;
    }
    public function listRecords($ajax=true,$columns = [], $arrayHandle = null)
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
        $conditions = [];
        $bind = [];
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
                $conditions[] = "{$fieldName} LIKE :{$fieldName}:";
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
        
        $total = $this->model->count([
                                        'conditions' => $conditions,
                                        'bind'       => $bind,
                                    ]);
        $data = $this->model->find([
                'conditions' => $conditions,
                'bind'       => $bind,
                'order'      => $order,
                'columns'    => $columns,
                'limit'      => $limit,
                'offset'     => $offset
            ]);

        if ($data) {
            $data = $data->toArray();
            $arrayHandleCallable = is_callable($arrayHandle);
            foreach($data as $key => $value) {
                if ($arrayHandleCallable) {
                    $value = $arrayHandle($value);
                }
                $data[$key] = $value;
            }
        } else {
            $data = [];
        }
        if($ajax) return $this->response(['error' => 0, 'data' => $data, 'total' => $total]);
        else return $data;
    }

    public function afterExecuteRoute(Dispatcher $dispatcher)
    {
        if (!$this->request->isAjax()) {
            $this->assets
                    ->collection('css')
                    ->addCss('/bower_components/bootstrap/dist/css/bootstrap.min.css')
                    ->addCss('/bower_components/font-awesome/css/font-awesome.css')
                    ->addCss('/'.THEME.'css/font.css')
                    ->addCss('/'.THEME.'css/main.css')
                    ->addCss('/'.THEME.'css/pos.css')
                    ->setSourcePath(PUBLIC_PATH)
                    ->setTargetPath(PUBLIC_PATH.DS.THEME.'/css/pos.min.css')
                    ->setTargetUri('/'.THEME.'css/pos.min.css')
                    ->join(true)
                    ->addFilter(new \Phalcon\Assets\Filters\Cssmin());
             $this->assets
                    ->collection('js')
                    ->addJs('/bower_components/jquery/dist/jquery.min.js')
                    ->addJs('/bower_components/bootstrap/dist/js/bootstrap.min.js')
                    ->addJs('/'.THEME.'js/pos.js')
                    ->setSourcePath(PUBLIC_PATH)
                    ->setTargetPath(PUBLIC_PATH.DS.THEME.'/js/pos.min.js')
                    ->setTargetUri('/'.THEME.'js/pos.min.js')
                    ->join(true)
                    ->addFilter(new \Phalcon\Assets\Filters\Jsmin());
            $this->view->baseURL = URL;
            $this->view->themeURL = THEME;
        }
        $this->view->setViewsDir($this->view->getViewsDir() . '/pos/');
    }

    final function getPost()
    {
        $postJSON = $this->request->getRawBody();
        if (!empty($postJSON)) {
            $postJSON = json_decode($postJSON, true);
        } else {
            $postJSON = [];
        }
        return array_merge($postJSON, $this->request->getPost());
    }

    final function abort($code = 404, $title = '', $message = '')
    {
        switch ($code) {
            case 404:
                if (empty($title)) {
                    $title = 'Page not found';
                    $message = 'This page this not exists. Please go back to homepage.';
                }
                $this->dispatcher->forward(array(
                    'controller'    => 'Errors',
                    'action'        => 'notFound',
                ));
                break;
        }
        $data = [
            'title'     => $title,
            'message'   => $message
        ];
        $this->dispatcher->setParams(['data' => $data]);
    }
}
