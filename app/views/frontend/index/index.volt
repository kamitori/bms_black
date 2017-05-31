<!-- Wellcome -->
{% set check = false %}
{% for image in banners %}
    {% if image.b_position =='home-slider' %}
        {% set check = true %}
        {% break %}
    {% endif %}
{% endfor %}
{% if check==true %}
    <? $today = strtotime(date('Y-m-d'));?>
    <div class="row banner" style="margin:0;">
        <div class="banner-header-holder">
            <div class="banner-header-container">
                <div class="banner-container">
                    <div class="banner-slide-indicator" style="display: none;">
                        <ul>
                            {% set run = 0 %}
                            {% for image in banners %}
                                {% if image.b_position =='home-slider' %}
                                    {% if image.start_date != null and image.end_date != null %}
                                        <? if(strtotime($image->start_date) < $today && strtotime($image->end_date) > $today){?>
                                            <li data-banner-indicator="{{run}}" id="indicator_{{run}}" data-banner-title="{{image.description}}" data-banner-descr="" class="temp-li {% if run==0 %} transparent active {% endif %}" style="background-color:black">
                                                <a></a>
                                            </li>
                                            {% set run+=1 %}
                                        <?}?>
                                    {% elseif image.end_date == null %}
                                            <? if(strtotime($image->start_date) < $today){ ?>
                                                <li data-banner-indicator="{{run}}" id="indicator_{{run}}" data-banner-title="{{image.description}}" data-banner-descr="" class="temp-li {% if run==0 %} transparent active {% endif %}" style="background-color:black">
                                                    <a></a>
                                                </li>
                                                {% set run+=1 %}
                                            <?}?>
                                    {% elseif image.start_date == null %}
                                            <? if(strtotime($image->end_date) > $today){ ?>
                                                <li data-banner-indicator="{{run}}" id="indicator_{{run}}" data-banner-title="{{image.description}}" data-banner-descr="" class="temp-li {% if run==0 %} transparent active {% endif %}" style="background-color:black">
                                                    <a></a>
                                                </li>
                                                {% set run+=1 %}
                                            <?}?>
                                    {% else %}
                                            <li data-banner-indicator="{{run}}" id="indicator_{{run}}" data-banner-title="{{image.description}}" data-banner-descr="" class="temp-li {% if run==0 %} transparent active {% endif %}" style="background-color:black">
                                                    <a></a>
                                            </li>
                                            {% set run+=1 %}
                                    {% endif %}
                                {% endif %}
                            {% endfor %}
                            
                        </ul>
                    </div>

                    <div class="side_btns_holder no-js-hidden">
                        <div class="banner-control-bar-prev" data-banner-indicator="" style="display: none;">
                            <a>Previous</a>
                        </div>
                        <div class="banner-control-bar-next" data-banner-indicator="" style="display: none;">
                            <a>Next</a>
                        </div>
                    </div>

                    <div style="overflow: hidden;min-height:50px; background-repeat:no-repeat; background-size: 100% 100%;" id="banner_div" data-attr-target="_self">
                        {% set images = '' %}
                        {% set description = '' %}
                        {% set link = '' %}
                        {% set product_id = '' %}
                        {% set run = 0 %}
                        <div class="bannerImages">
                            {% for image in banners %}
                                {% if image.b_position =='home-slider' %}
                                    {% if image.start_date != null and image.end_date != null %}
                                        <? if(strtotime($image->start_date) < $today && strtotime($image->end_date) > $today){?>
                                            <a href="javascript:void(0)" data-url="{{image.link}}" data-key="{{image.product_id}}" class="transparent" data-attr-target="_self">
                                                <img src="{{ baseURL }}/{{image.image}}" alt="{{image.alt}}" style="width:100%;max-height:300px;">
                                            </a>

                                            {% if run ==0 %}
                                                {% set temp = image.description %}
                                                {% set link = image.link %}
                                                {% set product_id = image.product_id %}
                                                {% set images = image.image %}
                                            {% endif %}
                                            {% set run = run +1 %}

                                            {% if(image.product_id !='') %}
                                                {% set product = jsd(image.product_name) %}
                                                 <div style="display:none">
                                                    <div class="">
                                                        <div id="product-item-{{ product['id'] }}" class="" >

                                                        <span class="product_item_sellprice hidden">$
                                                            {% if(product['sell_price']) %}
                                                                {{product['sell_price']}}
                                                            {% else %}
                                                                0.0
                                                            {% endif %}
                                                        </span>

                                                            <div class="">
                                                                <img src="{% if product['image'] is defined and product['image'] != '' %}{{ product['image'] }} {%else%}{{baseURL}}/themes/pizzahut/images/default.png{%endif%}" alt="{{product['name']}}" />
                                                            </div>
                                                            <div class="">
                                                                <div class="">
                                                                    <h2>{{ product['name'] }}</h2>
                                                                    <p class="description_item center-text-mobile" style="">{{ product['description'] }}</p>
                                                                    <p class="product_desciption center-text-mobile">{{ product['product_desciption'] }}</p>                    
                                                                </div>
                                                                <div class="">
                                                                    <p style="margin-bottom:0;line-height:2em;text-align:center;width:100%;">
                                                                        <span>{% if product['custom']==1 %}Starting from:{% else %}Price:<br/>{% endif %}</span>
                                                                        <span class="product_item_price">$<?php echo number_format($product['price'],2);?></span>
                                                                    </p>
                                                                </div>
                                                              
                                                            </div>
                                                            <input type="hidden" value="{{ product['combo_sales'] }}" id="combosales_{{ product['id'] }}" />
                                                        </div>
                                                        <input type="hidden" value="{{ product['custom'] }}" id="custom_{{ product['id'] }}" />
                                                        <input type="hidden" value="{{ product['combo'] }}" id="iscombo_{{ product['id'] }}" />
                                                        <input type="hidden" value="{{ product['use_group_order'] }}" id="isordergroup_{{ product['id'] }}" />                                                

                                                        
                                                    </div>    
                                                    <input type="hidden" id="selecting_product_id" value="" />                                            
                                                </div>
                                            {% endif %}
                                        <?}?>
                                    {% elseif image.end_date == null %}
                                            <a href="javascript:void(0)" data-url="{{image.link}}" data-key="{{image.product_id}}" class="transparent" data-attr-target="_self">
                                                <img src="{{ baseURL }}/{{image.image}}" alt="{{image.alt}}" style="width:100%;max-height:300px;">
                                            </a>

                                            {% if run ==0 %}
                                                {% set temp = image.description %}
                                                {% set link = image.link %}
                                                {% set product_id = image.product_id %}
                                                {% set images = image.image %}
                                            {% endif %}
                                            {% set run = run +1 %}

                                            {% if(image.product_id !='') %}
                                                {% set product = jsd(image.product_name) %}
                                                 <div style="display:none">
                                                    <div class="">
                                                        <div id="product-item-{{ product['id'] }}" class="" >

                                                        <span class="product_item_sellprice hidden">$
                                                            {% if(product['sell_price']) %}
                                                                {{product['sell_price']}}
                                                            {% else %}
                                                                0.0
                                                            {% endif %}
                                                        </span>

                                                            <div class="">
                                                                <img src="{% if product['image'] is defined and product['image'] != '' %}{{ product['image'] }} {%else%}{{baseURL}}/themes/pizzahut/images/default.png{%endif%}" alt="{{product['name']}}" />
                                                            </div>
                                                            <div class="">
                                                                <div class="">
                                                                    <h2>{{ product['name'] }}</h2>
                                                                    <p class="description_item center-text-mobile" style="">{{ product['description'] }}</p>
                                                                    <p class="product_desciption center-text-mobile">{{ product['product_desciption'] }}</p>                    
                                                                </div>
                                                                <div class="">
                                                                    <p style="margin-bottom:0;line-height:2em;text-align:center;width:100%;">
                                                                        <span>{% if product['custom']==1 %}Starting from:{% else %}Price:<br/>{% endif %}</span>
                                                                        <span class="product_item_price">$<?php echo number_format($product['price'],2);?></span>
                                                                    </p>
                                                                </div>
                                                              
                                                            </div>
                                                            <input type="hidden" value="{{ product['combo_sales'] }}" id="combosales_{{ product['id'] }}" />
                                                        </div>
                                                        <input type="hidden" value="{{ product['custom'] }}" id="custom_{{ product['id'] }}" />
                                                        <input type="hidden" value="{{ product['combo'] }}" id="iscombo_{{ product['id'] }}" />
                                                        <input type="hidden" value="{{ product['use_group_order'] }}" id="isordergroup_{{ product['id'] }}" />                                                

                                                        
                                                    </div>    
                                                    <input type="hidden" id="selecting_product_id" value="" />                                            
                                                </div>
                                            {% endif %}
                                    {% elseif image.start_date == null %}
                                        <? if(strtotime($image->end_date) > $today){ ?>
                                            <a href="javascript:void(0)" data-url="{{image.link}}" data-key="{{image.product_id}}" class="transparent" data-attr-target="_self">
                                                <img src="{{ baseURL }}/{{image.image}}" alt="{{image.alt}}" style="width:100%;max-height:300px;">
                                            </a>

                                            {% if run ==0 %}
                                                {% set temp = image.description %}
                                                {% set link = image.link %}
                                                {% set product_id = image.product_id %}
                                                {% set images = image.image %}
                                            {% endif %}
                                            {% set run = run +1 %}

                                            {% if(image.product_id !='') %}
                                                {% set product = jsd(image.product_name) %}
                                                 <div style="display:none">
                                                    <div class="">
                                                        <div id="product-item-{{ product['id'] }}" class="" >

                                                        <span class="product_item_sellprice hidden">$
                                                            {% if(product['sell_price']) %}
                                                                {{product['sell_price']}}
                                                            {% else %}
                                                                0.0
                                                            {% endif %}
                                                        </span>

                                                            <div class="">
                                                                <img src="{% if product['image'] is defined and product['image'] != '' %}{{ product['image'] }} {%else%}{{baseURL}}/themes/pizzahut/images/default.png{%endif%}" alt="{{product['name']}}" />
                                                            </div>
                                                            <div class="">
                                                                <div class="">
                                                                    <h2>{{ product['name'] }}</h2>
                                                                    <p class="description_item center-text-mobile" style="">{{ product['description'] }}</p>
                                                                    <p class="product_desciption center-text-mobile">{{ product['product_desciption'] }}</p>                    
                                                                </div>
                                                                <div class="">
                                                                    <p style="margin-bottom:0;line-height:2em;text-align:center;width:100%;">
                                                                        <span>{% if product['custom']==1 %}Starting from:{% else %}Price:<br/>{% endif %}</span>
                                                                        <span class="product_item_price">$<?php echo number_format($product['price'],2);?></span>
                                                                    </p>
                                                                </div>
                                                              
                                                            </div>
                                                            <input type="hidden" value="{{ product['combo_sales'] }}" id="combosales_{{ product['id'] }}" />
                                                        </div>
                                                        <input type="hidden" value="{{ product['custom'] }}" id="custom_{{ product['id'] }}" />
                                                        <input type="hidden" value="{{ product['combo'] }}" id="iscombo_{{ product['id'] }}" />
                                                        <input type="hidden" value="{{ product['use_group_order'] }}" id="isordergroup_{{ product['id'] }}" />                                                

                                                        
                                                    </div>    
                                                    <input type="hidden" id="selecting_product_id" value="" />                                            
                                                </div>
                                            {% endif %}
                                        <?}?>
                                    {% else %}
                                                <a href="javascript:void(0)" data-url="{{image.link}}" data-key="{{image.product_id}}" class="transparent" data-attr-target="_self">
                                                    <img src="{{ baseURL }}/{{image.image}}" alt="{{image.alt}}" style="width:100%;max-height:300px;">
                                                </a>

                                                {% if run ==0 %}
                                                    {% set temp = image.description %}
                                                    {% set link = image.link %}
                                                    {% set product_id = image.product_id %}
                                                    {% set images = image.image %}
                                                {% endif %}
                                                {% set run = run +1 %}

                                                {% if(image.product_id !='') %}
                                                    {% set product = jsd(image.product_name) %}
                                                     <div style="display:none">
                                                        <div class="">
                                                            <div id="product-item-{{ product['id'] }}" class="" >

                                                            <span class="product_item_sellprice hidden">$
                                                                {% if(product['sell_price']) %}
                                                                    {{product['sell_price']}}
                                                                {% else %}
                                                                    0.0
                                                                {% endif %}
                                                            </span>

                                                                <div class="">
                                                                    <img src="{% if product['image'] is defined and product['image'] != '' %}{{ product['image'] }} {%else%}{{baseURL}}/themes/pizzahut/images/default.png{%endif%}" alt="{{product['name']}}" />
                                                                </div>
                                                                <div class="">
                                                                    <div class="">
                                                                        <h2>{{ product['name'] }}</h2>
                                                                        <p class="description_item center-text-mobile" style="">{{ product['description'] }}</p>
                                                                        <p class="product_desciption center-text-mobile">{{ product['product_desciption'] }}</p>                    
                                                                    </div>
                                                                    <div class="">
                                                                        <p style="margin-bottom:0;line-height:2em;text-align:center;width:100%;">
                                                                            <span>{% if product['custom']==1 %}Starting from:{% else %}Price:<br/>{% endif %}</span>
                                                                            <span class="product_item_price">$<?php echo number_format($product['price'],2);?></span>
                                                                        </p>
                                                                    </div>
                                                                  
                                                                </div>
                                                                <input type="hidden" value="{{ product['combo_sales'] }}" id="combosales_{{ product['id'] }}" />
                                                            </div>
                                                            <input type="hidden" value="{{ product['custom'] }}" id="custom_{{ product['id'] }}" />
                                                            <input type="hidden" value="{{ product['combo'] }}" id="iscombo_{{ product['id'] }}" />
                                                            <input type="hidden" value="{{ product['use_group_order'] }}" id="isordergroup_{{ product['id'] }}" />                                                

                                                            
                                                        </div>    
                                                        <input type="hidden" id="selecting_product_id" value="" />                                            
                                                    </div>
                                                {% endif %}
                                    {% endif %}
                                {% endif %}
                            {% endfor %}
                            <a id="ref_remove" href="javascript:void(0)" data-url="{{link}}" data-key="{{product_id}}" class="transparent" data-attr-target="_self">
                                <img src="{{ baseURL }}/{{images}}" alt="{{description}}" style="width:100%;max-height:300px;">
                            </a>
                        </div>

                        <div class="bannerTextBand fadeout" style="margin-top: 250px; padding-left: 120px; height: 150px; width:100%;display:none"></div>
                        {% set run = 0 %}
                        {% set active = '' %}
                        {% for image in banners %}
                            {% if image.b_position =='home-slider' %}
                                {% if image.start_date != null and image.end_date != null %}
                                    <? if(strtotime($image->start_date) < $today && strtotime($image->end_date) > $today){?>
                                        {% if run==0 %}
                                            {% set active = 'transparent' %}
                                        {% endif %}
                                        <div style="margin-top: 150px; padding-left: 120px; height: 150px;" id="bannertext_{{run}}" class="bannerTextDiv">
                                            <p style="font-size:20px; margin-top: 20px; font-family: 'Myriad W01 Light'; color:white; opacity: .5;" class="titleText">{{image.description}}</p>
                                            <p style="color: white; font-family: 'Wendy W01 LightLP'; font-size:96px;  margin-top:-40px;"></p>
                                        </div>
                                        {% set run=run+1 %}
                                    <?}?>
                                {% elseif image.end_date == null %}
                                    <? if(strtotime($image->start_date) < $today){ ?>
                                        {% if run==0 %}
                                            {% set active = 'transparent' %}
                                        {% endif %}
                                        <div style="margin-top: 150px; padding-left: 120px; height: 150px;" id="bannertext_{{run}}" class="bannerTextDiv">
                                            <p style="font-size:20px; margin-top: 20px; font-family: 'Myriad W01 Light'; color:white; opacity: .5;" class="titleText">{{image.description}}</p>
                                            <p style="color: white; font-family: 'Wendy W01 LightLP'; font-size:96px;  margin-top:-40px;"></p>
                                        </div>
                                        {% set run=run+1 %}
                                    <?}?>
                                {% elseif image.start_date == null %}
                                    <? if(strtotime($image->end_date) > $today){ ?>
                                        {% if run==0 %}
                                        {% set active = 'transparent' %}
                                    {% endif %}
                                    <div style="margin-top: 150px; padding-left: 120px; height: 150px;" id="bannertext_{{run}}" class="bannerTextDiv">
                                        <p style="font-size:20px; margin-top: 20px; font-family: 'Myriad W01 Light'; color:white; opacity: .5;" class="titleText">{{image.description}}</p>
                                        <p style="color: white; font-family: 'Wendy W01 LightLP'; font-size:96px;  margin-top:-40px;"></p>
                                    </div>
                                    {% set run=run+1 %}
                                        <?}?>
                                {% else %}
                                    {% if run==0 %}
                                        {% set active = 'transparent' %}
                                    {% endif %}
                                    <div style="margin-top: 150px; padding-left: 120px; height: 150px;" id="bannertext_{{run}}" class="bannerTextDiv">
                                        <p style="font-size:20px; margin-top: 20px; font-family: 'Myriad W01 Light'; color:white; opacity: .5;" class="titleText">{{image.description}}</p>
                                        <p style="color: white; font-family: 'Wendy W01 LightLP'; font-size:96px;  margin-top:-40px;"></p>
                                    </div>
                                    {% set run=run+1 %}
                                {% endif %}
                            {% endif %}
                        {% endfor %}                     
                    </div>
                </div>
            </div>
        </div>
    </div>
{% endif %}
<div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
        <a href="http://group.banhmisub.com" target="_blank"><img src="{{baseURL}}/themes/pizzahut/images/banner10discount.gif" alt="Order by Group" style="width:100%;"></a>
    </div>
</div>
<div class="ng-scope" style="clear:both;{% if check==true %} margin-top:10px; {%endif%}">
    <div class="row welcome ng-scope">
        <div class="col-md-8">
            <h1 class="text-left ng-binding ng-scope">Welcome to BanhMi Sub!</h1>
            <div>
                <p class="text-left ng-binding ng-scope">
                    Order from your local BanhMiSub
                    {% if session_user == false %}
                    , or <a href="/users/sign-in" class="my-location ng-binding">
                    sign in
                    </a>
                    to reorder from your favorites.
                    {% endif %}
                </p>
            </div>
        </div>
        <div class="col-md-4 store-button ng-scope" style="line-height:90px;">
            <a class="button ph-primary-button button-welcome ng-binding" href="/banh-mi-subs">
                Start Your Order
            </a>
        </div>
    </div>
</div>
<div class="clearfix" style="clear:both"></div>
<div class="row banner ">
    {% for image in banners %}
        {% if image.b_position =='home-banner' %}
            {% if image.start_date != null and image.end_date != null %}
                <? if(strtotime($image->start_date) < $today && strtotime($image->end_date) > $today){?>
                    {% if image.link=='https://www.facebook.com/banhmisub/' %}
                    <div class="SliderImages col-md-12 ">
                        <a href="javascript:void(0)" onclick="checkLoginStateLike()" data-url="{{image.link}}" data-key="{{image.product_id}}" class="transparent" data-attr-target="_self">
                            <img src="{{ baseURL }}/{{image.image}}" alt="{{image.alt}}" >
                        </a>
                    {% else %}
                    <div class="SliderImages col-md-12 ">
                        <a href="javascript:void(0)" data-url="{{image.link}}" data-key="{{image.product_id}}" class="transparent" data-attr-target="_self">
                            <img src="{{ baseURL }}/{{image.image}}" alt="{{image.alt}}" >
                        </a>
                    {% endif %}

                    </div>
                    {% if image.id==1 %}
                        </div><div class="row banner">
                    {% endif %}

                    {% if(image.product_id !='') %}
                        {% set product = jsd(image.product_name) %}
                         <div style="display:none">
                            <div class="">
                                <div id="product-item-{{ product['id'] }}" class="" >
                                    <span class="product_item_sellprice hidden">$
                                        {% if(product['sell_price']) %}
                                            {{product['sell_price']}}
                                        {% else %}
                                            0.0
                                        {% endif %}
                                    </span>
                                    <div class="">
                                        <img src="{% if product['image'] is defined and product['image'] != '' %}{{ product['image'] }} {%else%}{{baseURL}}/themes/pizzahut/images/default.png{%endif%}" alt="{{product['name']}}" />
                                    </div>
                                    <div class="">
                                        <div class="">
                                            <h2>{{ product['name'] }}</h2>
                                            <p class="description_item center-text-mobile" style="">{{ product['description'] }}</p>
                                            <p class="product_desciption center-text-mobile">{{ product['product_desciption'] }}</p>                    
                                        </div>
                                        <div class="">
                                            <p style="margin-bottom:0;line-height:2em;text-align:center;width:100%;">
                                                <span>{% if product['custom']==1 %}Starting from:{% else %}Price:<br/>{% endif %}</span>
                                                <span class="product_item_price">$<?php echo number_format($product['price'],2);?></span>
                                            </p>
                                        </div>
                                      
                                    </div>
                                </div>
                                <input type="hidden" value="{{ product['custom'] }}" id="custom_{{ product['id'] }}" />
                                <input type="hidden" value="{{ product['combo'] }}" id="iscombo_{{ product['id'] }}" />
                                <input type="hidden" value="{{ product['use_group_order'] }}" id="isordergroup_{{ product['id'] }}" />
                                <input type="hidden" value="{{ product['combo_sales'] }}" id="combosales_{{ product['id'] }}" />
                            </div>    
                            <input type="hidden" id="selecting_product_id" value="" />
                        </div>
                    {% endif %}    
                <?}?>
            {% elseif image.end_date == null %}
                    <? if(strtotime($image->start_date) < $today){ ?>
                        {% if image.link=='https://www.facebook.com/banhmisub/' %}
                        <div class="SliderImages col-md-12 ">
                            <a href="javascript:void(0)" onclick="checkLoginStateLike()" data-url="{{image.link}}" data-key="{{image.product_id}}" class="transparent" data-attr-target="_self">
                                <img src="{{ baseURL }}/{{image.image}}" alt="{{image.alt}}" >
                            </a>
                        {% else %}
                        <div class="SliderImages col-md-12 ">
                            <a href="javascript:void(0)" data-url="{{image.link}}" data-key="{{image.product_id}}" class="transparent" data-attr-target="_self">
                                <img src="{{ baseURL }}/{{image.image}}" alt="{{image.alt}}" >
                            </a>
                        {% endif %}

                        </div>
                        {% if image.id==1 %}
                            </div><div class="row banner">
                        {% endif %}

                        {% if(image.product_id !='') %}
                            {% set product = jsd(image.product_name) %}
                             <div style="display:none">
                                <div class="">
                                    <div id="product-item-{{ product['id'] }}" class="" >
                                        <span class="product_item_sellprice hidden">$
                                            {% if(product['sell_price']) %}
                                                {{product['sell_price']}}
                                            {% else %}
                                                0.0
                                            {% endif %}
                                        </span>
                                        <div class="">
                                            <img src="{% if product['image'] is defined and product['image'] != '' %}{{ product['image'] }} {%else%}{{baseURL}}/themes/pizzahut/images/default.png{%endif%}" alt="{{product['name']}}" />
                                        </div>
                                        <div class="">
                                            <div class="">
                                                <h2>{{ product['name'] }}</h2>
                                                <p class="description_item center-text-mobile" style="">{{ product['description'] }}</p>
                                                <p class="product_desciption center-text-mobile">{{ product['product_desciption'] }}</p>                    
                                            </div>
                                            <div class="">
                                                <p style="margin-bottom:0;line-height:2em;text-align:center;width:100%;">
                                                    <span>{% if product['custom']==1 %}Starting from:{% else %}Price:<br/>{% endif %}</span>
                                                    <span class="product_item_price">$<?php echo number_format($product['price'],2);?></span>
                                                </p>
                                            </div>
                                          
                                        </div>
                                    </div>
                                    <input type="hidden" value="{{ product['custom'] }}" id="custom_{{ product['id'] }}" />
                                    <input type="hidden" value="{{ product['combo'] }}" id="iscombo_{{ product['id'] }}" />
                                    <input type="hidden" value="{{ product['use_group_order'] }}" id="isordergroup_{{ product['id'] }}" />
                                    <input type="hidden" value="{{ product['combo_sales'] }}" id="combosales_{{ product['id'] }}" />
                                </div>    
                                <input type="hidden" id="selecting_product_id" value="" />
                            </div>
                        {% endif %}    
                    <?}?>
            {% elseif image.start_date == null %}
                    <? if(strtotime($image->end_date) > $today){ ?>
                        {% if image.link=='https://www.facebook.com/banhmisub/' %}
                        <div class="SliderImages col-md-12 ">
                            <a href="javascript:void(0)" onclick="checkLoginStateLike()" data-url="{{image.link}}" data-key="{{image.product_id}}" class="transparent" data-attr-target="_self">
                                <img src="{{ baseURL }}/{{image.image}}" alt="{{image.alt}}" >
                            </a>
                        {% else %}
                        <div class="SliderImages col-md-12 ">
                            <a href="javascript:void(0)" data-url="{{image.link}}" data-key="{{image.product_id}}" class="transparent" data-attr-target="_self">
                                <img src="{{ baseURL }}/{{image.image}}" alt="{{image.alt}}" >
                            </a>
                        {% endif %}

                        </div>
                        {% if image.id==1 %}
                            </div><div class="row banner">
                        {% endif %}

                        {% if(image.product_id !='') %}
                            {% set product = jsd(image.product_name) %}
                             <div style="display:none">
                                <div class="">
                                    <div id="product-item-{{ product['id'] }}" class="" >
                                        <span class="product_item_sellprice hidden">$
                                            {% if(product['sell_price']) %}
                                                {{product['sell_price']}}
                                            {% else %}
                                                0.0
                                            {% endif %}
                                        </span>
                                        <div class="">
                                            <img src="{% if product['image'] is defined and product['image'] != '' %}{{ product['image'] }} {%else%}{{baseURL}}/themes/pizzahut/images/default.png{%endif%}" alt="{{product['name']}}" />
                                        </div>
                                        <div class="">
                                            <div class="">
                                                <h2>{{ product['name'] }}</h2>
                                                <p class="description_item center-text-mobile" style="">{{ product['description'] }}</p>
                                                <p class="product_desciption center-text-mobile">{{ product['product_desciption'] }}</p>                    
                                            </div>
                                            <div class="">
                                                <p style="margin-bottom:0;line-height:2em;text-align:center;width:100%;">
                                                    <span>{% if product['custom']==1 %}Starting from:{% else %}Price:<br/>{% endif %}</span>
                                                    <span class="product_item_price">$<?php echo number_format($product['price'],2);?></span>
                                                </p>
                                            </div>
                                          
                                        </div>
                                    </div>
                                    <input type="hidden" value="{{ product['custom'] }}" id="custom_{{ product['id'] }}" />
                                    <input type="hidden" value="{{ product['combo'] }}" id="iscombo_{{ product['id'] }}" />
                                    <input type="hidden" value="{{ product['use_group_order'] }}" id="isordergroup_{{ product['id'] }}" />
                                    <input type="hidden" value="{{ product['combo_sales'] }}" id="combosales_{{ product['id'] }}" />
                                </div>    
                                <input type="hidden" id="selecting_product_id" value="" />
                            </div>
                        {% endif %}    
                    <?}?>
            {% else %}
                    {% if image.link=='https://www.facebook.com/banhmisub/' %}
                    <div class="SliderImages col-md-12 ">
                        <a href="javascript:void(0)" onclick="checkLoginStateLike()" data-url="{{image.link}}" data-key="{{image.product_id}}" class="transparent" data-attr-target="_self">
                            <img src="{{ baseURL }}/{{image.image}}" alt="{{image.alt}}" >
                        </a>
                    {% else %}
                    <div class="SliderImages col-md-12 ">
                        <a href="javascript:void(0)" data-url="{{image.link}}" data-key="{{image.product_id}}" class="transparent" data-attr-target="_self">
                            <img src="{{ baseURL }}/{{image.image}}" alt="{{image.alt}}" >
                        </a>
                    {% endif %}

                    </div>
                    {% if image.id==1 %}
                        </div><div class="row banner">
                    {% endif %}

                    {% if(image.product_id !='') %}
                        {% set product = jsd(image.product_name) %}
                         <div style="display:none">
                            <div class="">
                                <div id="product-item-{{ product['id'] }}" class="" >
                                    <span class="product_item_sellprice hidden">$
                                        {% if(product['sell_price']) %}
                                            {{product['sell_price']}}
                                        {% else %}
                                            0.0
                                        {% endif %}
                                    </span>
                                    <div class="">
                                        <img src="{% if product['image'] is defined and product['image'] != '' %}{{ product['image'] }} {%else%}{{baseURL}}/themes/pizzahut/images/default.png{%endif%}" alt="{{product['name']}}" />
                                    </div>
                                    <div class="">
                                        <div class="">
                                            <h2>{{ product['name'] }}</h2>
                                            <p class="description_item center-text-mobile" style="">{{ product['description'] }}</p>
                                            <p class="product_desciption center-text-mobile">{{ product['product_desciption'] }}</p>                    
                                        </div>
                                        <div class="">
                                            <p style="margin-bottom:0;line-height:2em;text-align:center;width:100%;">
                                                <span>{% if product['custom']==1 %}Starting from:{% else %}Price:<br/>{% endif %}</span>
                                                <span class="product_item_price">$<?php echo number_format($product['price'],2);?></span>
                                            </p>
                                        </div>
                                      
                                    </div>
                                </div>
                                <input type="hidden" value="{{ product['custom'] }}" id="custom_{{ product['id'] }}" />
                                <input type="hidden" value="{{ product['combo'] }}" id="iscombo_{{ product['id'] }}" />
                                <input type="hidden" value="{{ product['use_group_order'] }}" id="isordergroup_{{ product['id'] }}" />
                                <input type="hidden" value="{{ product['combo_sales'] }}" id="combosales_{{ product['id'] }}" />
                            </div>    
                            <input type="hidden" id="selecting_product_id" value="" />
                        </div>
                    {% endif %}    
            {% endif %}
        {% endif %}
    {% endfor %}
</div>




