<?php
namespace RW\Controllers\Api;
use RW\Models\Users;
use RW\Models\JTContact;
use RW\Models\JTCompany;
use RW\Cart\Cart;
use RW\Models\Contacts;
use RW\Models\JTOrder;
use RW\Models\Pages;

class UsersController extends ControllerBase
{
	public function indexAction()
	{
	}
	public function orderHistoryAction(){
		$arr_user = $this->session->get('user');

		$arr_data = array();
		if(empty($arr_user)){

		}else{
			$v_contact_id = (string)$arr_user['_id'];
			$arr_orders = JTOrder::find(array(
				'conditions'=>array('contact_id'=>new \MongoId($v_contact_id), 'deleted'=>false)
			));            
			foreach($arr_orders as $arr){   
				$arr_data [] = array(
					'name'=>$arr->name,
					'total'=>$arr->sum_amount,
					'number'=>$arr->code,
					'status'=>$arr->status,
					'_id'=>$arr->_id
				);
			}
		}        
		$this->view->orders = $arr_data;
	}
	public function contactUsAction(){
		if($this->session->has('message')){            
			$this->view->message = $this->session->get('message');
			$this->session->remove('message');
		}

		$page = Pages::findFirstByShortName('contact-us');
		if ($page) {
			$this->view->summary = $page->summary;
			$this->view->content = $page->content;
			$this->view->title = $page->name." | Banh Mi SUB";
		}

		if($this->request->isPost())
		{
			$filter = new \Phalcon\Filter;
			$contact_name = $filter->sanitize($this->request->getPost('contact_name'), 'string');
			$contact_phone = $filter->sanitize($this->request->getPost('contact_phone'), 'string');
			$contact_email = $filter->sanitize($this->request->getPost('contact_email'), 'string');
			$message = $filter->sanitize($this->request->getPost('message'), 'string');
			$primary_interest = $filter->sanitize($this->request->getPost('primary_interest'), 'string');
			
			// Create JT contact
			$contact = new Contacts();
			$contact->contact_name = $contact_name;
			$contact->contact_phone = $contact_phone;
			$contact->contact_email =$contact_email;
			$contact->message = $message;
			$contact->primary_interest = $primary_interest;
			
			if($contact->save()){
				$this->session->set('message','Message has been sent');
				return $this->response->redirect('/users/contact-us');
			}            
		}
	}
	public function createAccountAction()
	{        
		$arr_return = [
				"error"=>0,
				"message"=>"",
				"data"=>[],
				];
		
		if($this->request->isPost())
		{            
            $data = $this->request->getPost();
            $user_data = json_decode($data['user']);

            $fullname = $user_data->uName;
            $email = trim($user_data->uEmail);
            $phone = $user_data->uPhone;
            $password = trim($user_data->uPassword);
			
			$user = JTContact::findFirst(array(array('email'=>$email)));
			if($user)
			{
				$arr_return['error'] = 1;
				$arr_return['message'] = 'Your email is already existed.';
				return $this->response($arr_return);
			}

			// Create JT contact
			$contact = new JTContact();
			$contact->fullname = $fullname;
			$contact->password = $this->security->hash($password);
			$contact->email = $email;
			$contact->mobile = $phone;
			$contact->subscribe = 0;
			$contact->phone = $phone;
			$contact->is_customer = 1;
			$contact->is_employee = 0;

			$company = JTCompany::findFirst(
										array(array(
												'name' => 'Retail'
											)
										)
									);
			if($company){
				$contact->company = $company->name;
				$contact->company_id = $company->_id;
			}

			$contact_addresses = array();
			$contact_addresses['country'] = 'Canada';
			$contact_addresses['country_id'] = 'CA';
			$contact_addresses['province_state'] = "";
			$contact_addresses['province_state_id'] = "";
			$contact_addresses['address_1'] = "";
			$contact_addresses['town_city'] = "";
			$contact_addresses['zip_postcode'] = "";
			$contact->addresses = array($contact_addresses);
			if($contact->save()){
				$arr_return['message'] = 'New account created successfully.';
			}            
		}
		return $this->response($arr_return);
	}    

	public function checkUserAction()
	{
		if($this->request->isAjax()){
			$email = $this->request->hasPost('email')?$this->request->getPost('email'):"";
			$user = JTContact::findFirst(array(array('email'=>$email)));
			if($user){
				if($user->facebook_id==""){
					$user->facebook_id = $this->request->getPost('fb_id');
					$user->save();
				} 
				$user = $user->toArray();
			}else{
				$user = array();
			}
			if($this->request->hasPost('fb_id') && $this->request->getPost('fb_id') !='' && count($user)){
				$this->session->set('user',$user);
			}
			echo count($user)?1:0;
			$this->view->disable();
		}
	}

	public function signInAction()
	{   
		$arr_return = [
				"error"=>1,
				"message"=>"",
				"data"=>[],
				];
		if($this->request->isPost()){
			$email = $this->request->hasPost('email')?$this->request->getPost('email'):'';
			$user = JTContact::findFirst(array(array('email'=>$email)));
			$password = $this->request->hasPost('pass')?$this->request->getPost('pass'):'';
			if ($user) {
				if ($this->security->checkHash($password, $user->password)) {
					$arr_return['error'] = 0; 
					$arr_return['data'] = $user->toArray();
				}else{
					$arr_return['message'] = 'Wrong email or password. Please check again';
				}
			}else{
				$arr_return['message']  = 'Wrong email or password. Please check again';
			}
		}else{
			$arr_return['message']  = 'Error connection';
		}
		return $this->response($arr_return);
	}
	public function forgotPasswordAction()
	{
		if($this->request->isPost())
		{            
			$email = trim($this->request->getPost('email'));
			$user = JTContact::findFirst(array(array('email'=>$email)));
			if($user){
				$id=(string)$user->_id;
				$hash=$user->password;
				$link = URL.'/users/reset-password/?id='.urlencode($id)."&hash=".urlencode($hash);
				$html='
					<p><a href="'.$link.'">Click here to reset your password</a></p>
					<p>If you can not see link, please copy this URL to your browser: <br/>'.urldecode($link).'</p> 
					';
				// Create a message
				$message = $this->mailer->message->setSubject('Reset password from '.URL)
				->setFrom(array($this->config->smtp->from->email => $this->config->smtp->from->name))
				->setTo(array($email))
				->setBody($html, 'text/html', 'utf-8');
				$result = $this->mailer->send($message);
				if($result){
					$this->view->message = 'We have sent you email to reset password';
				}else{
					$this->view->message = 'Error send mail. Please try again';
				}
			}else{
				$this->view->message = 'Wrong email. Please check again';   
			}
		}
	}

	public function resetPasswordAction()
	{
		$id = trim($this->request->getQuery('id'));
		$hash = trim($this->request->getQuery('hash'));
		$user = JTContact::findFirst(array(array('_id'=>new \MongoID($id))));
		if($user){
			if($user->password != $hash){
				$this->view->disable();
				echo 'Error authorize. <a href="'.URL.'">Click here </a> to come back home.';
			}
			
		}else{
			$this->view->disable();
			echo 'Error authorize. <a href="'.URL.'">Click here </a> to come back home.';
		}
		if($this->request->isPost())
		{
			$password = $this->request->getPost('password');
			$re_password = $this->request->getPost('re_password');
			if($password != $re_password){
				$this->view->message = "Re-password is not correct.";
			}else{
				$user->password = $this->security->hash($password);
				if($user->save()){
					//clear session user
					if($this->session->has("user")){
					$this->session->remove("user");
					}
					//clear session guest
					if($this->session->has("guest")){
					$this->session->remove("guest");
					}
					//clear session store
					if($this->session->has("store")){
					$this->session->remove("store");
					}
					//clear cart
					(new Cart)->destroy();
					return $this->response->redirect('/users/sign-in');
				}else{
					$this->view->message = "Error reset password.";
				}
			}
		}

	}

	public function logoutAction()
	{
		//clear session user
		if($this->session->has("user")){
			$this->session->remove("user");
		}
		//clear session guest
		if($this->session->has("guest")){
			$this->session->remove("guest");
		}
		//clear session store
		if($this->session->has("store")){
			$this->session->remove("store");
		}
		//clear cart
		(new Cart)->destroy();

		return $this->response->redirect('/');
	}

}
