{% if cart['main_total'] > 0 %}
<div class="nav_cart row mobile-hide">
    <div class="col-xs-3 step complete" data-id="order_summary">
        <a href="/carts/check-out">
            <span>Order Summary</span>
            <span class="end"></span>
        </a>
    </div>
    <div class="col-xs-3 step complete last" data-id="checkout">
        <span>Checkout</span>
        <span class="end"></span>
    </div>
    <div class="col-xs-3 step here " data-id="details">
        <span>Details</span>
        <span class="end"></span>
    </div>
    <div class="col-xs-3 step" data-id="payment">
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
            <span>Details</span>
    </div>
</div> 
<div class="main_cart col-md-8" id="order_summary" style="margin-top:25px;">
    <div class="row" style="">
        <!-- <div class="col-xs-12"> -->
            <div class="columns col-xs-12 row" style="margin-bottom:20px;border-bottom:1px dotted white;">
                <h4 class="ng-binding" style="font-size:23px;color:#A03123;text-align:left;">
                    Your Details
                    <span class="ph-required ng-binding">* required</span>
                </h4>
            </div>
            <div class="columns col-xs-12 row" style="margin-bottom:20px;">
                <div class=" col-md-4 col-xs-12" style="line-height:30px">
                    <label class="group-label ng-binding">
                        Full name <span class="ph-required">*</span>
                    </label>
                </div>
                <div class="col-md-8 col-xs-12">
                    <div class="control-group row ng-scope">
                        <div class="fld-ctr columns">
                            <input type="text" placeholder="Your name" required name="order_user_name" id="order_user_name" class="form-control required_field" value="{% if data['name'] is defined and bospace(data['name'])!=' ' and data['name']!=null %}{{data['name']}}{% elseif contact_addresses['full_name'] is defined %}{{contact_addresses['full_name']}}{% endif %}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="columns col-xs-12 row" style="margin-bottom:20px;">
                <div class=" col-md-4 col-xs-12" style="line-height:30px">
                    <label class="group-label ng-binding">
                        Email Address <span class="ph-required">*</span>
                    </label>
                </div>
                <div class="col-md-8 col-xs-12">
                    <div class="control-group row ng-scope">
                        <div class="fld-ctr columns">
                            <input type="text" placeholder="Your email" required name="order_user_email" id="order_user_email" class="form-control required_field" value="{% if data['email'] is defined and bospace(data['email'])!=' ' and data['email']!=null %}{{data['email']}}{% elseif contact_addresses['emails'] is defined %}{{contact_addresses['emails']}}{% endif %}">
                            <span class="error_email" style="display:block; color:yellow;"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="columns col-xs-12 row" style="margin-bottom:20px;">
                <div class=" col-md-4 col-xs-12" style="line-height:30px">
                    <label class="group-label ng-binding">
                        Phone number <span class="ph-required">*</span>
                    </label>
                </div>
                <div class="col-md-8 col-xs-12">
                    <div class="control-group row ng-scope">
                        <div class="fld-ctr columns">
                            <input type="text" placeholder="xxx-xxx-xxxx" required name="order_phone" id="order_phone" class="form-control required_field" value="{% if data['phone'] is defined and bospace(data['phone'])!=' ' and data['phone']!=null %}{{data['phone']}}{% elseif contact_addresses['phone'] is defined %}{{contact_addresses['phone']}}{% endif %}" maxlength="12">
                            <span class="error_phone" style="display:block; color:yellow;"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="columns col-xs-12 row" style="margin-bottom:20px;">
                <div class=" col-md-4 col-xs-12" style="line-height:30px">
                    <label class="group-label ng-binding">
                        Special note
                    </label>
                </div>
                <div class="col-md-8 col-xs-12">
                    <div class="control-group row ng-scope">
                        <div class="fld-ctr columns">
                            <textarea placeholder="xxxxxxxxxx" required name="notes" id="notes" class="form-control" style="height:75px;max-width:100%">{% if data['note'] is defined and bospace(data['note'])!=' ' and data['note']!=null %}{{removespace(data['note'])}}{% elseif contact_addresses['note'] is defined %}{{contact_addresses['note']}}{% endif %}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="columns col-xs-12 row" style="margin-bottom:20px;border-bottom:1px dotted white;">
                <h4 class="ng-binding" style="font-size:23px;color:#A03123;text-align:left;">
                    Would you like to ?
                </h4>
            </div>
            <div class="columns col-xs-12 row" style="margin-bottom:20px;">
                <div class="columns col-md-6 row" style="color:white;">
                    <input type="radio" name="pickup_option" value="pickup" checked id="pickup" onchange="changeOption(this)" /> <label style="cursor:pointer;color:white" for="pickup" >Pickup order </label>
                    <div class='input-group date' id='delivery_date_input' style='width:200px;'>
                        <input type='text' class="form-control required_field" name="delivery_date" id="delivery_date" style="pointer-events:none;"/>
                        <span class="input-group-addon">
                            <span class="fa fa-clock-o"></span>
                        </span>
                    </div>
                </div>
                <div class="columns col-md-6 row" style="color:white;">
                    <input onchange="changeOption(this)" type="radio" name="pickup_option" value="delivery"  id="delivery"  /> <label for="delivery" style="cursor:pointer;color:white" > Delivery order to</label>
                    <select id="user_address_list" class="form-control" onchange="changeAddressDetail(this)">
                        {% for address in arr_user_address %}
                            <option data-key='{{address['key']}}' value='{{address['json']}}'>{{address['text']}}</option>
                        {% endfor %}
                    </select>
                </div>
            </div>
            <div class="columns col-xs-12 row address_field" style="margin-bottom:20px;border-bottom:1px dotted white;display:none">
                <h4 class="ng-binding" style="font-size:23px;color:#A03123;text-align:left;">
                    Address Details
                    <span class="ph-required ng-binding">* required</span>
                </h4>
            </div>            
            <input type="hidden" name="key_address" id="key_address">
            <div class="columns col-xs-12 row address_field" style="margin-bottom:20px;display:none">
                <div class=" col-md-4 col-xs-12" style="line-height:30px">
                    <label class="group-label ng-binding">
                        Postal code <span class="ph-required">*</span>
                    </label>
                </div>
                <div class="col-md-8 col-xs-12">
                    <div class="control-group row ng-scope">
                        <div class="fld-ctr columns">
                            <input type="text" onkeypress="getAddress(this)" placeholder="Postal code" required name="postalcode" id="postal_code" class="form-control address_fields" value="{% if datas['postalcode'] is defined and bospace(datas['postalcode'])!=' ' and datas['postalcode']!=null %}{{datas['postalcode']}}{% elseif contact_addresses['zip_postcode'] is defined %}{{contact_addresses['zip_postcode']}}{% endif %}">
                            <div class="address_suggest">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="columns col-xs-12 row address_field" style="margin-bottom:20px;display:none">
                <div class=" col-md-4 col-xs-12" style="line-height:30px">
                    <label class="group-label ng-binding">
                        Street address <span class="ph-required">*</span>
                    </label>
                </div>
                <div class="col-md-8 col-xs-12">
                    <div class="control-group row ng-scope">
                        <div class="fld-ctr columns">
                            <input type="text" onchange="changeStreetAddress()" placeholder="Street address" required name="address_1" id="address_1" class="form-control address_fields" value="{% if datas['address_1'] is defined and bospace(datas['address_1'])!=' ' and datas['address_1']!=null %}{{datas['address_1']}}{% elseif contact_addresses['address_1'] is defined %}{{ contact_addresses['address_1'] }}{% endif %}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="columns col-xs-12 row address_field" style="margin-bottom:20px;display:none">
                <div class=" col-md-4 col-xs-12" style="line-height:30px">
                    <label class="group-label ng-binding">
                        City <span class="ph-required">*</span>
                    </label>
                </div>
                <div class="col-md-8 col-xs-12">
                    <div class="control-group row ng-scope">
                        <div class="fld-ctr columns">
                            <input type="text" onchange="changeStreetAddress()" placeholder="City" required name="city" id="city" class="form-control address_fields" value="{% if datas['city'] is defined and bospace(datas['city'])!=' ' and datas['city']!=null %}{{datas['city']}}{% elseif contact_addresses['town_city'] is defined %}{{contact_addresses['town_city']}}{% endif %}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="columns col-xs-12 row address_field" style="margin-bottom:20px;display:none">
                <div class=" col-md-4 col-xs-12" style="line-height:30px">
                    <label class="group-label ng-binding">
                        Province <span class="ph-required">*</span>
                    </label>
                </div>
                <div class="col-md-8 col-xs-12">
                    <div class="control-group row ng-scope">
                        <div class="fld-ctr columns">
                            <select placeholder="Province" onchange="changeStreetAddress()" required name="province" id="province" class="form-control address_fields">
                            {% for province in arr_province %}
                                <option value="{{province['key']}}" data-name="{{province['name']}}" {% if datas['province'] is defined and bospace(datas['province'])!=' ' and datas['province']!=null and datas['province']==province['name']%} selected="selected"{% elseif contact_addresses['province_state'] is defined and contact_addresses['province_state'] == province['name']%} selected="selected" {% endif %}>{{province['name']}}</option>
                            {% endfor %}
                            </select>
                        </div>
                    </div>
                </div>
            </div>    
            <div class="columns col-xs-12 row" style="margin-bottom:20px;border-bottom:1px dotted white;">&nbsp;</div>        
            <div class="columns col-xs-12 row" style="margin-bottom:20px;">
                <div class=" col-md-4 col-xs-12" style="line-height:30px">
                    <label class="group-label ng-binding">
                        Captcha <span class="ph-required">*</span>
                    </label>
                </div>
                <div class="col-md-8 col-xs-12">
                    <div class="control-group row ng-scope">
                        <div class="fld-ctr columns">
                            <p><canvas id="canvas_captcha" width="400"></canvas> <button class="btn btn-xs" title="Renew captcha code" onclick="createCaptcha()" type="button"><i class="fa fa-refresh"></i></button></p>
                            <input type="text" placeholder="Captcha code" required name="captcha_code" id="captcha_code" class="form-control required_field" maxlength="5">
                            <span class="error_captcha" style="display:block; color:yellow;"></span>
                        </div>
                    </div>
                </div>
            </div>
            {% if is_login == 0 %}

                <div class="columns col-xs-12 row" style="margin-bottom:20px;border-bottom:1px dotted white;">
                    <h4 class="ng-binding" style="font-size:23px;color:#A03123;text-align:left;">
                        <label style="color:#A03123;cursor:pointer" for="create_account" > Create an account or Sign in</label>  <input checked onchange="create_account(this)" type="checkbox" id="create_account" />
                    </h4>
                    <h3 id="error_messages" style="color:red"></h3>
                </div>
                <div class="columns col-xs-12 row" style="margin-bottom:20px;" id="create_account_field">
                    <div class=" col-md-4 col-xs-12" style="line-height:30px">
                        <label class="group-label ng-binding">
                            Password <span class="ph-required">*</span>
                        </label>
                    </div>
                    <div class="col-md-8 col-xs-12">
                        <div class="control-group row ng-scope">
                            <div class="fld-ctr columns">
<!--                                 <input type="text" style="visibility:hidden;">
                                <input type="password" style="visibility:hidden;">
                                <input type="text" style="display:none">
                                <input type="password" style="display:none"> -->

                                <input type="password" placeholder="Your password" required name="password" id="password" class="form-control required_field_account" value="">
                            </div>
                        </div>
                    </div>
                </div>

            {% endif %}

            <div class="columns col-xs-12 row" style="margin-bottom:20px;">
                <div class=" col-md-4 col-xs-12" style="line-height:30px">
                    <label class="group-label ng-binding">
                        &nbsp;
                    </label>
                </div>
                <div class="col-md-5 col-xs-12" style="margin-bottom:10px;">
                    <a class="btn btn-lg btn-danger btn-block uppercase" href="/"> Continue shopping</a>                    
                </div>
                <div class="col-md-3 col-xs-12">
                    <div class="control-group row ng-scope">
                        <div class="fld-ctr columns">
                            <input type="button" value="Process" onclick="processNext()" class="btn btn-lg btn-danger btn-block uppercase">
                        </div>
                    </div>
                </div>
            </div>
        <!-- </div> -->
    </div>
</div>
<div class="col-md-4 " style="margin-top:25px;">
    <div class="bg-std col-xs-12" style="font-size:21px;">
        <div class="columns col-xs-12 row" style="margin-bottom:10px;border-bottom:1px dotted white;">
            <h4 class="ng-binding" style="font-size:23px;color:#A03123;text-align:left;">
                Order Summary
            </h4>
        </div>
        {% set max_item = 0 %}
        {% for item in cart['items'] %}
            {% set max_item = max_item + 1 %}
            {% if max_item > 7 %} 
                <div class="columns col-xs-12 row" style="color:white;text-align:center;padding-top:10px;">
                    <a href="/carts/check-out"> See more ...</a>
                </div>
                {% break %}
            {% else %}
                <div class="columns col-xs-12 row" style="color:white;padding-right:0;padding-left:0">
                    <div class="col-md-7 col-xs-12" style="line-height:25px;height:25px;overflow:hidden" title="{{item['name']}} ({{item['quantity']}})">
                        {{item['name']}} ({{item['quantity']}})
                    </div>
                    <div class="col-md-5 col-xs-12" style="text-align:right;vertical-align:middle;color:yellow;padding-bottom:15px;">
                        {{dinhdangtien(item['sell_price'] * ( item['quantity'] - item['quantity_promo']) )}}
                    </div>
                </div>
            {% endif %}
        {% endfor %}
        <div class="columns col-xs-12 row"> &nbsp; </div>
        <div class="columns col-xs-12 row" style="padding-top:10px;color:white;border-top:1px dotted white;;padding-right:0;padding-left:0">
            <div class="col-md-7 col-xs-12" style="line-height:30px">
                Subtotal
            </div>
            <div class="col-md-5 col-xs-12" style="text-align:right;vertical-align:middle;padding-top:10px;color:yellow">
                {{dinhdangtien(cart['total'])}}
            </div>
        </div>
        <div class="columns col-xs-12 row"> &nbsp; </div>
        <div class="columns col-xs-12 row" style="padding-top:10px;color:white;border-top:1px dotted white;;padding-right:0;padding-left:0">
            <div class="col-md-7 col-xs-12" style="line-height:30px">
                Discount
            </div>
            <div class="col-md-5 col-xs-12" style="text-align:right;vertical-align:middle;padding-top:10px;color:yellow">
                 - {{dinhdangtien(total_discount)}}
            </div>
        </div>
        <div class="columns col-xs-12 row" style="padding-top:10px;color:white;padding-right:0;padding-left:0">
            <div class="col-md-7 col-xs-12" style="line-height:30px">
                Tax
            </div>
            <div class="col-md-5 col-xs-12" style="text-align:right;vertical-align:middle;padding-top:10px;color:yellow">
                {{dinhdangtien(cart['tax'])}}
            </div>
        </div>
        <div class="columns col-xs-12 row" style="padding-top:10px;color:white;padding-right:0;padding-left:0">
            <div class="col-md-7 col-xs-12" style="line-height:30px">
                Delivery fee
            </div>
            <div class="col-md-5 col-xs-12" id="shipping_fee" style="text-align:right;vertical-align:middle;padding-top:10px;color:yellow">
                {{dinhdangtien(cart['delivery_cost'])}}
            </div>
        </div>
        <div class="columns col-xs-12 row" style="padding-top:10px;color:white;padding-bottom:10px;padding-right:0;padding-left:0">
            <div class="col-md-7 col-xs-12" style="line-height:30px">
                Your Total
            </div>
            <div class="col-md-5 col-xs-12" id="main_total" style="text-align:right;vertical-align:middle;padding-top:10px;color:yellow">
                {{dinhdangtien(cart['main_total'])}}
            </div>
        </div>

    </div>
</div>

<div class="col-md-4 " style="margin-top:15px;">
    <!-- <div class="bg-std col-xs-12" style="font-size:21px;"> -->
        <!-- <div class="columns col-xs-12 row" style="margin-bottom:20px;border-bottom:1px dotted white;">
            <h4 class="ng-binding" style="font-size:23px;color:#A03123;text-align:left;">
                Would you like to ?
            </h4>
        </div>

        <div class="columns col-xs-12 row" style="color:white;padding-bottom:20px">
            <input type="radio" name="pickup_option" value="pickup" checked id="pickup" onchange="changeOption(this)" /> <label style="cursor:pointer;color:white" for="pickup" >Pickup order </label>
        </div>
        <div class="columns col-xs-12 row" style="padding-top:10px;color:white;padding-bottom:20px; display:none">
            <input onchange="changeOption(this)" type="radio" name="pickup_option" value="delivery"  id="delivery"  /> <label for="delivery" style="cursor:pointer;color:white" > Delivery order</label>
        </div>
        <div class="columns col-xs-12 row" style="padding-top:10px;color:white;padding-bottom:20px">
            <div class='input-group date' id='delivery_date_input'>
                <input type='text' class="form-control required_field" name="delivery_date" id="delivery_date" style="pointer-events:none;"/>
                <span class="input-group-addon">
                    <span class="fa fa-clock-o"></span>
                </span>
            </div>
        </div> -->
    <!-- </div> -->
</div>

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
