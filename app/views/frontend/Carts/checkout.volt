{% if cart['main_total']>0 %}
	<div class="nav_cart row mobile-hide">
		<div class="col-xs-3 step here" data-id="order_summary">
			<a href="/carts/check-out">
				<span>Order Summary</span>
				<span class="end"></span>
			</a>
		</div>
		<div class="col-xs-3 step" data-id="checkout">
			<span>Checkout</span>
			<span class="end"></span>
		</div>
		<div class="col-xs-3 step" data-id="details">
			<span>Details</span>
			<span class="end"></span>
		</div>
		<div class="col-xs-3 step" data-id="payment">
			<span>Payment</span>
			<span class="end"></span>
		</div>
	</div>
	<div class="nav_cart mobile-show">
		<div class="col-xs-12 step here" data-id="order_summary" style="width:100% !important;">
			<a href="/carts/check-out" style="display:block;width:100%;" class="text-center">
				<span>Order Summary</span>
			</a>
		</div>
	</div> 
	<div>&nbsp;</div>
	<div class="main_cart mobile-hide" id="order_summary">
		<div id="main_cart">
			<div class="row" id="head">
				<div class="col-xs-6">
					<div class="col-xs-5 text-justify">
						<a href="/" style="font-size: 18px;">Continue shopping</a>
					</div>
				</div>
				<div class="col-xs-6" style="line-height: 45px;">				
					<div class="col-xs-2 text-justify" style="font-size: 18px;">Or</div>
					<div class="col-xs-6 col-xs-offset-4 text-right"><button class="btn btn-lg btn-danger btn-block uppercase" onclick="gotoDetail()">Checkout</button></div>	
				</div>
			</div>
			<div>&nbsp;</div>
			<div class="row" id="list_product">
				{% set comid = -1 %}
				{% set uid = -1 %}
				{% set promo_quantity = false %}
				{% for index,item in items['items'] %}
					{% if item['quantity_promo'] is defined and item['quantity_promo'] >0 %}
						{% set promo_quantity = true %}
					{% endif %}
				    {% if item['combo_id'] is defined and comid != item['combo_id'] %}
				    {% set comid = item['combo_id'] %}
				    <div class="cartbox_item col-xs-12 combo_option" style="background-color:#d8b6b2">
				        <div class="cartbox_des col-xs-6">
				            <div class="cartbox_img"><h3>YOUR COMBO {{item['combo_id']+1}}</h3></div>
				            <div class="cartbox_name"><h3></h3></div>
				        </div>
				        <div class="cartbox_qty col-xs-3">
				            <div class="qtybox">
				                <button class="btn down mainbt" onclick="qtyAddCombo(0,'{{index}}',{{item['combo_id']}})">-</button>
				                <input class="popup_qtycombo" id="popup_qtycombo_{{index}}" type="text" value="{{ combo_list[item['combo_id']]['quantity'] }}" style="width: 55px;">
				                <button class="btn up mainbt" onclick="qtyAddCombo(1,'{{index}}',{{item['combo_id']}})">+</button>
				            </div>
				            <button style="font-size:1.3em"  class="btn mainbt btedit" onclick="deleteCombo('{{item['combo_id']}}')">Delete</button>
				            <!-- <button class="btn up mainbt btedit" style="color:#111;" onclick="editCombo('{{item['combo_id']}}')">More</button> -->
				        </div>
				        <div class="cartbox_price col-xs-3">
				            $<span  id="price_fc_{{index}}"><?php echo number_format((double)$combo_list[$item['combo_id']]['total'],2) ?></span>
				        </div>
				    </div>
				    {% endif %}

				    {% if item['user_id'] is defined and uid != item['user_id'] %}
				    {% set uid = item['user_id'] %}
				    <div class="item cartbox_item col-xs-12 user_group_box">
				        <div class="col-xs-6 cartbox_des">
				            <h3 style="text-align:left">
				                {% if item['user_id'] is defined and user_list[item['user_id']] is defined %}
				                    {{user_list[item['user_id']]}}
				                {% endif %}
				            </h3>
				        </div>
				        <div class="col-xs-3 cartbox_qty">
				            <button style="font-size:1em; float:right"  class="btn mainbt btedit" onclick="deleteGroupByUser('{{item['user_id']}}')">Delete</button>
				            <button class="btn mainbt btedit" style="color:#111; float:right" onclick="changeGroupByUser('{{index}}','{{item['user_id']}}')">More</button>
				        </div>
				        <div class="col-xs-3 cartbox_price">
				            $<span class="group_total"  id="price_fc_{{index}}" {% if item['user_id'] is defined %}data-user-id="{{item['user_id']}}"{% endif %}>
				                {% if item['user_id'] is defined and user_data[item['user_id']]['total'] is defined %}
				                    {{user_data[item['user_id']]['total'] }}
				                {% endif %}
				            </span>
				            {% set show_discount_text = 'none' %}
				            {% if item['user_id'] is defined and user_data[item['user_id']]['discount'] is defined and user_data[item['user_id']]['discount'] > 0 %}
				            	{% set show_discount_text = 'block' %}
				            {% endif %}
				            <div class="group_discount" data-user-id="{{item['user_id']}}" style="display:{{show_discount_text}}"><span style="color:#111; font-size:14px">(discount {{user_data[item['user_id']]['discount']*100}}%)</span></div>
				        </div>
				    </div>
				    {% endif %}

					{% if item['combo_id'] is defined %}
					    <div class="col-xs-4 combo_option" id="cartbox_{{index}}" style="padding:1%;">
					        <div class="cartbox_des combo_des">
					            <div class="cartbox_img combo_img">
					                <div class="combo_imgbox">
					                {% if item['image'] is empty %}
					                    <img src="{{ baseURL }}/{{ theme }}/images/default.png" alt="{{ item['name'] }}" />
					                {% else %}
					                    <img src="{{ item['image'] }}" alt="{{ item['name'] }}" />
					                {% endif %}
					                </div>
					                <p><button class="btn up btedit" style="color:#111;" onclick="changeCombo('{{index}}','{{item['combo_step']}}')">Change</button></p>
					            </div>
					            <div class="cartbox_name combo_name">
					                <h3>{{ item['name'] }}</h3>
					                {% if item['options']|length >0 %}
					                    {% for opp in item['options'] %}
					                        {% if opp['is_change'] is defined and opp['is_change']==1 %}
					                            <p data-idp="{{opp['_id']}}">
					                            {% if opp['group_type'] is defined and opp['group_type']!='Exc' and opp['isfinish']==0 %}
					                                <b>{{opp['quantity']}}</b>:
					                            {% endif %}
					                            {{opp['name']}}
					                            {% if opp['isfinish']==1 %}
					                                <b>({{finish_option[opp['_id']][opp['finish']]}})</b>
					                            {% endif %}
					                            </p>
					                        {% endif %}
					                    {% endfor %}
					                {% endif %}
					                <div class="sell_price">
					                    Unit price: <b>$<?php echo number_format((double)$item['sell_price'],2) ?></b>
					                </div>
					                <div class="cart_note">
					                    {% if item['note']!='' %}<b><i>Note:</i></b> <span id="note_product_{{index}}">{{item['note']}}</span>{% endif %}
					                </div>
					                <div class="cart_quantity">
					                    {% if item['quantity']!='' %}
					                    	<div style="font-size:2em; color:#A03123; margin-top:20px;">x  {{item['quantity']}} </div>
					                    {% endif %}
					                </div>
					                <div class="cart_total">
					                    {% if item['total']!='' %}
					                    	<div style="color:#FFF; margin-top:20px;">
					                    		Total: $<?php echo number_format((double)$item['total'],2); ?>
					                    	</div>
					                    {% endif %}
					                </div>
					                <div class="description_item" style="display:none">{{ item['description']}}</div>
					            </div>
					        </div>
					        <div class="cartbox_qty" style="display:none;">
					            <div class="qtybox">
					                <button class="btn down mainbt" onclick="downQty('fc_{{index}}','update_cart')">-</button>
					                <input class="popup_qty" type="text" value="{{ item['quantity'] }}" id="fc_{{index}}" style="width: 55px;">
					                <button class="btn up mainbt" onclick="upQty('fc_{{index}}','update_cart')">+</button>
					            </div>
					           
					        </div>
					        <div class="cartbox_price" style="display:none;">
					        </div>
					    </div>
					{% else %}			
						<div class="item" id="cartbox_{{index}}">
							<div class="col-xs-2 image">
								<img class="img-thumb" src="{{item['image']}}" alt="{{item['description']}}">
							</div>
							<div class="col-xs-4">
								<p>
									<span class="uppercase name" style="color:#fff" >
										{{item['name']}}
										{% if item['is_banhmisub_category'] and item['is_11_inch'] %}
											<br /><br />11" BANHMI SUBS
										{% endif %}
									</span>
									<span class="hidden description">{{item['description']}}</span></p>
								<div class="options"> 
								
				                {% if item['options']|length >0 %}
				                    {% for opp in item['options'] %}
				                        {% if opp['is_change'] is defined and opp['is_change']==1 %}
				                            <p data-idp="{{opp['_id']}}">
				                            {% if opp['group_type'] is defined and opp['group_type']!='Exc' and opp['isfinish']==0 %}
				                                <b>{{opp['quantity']}}</b>:
				                            {% endif %}
				                            {{opp['name']}}
				                            {% if opp['isfinish']==1 %}
				                                <b>({{finish_option[opp['_id']][opp['finish']]}})</b>
				                            {% endif %}
				                            </p>
				                        {% endif %}
				                    {% endfor %}
				                {% endif %}
				                </div>
				                <p><span class="note">{% if item['note'] is defined and item['note'] != "" %}({{item['note']}}){% endif %}</span></p>
							</div>
							<div class="col-xs-2 text-left price_one price sell_price">
								{{dinhdangtien(item['sell_price'])}}
							</div>
							<div class="col-xs-2 quantity">
								QTY
								<br/>
								<div class="input_number">
									<input type="text" name="" onchange="updateQuantity('{{index}}',this)" value="{{item['quantity']}}" maxlength="2" />
									<span class="spinner">
										<i class="fa fa-caret-up up"></i>
										<i class="fa fa-caret-down down"></i>
									</span>
								</div>
								<br/>
								<a href="{{baseURL}}/carts/remove-link-item/{{index}}">Delete</a>
								&nbsp;&nbsp;&nbsp;
								<a href="#" onclick="editCart('{{index}}','{{item['_id']}}')">Edit</a>
							</div>
							<div class="col-xs-2 text-right price" id="{{index}}" {% if item['user_id'] is defined %}data-user-id="{{item['user_id']}}"{% endif %}>
								{{dinhdangtien(item['sell_price']* (item['quantity'] - item['quantity_promo']) )}}
							</div>
						</div>
					{% endif %}		
				{% endfor %}		
			</div>			
			<div id="current_promo" class="col-xs-12 text-left uppercase row" style="padding-bottom:20px;border-bottom: 1px solid;">
				{% if promo_quantity %}
					<h2 style="margin-bottom:20px;">THE FOLLOWING ITEM IS FREE DUE TO PROMO EVENTS</h2>
					{% for index,item in items['items'] %}
						{% if item['quantity_promo'] > 0 %}
							<div class="item col-md-12 col-xs-12 col-lg-12" style="margin-bottom:10px;">
								<div class="col-xs-2 image">
									<img class="img-thumb" src="{{item['image']}}" alt="{{item['description']}}">
								</div>
								<div class="col-xs-4">
									<p><span class="uppercase name" style="color:#fff">{{item['name']}}</span><span class="hidden description">{{item['description']}}</span></p>
									<div class="options">               				                        				                    				                        				                    				                        				                    				                        				                    				                        				                    				                        				                    				                        				                    				                        				                    				                        				                    				                        				                    				                        				                    				                        				                    				                        				                    				                				                </div>
					                <p><span class="note"></span></p>
								</div>
								<div class="col-xs-2 text-left price_one price sell_price">
									{{item['sell_price']}}							</div>
								<div class="col-xs-2 quantity">
									{{item['quantity_promo']}}
								</div>
								<div class="col-xs-2 text-right price">
									{{item['sell_price']}}						</div>
							</div>
						{% endif %}
					{% endfor %}
					<br/><br/>
					<small style="color:red">*Note: Price of these items already exclude on your orders</small>
					<br />
					<small style="color:red">*Note: Apply only for 11" banhmisub and Sate Flat Bread</small>
				{% endif %}				
			</div>			
			<!-- <div id="random_product" style="height:1px;padding: 0; ">
				&nbsp;
			</div> -->

			<div  class="row" id="total">
				<div class="col-xs-12 line" style="font-size:1.2em;">
					<div class="col-xs-6 text-left uppercase" style="padding-bottom:20px;">
						Subtotal
					</div>
					<div class="col-xs-6 text-right"  style="padding-bottom:20px;" id="sub_total">
						{{dinhdangtien(items['total'])}}
					</div>

					<div class="col-md-9 text-left uppercase">
						Voucher&nbsp;&nbsp;&nbsp;
						<input type="text" value="{{items['voucher_code']}}" class="form-control" id="coupon_code" style="width: 125px;display:inline;" maxlength="10" {% if items['voucher_code']!=''%} disabled="disabled" {% endif %}>
						<span class="bt_apply_voucher">
							{% if items['voucher_code']!=''%}
							<button class="btn btn-pizzahut btn-danger" type="button" onclick="removeCoupon()">Remove</button>
							{% else %}
							<button class="btn btn-pizzahut btn-danger" type="button" onclick="applyCoupon()">Submit</button>
							{% endif %}
						</span>
						<span id="error_coupon"></span>
						<span id="coupon_value"></span>
					</div>
					<div class="col-md-9 text-left uppercase" style="margin-top:20px;">
						PROMO &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="text" value="{{items['promo_code']}}" class="form-control" id="promo_code" style="width: 125px;display:inline;" maxlength="10" {% if items['promo_code']!=''%} disabled="disabled" {% endif %}>
						<span class="bt_apply_promo">
							{% if items['promo_code']!=''%}
							<button class="btn btn-pizzahut btn-danger" type="button" onclick="removePromo()">Remove</button>
							{% else %}
							<button class="btn btn-pizzahut btn-danger" type="button" onclick="applyPromo()">Submit</button>
							{% endif %}
						</span>
						<span id="error_promo"></span>
						<span id="promo_value"></span>
					</div>
					<div class="col-md-3 text-right" id="voucher_amount" style="padding-bottom:20px;">
						{% if items['voucher_code']!=''%}
						-{{dinhdangtien(items['discount_total'])}}
						{% else %}
						-{{dinhdangtien(0)}}
						{% endif %}
					</div>
					<div class="col-xs-12 text-left uppercase" style="padding-bottom:20px;">
					</div>



					<div class="col-xs-6 text-left uppercase" style="padding-bottom:20px;">
						Tax
					</div>
					<div class="col-xs-6 text-right" style="padding-bottom:20px;" id="tax_data">
						{{dinhdangtien(items['tax'])}}
					</div>

					<div class="col-xs-6 text-left uppercase" style="padding-bottom:20px;">
						Your Total
					</div>
					<div class="col-xs-6 text-right" id="total_amount" style="padding-bottom:20px;">
						{{dinhdangtien(items['main_total'])}}
					</div>
								
					<div class="col-xs-12 col-xs-offset-6 line text-right"  style="font-size:1em;">
						<p>
							<button class="btn btn-warning btn-lg uppercase" onclick="clear_cart()">Clear Cart</button>
							<button class="btn btn-danger btn-lg uppercase" onclick="gotoDetail()">Checkout</button>
						</p>
						<p>
							<a href="/" class="small">Continue Shopping</a>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="main_cart mobile-show" id="order_summary">
		<div id="main_cart">
			<div class="row" id="head">
				<div class="col-xs-12 text-center">
					<a class="btn btn-danger btn-block uppercase" href="/">Continue shopping</a>
					<br/>
					<button class="btn btn-danger btn-block uppercase" onclick="gotoDetail()">Checkout</button>	
				</div>
			</div>
			<div>&nbsp;</div>
			<div class="row" id="list_product">
				{% set comid = -1 %}
				{% set uid = -1 %}

				{% for index,item in items['items'] %}

				    {% if item['combo_id'] is defined and comid != item['combo_id'] %}
				    {% set comid = item['combo_id'] %}
				    <div class="cartbox_item col-xs-12 combo_option" style="background-color:#d8b6b2;padding-right:30px;">
				        <div class="cartbox_des">
				            <div class="cartbox_img"><h3>YOUR COMBO{{item['combo_id']+1}}</h3></div>
				            <div class="cartbox_name"><h3></h3></div>
				        </div>
				        <div class="cartbox_qty">
				            <div class="qtybox">
				                <button class="btn down mainbt" onclick="qtyAddCombo(0,'{{index}}',{{item['combo_id']}})">-</button>
				                <input class="popup_qtycombo" id="popup_qtycombo_{{index}}" type="text" value="{{ combo_list[item['combo_id']]['quantity'] }}" style="width: 80px;">
				                <button class="btn up mainbt" onclick="qtyAddCombo(1,'{{index}}',{{item['combo_id']}})">+</button>
				            </div>
				            <button style="font-size:1.3em" class="btn mainbt btedit" onclick="deleteCombo('{{item['combo_id']}}')">Delete</button>
				            <!-- <button class="btn up mainbt btedit" style="color:#111;" onclick="editCombo('{{item['combo_id']}}')">More</button> -->
				        </div>
				        <div class="cartbox_price">
				            <span  id="price_fc_{{index}}">$<?php echo number_format((double)$combo_list[$item['combo_id']]['total'],2) ?></span>
				        </div>
				    </div>
				    {% endif %}

				    {% if item['user_id'] is defined and uid != item['user_id'] %}
				    {% set uid = item['user_id'] %}
				    <div class="item cartbox_item col-xs-12 user_group_box">
				        <div class="col-xs-12 cartbox_des">
				            <h3 style="text-align:left;">
				                {% if item['user_id'] is defined and user_list[item['user_id']] is defined %}
				                    {{user_list[item['user_id']]}}
				                {% endif %}
				            </h3>
				        </div>
				        <div class="col-xs-12 cartbox_qty">
				            <button style="font-size:1em; float:left"  class="btn mainbt btedit" onclick="deleteGroupByUser('{{item['user_id']}}')">Delete</button>
				            <button class="btn mainbt btedit" style="color:#111; float:left" onclick="changeGroupByUser('{{index}}','{{item['user_id']}}')">More</button>
				        </div>
				        <div class="col-xs-12 cartbox_price">
				            $<span class="group_total"  id="price_fc_{{index}}" {% if item['user_id'] is defined %}data-user-id="{{item['user_id']}}"{% endif %}>
				                {% if item['user_id'] is defined and user_data[item['user_id']]['total'] is defined %}
				                    {{user_data[item['user_id']]['total'] }}
				                {% endif %}
				            </span>
				            {% set show_discount_text = 'none' %}
				            {% if item['user_id'] is defined and user_data[item['user_id']]['discount'] is defined and user_data[item['user_id']]['discount'] > 0 %}
				            	{% set show_discount_text = 'block' %}
				            {% endif %}
				            <div class="group_discount" data-user-id="{{item['user_id']}}" style="display:{{show_discount_text}}"><span style="color:#111; font-size:14px">(discount {{user_data[item['user_id']]['discount']*100}}%)</span></div>
				        </div>
				    </div>
				    {% endif %}

					{% if item['combo_id'] is defined %}
					    <div class="col-xs-12 combo_option" id="cartbox_{{index}}" style="padding:1%;">
					        <div class="cartbox_des combo_des">
					            <div class="cartbox_img combo_img">
							<button class="btn up btedit btn-block" style="color:#111;" onclick="changeCombo('{{index}}','{{item['combo_step']}}')">Change</button>
					            </div>
					            <div class="cartbox_name combo_name">
					                <h3>{{ item['name'] }}</h3>
					                {% if item['options']|length >0 %}
					                    {% for opp in item['options'] %}
					                        {% if opp['is_change'] is defined and opp['is_change']==1 %}
					                            <p data-idp="{{opp['_id']}}">
					                            {% if opp['group_type'] is defined and opp['group_type']!='Exc' and opp['isfinish']==0 %}
					                                <b>{{opp['quantity']}}</b>:
					                            {% endif %}
					                            {{opp['name']}}
					                            {% if opp['isfinish']==1 %}
					                                <b>({{finish_option[opp['_id']][opp['finish']]}})</b>
					                            {% endif %}
					                            </p>
					                        {% endif %}
					                    {% endfor %}
					                {% endif %}
					                <div class="sell_price" style="text-align: right; font-size: 1.2em;">
					                    <b>$<?php echo number_format((double)$item['sell_price'],2) ?></b>
					                </div>
					                <div class="cart_note">
					                    {% if item['note']!='' %}<b><i>Note:</i></b> <span id="note_product_{{index}}">{{item['note']}}</span>{% endif %}
					                </div>
					                <div class="description_item" style="display:none">{{ item['description']}}</div>
					            </div>

					        </div>
					        <div class="cartbox_qty" style="display:none;">
					            <div class="qtybox">
					                <button class="btn down mainbt" onclick="downQty('fc_{{index}}','update_cart')">-</button>
					                <input class="popup_qty" type="text" value="{{ item['quantity'] }}" id="fc_{{index}}" style="width: 80px;">
					                <button class="btn up mainbt" onclick="upQty('fc_{{index}}','update_cart')">+</button>
					            </div>
					           
					        </div>
					        <div class="cartbox_price" style="display:none;">
					        </div>
					    </div>
					{% else %}			
						<div class="item" id="cartbox_{{index}}">
							<div class="col-xs-12">
								<p><span class="uppercase name" style="color:#fff" >{{item['name']}}</span><span class="hidden description">{{item['description']}}</span></p>
								<div class="options"> 
								
				                {% if item['options']|length >0 %}
				                    {% for opp in item['options'] %}
				                        {% if opp['is_change'] is defined and opp['is_change']==1 %}
				                            <p data-idp="{{opp['_id']}}">
				                            {% if opp['group_type'] is defined and opp['group_type']!='Exc' and opp['isfinish']==0 %}
				                                <b>{{opp['quantity']}}</b>:
				                            {% endif %}
				                            {{opp['name']}}
				                            {% if opp['isfinish']==1 %}
				                                <b>({{finish_option[opp['_id']][opp['finish']]}})</b>
				                            {% endif %}
				                            </p>
				                        {% endif %}
				                    {% endfor %}
				                {% endif %}
				                </div>
							</div>
							<div class="col-xs-6 quantity">
								QTY
								<br/>
								<div class="input_number">
									<input type="text" name="" onchange="updateQuantity('{{index}}',this)" value="{{item['quantity']}}" maxlength="2" />
									<span class="spinner">
										<i class="fa fa-caret-up up"></i>
										<i class="fa fa-caret-down down"></i>
									</span>
								</div>
								<br/>
								<a href="{{baseURL}}/carts/remove-link-item/{{index}}">Delete</a>
							</div>
							<div class="col-xs-6 text-right price" id="{{index}}" style="padding-right:10px;line-height:100px;" {% if item['user_id'] is defined %}data-user-id="{{item['user_id']}}"{% endif %}>
								{{dinhdangtien(item['sell_price']*item['quantity'])}}
							</div>
						</div>
					{% endif %}		
				{% endfor %}		
			</div>

			<div id="random_product" style="height:1px;padding: 0; ">
				&nbsp;
			</div>

			<div  class="row" id="total">
				<div class="col-xs-12 line">
					<div class="col-xs-6 text-left uppercase" style="padding-bottom:20px;">
						Subtotal
					</div>
					<div class="col-xs-6 text-right"  style="padding-bottom:20px;" id="sub_total">
						{{dinhdangtien(items['total'])}}
					</div>
					<div class="col-xs-6 text-left uppercase" style="padding-bottom:20px;">
						Tax
					</div>
					<div class="col-xs-6 text-right" style="padding-bottom:20px;" id="tax_data">
						{{dinhdangtien(items['tax'])}}
					</div>
					<div class="col-xs-6 text-left uppercase" style="padding-bottom:20px;">
						Your Total
					</div>
					<div class="col-xs-6 text-right" id="total_amount" style="padding-bottom:20px;">
						{{dinhdangtien(items['main_total'])}}
					</div>			
					<div class="col-xs-12 text-center">
						<a class="btn btn-danger btn-block uppercase" href="/">Continue shopping</a>
						<br/>
						<button class="btn btn-danger btn-block uppercase" onclick="gotoDetail()">Checkout</button>
						<br/>
						<button class="btn btn-warning btn-block uppercase" onclick="clear_cart()">Clear Cart</button>	
					</div>
				</div>
			</div>
		</div>
	</div>
{% else %}
	<div class="main_cart_empty">
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
	</div>
{% endif %}