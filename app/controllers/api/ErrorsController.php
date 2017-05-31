<?php
namespace RW\Controllers\Api;

class ErrorsController extends ControllerBase {

    private $data;

    public function initialize()
    {
        $this->data = $this->getData();
        parent::initialize();
    }

    public function notFoundAction()
    {
        $this->view->title = $this->data['title'];
        $this->view->message = $this->data['message'];
        $this->assets
                    ->collection('css')
                    ->addCss('/bower_components/bootstrap/dist/css/bootstrap.min.css')
                    ->addCss('/bower_components/font-awesome/css/font-awesome.css')
                    ->addCss('/'.THEME.'css/main.css')
                    ->setSourcePath(PUBLIC_PATH)
                    ->setTargetPath(PUBLIC_PATH.DS.THEME.'/css/app.min.css')
                    ->setTargetUri('/'.THEME.'css/app.min.css')
                    ->join(true)
                    ->addFilter(new \Phalcon\Assets\Filters\Cssmin());
        $this->view->content = $this->view->partial('Errors/notFound');
    }

    public function uncaughtExceptionAction()
    {

    }

    private function getData()
    {
        return array_merge(['title' => '', 'message' => ''], $this->dispatcher->getParam('data'));
    }
}
