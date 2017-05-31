<?php
namespace RW\Controllers\Admin;

class FaqsController extends ControllerBase {

    protected $notFoundMessage = 'This faq did not exist.';

    public function listAction()
    {
        return $this->listRecords(['id', 'name', 'answer','order_no']);
    }

    public function editAction($id = 0)
    {
        $filter = new \Phalcon\Filter;
        $faqs = $this->model->findFirst($filter->sanitize($id, 'int'));
        if ($faqs) {            
            return $this->response(['error' => 0, 'data' => $faqs]);
        } else {
            return $this->error404($this->notFoundMessage);
        }
    }

    public function updateAction()
    {
        $filter = new \Phalcon\Filter;
        $data = $this->getPost();
        $data = array_merge(['name' => '', 'content' => '', 'order_no' => 1, 'meta_title' => '', 'meta_description' => ''], $data);
        if (isset($data['id'])) {
            $faq = $this->model->findFirst($filter->sanitize($data['id'], 'int'));
            if ($faq) {
                $message = 'has been updated';
            } else {
                return $this->error404($this->notFoundMessage);
            }
        } else {
            $faq = new $this->model;
            $message = 'has been created';
        }
        $faq->name = $filter->sanitize($data['name'], 'string');
        $faq->short_name = removeVietnamseChac($faq->name);
        $faq->answer = $data['answer'];
        $faq->order_no = $data['order_no'];
        $faq->active = $filter->sanitize($data['active'], 'int');        
        if ($faq->save() === true) {
            $arrReturn = ['error' => 0, 'message' => 'faq <b>'.$faq->name.'</b> '.$message.' successful.', 'data' => ['id' => $faq->getId()]];
        } else {
            $arrReturn = ['error' => 1, 'messages' => $faq->getMessages()];
        }

        return $this->response($arrReturn);
    }
}
