<?php
namespace RW\Controllers\Admin;

class ContactsController extends ControllerBase {

    protected $notFoundMessage = 'This category did not exist.';

    public function listAction()
    {
        $arr_where [] = array('field'=>'deleted','parameter'=>'=','value'=>0);
        return $this->listRecords(['id', 'contact_name', 'contact_email','message',"contact_phone"], function($array) {            
            return $array;
        },$arr_where);
    }

    public function editAction($id = 0)
    {                
        return $this->editRecord($id, function($contact) {            
            settype($contact->id, 'int');
            return $contact;
        });
    }

    public function updateAction()
   {
        $filter = new \Phalcon\Filter;
        $data = $this->getPost();
        $data = array_merge(['link' => ''], $data);
        if (isset($data['id'])) {
            $contact = $this->model->findFirst($filter->sanitize($data['id'], 'int'));
            if ($contact) {
                $message = 'has been updated';
            } else {
                return $this->error404($this->notFoundMessage);
            }
        } else {
            $contact = new $this->model;
            $message = 'has been created';
        }
        $contact->contact_name = $filter->sanitize($data['contact_name'], 'string');
        $contact->contact_email = $filter->sanitize($data['contact_email'], 'string');
        $contact->contact_phone = $filter->sanitize($data['contact_phone'], 'string');
        $contact->company_name = $filter->sanitize($data['company_name'], 'string');
        $contact->message = $filter->sanitize($data['message'], 'string');
        $session = $this->auth->getIdentity();
        $contact->created_by = $session['id'];
        $contact->created_at = strtotime(date('m-d-Y'));
        
        if ($contact->save() === true) {
            $arrReturn = ['error' => 0, 'messages' => 'category <b>'.$contact->contact_name.'</b> '.$message.' successful.', 'data' => ['id' => $contact->getId()]];
        } else {
            $arrReturn = ['error' => 1, 'messages' => $contact->getMessages()];
        }

        return $this->response($arrReturn);
    }

    public function getOptionsAction()
    {        
    }

}
