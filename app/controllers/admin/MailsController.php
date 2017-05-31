<?php
namespace RW\Controllers\Admin;
use RW\Models\Mails;
class MailsController extends ControllerBase {
		public function syncMailListAction(){
			$message = Mails::syncListMail();
			echo $message;
		}

		public function getCampaignsAction(){
			$list_campaign = Mails::getListCampaign();
			$arr_return = array();
			if(count($list_campaign)>0){
				foreach ($list_campaign as $key => $value) {
					$arr_return[]=array(
						'id'=>$value['id'],
						'name'=>$value['settings']['title'],
						'data'=>json_encode($value)
					);
				}
			}
			return $this->response(['error' => 0, 'data' => $arr_return]);
		}

		public function getTemplateAction(){
			$url = $this->getPost()['url'];
			$template = Mails::getTemplate($url);
			return $this->response(['error' => 0, 'data' => $template]);
		}

		public function sendCampaignAction(){
			$data = $this->getPost();
			$subject = $data['subject'];
			$name = $data['name'].' - '.date('d-m-Y',time());
			$from_name = $data['from_name'];
			$from_mail = $data['from_mail'];
			$id_campaign = $data['id_campaign'];
			$html = $data['html'];
			$template_id = Mails::createTemplate($html);
			$list = Mails::getList();
			$arr_data = array(
				'type'			=>	'regular',
				'content_type'	=>"template",
				'settings'		=>	array(
					'subject_line'	=>	$subject,
					'title'			=>	$name,
					'from_name'		=>	$from_name,
					'reply_to'		=>	$from_mail,
					'template_id'	=>	$template_id,
					'inline_css'	=>	true,
				),
				'recipients'=>array(
					'list_id' 	=> $list['id'],
					'list_name'	=> $list['name']
				)
			);
			$id_campaign = Mails::createCampaign($arr_data);
			$update_content = Mails::updateContent($id_campaign,$html);
			$response = Mails::sendCampaign($id_campaign);
			return $this->response(['error' => $response]);
		}
}