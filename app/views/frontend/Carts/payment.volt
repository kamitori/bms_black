{% if cart['main_total'] >0 %}
<div class="nav_cart row mobile-hide">
	<div class="col-xs-3 step complete" data-id="order_summary">
		<a href="/carts/check-out">
			<span>Order Summary</span>
			<span class="end"></span>
		</a>
	</div>
	<div class="col-xs-3 step complete" data-id="checkout">
		<span>Checkout</span>
		<span class="end"></span>
	</div>
	<div class="col-xs-3 step  complete last" data-id="details">
		<span>Details</span>
		<span class="end"></span>
	</div>
	<div class="col-xs-3 step here" data-id="payment">
		<span>Payment</span>
		<span class="end"></span>
	</div>
</div>
<div class="nav_cart mobile-show row">
    <div class="col-xs-12 step here" data-id="order_summary" style="width:100% !important;">
        <a href="/carts/check-out" style="display:block;width:100%;" class="text-center">
            <span>Order Summary</span>
        </a>
    </div>
    <div class="col-xs-12 step here text-center" data-id="order_summary" style="width:100% !important;margin-top:15px;">
    	<a href="/carts/detail" style="display:block;width:100%;" class="text-center">
            <span>Details</span>
        </a>
    </div>
</div> 
<div class="main_cart col-md-8" id="order_summary" style="margin-top:25px;">
	<form method="POST" action="{{baseURL}}/carts/processing" autocomplete="new-password" id="form_payment">
		<div class="row">
			<div class="col-xs-12">
				<div class="columns" style="margin-bottom:10px;border-bottom:1px dotted white;margin-right:30px">
					<h4 class="ng-binding" style="">
						YOUR PAYMENT INFORMATION
						<span class="ph-required ng-binding">* required</span>
					</h4>
				</div>
				<div class="col-md-12 text-center" style="margin-bottom:10px; color:red;">{{error_payment}}</div>
				<div class="columns col-xs-12" style="margin-bottom:20px;">                
					<input type="radio" name="method" value="cash" id="cash" checked class="payment"/>
					<label class="group-label ng-binding" for="cash" style="cursor:pointer">
						Cash
					</label>
				</div>
				<div class="columns col-xs-12" style="margin-bottom:20px;">                
					<input type="radio" name="method" value="card" id="card" class="payment"/>
					<label class="group-label ng-binding" for="card" style="cursor:pointer">
						Card (A card must be presented at time of payment)
					</label>
				</div>
				<div class="columns col-xs-12" style="margin-bottom:20px; display:none;" id="block-payment"> 
						<div class="col-md-4" style="margin-bottom:10px;"><label class="group-label ng-binding">Card type:</label></div>
						<div class="col-md-8" style="margin-bottom:10px;">
								<select id="cardtype" name="cardtype" class="form-control required_field" required="">
									<option value="VI">VISA</option>
									<option value="MA">MasterCard</option>
									<option value="AM">American Express</option>
									<option value="DI">Discover</option>
									<option value="JC">JCB</option>
								</select>
						</div>
						<div class="col-md-4" style="margin-bottom:10px;"><label class="group-label ng-binding">Name on card:</label></div>
						<div class="col-md-8" style="margin-bottom:10px;">
								<input type="text" id="nameoncard"   name="nameoncard" maxlength="64" autocomplete="new-password" class="form-control required_field" required=""/>
						</div>
						<div class="col-md-4" style="margin-bottom:10px;"><label class="group-label ng-binding">Card number:</label></div>
						<div class="col-md-8" style="margin-bottom:10px;">
								<input type="text" id="cardnumber" name="cardnumber" value="" autocomplete="new-password" onchange="CheckCardNumber(this)" class="form-control required_field" required=""/>
						</div>
						<div class="col-md-4" style="margin-bottom:10px;"><label class="group-label ng-binding">Expiry Date:</label></div>
						<div class="col-md-8" style="margin-bottom:10px;">
							   <div class="col-xs-2"><label class="group-label ng-binding">Month</label></div>
							   <div class="col-xs-4">
								   <select id="expmonth"  name="expmonth" class="form-control required_field" required="" autocomplete="new-password">
									   <option value="">--</option>
									   <option value="01">01</option>
									   <option value="02">02</option>
									   <option value="03">03</option>
									   <option value="04">04</option>
									   <option value="05">05</option>
									   <option value="06">06</option>
									   <option value="07">07</option>
									   <option value="08">08</option>
									   <option value="09">09</option>
									   <option value="10">10</option>
									   <option value="11">11</option>
									   <option value="12">12</option>
								   </select>
							   </div>
							   <div class="col-xs-2"><label class="group-label ng-binding">Year</label></div>
							   <div class="col-xs-4">
									<select id="expyear"  name="expyear"  class="form-control required_field" required="" autocomplete="new-password">
										<option value="">--</option>
									</select>
								 </div>
						</div>
						<div class="col-md-4" style="margin-bottom:10px;"><label class="group-label ng-binding">CVD:</label></div>
						<div class="col-md-8" style="margin-bottom:10px;">
								<input type="password" id="cvd"  name="cvd"  maxlength="16" autocomplete="new-password" class="form-control required_field" required=""/>
						</div>
						<div class="col-md-12 text-center" id="error_check_payment" style="margin-bottom:10px; color:red;"></div>
				</div>
				

				<div class="columns col-xs-12" style="margin-bottom:20px;padding:0">
					<div class="col-md-4">
						<a class="btn btn-lg btn-success btn-block uppercase" href="/carts/detail">Change Details</a>
					   
					</div>
					<div class="col-md-5">
						<a class="btn btn-lg btn-danger btn-block uppercase" href="/"> Continue shopping</a>
					</div>
					<div class="col-md-3">
						<input type="button" onclick="submitPayment()" value="Place order" class="btn btn-lg btn-danger btn-block uppercase">
					</div>
				</div>

			</div>
		</div>
	</form>
</div>
<div class="col-md-4" style="margin-top:25px;">
	<div class="col-xs-12 bg-std" style="font-size:21px;">
		<div class="columns col-xs-12" style="border-bottom:1px dotted white;padding-left:0">
			<h4 class="ng-binding" style="padding-top:10px;color:red;text-align:left;">
				ORDER SUMMARY
			</h4>
		</div>
		{% set max_item = 0 %}
		{% for item in cart['items'] %}
			{% set max_item = max_item + 1 %}
			{% if max_item > 7 %} 
				<div class="columns col-xs-12 row" style="color:white;text-align:center;padding-top:10px;padding-bottom:10px;">
					<a href="/carts/check-out"> See more ...</a>
				</div>
				{% break %}
			{% else %}
				<div class="columns col-xs-12 row" style="color:white;padding-right:0;padding-left:0;padding-top:10px;">
					<div class="col-md-7 col-xs-12" style="line-height:25px;height:25px;overflow:hidden" title="{{item['name']}} ({{item['quantity']}})">
						{{item['name']}} ({{item['quantity']}})
					</div>
					<div class="col-md-5 col-xs-12" style="text-align:right;vertical-align:middle;color:yellow;padding-bottom:15px;">
						{{dinhdangtien(item['sell_price'] * item['quantity'])}}
					</div>
				</div>
			{% endif %}
		{% endfor %}
		<div class="columns col-xs-12" style="padding-top:10px;color:white;border-top:1px dotted white;padding-left:0">
			<div class="col-xs-7" style="line-height:30px;padding-right:0;padding-left:0">
				Subtotal
			</div>
			<div class="col-xs-5" style="text-align:right;vertical-align:middle;padding-top:10px;color:yellow">
				{{dinhdangtien(cart['total'])}}
			</div>
		</div>

		<div class="columns col-xs-12" style="padding-top:10px;color:white;padding-left:0">
			<div class="col-xs-7" style="line-height:30px;padding-left:0">
				Tax
			</div>
			<div class="col-xs-5" style="text-align:right;vertical-align:middle;padding-top:10px;color:yellow">
				{{dinhdangtien(cart['tax'])}}
			</div>
		</div>
		<div class="columns col-xs-12 row" style="padding-top:10px;color:white;padding-right:0;padding-left:0">
            <div class="col-xs-7" style="line-height:30px">
                Delivery fee
            </div>
            <div class="col-xs-5" id="shipping_fee" style="text-align:right;vertical-align:middle;padding-top:10px;color:yellow">
                {{dinhdangtien(cart['delivery_cost'])}}
            </div>
        </div>
		<div class="columns col-xs-12" style="padding-top:10px;color:white;;padding-bottom:40px;padding-left:0">
			<div class="col-xs-7" style="line-height:30px;padding-left:0">
				Your Total
			</div>
			<div class="col-xs-5" style="text-align:right;vertical-align:middle;padding-top:10px;color:yellow">
				{{dinhdangtien(cart['main_total'])}}
			</div>
		</div>

	</div>
</div>
<script type="text/javascript">
	var select = document.getElementById('expyear');
	var min = parseInt(new Date().getFullYear());
	var max = min +10;
	for (var i = min; i<=max; i++){
		var opt = document.createElement('option');
		opt.value = i-2000;
		opt.innerHTML = i;
		select.appendChild(opt);
	}

	function CheckCardNumber(obj)
	{
		if(document.getElementById("card").checked){
			var type = document.getElementById("cardtype").value;
			switch(type){
					case "VI":
							var re16digit=/^(?:4[0-9]{12}(?:[0-9]{3})?)$/;
						if (!re16digit.test(obj.value)){
						alert("Not a valid Visa card number!");
							return false;
						}else{
							return true;
						}
						break;
					case "MA":
						var re16digit =/^(?:5[1-5][0-9]{2}|222[1-9]|22[3-9][0-9]|2[3-6][0-9]{2}|27[01][0-9]|2720)[0-9]{12}$/; 
						if (!re16digit.test(obj.value)){
						alert("Not a valid Mastercard card number!");
							return false;
						}else{
							return true;
						}
						break;
					case "AM":
						var re16digit=/^(?:3[47][0-9]{13})$/;  
						if (!re16digit.test(obj.value)){
						alert("Not a valid Amercican Express card number!");
							return false;
						}else{
							return true;
						}
						break;
					case "DI":
						var re16digit=/6(?:011|5[0-9]{2})[0-9]{12}/;  
						if (!re16digit.test(obj.value)){
						alert("Not a valid Discover card number!");
							return false;
						}else{
							return true;
						}
						break;
					case "JC":
						var re16digit=/(?:2131|1800|35\d{3})\d{11}/;  
						if (!re16digit.test(obj.value)){
						alert("Not a valid JCB card number!");
							return false;
						}else{
							return true;
						}
						break;
						default:
						var re16digit=/^\d{16}$/
						if (!re16digit.test(obj.value)){
						alert("Please enter your 16 digit credit card numbers");
							return false;
						}else{
							return true;
						}
						break;
			}
		}		
	}
	function ValidateForm(obj){
		var a = CheckCardNumber(document.getElementById("cardnumber"));
		if(a){
							if (obj.checkValidity()) {
								obj.submit();
							}else{
								document.getElementById("error_check_payment").textContent = "Please fill in all fields!";
							}
		}else{
			return false;
		}
	}
</script>
{% else %}
	<div class="row welcome ng-scope">
		<div class="col-md-8">
			<h1 class="text-left ng-binding ng-scope">Hey There!</h1>
			<div>
				<p class="text-left ng-binding ng-scope">
					Your cart is empty. Start shopping now !
				</p>
			</div>
		</div>
		<div class="col-md-4 store-button ng-scope">
			<a class="button ph-primary-button button-welcome ng-binding" href="/banh-mi-subs">
				Start Your Order
			</a>
		</div>
	</div>
{% endif %}
