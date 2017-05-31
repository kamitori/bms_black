<?php
namespace RW\Controllers;
use RW\Models\Users;
use RW\Models\JTContact;
use RW\Models\JTCompany;
use RW\Models\JTProvince;
use RW\Cart\Cart;
use RW\Models\Contacts;
use RW\Models\JTOrder;
use RW\Models\Pages;
use RW\Models\Vouchers;
use RW\Models\FacebookLikeVoucher;
use RW\Models\Mails;
class UsersController extends ControllerBase
{
    public function indexAction()
    {
    }
    public function addressAction()
    {
        if($this->session->has('user')){
            $provinces = JTProvince::find(array(
                'conditions'=>array('deleted'=>false,'country_id'=>'CA')
            ));
            $arr_province = array();
            foreach($provinces as $province){
                $arr_province[] = $province->toArray();
            }
            $this->view->arr_province = $arr_province;
        }else{
            return $this->response->redirect('/users/signin');
        }
        
    }
    public function addAddressAction(){
        if (!$this->request->isAjax()) {
            return $this->error404();
        }
        $arr_return = [
            'error'=>1,
            'message'=>''
        ];
        $this->view->disable();
        if($this->session->has('user')){
            $arr_user = $this->session->get('user');
            $id = new \MongoId($arr_user['_id']);
            $contact = JTContact::findFirst(array(array('_id'=>$id)));
            $contact_addresses = $contact->addresses;
            $arr_new_address = array();
            $arr_new_address['zip_postcode'] = $this->request->hasPost('zip_postcode')?$this->request->getPost('zip_postcode'):'';
            $arr_new_address['address_1'] = $this->request->hasPost('address_1')?$this->request->getPost('address_1'):'';
            $arr_new_address['town_city'] = $this->request->hasPost('town_city')?$this->request->getPost('town_city'):'';
            $arr_new_address['province_state_id'] = $this->request->hasPost('province_state_id')?$this->request->getPost('province_state_id'):'';
            $arr_new_address['province_state'] = $this->request->hasPost('province_state')?$this->request->getPost('province_state'):'';
            $arr_new_address['country_id'] = $this->request->hasPost('country_id')?$this->request->getPost('country_id'):'CA';
            $arr_new_address['country'] = $this->request->hasPost('country')?$this->request->getPost('country'):'Canada';
            if($this->request->hasPost('key')){
                $contact_addresses[$this->request->getPost('key')] = $arr_new_address;
            }else{
                $contact_addresses[] = $arr_new_address;
            }
            $contact->addresses = $contact_addresses;
            $contact->save();
            $arr_return['error'] = 0;
        }else{
            $arr_return['message'] = 'You are not signed in';
        }
        return $this->response($arr_return);
    }

    public function getAddressAction(){
        if (!$this->request->isAjax()) {
            return $this->error404();
        }
        $arr_return = [
            'error'=>1,
            'message'=>'',
            'addresses'=>[]
        ];
        $this->view->disable();
        if($this->session->has('user')){
            $arr_user = $this->session->get('user');
            $id = new \MongoId($arr_user['_id']);
            $contact = JTContact::findFirst(array(array('_id'=>$id)));
            $contact_addresses = $contact->addresses;
            if(count($contact_addresses)){
                $arr_return['addresses'] = $contact_addresses;
            }
            $arr_return['error']=0;
        }else{
            $arr_return['message'] = 'You are not signed in';
        }
        return $this->response($arr_return);
    }

    public function removeAddressAction(){
        if (!$this->request->isAjax()) {
            return $this->error404();
        }
        $arr_return = [
            'error'=>1,
            'message'=>''
        ];
        $this->view->disable();
        if($this->session->has('user')){
            $key = $this->request->getPost('key');
            $arr_user = $this->session->get('user');
            $id = new \MongoId($arr_user['_id']);
            $contact = JTContact::findFirst(array(array('_id'=>$id)));
            $contact_addresses = $contact->addresses;
            unset($contact_addresses[$key]);
            $contact->addresses = array_values($contact_addresses);
            $contact->save();
            $arr_return['error'] = 0;
        }else{
            $arr_return['message'] = 'You are not signed in';
        }
        return $this->response($arr_return);
    }
    public function checkLikeuserAction(){
        if (!$this->request->isAjax()) {
            return $this->error404();
        }
        $this->view->disable();
        $arr_return = ['error'=>1,'message'=>'Cannot connect to facebook'];
        $filter = new \Phalcon\Filter;
        $email = trim($filter->sanitize($this->request->getPost('email'),'string'));
        $fb_id = trim($filter->sanitize($this->request->getPost('fb_id'),'string'));
        $first_name = trim($filter->sanitize($this->request->getPost('first_name'),'string'));
        $last_name = trim($filter->sanitize($this->request->getPost('last_name'),'string'));
        if($fb_id!=''){
            $FacebookLikeVoucher = new FacebookLikeVoucher;
            $voucher = $FacebookLikeVoucher->getVoucher($fb_id,$first_name,$last_name,$email);
            $title = 'Thank you for support! '.$first_name;
            $str ='<br/>Please use this coupon for a 10% discount off your next order with us.';
            $str .='<br /><br /><div class="voucher_style" >'.$voucher.'</div>';
            $arr_return = array('error'=>0,'message'=>$str,'title'=>$title);
        }
        return $this->response($arr_return);
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
        if($this->request->isPost())
        {            
            $email = trim($this->request->getPost('email'));
            $user = JTContact::findFirst(array(array('email'=>$email)));
            if($user)
            {
                $this->view->message = 'Your email is already existed.';
                return;
            }
            $first_name = $this->request->getPost('first_name');
            $last_name = $this->request->getPost('last_name');
            $facebook_id = $this->request->hasPost('facebook_id')?$this->request->getPost('facebook_id'):'';            
            $subscribe = $this->request->hasPost('subscribe')?1:0;
            $phone = $this->request->getPost('phone');
            if($this->request->hasPost('month') && $this->request->hasPost('day')){
                $birthday = $this->request->getPost('day').'-'. $this->request->getPost('month');;
            }
            $address = $this->request->hasPost('address')?$this->request->getPost('address'):'';
            $town_city = $this->request->hasPost('town_city')?$this->request->getPost('town_city'):'';
            $province = $this->request->hasPost('province')?$this->request->getPost('province'):'';
            $province = explode('-',$province);
            $province_id = $province[0];
            $province_name = $province[1];
            $postcode = $this->request->hasPost('postal_code')?$this->request->getPost('postal_code'):'';
            $password = $this->request->getPost('password');
            
            // Create JT contact
            $contact = new JTContact();
            $contact->first_name = $first_name;
            $contact->last_name = $last_name;
            $contact->fullname =$first_name.' '.$last_name;
            $contact->password = $this->security->hash($password);
            $contact->email = $email;
            $contact->mobile = $phone;
            $contact->subscribe = $subscribe;
            $contact->phone = $phone;
            $contact->birthday = $birthday;
            $contact->facebook_id = $facebook_id;
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
            $contact_addresses['province_state'] = $province_name;
            $contact_addresses['province_state_id'] = $province_id;
            $contact_addresses['address_1'] = $address;
            $contact_addresses['town_city'] = $town_city;
            $contact_addresses['zip_postcode'] = $postcode;
            $contact->addresses = array($contact_addresses);
            if($contact->save()){
                $id_member = Mails::addMember(array(
                        'email'=>$contact->email,
                        'first_name'=>$contact->first_name,
                        'last_name'=>$contact->last_name,
                    ));
                $this->session->set('user',$contact->toArray());
                return $this->response->redirect('/');
            }            
        }
        $this->view->title = " Create Account | Banh Mi SUB";
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
        if($this->cookies->has('s-l-u'))
        {
            $arr_user = json_decode($this->cookies->get('s-l-u')->getValue(),true);
            $this->view->u = trim($arr_user['u']);
            $this->view->p = trim($arr_user['p']);
        }        
        if(!$this->session->has('user')){
            $this->view->message = '';
            if($this->request->isPost()){
                $email = $this->request->hasPost('email')?$this->request->getPost('email'):'';
                $user = JTContact::findFirst(array(array('email'=>$email)));
                $password = $this->request->hasPost('password')?$this->request->getPost('password'):'';
                if($this->request->hasPost('remember')){
                    $arr = array('p'=>$password,'u'=>$email);
                    $this->cookies->set('s-l-u', json_encode($arr), time() + 30 * 86400);
                    $this->cookies->send();
                }
                if ($user) {
                    if ($this->security->checkHash($password, $user->password)) {
                        $this->session->set('user',$user->toArray());
                        return $this->response->redirect('/');
                    }else{
                        $this->view->message = 'Wrong email or password. Please check again';
                    }
                }else{
                    $this->view->message = 'Wrong email or password. Please check again';
                }
            }else{
                // $this->assets->collection('pageJS')->addJs('/'.THEME.'js/facebook_login.js');
            }
        }else{
            return $this->response->redirect('/');
        }
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
        //clear session store
        if($this->session->has("user_data")){
            $this->session->remove("user_data");
        }
        //clear cart
        (new Cart)->destroy();

        return $this->response->redirect('/');
    }

}
