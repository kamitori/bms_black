<div class="row">
	<div class="col-md-12">
		<div class="pizzabanner">			
			{% if category['image'] is defined and category['image']!='' %}
			<img src="{% if category['image'] is defined and category['image'] !='' %}{{category['image']}}{% else %}/themes/pizzahut/images/89ba8837-00ed-4443-841f-9f075cdb38c5.png{% endif %}" alt="{% if category['alt'] is defined %}{{category['alt']}}{% endif %}" />
			{% else %}
				<h1>{{ category['name'] }}</h1>
			{% endif %}
		</div>        
	</div>
</div>
<h2 class="description">{{ category['description'] }}</h2>
<div class="row">
	{% for product in products %}
	<div class="col-md-6 product-block" style="min-height:450px;">
		<div id="product-item-{{ product['id'] }}" class="product-item"   style="min-height:450px;">
			<div class="image-container col-md-12 col-xs-12">
				<img src="{% if product['image'] is defined and product['image'] != '' %}{{ product['image'] }}{%else%}{{baseURL}}/themes/pizzahut/images/default.png{%endif%}" alt="{{product['name']}}" data-product-id="{{ product['id'] }}" onclick="addCart('{{ product['id'] }}')"/>
			</div>
			<div class="right-text col-md-12 col-xs-12">
				<div class="col-md-8 col-xs-12">
					<h2 style="" class=" center-text-mobile">{{ product['name'] }}</h2>
					<p class="description_item center-text-mobile" style="">{{ product['description'] }}</p>
					<p class="product_desciption center-text-mobile">{{ product['product_desciption'] }}</p>					
				</div>
				<div class="col-md-4 col-xs-12">
					<p style="margin-bottom:0;line-height:2em;text-align:center;width:100%;">
						<span>
							{% if product['custom']==1 %}
								<?php if(strpos(strtolower($product['name']),"combo")===false){ ?>
									Starting from:
								<?php }else{ ?>
									Price:
								<?php } ?>
							{% else %}
								{% if product['combo']==1 %}
									Starting from:
								{%else%}
								 	Price:
								{% endif %}
							{% endif %}<br/>
						</span>
						<span class="product_item_price">$<?php echo number_format($product['price'],2);?></span>
						<span class="product_item_sellprice hidden">$<?php echo number_format($product['sell_price'],2);?></span>
					</p>

					<div class="">
					{% if session_user == false and session_store == false %}                
						<!-- <button class="button pizza-button order-product-now-btn" data-product-id="{{ product['id'] }}" data-toggle="modal" data-target="#myModal" onclick="setSelectingProduct('{{ product['id'] }}')">
							<span>Find Stores</span>
						</button>   -->   
						<button class="button pizza-button order-product-now-btn" data-product-id="{{ product['id'] }}" onclick="addCart('{{ product['id'] }}')">
							{% if product['custom'] == 1 %}
								<span>Order</span>
							{% else %}
								<span>Order</span>
							{% endif %}
						</button>            
					{% else %}
						<button class="button pizza-button order-product-now-btn" data-product-id="{{ product['id'] }}" onclick="addCart('{{ product['id'] }}')">
							{% if product['custom'] == 1 %}
								<span>Order</span>
							{% else %}
								<span>Order</span>
							{% endif %}
						</button>                
					{% endif %}
					</div>  										
				</div>
				<!-- <div class="selectbox bottom-item" style="clear:both">
					<div class="selectdiv">
						<select id="selectpicker_{{ product['id'] }}" class="selectpicker">
						  <option value="standard">Standard</option>
						  {% if product['custom']==1 %}
							<option value="custom">Custom</option>
						  {% endif %}
						</select>
					</div>
				</div> -->
              
			</div>
		</div>
		<input type="hidden" value="{{ product['custom'] }}" id="custom_{{ product['id'] }}" />
		<input type="hidden" value="{{ product['combo'] }}" id="iscombo_{{ product['id'] }}" />
		<input type="hidden" value="{{ product['use_group_order'] }}" id="isordergroup_{{ product['id'] }}" />
		<input type="hidden" value="{{ product['combo_sales'] }}" id="combosales_{{ product['id'] }}" />
	</div>    
	{% endfor %}
	<input type="hidden" id="selecting_product_id" value="" />
</div>
