<?php
namespace RW\Controllers;

use RW\Models\Pages;
use RW\Models\Faqs;


class PagesController extends ControllerBase
{
	public function indexAction()
	{
		$pageName = $this->dispatcher->getParam('pageName');
		$page = Pages::findFirstByShortName($pageName);
		if ($page) {
			$this->view->content = $page->content;
			$this->view->description = $page->description;
			$this->view->title = $page->name." | Banh Mi SUB";
		}else{
			return $this->response->redirect(SITE_301_URL);
		}
	}

	public function faqsAction(){
		$this->view->content =  Faqs::find(
			array(
				'active = 1 and deleted = 0',
				"order" => "order_no ASC"
			)
		);
		$this->view->title = "Frequently Asked Questions | Banh Mi SUB";
		$this->view->content = $this->view->content->toArray();
	}

}
