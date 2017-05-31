'use strict';

app.controller('MailController', ['$rootScope', '$scope', '$stateParams', '$http', '$location', 'Upload', 'toaster', 'dialogs', 'uiGridConstants', 'RW', function($rootScope, $scope, $stateParams, $http, $location, Upload, toaster, dialogs, uiGridConstants, RW) {
	$rootScope.title = 'Mail';

	$scope.syncMailList = function() {
		 $http.get(appHelper.adminURL('mails/sync-mail-list'))
				.success(function(result) {
					toaster.pop('success', '', result);
				});
	};
	
	$scope.reset_form = function() {
		document.getElementById('select_campaign').value = 0;
		document.getElementById('subject').value = '';
		document.getElementById('campaign_name').value = '';
		document.getElementById('from_name').value = '';
		document.getElementById('from_mail').value = '';
		document.getElementById('id_campaign').value = '';
		$scope.content = '';
	};

	$scope.loadCampaign = function(id_campaign){
		var nodeList = document.getElementsByTagName('option');
		var iterator = 0;
		var node = null;
		var nodeReturn = null;
		while (node = nodeList[iterator++]) {
			if (node.getAttribute('value')==id_campaign) 
				nodeReturn = node;
		}
		if(nodeReturn && nodeReturn.getAttribute('data-campaign')){
			var data = JSON.parse(nodeReturn.getAttribute('data-campaign'));
			console.log(data);
			document.getElementById('subject').value = data.settings.subject_line;
			document.getElementById('campaign_name').value = data.settings.title;
			document.getElementById('from_name').value = data.settings.from_name;
			document.getElementById('from_mail').value = data.settings.reply_to;
			document.getElementById('id_campaign').value = data.id;
			$http({
				   withCredentials: false,
				   method: 'post',
				   url: appHelper.adminURL('mails/get-template'),
				   headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				   data: {
						'url':data.long_archive_url
				   }
			 }).success(function(result) {
				if(result.error === 0){
					$scope.content = result.data;
				}
			 });
		}else{
			document.getElementById('subject').value = '';
			document.getElementById('campaign_name').value = '';
			document.getElementById('from_name').value = '';
			document.getElementById('from_mail').value = '';
			document.getElementById('id_campaign').value = '';
			$scope.content = '';
		}
	}
	$http.get(appHelper.adminURL('mails/get-campaigns'))
				.success(function(result) {
					if (result.error === 0) {
						$scope.campaigns = result.data;
					}
	});

	$scope.sendCampaign = function(){
		var subject = document.getElementById('subject').value;
		var name = document.getElementById('campaign_name').value;
		var from_name = document.getElementById('from_name').value;
		var from_mail = document.getElementById('from_mail').value;
		var id_campaign = document.getElementById('id_campaign').value;
		var html = $scope.content;
		var check = true;
		if(name.trim()=="" && check){
			check=false;
			toaster.pop('error','Error','Campaign name is empty');
		}
		if(subject.trim()=="" && check){
			check=false;
			toaster.pop('error','Error','Email subject is empty');
		}
		if(from_name.trim()=="" && check){
			check=false;
			toaster.pop('error','Error','From name is empty');
		}
		if(from_mail.trim()=="" && check){
			check=false;
			toaster.pop('error','Error','From email is empty');
		}
		var re = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
		if(!re.test(from_mail) && check){
			check=false;
			toaster.pop('error','Error','From email is not correct');
		}
		if(check){
			var data = {
				'subject' 		: subject,
				'name' 			: name,
				'from_name' 	: from_name,
				'from_mail'	  	: from_mail,
				'id_campaign' 	: id_campaign,
				'html' 			: html,
			};
			$http({
				withCredentials: false,
				method: 'post',
				url: appHelper.adminURL('mails/send-campaign'),
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				data: data
			}).success(function(result) {
				if(result.error===0){
					toaster.pop('success','','Send email successful');
				}else{
					toaster.pop('error','Error','Error sending email');
				}
			});
		}else{
			return;
		}
		
	}
}]);