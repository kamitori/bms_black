<!-- Modal -->
 <div class="modal" id="myModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
				<h2 class="modal-title" id="myModalLabel"><!-- Start your order --></h2>
			</div>
			<div class="modal-body">
				<div class="padding-sides-40"  style="clear:both;">
					<div class="row margin-bottom-20 border-bottom">
						<div class="col-md-6 temp-text-right">
							<label for="dispositionCarryout" class="modal-regular-text">
							<span class="carryout-icon"></span>
							Carryout
							</label>&nbsp;
							<input type="radio" name="disposition" id="dispositionCarryout" class="ng-pristine ng-untouched ng-valid" value="carryout" onchange="selectDisposition(this)">
						</div>
						<div class="col-md-6 text-left">
							<label for="dispositionDelivery" class="modal-regular-text">
							<span class="delivery-icon"></span>
							Delivery
							</label>&nbsp;
							<input type="radio" checked="checked" name="disposition" id="dispositionDelivery" class="ng-pristine ng-untouched ng-valid" value="delivery" onchange="selectDisposition(this)">
						</div>
					</div>
				</div>
				<div id="DeliveryForm" class="row padding-sides-40 formpopup">
					<form>
						<div class="row">
							<div class="col-md-5">
								<label class="modal-regular-text">Postal Code</label>
								<div class="control-group">
									<input type="text" name="popup_postalcode" class="input-popup" placeholder="Enter Postal Code" />
									<small class="hidden">Postal Code is required.</small>
									<small class="hidden">Incorrect Postal Code format.</small>
								</div>
							</div>
							<div class="col-md-2">
								<p class="ortext">or</p>
							</div>
							<div class="col-md-5 current_location">
								<a href="#" style="line-height: 40px;" onclick="getCurrentLocation()">
									<i class="fa fa-location-arrow" aria-hidden="true"></i> 
									<span>Your current location</span>
								</a>
							</div>
						</div>
					</form>
				</div>
				
				<div id="AddressShip" class="row padding-sides-40 formpopup">
					<div class="row">
						<div class="col-md-5 fixwidth">
							<hr class="hr-white-thin">
						</div>
						<div class="col-md-2 fixwidth">
							<p class="ortext ortext2">or</p>
						</div>
						<div class="col-md-5 fixwidth">
							<hr class="hr-white-thin">
						</div>
					</div>
					<div class="row">
						<div class="col-md-5">
							<label class="modal-regular-text">Street Number</label>
							<div class="control-group">
								<input type="text" name="popup_street_number" class="input-popup" placeholder="Street Number" value="" />
							</div>
						</div>
						<div class="col-md-2"></div>
						<div class="col-md-5">
							<label class="modal-regular-text">Street Name</label>
							<div class="control-group">
								<input type="text" name="popup_street_name" class="input-popup" placeholder="Street Name" value="" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-5">
							<label class="modal-regular-text">City</label>
							<div class="control-group">
								<input type="text" name="popup_city" class="input-popup" placeholder="City" value="Calgary" />
							</div>
						</div>
						<div class="col-md-2"></div>
						<div class="col-md-5">
							<label class="modal-regular-text">Province</label>
							<div class="control-group">
								<input type="text" name="popup_province" class="input-popup" placeholder="Province" value="Alberta" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-5">
							<label class="modal-regular-text">Apart., Hotel, Dorm Or Office?</label>
							<div class="control-group">
								<input type="text" name="popup_housing_type" class="input-popup" placeholder="Apt/Unit/Suite Number" />
							</div>
						</div>
						<div class="col-md-2"></div>
						<div class="col-md-5">
							<label class="modal-regular-text">Buzz Code, Building Name etc.</label>
							<div class="control-group">
								<input type="text" name="popup_housing_name" class="input-popup" placeholder="Buzz Code, Building Name etc." />
							</div>
						</div>
					</div>
				</div>
				<div class="row padding-sides-40 formpopup">
					<div class="row">
						<div class="col-md-12">
							<button type="button" class="button pizza-button popup-button" onclick="findStores()">Find a store</button>
						</div>
					</div>
				</div>
				<div id="store_selection">

				</div>
			</div>
			{% if session_user == false %}
			<div class="modal-footer" style="border-top:none">
				<div class="row">
					<div class="col-md-5 fixwidth">
						<hr class="hr-white-thin">
					</div>
					<div class="col-md-2 fixwidth">
						<p class="ortext ortext2">or</p>
					</div>
					<div class="col-md-5 fixwidth">
						<hr class="hr-white-thin">
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 fixwidth">
						<p class="text-center">Sign in to your account</p>
					</div>
				</div>
				<div class="row formpopup">
					<div class="col-md-12">
						<form method="post" action="users/sign-in">
							<div class="row">
								<div class="col-md-6">
									<input type="text" name="email" class="input-popup sign-input" placeholder="Email address" />
								</div>
								<div class="col-md-6">
									<input type="password" name="password" class="input-popup sign-input" placeholder="Password" />
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 remembercheck">
									<a href="{{baseURL}}/users/forgot-password">Forgot your password?</a>
								</div>
								<div class="col-md-6">
									<button type="submit" class="button pizza-button">Sign in</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			{% endif %}
		</div>
	</div>
 </div>
 <div class="modal" id="modalProduct" role="dialog" aria-labelledby="modalProductLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content" style="height: 90vh;">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
				<h2 class="modal-title" id="modalProductLabel">Start your order</h2>
			</div>
			<div class="modal-body" id="modalProductContent">
				<div class="row">
					<div class="col-md-9 row" id="product_list">
						<div class="col-md-12" id="product_tag">
							
						</div>
						<div class="col-md-12" id="product_grid">
							
						</div>
					</div>
					<div class="col-md-3" id="category_list">
						
					</div>
				</div>
			</div>
		</div>
	</div>
 </div>

 <!-- Modal product options -->
 <div class="modal" id="modalProductOptions" role="dialog" aria-labelledby="modalProductOptionsLabel">
	<div class="modal-dialog" role="document" style="width: 80%;">
		<div class="modal-content" style="height: 90vh;">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
				<h2 class="modal-title" id="modalProductOptionsLabel"></h2>
			</div>
			<div class="modal-body" id="modalProductOptionsContent">
				<div style="padding-left:2%; padding-right:2%;background:#fff;">
	                <div class="productitem col-md-7">
	                    <div class="popup_title col-md-6">
	                        <h2 style="text-align:left">Banh mi SUB</h2>
	                        <p class="description_item"></p>
	                        <textarea class="note_product" style="display:none;"></textarea>
	                        <button class="button pizza-button width-auto add_note_product" onclick="addNoteProduct()" style="display:block;"> Add Note</button>
	                        <button class="button pizza-button width-auto hidden_note_product" onclick="hiddenNoteProduct()" style="display:none;"> Hidden Note</button>
	                        <button class="button pizza-button width-auto clear_note_product" onclick="clearNoteProduct()" style="display:none;">Clear Note</button>
	                    </div>
	                    <div class="popup_image col-md-6">
	                        <img src="{{ baseURL }}/themes/pizzahut/images/default.png" alt="Banh mi" />
	                    </div>
	                </div>
	                <div class="popup_prices col-md-5 col-xs-12">
	                    <div class="op_description col-md-12">
	                    
	                    </div>
	                    <div class="popup_amount col-md-6 col-xs-6">
	                        <button class="btn down mainbt" onclick="downQty('popup_qty_main')">-</button>
	                        <input class="popup_qty popup_qty_main" type="text" onchange="onFocusQuantity(this)" onfocus="onFocusQuantity(this)" value="1" id="popup_qty_main" />
	                        <button class="btn up mainbt" onclick="upQty('popup_qty_main')">+</button>
	                        <input id="sell_price_popup_qty_main" value="0" type="hidden" />
	                    </div>
	                    <div class="popup_price col-md-6 col-xs-6">
	                        $10.00
	                    </div>
	                    <div class="popup_add_bt col-md-6 col-xs-12">
	                        <button class="popup_add_cart totalbg button pizza-button mobile-hide" onclick="addCustomCart()" style="display:block">Add to Cart</button>
	                        <button class="popup_update_cart totalbg button pizza-button mobile-hide" onclick="updateCart()" style="display:none">Update Cart</button>
	                        <input class="item_id" type="hidden" value="" />
	                        <input class="cart_id" type="hidden" value="" />
	                    </div>
			<div style="padding-top:15px;clear:both;">
				<button class="popup_add_cart popup_add_cart_mobile totalbg button pizza-button mobile-show" onclick="addCustomCart()" style="margin-top:15px;">Add to Cart</button>
				<button class="popup_update_cart popup_update_cart_mobile totalbg button pizza-button" onclick="updateCart()" style="display:none">Update Cart</button>
			</div>	
	                    <div class="popup_control" style="display:none;">
	                        <button class="back_level" onclick="optionLevel(1)" style="display:block;">« Back option</button>
	                        <button class="next_level" onclick="optionLevel(2)" style="display:block;">Continue »</button>
	                    </div>
	                </div>
	                <div class="tabs mobile-hide">
	                    {{ partial('Categories/option_product') }}
	                </div>
	                <div class="tabs mobile-show">
	                    {{ partial('Categories/option_product_mobile') }}
	                </div>
	                <div style="padding-top:15px;clear:both;">
	                	<button class="popup_add_cart  totalbg button pizza-button mobile-show" onclick="addCustomCart()" style="margin-top:15px;">Add to Cart</button>
	                </div>			
				</div>
			</div>
		</div>
	</div>
 </div>
 <!-- End modal product options -->

 <div class="modal fade" id="use-group-modal" role="dialog">
    <div class="modal-dialog" style="width:70%">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Please enter the name of Guest</h4>
            </div>
            <div class="modal-body use_group_box">
                <p>Please input name  of member in group !</p>
                <form class="use_group_form">
                    <div class="form-group">
                        <label for="username_1"></label>
                        <input type="text" class="form-control username" id="username_1" placeholder="Name of Guest 1" value="Name of Guest {% if next_uid is defined %}{{next_uid}}{%endif%}">
                    </div>
                </form>
                <!-- <button type="button" class="btn btn-default" style="float:right;" onclick="AddLineUsename()">Add more Member</button> -->
                <button type="button" class="btn btn-default" id="begin_choice_product" data-proid="" onclick="setGroup();">Select Menu Item</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
var Mylocation={};
var ListCurrentProduct = {}

function getCurrentLocation(){
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(localPosition,showError);
		console.log(navigator.geolocation);
	} else {
		alert("Geolocation is not supported by this browser.");
	}
}
function localPosition(position){
	$.get('http://maps.googleapis.com/maps/api/geocode/json?latlng='+data.latitude+','+data.longitude+'&sensor=true',function(location){
            	Mylocation = {
            		street_number : location['results'][0]['address_components'][0]['long_name'],
            		street_name : location['results'][0]['address_components'][1]['long_name'],
            		city : location['results'][0]['address_components'][3]['types'][0]=="administrative_area_level_2"?location['results'][0]['address_components'][3]['long_name']:location['results'][0]['address_components'][4]['long_name'],
            		province : location['results'][0]['address_components'][4]['types'][0]=="administrative_area_level_1"?location['results'][0]['address_components'][4]['long_name']:location['results'][0]['address_components'][5]['long_name'],
            	}
            	updateMylocation();
            })  
}
function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            $.getJSON('https://geoip-db.com/json/geoip.php?jsonp=?') 
	         .done (function(data)
	         {
	            $.get('http://maps.googleapis.com/maps/api/geocode/json?latlng='+data.latitude+','+data.longitude+'&sensor=true',function(location){
			Mylocation = {
				street_number : location['results'][0]['address_components'][0]['long_name'],
	            		street_name : location['results'][0]['address_components'][1]['long_name'],
	            		city : location['results'][0]['address_components'][3]['types'][0]=="administrative_area_level_2"?location['results'][0]['address_components'][3]['long_name']:location['results'][0]['address_components'][4]['long_name'],
	            		province : location['results'][0]['address_components'][4]['types'][0]=="administrative_area_level_1"?location['results'][0]['address_components'][4]['long_name']:location['results'][0]['address_components'][5]['long_name'],
			}
			updateMylocation();
	            })           
	         });
            break;
        case error.POSITION_UNAVAILABLE:
            alert("Location information is unavailable.");
            break;
        case error.TIMEOUT:
            alert("The request to get user location timed out.");
            break;
        case error.UNKNOWN_ERROR:
            alert("An unknown error occurred.");
            break;
    }
}
function updateMylocation(){
	$("input[name=popup_street_number]").val(Mylocation['street_number']);
	$("input[name=popup_street_name]").val(Mylocation['street_name']);
	$("input[name=popup_city]").val(Mylocation['city']);
	$("input[name=popup_province]").val(Mylocation['province']);
}
function selectDisposition(obj)
{
	if($(obj).is(':checked'))
	{
		if($(obj).val() == 'carryout')
		{
			$('#AddressShip').hide();
			$("#DeliveryForm").hide();
			$("#AddressShip input").val("");
			findStores();
		}
		else
		{
			$('#AddressShip').show();
			$("#DeliveryForm").show();
		}
	}
}
function setSelectingProduct(product_id)
{
	if(product_id != undefined)
	{
		$('#selecting_product_id').val(product_id);
	}
}
function findStores()
{
	var postalcode = $('input[name="popup_postalcode"]').val();
	var street_number = $('input[name="popup_street_number"]').val();
	var street_name = $('input[name="popup_street_name"]').val();
	var city = $('input[name="popup_city"]').val();
	var province = $('input[name="popup_province"]').val();
	var housing_type = $('input[name="popup_housing_type"]').val();
	var housing_name = $('input[name="popup_housing_name"]').val();
	$.ajax({
		url : appHelper.baseURL+'/indexs/find-stores',
		type: 'POST',
		data:{
			postalcode : postalcode,
			street_number : street_number,
			street_name : street_name,
			city : city,
			province : province,
			housing_type : housing_type,
			housing_name : housing_name
		},
		success:function(data){
			if(data.error){
				alert(data.message);
			}else{
				var html='';
				var index = 1;
				$.each(data.data, function(key,value){
					html+=	'<div class="row store-selection-row">';
					html+=		'<div class="col-md-1">';
					html+=			'<div class="columns">';
					html+=				'<span class="list-bullet-round-red">'+index+'</span>';
					html+=			'</div>';
					html+=		'</div>';
					html+=		'<div class="col-md-4">';
					html+=			'<div class="columns">';
					html+=				'<p>'+value['address_1']+'</p>';
					html+=				'<p>'+value['town_city']+' '+value['province_state_id']+', '+value['zip_postcode']+'</p>';
					html+=				'<p><a href="#" target="_blank">Store details</a></p>';
					html+=			'</div>';
					html+=		'</div>';
					html+=		'<div class="col-md-4">';
					html+=			'<div class="columns">';
					html+=				'<p>Today\'s hours</p>';
					html+=				'<p>08:00AM-07:00PM</p>';
					html+=			'</div>';
					html+=		'</div>';
					html+=		'<div class="col-md-3">';
					html+=			'<button class="button" type="button" onclick="selectStore(this)" data-store=\''+JSON.stringify(value)+'\'>';
					html+=				'<span>Select</span>';
					html+=			'</button>';
					html+=		'</div>';
					html+=	'</div>';
				index++;
					$("#store_selection").html(html);
				});

				location.href = '#store_selection';
			}
		}        
	})
}
function selectStore(obj)
{
	var disposition = '';
	if($('#dispositionCarryout').is(':checked'))
	{
		disposition = $('#dispositionCarryout').val();
	}
	else if($('#dispositionDelivery').is(':checked'))
	{
		disposition = $('#dispositionDelivery').val();   
	}
	var postalcode = $('input[name="popup_postalcode"]').val();
	var street_number = $('input[name="popup_street_number"]').val();
	var street_name = $('input[name="popup_street_name"]').val();
	var city = $('input[name="popup_city"]').val();
	var province = $('input[name="popup_province"]').val();
	var housing_type = $('input[name="popup_housing_type"]').val();
	var housing_name = $('input[name="popup_housing_name"]').val();

	data = $(obj).attr('data-store');
	$.ajax({
		url : appHelper.baseURL+'/indexs/select-stores',
		type: 'POST',
		data:{
			data:data,
			disposition:disposition,
			postalcode : postalcode,
			street_number : street_number,
			street_name : street_name,
			city : city,
			province : province,
			housing_type : housing_type,
			housing_name : housing_name
		},
		success:function(data){
			if(data.error == 0)
			{
				//active add product
				$('.order-product-now-btn').attr('data-toggle', 'none')
			    $('.order-product-now-btn').bind('touchstart click',function(e){
			    	addCart($(this).attr('data-product-id'));
			    });
			    //change button text
			    $('.order-product-now-btn').each(function(){
			    	pid = $(this).attr('data-product-id');
			    	if($('#custom_'+pid).val() == 1)
			    	{
			    		$('span', $(this)).text('Custom options');	
			    	}
			    	else
			    	{
			    		$('span', $(this)).text('Order now');		
			    	}
			    	
			    });

				$('#myModal').modal('hide'); 
				
				//show products and options
				var product_id = $('#selecting_product_id').val();
				if(product_id != ''){
					addCart(product_id);
				}
				else{
					popupProduct();	
				}
			}            
		}        
	})

}
function popupProduct(){
	getCategoryList();
	getProduct('');
	$("#modalProduct").modal('show');
}
function getCategoryList(){
	$.get(appHelper.baseURL+'/indexs/get-category-list',function(data){
		if(data.error==0){
			var html = '<ul class="list-unstyled">';
			$.each(data.data,function(key,value){
				html+='<li><a onclick="getProduct(\''+value['name']+'\')" class="btn btn-lg btn-block button ph-primary-button">'+value['name']+'</a></li>'
			})
			html+='</ul>';
			$("#category_list").html(html);
		}else{
			alert(data.message)
		}
	});
}
function getProduct(name){
	$.ajax({
		url:appHelper.baseURL+'/indexs/get-product-by-category',
		type:"POST",
		data:{
			cate_name: name
		},
		success:function(data){
			if(data.error==0){
				ListCurrentProduct = data.data.product_list;
				var tag_list = data.data.tag_list;
				if(Object.keys(tag_list).length>1){
					var html = '';
					html+='<div class="btn-group" role="group">';
					html+=	'<button type="button" class="btn red-button active" onclick="loadProductByTag(\'all\')">All</button>';
					$.each(tag_list,function(key,value){
						html+='<button type="button" class="btn red-button" onclick="loadProductByTag(\''+value+'\')">'+value+'</button>';
					})
					html+='</div>';
					$("#product_tag").html(html);
					$("#product_grid").height($("#product_list").height()-$("#product_tag").height());
					loadProductByTag('all');
					productGridScroll();
				}else{
					$("#product_tag").html('<h3>Not Found Product</h3>');
					ListCurrentProduct = {};
					$("#product_grid").html('');
				}
					
			}
		}
	});
}

function loadProductByTag(tag){
	var html = '';
	$.each(ListCurrentProduct,function(key,value){
		if(tag=='all'){
			html+='<div class="col-md-4">';
			html+='	<div id="product-item-'+value.id+'" class="product_item">';
			html+='		<h3>'+value.name.toUpperCase()+'</h3>';
			html+='		<div class="product_item_img"> ';
			html+='			<img src="'+value.image+'" alt="'+value.name+'">';
			html+='		</div>';
			html+='		<div class="product_item_desc">';
			html+='			<span class="description_item">';
			html+='				'+value.description.replace(/<[^>]+>/ig,"");+'&nbsp;                       ';
			html+='			</span>';
			html+='			<span class="off_in_small">Starting from: </span>';
			html+='			<span class="product_item_price">$'+value.price+'</span>';
			html+='		</div>';
			html+='		<div class="add_to_cart" data-id="'+value.id+'" onclick="addCart(\''+value.id+'\')"> <i class="fa fa-plus"></i> </div>';
			html+='	</div>';
			html+='</div>';
		}else{
			if(value.tag == tag){
				html+='<div class="col-md-4">';
				html+='	<div id="product-item-'+value.id+'" class="product_item">';
				html+='		<h3>'+value.name.toUpperCase()+'</h3>';
				html+='		<div class="product_item_img"> ';
				html+='			<img src="'+value.image+'" alt="'+value.name+'">';
				html+='		</div>';
				html+='		<div class="product_item_desc">';
				html+='			<span class="description_item clear">';
				html+='				'+value.description.replace(/<[^>]+>/ig,"");+'&nbsp;                       ';
				html+='			</span>';
				html+='			<span class="off_in_small">Starting from: </span>';
				html+='			<span class="product_item_price">$'+value.price+'</span>';
				html+='		</div>';
				html+='		<div class="add_to_cart" data-id="'+value.id+'" onclick="addCart(\''+value.id+'\')"> <i class="fa fa-plus"></i> </div>';
				html+='	</div>';
				html+='</div>';
			}
		}
		$("#product_grid").html('<div class="scroller">'+html+'</div>');
		setHeightProductItem();
	})
}

function setHeightProductItem(){
	var max_height_product_item=0;
	$("#product_grid .col-md-4").each(function(key,elem){
		if(max_height_product_item<$(elem).height()){
			max_height_product_item = $(elem).height();
		}
	})
	$("#product_grid .col-md-4").height(max_height_product_item);

	var max_height_product_des=0;
	$("#product_grid .product_item_desc").each(function(key,elem){
		if(max_height_product_des<$(elem).height()){
			max_height_product_des = $(elem).height();
		}
	})
	$("#product_grid .product_item_desc").each(function(key,elem){
		$(this).css({
			"margin-top":max_height_product_des-$(this).height()
		})
	});
}

function productGridScroll(){
	if($("#product_grid").length  && $("#product_grid .col-md-4").length){
		var list_product_cart_scroll = new IScroll('#product_grid',{
			snap: '.col-md-4',
			mouseWheel: true,
		});
	}
}
</script>
