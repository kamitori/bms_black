var captcha_code = "";
max_distance = 0;
$(function(){
	if($("#canvas_captcha").length){
		var canvas = document.getElementById('canvas_captcha');
      		var context = canvas.getContext('2d');
      		context.fillStyle = '#FFF';
      		context.fill();
      		var possible = "abcdefghijklmnopqrstuvwxyz";
      		var captcha_code_create = "";
      		for( var i=0; i < 5; i++ )
       			captcha_code_create += possible.charAt(Math.floor(Math.random() * possible.length));
       		captcha_code = captcha_code_create;
      		captcha_view = captcha_code.toUpperCase();
		arr_color = ["blue","green","red","brown","magenta","orange","peru","crimson","yellowgreen","cyan","black"];
		arr_fontsize = [78,79,80,81,82,83,84];
		arr_font = ["Times New Roman","Tahoma","Courier","Helvetica","Trebuchet MS","Arial Black","Georgia","Verdana"];
		arr_style=["","bold","italic","bold italic"];
		for(i=0; i<captcha_code.length;i++){
			var fillStyle = arr_color[Math.floor(Math.random() * arr_color.length)];
			context.fillStyle = fillStyle;
			context.font = arr_style[Math.floor(Math.random() * arr_style.length)]+" "+arr_fontsize[Math.floor(Math.random() * arr_fontsize.length)]+'px '+arr_font[Math.floor(Math.random() * arr_font.length)];
			context.fillText(captcha_view[i],80*i,65+(Math.random()*40));
			// if(Math.random()<0.4){
			// 	context.beginPath();
			// 	context.moveTo(0,Math.random()*captcha_code.length*10);
			// 	context.lineTo(500,Math.random()*captcha_code.length*10);
			// 	context.stroke();
			// 	context.lineWidth = Math.random()*5;
			// 	context.strokeStyle = arr_color[Math.floor(Math.random() * arr_color.length)];
			// }
		}
	}
});
$(function () {
    var time_now = new Date();
    var default_time = new Date(time_now.getTime()+(1800*1000));//Mặc định là set sau 30 phút
    var min_date = new Date(time_now.getTime()+(1800*1000)); // Set thấp nhất là sau 30 phút
    var max_date = new Date(time_now.getTime()+(86400*7*1000)); // Set cao nhất là sau 1 tuần
    var str = $("#list_hours").val();    
    var hours = str.split(',').map(function(e) {return +e});
    $('#delivery_date_input').datetimepicker({
		sideBySide: true,
		defaultDate: default_time.getTime(),
		minDate: min_date.getTime(),
		maxDate: max_date.getTime(),
		enabledHours: hours
    });
});
$('.selectpicker').selectpicker();
$(".select2").select2();
$(".select_month").on("change",function(){

	var month = $(this).val();
	var day=31
	if(month==4||month==6||month==9||month==11){
		day=30;
	}
	if(month==2){
		day=29;
	}
	var html='';
	for(i=1;i<=day;i++){
		html+='<option value="'+i+'">'+i+'</option>';
	}
	$(".select_day").html(html);
	$(".select_day").trigger("change");
})

$(".input_number .up").on("click",function(){
	var value = $(this).parent().prev().val();
	value = parseInt(value);
	value++;
	if(value>99) value=99;
	$(this).parent().prev().val(value);
	$(this).parent().prev().change();
})

$(".input_number .down").on("click",function(){
	var value = $(this).parent().prev().val();
	value = parseInt(value);
	value--;
	if(value<1) value=1;
	$(this).parent().prev().val(value);
	$(this).parent().prev().change();
})

$(".input_number input").keydown(function(e){
	// Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
             // Allow: Ctrl+C
            (e.keyCode == 67 && e.ctrlKey === true) ||
             // Allow: Ctrl+X
            (e.keyCode == 88 && e.ctrlKey === true) ||
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
})
$(".input_number input").change(function(){
	if($(this).val()<=0) {
		$(this).val(1);
		$(this).change();
	}
})

$(".popup_qty").keydown(function(e){
	// Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
             // Allow: Ctrl+C
            (e.keyCode == 67 && e.ctrlKey === true) ||
             // Allow: Ctrl+X
            (e.keyCode == 88 && e.ctrlKey === true) ||
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
})
$("#popup_qty_main").change(function(){
	if($(this).val()<=0) {
		$(this).val(1);
		$(this).change();
	}
})

$(".popup_amount .popup_qty").change(function(){
	if($(this).val()<0) {
		$(this).val(0);
		$(this).change();
	}
})

$("#select_day_ship").each(function(key,value){
	create_day(value);
})
$("#product_grid").height($("#product_list").height()-$("#product_tag").height());
$(window).resize(function(){
	$("#product_grid").height($("#product_list").height()-$("#product_tag").height());
})

$("#product_tag").delegate("button","click",function(){
	$("#product_tag button").removeClass('active');
	$(this).addClass('active');
})


$("#banner_div").height($(".bannerImages a img").height());

$(window).resize(function(){
	if($(window).width()>768){
		$("#mobile").hide();
		$("#mobile").attr("show",0);
	}
	$("#desktop").css({"width":$(".main-content").width()})
	setTimeout(function(){
		$("#banner_div").height($(".bannerImages a img").height());
	},200)
})

$("#menu-mobile .parent_menu").on("click",function(){
	if($(this).next().attr("show")==0){
		$("#menu-mobile .sub_menu").hide();
		$(this).next().show();
		$(this).next().attr("show",1);
	}else{
		$("#menu-mobile .sub_menu").hide();
		$(this).next().hide();
		$(this).next().attr("show",0);
	}
	
})

if($("#product_grid").length  && $("#product_grid .col-md-4").length){
		var list_product_cart_scroll = new IScroll('#product_grid',{
			snap: '.col-md-4',
			mouseWheel: true,
		});
	}
$(document).ready(function(){
	if($(".product-item img").prop("complete")){
		if($(".product-item")){
			$(".product-item").css({"min-height":0});
			var max_height_product_item=0;
			$(".product-item").each(function(key,elem){
				if($(elem).height()>max_height_product_item){
					max_height_product_item = $(elem).height();
				}
			});
			$(".product-item").height(max_height_product_item);
			if($(window).width()>768 && $(".product-item .image-container img").attr("src")){
				$(".product-item .image-container").css({"line-height":(max_height_product_item*1/2)+"px"});
			}
			
		}

		if($(".product-block")){
			$(".product-block").css({"min-height":0});
			var max_height_product_block=0;
			$(".product-block").each(function(key,elem){
				if($(elem).height()>max_height_product_block){
					max_height_product_block = $(elem).height();
				}
			});
			if($(window).width()>768 && $(".product-item .image-container img").attr("src")){
				$(".product-block").height(max_height_product_block);
			}
		}

	}
})





init_cart_nav();
function updateQuantity(index,obj){
	$.ajax({
		url : '/carts/update-quantity',
		type: 'POST',
		data:{
			cartKey : index,
			quantity: $(obj).val()
		},
		success:function(data){
			$("#current_promo").html(data.data_promo);
			if($(window).width()>768){
				mobile_class = ".mobile-hide ";
			}else{
				mobile_class = ".mobile-show ";
			}
			$(mobile_class).find("#"+index).html("$"+data.product.total);
			$(mobile_class).find("#sub_total").html("$"+data.cart.total);
			$(mobile_class).find("#tax_data").html("$"+data.cart.tax);
			var total = (parseFloat(data.cart.total)+parseFloat(data.cart.tax)).toFixed(2);
			$(mobile_class).find("#total_amount").html("$"+total);
			$("#header_main_total").html(" $"+total );
			$(mobile_class).find("#main_order_qty").html(data.cart.quantity);

			//update user group total
			if(data.product.group_total != undefined)
			{
				var group_total_tag = $('.group_total[data-user-id="'+data.product.user_id+'"]', mobile_class);
				if(group_total_tag.length > 0){
					group_total_tag.text(data.product.group_total);		
				}				
				
				// var group_discount_tag = $('.group_discount[data-user-id="'+data.product.user_id+'"]', mobile_class);

				// var group_total_before_discount = 0;
				// $('.price[data-user-id="'+data.product.user_id+'"]', mobile_class).each(function() {
				// 	var thenum = parseFloat($(this).text().replace( /[^\d\.]*/g, ''));
				// 	group_total_before_discount += thenum;
				// });
				// console.log('group_total_before_discount:'+group_total_before_discount);
				// if(group_total_before_discount >= $('#order_group_discount_condition').val()){
				// 	group_discount_tag.show();
				// }
				// else{
				// 	group_discount_tag.hide();
				// }			
			}

			location.href = '/carts/check-out';
		}	
	})
}
function gotoDetail(){
	var combo_step = $(".comboitem").attr("data-step");
	if(combo_step == '1' || combo_step == '2' || combo_step == '3')
	{
		alertB('Please finish your combo.');
		return;
	}
	window.location.href = location.protocol+'//'+location.hostname+(location.port ? ':'+location.port: '') + '/carts/detail';
}
function processNextStep(){

}
function changeOption(obj){
	if($(obj).val() =='delivery'){
		var main_total = parseFloat($('#main_total').text().replace( /[^\d\.]*/g, ''));
		console.log('main_total:'+main_total);
		if(main_total < 75){
			alertB('We only delivery with the minimum order of $75.');
			return;
		}
		$(".address_field").show();
		$("#address_1").change();
		$("#key_address").val(0)
	}else{
		$(".address_field").hide();
		max_distance = 0;
		updateShippingFee(0);
	}
}
function processNext(){
	var check = true;
	var data = {};
	$(".error_phone").text("");
	$(".error_email").text("");
	$(".error_captcha").text("");
	$('.required_field').each(function(){
		var temp = $(this).val();
		temp = temp.replace( / +(?= )/g, '');
		if(temp=='' || temp==' '){
			check = false;
			$(this).css('border','3px solid red ')
		}else{
			$(this).css("border","none");
		}
		data[$(this).attr('id')] = temp;
		if($(this).attr("id") == "order_phone"){
			pattern_phone =/\d{3}[\-]?\d{3}[\-]?\d{4}$/;
			if(!pattern_phone.test($(this).val())){
				check = false;
				$(this).css('border','3px solid red ');
				$(".error_phone").text("Phone number is wrong format .Ex: 123-456-7890");
			}else{
				$(this).css("border","none");
			}
		}

		if($(this).attr("id") == "order_user_email"){
			pattern_phone =/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,8}$/;
			if(!pattern_phone.test($(this).val())){
				check = false;
				$(this).css('border','3px solid red ');
				$(".error_email").text("Email is wrong");
			}else{
				$(this).css("border","none");
			}
		}

		if($(this).attr("id") == "captcha_code"){
			your_cap = $(this).val().toLowerCase();
			my_cap = captcha_code.toLowerCase();
			if(your_cap != my_cap){
				check = false;
				$(this).css('border','3px solid red ');
				$(".error_captcha").text("Captcha is wrong");
			}else{
				$(this).css("border","none");
			}
		}

	});
	data['key_address'] = $("#key_address").val();
	var pickupdata = $("input:radio[name='pickup_option']:checked").val();
	data["pickup_option"] = pickupdata;
	if(pickupdata=='delivery'){
		var main_total = parseFloat($('#main_total').text().replace( /[^\d\.]*/g, ''));
		if(main_total < 75){
			alertB('We only delivery with the minimum order of $75.');
			check = false;
		}
		$('.address_fields').each(function(){
			var temp = $(this).val();
			temp = temp.replace( / +(?= )/g, '');
			if(temp=='' || temp==' '){
				check = false;
				$(this).css('border','3px solid red ')
			}
			data[$(this).attr('id')] = temp;
		});
	}
	data['notes'] = $("#notes").val();
	if($("#create_account").is(':checked')){
		var temp = $("#password").val();
		if(temp=='' || temp.length <=5){
			check = false;
			$("#password").css('border','3px solid red ')	
		}
		data['password'] = temp;
	}
	if(max_distance>10){
		check = false;
		alert_h("We only delivery in 10km from our location, please choose option pickup or call our store for delivery");
	}
	if(isNaN(max_distance)){
		check = false;
		alert_h("Your address can not locate");
	}
	if(!check){
		return false;
	}else{
		$.ajax({
			url : '/carts/save-address',
			type: 'POST',
			data:data,
			success:function(data){
				if(data.error==0){
					window.location.href = location.protocol+'//'+location.hostname+(location.port ? ':'+location.port: '') + '/carts/pay-ment';
				}else{
					$("#error_messages").html(data.message)
				}
			}
		})
	}
}
function init_cart_nav(){

	var id_here = window.location.hash.substr(1);

	var array_id = ["order_summary","checkout","details","payment"];
	if(array_id.indexOf(id_here)<0){
		id_here = array_id[0];
	}	
	for(var i=0;i<=array_id.indexOf(id_here);i++){
		value = array_id[i];
		if(value==id_here){			
			$("[data-id="+array_id[i-1]+"]").addClass('prev');
		}else{
			$("[data-id="+value+"]").addClass('complete');
		}
	}

	$(".main_cart").addClass("hidden");
	$("#"+id_here).removeClass("hidden");
}

function check_reinput(input){
	var value = $("#form_create_account #"+input).val();
 	var re_value = $("#form_create_account #re_"+input).val();
 	var _input =  $("#form_create_account #re_"+input)[0];
 	if(value!=re_value){
 		_input.setCustomValidity(input.substring(0,1).toLocaleUpperCase() + input.substring(1)+' confirmation should be equal to '+input+'');
 	}else{
 		_input.setCustomValidity('');
 	}
}

function check_user(obj){
	var email = $("#form_create_account #email").val();
	$.ajax({
		url : '/user/check-user',
		type: 'POST',
		data:{
			email : email
		},
		success:function(data){
			if(data==1){
				obj.setCustomValidity('Your email has been used to created account');
			}else{
				obj.setCustomValidity('');
			}
		}
	})
}

function ship_time(){
	if($("#ship_time_now").is(":checked")){
		$(".ship_later").hide();
	}
	if($("#ship_time_later").is(":checked")){
		$(".ship_later").show();
	}
}

function create_day(obj){
	var now = new Date(srvTime());
	if(now.getHours()>23){
		now = now.setDate(now.getDate()+1);
		now = new Date(now);
	}
	var array_day = [];
	for(i=0;i<8;i++){
		array_day[i] = [];
		var date = now;
		var options = { weekday: 'long', month: 'numeric', day: 'numeric' };
		array_day[i]['text'] = date.toLocaleDateString('en-US', options);
		array_day[i]['value'] = 	date.getDate()+"-"+date.getMonth();
		now = now.setDate(now.getDate()+1);
		now = new Date(now);
	}
	var html='';
	$.each(array_day,function(key,elem){
		html+='<option value="'+elem.value+'">'+elem.text+'</option>';
	})
	$(obj).html(html);
	$(obj).select2();
}

function create_hour(obj_day,obj_hour){
	var now = new Date(srvTime());
	var day = $(obj_day).val().split('-')[0];
	if(day != now.getDate()){
		start = 0;
	}else{
		if(now.getHours()<11 || now.getHours()>23){
			start=0
		}else{
			var hour = now.getHours();
			var minute = now.getMinutes();
			start = (hour-11)*4;
			start+= Math.ceil(minute/15);
		}
	}
	alert(start);
}

function srvTime(){
	try {
	    //FF, Opera, Safari, Chrome
	    xmlHttp = new XMLHttpRequest();
	}
	catch (err1) {
	    //IE
	    try {
	        xmlHttp = new ActiveXObject('Msxml2.XMLHTTP');
	    }
	    catch (err2) {
	        try {
	            xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
	        }
	        catch (eerr3) {
	            //AJAX not supported, use CPU time.
	            alert("AJAX not supported");
	        }
	    }
	}
	xmlHttp.open('HEAD',window.location.href.toString(),false);
	xmlHttp.setRequestHeader("Content-Type", "text/html");
	xmlHttp.send('');
	console.log(xmlHttp.getResponseHeader("Date"));
	return xmlHttp.getResponseHeader("Date");
}

function toggle_menu_mobile(){
	if($("#mobile").attr("show")==0){
		$("#mobile").show();
		$("#desktop").css({"margin-left":"280px","width":$(".main-content").width()})
		$("#mobile").attr("show",1)
	}else{
		$("#mobile").hide();
		$("#desktop").css({"margin-left":"0","width":$(".main-content").width()})
		$("#mobile").attr("show",0)
	}
}
function create_account(obj){
	if($(obj).is(':checked')){
		$("#create_account_field").show();
	}else{
		$("#create_account_field").hide();
	}
}
to_top_();
function to_top_(){
	var offset = 300,
		offset_opacity = 1200,
		scroll_top_duration = 700,
		$back_to_top = $('.cd-top');
	$(window).scroll(function(){
		( $(this).scrollTop() > offset ) ? $back_to_top.addClass('cd-is-visible') : $back_to_top.removeClass('cd-is-visible cd-fade-out');
		if( $(this).scrollTop() > offset_opacity ) { 
			$back_to_top.addClass('cd-fade-out');
		}
	});
	
	$back_to_top.on('click', function(event){
		event.preventDefault();
		$('body,html').animate({
			scrollTop: 0 ,
		 	}, scroll_top_duration
		);
	});
}

function setHeightListProduct(){
	setTimeout(function(){
		if($(".product-item")){
			var max_height_product_item=0;
			$(".product-item").each(function(key,elem){
				if($(elem).height()>max_height_product_item){
					max_height_product_item = $(elem).height();
				}
			});
			$(".product-item").height(max_height_product_item);
			if($(window).width()>768 && $(".product-item .image-container img").attr("src")){
				$(".product-item .image-container").css({"line-height":(max_height_product_item*1/2)+"px"});
			}
			
		}

		if($(".product-block")){
			var max_height_product_block=0;
			$(".product-block").each(function(key,elem){
				if($(elem).height()>max_height_product_block){
					max_height_product_block = $(elem).height();
				}
			});
			if($(window).width()>768 && $(".product-item .image-container img").attr("src")){
				$(".product-block").height(max_height_product_block);
			}
		}
	},300);
}

function createCaptcha(){
	var canvas = document.getElementById('canvas_captcha');
	var context = canvas.getContext('2d');
	context.clearRect(0, 0, canvas.width, canvas.height);
	context.fillStyle = '#FFF';
	context.fill();
	var possible = "abcdefghijklmnopqrstuvwxyz";
	var captcha_code_create = "";
	for( var i=0; i < 5; i++ )
		captcha_code_create += possible.charAt(Math.floor(Math.random() * possible.length));
	captcha_code = captcha_code_create;
	captcha_view = captcha_code.toUpperCase();
	arr_color = ["blue","black","green","red","brown","magenta","orange","peru","crimson","yellowgreen","cyan"];
	arr_fontsize = [78,79,80,81,82,83,84];
	arr_font = ["Times New Roman","Tahoma","Courier","Helvetica","Trebuchet MS","Arial Black","Georgia","Verdana"];
	arr_style=["","bold","italic","bold italic"];
	for(i=0; i<captcha_code.length;i++){
		var fillStyle = arr_color[Math.floor(Math.random() * arr_color.length)];
		context.fillStyle = fillStyle;
		context.font = arr_style[Math.floor(Math.random() * arr_style.length)]+" "+arr_fontsize[Math.floor(Math.random() * arr_fontsize.length)]+'px '+arr_font[Math.floor(Math.random() * arr_font.length)];
		context.fillText(captcha_view[i],80*i,65+(Math.random()*40));
	}
}


var validate_contact_us = false;
function submitContact(e){
	$(".error_captcha").text("");
	if($("#captcha_code").val().trim().toLowerCase() != captcha_code.trim().toLowerCase()){
		$(".error_captcha").text("Captcha is wrong");
		return false;
	}
}

$(".payment").on("click",function(){
	if($(this).val()=="card"){
		console.log("show");
		$("#block-payment").show();
	}else{
		console.log("hide");
		$("#block-payment").hide();
	}
});


function showAddress(){
		$("#frm_address").toggle();
}

function addAddress(){
	var data = $("#frm_address").serializeArray();
	data.push(
		{
			'name':'country',
			'value':'Canada'
		},
		{
			'name':'country_id',
			'value':'CA'
		},
		{
			'name':'province_state',
			'value':$('#province option:selected').attr('data-name')
		}
	);
	var check = true;
	$.each(data,function(key,value){
		if(!value.value.length)
			check=check&&false;
	});
	if(check){
		$.ajax({
			url : '/users/addAddress',
			type: 'POST',
			data : $.param(data),
			success:function(data){
				if(data.error){
					alert_h('',data.message);
				}else{
					successAlert_h('','Successfully added address')
					$("#frm_address").get(0).reset();
					getListAddress();
				}
			}
		})
	}else{
		alert_h('','Please fill all input');
	}
	
}

function removeAddress(obj){
	key = $(obj).attr('data-key');
	confirm_h('','Do you want to remove this address?','default',function(){
		console.log(key);
		$.ajax({
			url : '/users/removeAddress',
			type: 'POST',
			data: {key:key},
			success:function(data){
				if(data.error){
					alert_h('',data.message);
				}else{
					getListAddress();
				}
			}
		})
	}, null);
}
function editAddress(obj){
	data = JSON.parse($(obj).attr('data-address'));
	key = $(obj).attr('data-key');
	$("#frm_address input[name='key']").val(key);
	$("#frm_address input[name='zip_postcode']").val(data['zip_postcode']);
	$("#frm_address input[name='address_1']").val(data['address_1']);
	$("#frm_address input[name='town_city']").val(data['town_city']);
	$("#frm_address select[name='province_state_id']").val(data['province_state_id']);
	$("#frm_address").show();
}
function getListAddress(){
	$.ajax({
		url: '/users/getAddress',
		type: 'GET',
		success:function(data){
			if(data.error){
				alert_h('',data.message);
			}else{
				if(data.addresses.length){
					$html = '';
					$.each(data.addresses,function(key,address){
						var json_address = JSON.stringify(address);
						$html+='<div class="col-md-4 col-xs-12">';
						$html+='	<div class="item-address">';
						$html+='		<p>Postal code: '+address.zip_postcode+'</p>';
						$html+='		<p>Address: '+address.address_1+'</p>';
						$html+='		<p>City: '+address.town_city+'</p>';
						$html+='		<p>Province: '+address.province_state+'</p>';
						$html+='		<p class="text-center">';
						$html+='			<button class="btn btn-default pizbutton" data-key="'+key+'" onclick="removeAddress(this)">Remove</button>';
						$html+='			<button class="btn btn-default pizbutton" data-key="'+key+'" data-address=\''+json_address+'\' onclick="editAddress(this)">Edit</button>';
						$html+='		</p>';
						$html+='	</div></div>';
					});
					$("#list-address").html($html);
				}
			}
		}
	})
}

function changeAddressDetail(obj){
	data = JSON.parse($(obj).val());
	if($("#delivery").is(':checked')){
		$("#postal_code").val(data.zip_postcode);
		$("#address_1").val(data.address_1);
		$("#city").val(data.town_city);
		$("#province").val(data.province_state_id);
		$("#key_address").val($('#user_address_list option:selected').attr("data-key"));
		$("#address_1").change();
	}
}

function changeStreetAddress(){
	address = $("#address_1").val()+" "+$("#city").val()+" "+$("#province option:selected").text();
	$.ajax({
		url : 'http://geocoder.ca/?locate='+address+'&geoit=xml&topmatches=1',
		type: 'GET',
		success: function(data){
			xmlDoc = $.parseXML( data ),
			$xml = $( xmlDoc ),
			lat1 = $xml.find( "latt").text();
			long1 = $xml.find( "longt").text();
			store_lat = 51.056996;
			store_long = -113.989272;
			max_distance = getDistanceFromLatLonInKm(store_lat,store_long,lat1,long1);
			console.log(max_distance);
			if(isNaN(max_distance)){
				alert_h("Your address can not locate");
			}
			if(max_distance<5){
				//alert_h("Delivery fee is free");
				updateShippingFee(0);
			}else if(max_distance<=10){
				//alert_h("Delivery fee is $10");
				updateShippingFee(10);
			}else{
				updateShippingFee(0);
				alert_h("We only delivery in 10km from our location, please choose option pickup or call our store for delivery");
			}
		}
	});
}
function updateShippingFee(price){
	$.ajax({
		url : '/carts/updateShippingFee',
		type: 'POST',
		data: {price:price},
		success:function(data){
			old_price = parseFloat($("#shipping_fee").text().replace("$",""));
			main_total = parseFloat($("#main_total").text().replace("$",""));
			new_main_total = main_total+(price-old_price);
			$("#shipping_fee").text('$'+price+'.00');
			$("#main_total").text('$'+new_main_total.toFixed(2));
			if(data.error==0)
			alert_h('',data.message);
		}
	})
}
function deg2rad(deg) {
  return deg * (Math.PI/180)
}
function getDistanceFromLatLonInKm(lat1,lon1,lat2,lon2) {
  var R = 6371; // Radius of the earth in km
  var dLat = deg2rad(lat2-lat1);  // deg2rad below
  var dLon = deg2rad(lon2-lon1); 
  var a = 
    Math.sin(dLat/2) * Math.sin(dLat/2) +
    Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
    Math.sin(dLon/2) * Math.sin(dLon/2)
    ; 
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
  var d = R * c; // Distance in km
  return d;
}
$(function(){
	if($("#list-address").length){
		getListAddress();
	}

	$(".address_field input").on("keypress",function(){
		$("#key_address").val("");
	})
})