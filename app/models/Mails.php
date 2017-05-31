<?php
namespace RW\Models;

use Phalcon\Mvc\Model\Validator\PresenceOf;
use RW\Models\JTContact;

class Mails extends ModelBase {
	public static function getList(){
		$apikey = MAILCHIMP_API_KEY;
		$server_mailchimp = 'https://us15.api.mailchimp.com/3.0';
		$auth = base64_encode( 'user:'.$apikey );
		$url = $server_mailchimp.'/lists';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
													'Authorization: Basic '.$auth));
		curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/3.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		// curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                                                    
		$result = curl_exec($ch);
		curl_close($ch);

		$list = json_decode($result,true);
		$list_return = '';
		if(isset($list['lists'][0]['id'])){
			$list_return = $list['lists'][0];
		}else{
			$data = array(
				'name'=>'Bánh Mì Sub',
				'contact'=>array(
					'company'=>'Banhmisub',
					'address1'=>'3145 - 5th Avenue NE',
					'city'=>'Calgary',
					'state'=>'Alberta',
					'zip'=>'T2A6T8',
					'country'=>'Canada',
				),
				'permission_reminder'=>'Banhmisub Email',
				'campaign_defaults'=> array(
					'from_name' => 'Trí Test Mail',
					'from_email' => 'nmtri44@gmail.com',
					'subject' => '',
					'language' => 'en',
				),
				'email_type_option'=>false
			);
			$json_data = json_encode($data);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: Basic '.$auth));
			curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/3.0');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);                                
			$result = curl_exec($ch);
			curl_close($ch);
			$list = json_decode($result,true);
			$list_return = $list;
		}
		return $list_return;
	}
	public static function getListId(){
		$apikey = MAILCHIMP_API_KEY;
		$server_mailchimp = 'https://us15.api.mailchimp.com/3.0';
		$auth = base64_encode( 'user:'.$apikey );
		$url = $server_mailchimp.'/lists';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
													'Authorization: Basic '.$auth));
		curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/3.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		// curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                                                    
		$result = curl_exec($ch);
		curl_close($ch);

		$list = json_decode($result,true);
		$id_list = '';
		if(isset($list['lists'][0]['id'])){
			$id_list = $list['lists'][0]['id'];
		}else{
			$data = array(
				'name'=>'Bánh Mì Sub',
				'contact'=>array(
					'company'=>'Banhmisub',
					'address1'=>'3145 - 5th Avenue NE',
					'city'=>'Calgary',
					'state'=>'Alberta',
					'zip'=>'T2A6T8',
					'country'=>'Canada',
				),
				'permission_reminder'=>'Banhmisub Email',
				'campaign_defaults'=> array(
					'from_name' => 'Trí Test Mail',
					'from_email' => 'nmtri44@gmail.com',
					'subject' => '',
					'language' => 'en',
				),
				'email_type_option'=>false
			);
			$json_data = json_encode($data);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: Basic '.$auth));
			curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/3.0');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);                                
			$result = curl_exec($ch);
			curl_close($ch);
			$list = json_decode($result,true);
			$id_list = $list['id'];
		}
		return $id_list;
	}

	public static function addMember($arr_member){
		$id_list = self::getListId();
		$apikey = MAILCHIMP_API_KEY;
		$server_mailchimp = 'https://us15.api.mailchimp.com/3.0';
		$auth = base64_encode( 'user:'.$apikey );
		$url = $server_mailchimp.'/lists/'.$id_list.'/members';
		$data = array(
						'apikey'        => $apikey,
						'email_address' => $arr_member['email'],
						'status'        => 'subscribed',
						'merge_fields'  => array(                
							'FNAME' => $arr_member['first_name'],
							'LNAME' => $arr_member['last_name'],
						)
		);
		$json_data = json_encode($data);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: Basic '.$auth));
		curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/3.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);                                
		$result = curl_exec($ch);
		curl_close($ch);
		$member = json_decode($result,true);
		$id_member = $member['id'];
		return $id_member;
	}

	public static function syncListMail(){
		$apikey = MAILCHIMP_API_KEY;
		$server_mailchimp = 'https://us15.api.mailchimp.com/3.0';
		$id_list = self::getListId();
		if($id_list){
			$auth = base64_encode( 'user:'.$apikey );
			$url = $server_mailchimp.'/lists/'.$id_list.'/members';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
														'Authorization: Basic '.$auth));
			curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/3.0');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			// curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                                                    
			$result = curl_exec($ch);
			curl_close($ch);
			$list_mail = json_decode($result,true);
			$list_mail_from_chimp = array();
			foreach ($list_mail['members'] as $key => $value) {
				$list_mail_from_chimp[] = $value['email_address'];
			}
			$list_contact_from_server = array();
			$list_contact = JTContact::find(array(array('deleted'=>false)));
			$filter = new \Phalcon\Filter;
			foreach ($list_contact as $key => $value) {
				$tmp = array();
				$tmp['first_name'] = $value->first_name;
				$tmp['last_name'] = $value->last_name;
				$tmp['email'] = $value->email;
				if(isset($tmp['email']) && $tmp['email']!='' && filter_var($tmp['email'],FILTER_VALIDATE_EMAIL)){
					$list_contact_from_server[] = $tmp;
				}
			}
			$arr_batch = array(
				'operations' => array()
			);
			//Add data to batch
			foreach ($list_contact_from_server as $key => $value) {
				if(!in_array($value['email'], $list_mail_from_chimp)){
					$data = array(
						'apikey'        => $apikey,
						'email_address' => $value['email'],
						'status'        => 'subscribed',
						'merge_fields'  => array(                
							'FNAME' => $value['first_name'],
							'LNAME' => $value['last_name'],
						)
					);
					$json_data = json_encode($data);
					$arr_batch['operations'][] = array(
						'method' => 'POST',
						'path'   => '/lists/'.$id_list.'/members/',
						'body'   => $json_data
					);
				}
			}
			$json_post = json_encode($arr_batch);
			$url = $server_mailchimp.'/batches/';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: Basic '.$auth));
			curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/3.0');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json_post);                                
			$result = curl_exec($ch);
			curl_close($ch);
			return "Sync successful";
			// pr(json_decode($result));
		}
	}
	public static function getListCampaign(){
		$apikey = MAILCHIMP_API_KEY;
		$server_mailchimp = 'https://us15.api.mailchimp.com/3.0';
		$auth = base64_encode( 'user:'.$apikey );
		$url = $server_mailchimp.'/campaigns';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
													'Authorization: Basic '.$auth));
		curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/3.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		// curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                                                    
		$result = curl_exec($ch);
		curl_close($ch);

		$list = json_decode($result,true);
		return $list['campaigns'];
	}

	public static function getTemplate($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
		// 											'Authorization: Basic '.$auth));
		curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/3.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		// curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                                                    
		$result = curl_exec($ch);
		curl_close($ch);
		$result = preg_replace('/\s+/', ' ', trim($result));
		$pattern = "/<!-- BEGIN TEMPLATE \/\/ -->.*<!-- \/\/ END TEMPLATE -->/";
		preg_match($pattern,$result,$template);
		if(count($template)){
			return $template[0];
		}else{
			return '';
		}
	}

	public static function createTemplate($html){
	 	$data = array(
			'name'=>'Bánh Mì Sub - '.date('d-m-Y',time()).' - '.time(),
			'html'=>$html
		);
		$json_data = json_encode($data);
		$apikey = MAILCHIMP_API_KEY;
		$server_mailchimp = 'https://us15.api.mailchimp.com/3.0';
		$auth = base64_encode( 'user:'.$apikey );
		$url = $server_mailchimp.'/templates';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: Basic '.$auth));
		curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/3.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);                                
		$result = curl_exec($ch);
		curl_close($ch);
		$template = json_decode($result,true);
		$id_template = $template['id'];
		return $id_template;
	}
	public static function createCampaign($data){
		$json_data = json_encode($data);
		$apikey = MAILCHIMP_API_KEY;
		$server_mailchimp = 'https://us15.api.mailchimp.com/3.0';
		$auth = base64_encode( 'user:'.$apikey );
		$url = $server_mailchimp.'/campaigns';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: Basic '.$auth));
		curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/3.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);                                
		$result = curl_exec($ch);
		curl_close($ch);
		$campaign = json_decode($result,true);
		$id_campaign = $campaign['id'];
		return $id_campaign;
	}
	public static function updateCampaign($id,$data){
		$json_data = json_encode($data);
		$apikey = MAILCHIMP_API_KEY;
		$server_mailchimp = 'https://us15.api.mailchimp.com/3.0';
		$auth = base64_encode( 'user:'.$apikey );
		$url = $server_mailchimp.'/campaigns/'.$id;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: Basic '.$auth));
		curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/3.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);                                
		$result = curl_exec($ch);
		curl_close($ch);
		$template = json_decode($result,true);
		$id_template = $template['id'];
		return $id_template;
	}

	public static function updateContent($id,$html){
		$data = array(
			'html'	=> $html
		);
		$json_data = json_encode($data);
		$apikey = MAILCHIMP_API_KEY;
		$server_mailchimp = 'https://us15.api.mailchimp.com/3.0';
		$auth = base64_encode( 'user:'.$apikey );
		$url = $server_mailchimp.'/campaigns/'.$id.'/content';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: Basic '.$auth));
		curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/3.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);                                
		$result = curl_exec($ch);
		curl_close($ch);
		$result = json_decode($result,true);
		return $result;
	}

	public static function sendCampaign($id){
		$apikey = MAILCHIMP_API_KEY;
		$server_mailchimp = 'https://us15.api.mailchimp.com/3.0';
		$auth = base64_encode( 'user:'.$apikey );
		$url = $server_mailchimp.'/campaigns/'.$id.'/actions/send';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
													'Authorization: Basic '.$auth));
		curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/3.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                                                    
		$result = curl_exec($ch);
		curl_close($ch);
		$result = json_decode($result,true);
		return 0;
	}
}