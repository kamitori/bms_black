{% if option is defined %}
<input type="hidden" id="cate_banhmisub" value="{{banhmisub}}" />
<input type="hidden" id="cate_banhmisub_size" value="1" />
{% set n=1 %}
{% set sum = option|length %}
{% for level,arr_option in option %}
{% if arr_option|length > 0 %}
<div class="level_{{n}} level_box" style="display:{% if n==1 %}block{% else %}none{% endif %}">
    <div class="popup_tab" style="width: 100%; float: left;">
        <div style="float:right; width:60%;">
            <div class="col-md-4 continue">
            {% if n>1 %}            
                <button class="backlevel nextlevel button pizza-button width-auto" onclick="optionLevel({{n-1}})" style="display:block;{% if n<sum %}margin-right: 20%;{% endif %}">&laquo; Back option</button>            
            {% endif %}
            </div>
        {% if n<sum %}            
            <div class="col-md-4 continue">Continue to customize order.</div>
            <div class="col-md-4 continue"><button class="nextlevel button pizza-button width-auto" onclick="optionLevel({{n+1}})" style="display:block" title="Continue to pick up options.">Continue &raquo;</button></div>
            
        {% endif %}
        </div>
        
        <ul class="nav nav-tabs product_tab" role="tablist" style="float:left">
            {% if arr_group[level] is defined %}
                {% for key,value in arr_group[level] %}
                    <li role="presentation" class="{% if loop.first %} active {% endif %}">
                        <a href="#item_{{key}}" aria-controls="item_{{key}}" role="tab" data-toggle="tab">{{value}}</a>
                    </li>
                {% endfor %}
            {% endif %}
        </ul>

    </div>
    <div class="popup_option">
        <div class="tab-content">
            {% for groupid,value in arr_option %}
                <div role="tabpanel" class="tab-pane {% if loop.first %} active {% endif %}" id="item_{{groupid}}">
                    {% for item in value %}
                        <div class="col-md-3 col-xs-12 option_item {% if item['group_type'] == 'Exc' %}excludes {% if item['quantity']>0 %}option_item_active" data-active="1{% else%}"data-active="0{%endif%}{% endif %}" data-group-type="{{item['group_type']}}" data-group="{{item['option_group']}}" data-pid="{{item['product_id']}}">
                        <div class="option_item_block">
                            <div class="titbox">
                                <h3 id="name_{{item['_id']}}">{{item['name']}}</h3>
                            </div>
                            <div class="imagebox">
                                {% if item['image']|length >0 %}
                                    <img class="{% if item['group_type'] != 'Exc' %}change_qty_img{% endif %}" src="{{item['image']}}" alt="{{item['_id']}}" id="img_{{item['_id']}}" />
                                {% else %}
                                    <img class="{% if item['group_type'] != 'Exc' %}change_qty_img{% endif %}" src="http://jt.banhmisub.com/upload/2015_10/2015_10_31_161154_894752.jpg" alt="{{item['product_id']}}" id="img_{{item['_id']}}" />

                                {% endif %}
                                    <img class="change_qty_img ximg ximg_{{item['product_id']}}" src="{{baseURL}}/themes/pizzahut/images/deletex.png" alt="{{item['product_id']}}" id="ximg_{{item['product_id']}}" style="display:{% if item['finish'] is defined and item['finish']|length > 0 and item['finish']==0 %}block{% elseif item['quantity']==0 and item['group_type'] != 'Exc'%}block{% else %}none{% endif %};" />
                            </div>
                            <div class="popup_amount" style="{% if item['group_type'] == 'Exc' %}display:none;{% endif %}">
                               {% if item['finish'] is defined and  item['finish']|length > 0 %}
                                    <button class="btn down mainbt btforselect" onclick="downQty('{{item['product_id']}}')">-</button>
                                    {% set idkey = item['product_id'] %}
                                    <select class="popup_qty {{item['product_id']}}" data-group-level="{{level}}" data-group-qty="{{item['option_group']}}" data-group-type="{{item['group_type']}}" data-option-type="{{item['option_type']}}"  data-group-finish="1" data-group-order="{{item['group_order']}}" id="{{item['product_id']}}" onchange="changeQty('{{item['product_id']}}')">
                                        {% for fikey,fiop in finish_option[idkey] %}
                                            <option value="{{fikey}}" {% if fikey==item['finish'] %} selected="selected" {% endif%}>{{fiop}}</option>
                                        {% endfor %}
                                    </select>
                                    <button class="btn up mainbt btforselect" onclick="upQty('{{item['product_id']}}')">+</button>
                                {% else %}
                                    <button class="btn down mainbt" onclick="downQty('{{item['product_id']}}')">-</button>
                                    <input class="popup_qty {{item['product_id']}}" type="text" data-group-level="{{level}}"  data-group-qty="{{item['option_group']}}" data-group-type="{{item['group_type']}}" data-option-type="{{item['option_type']}}"  value="{{item['quantity']}}" data-group-finish="0" data-group-order="{{item['group_order']}}" id="{{item['product_id']}}" onchange="changeQty('{{item['product_id']}}')" />
                                    <button class="btn up mainbt" onclick="upQty('{{item['product_id']}}')">+</button>
                                {% endif %}    

                                <input id="sell_price_{{item['product_id']}}" value="{{item['sell_price']}}" type="hidden" />
                                <input id="group_id_{{item['product_id']}}" value="{{groupid}}" type="hidden" />
                                <input id="group_name_{{item['product_id']}}" value="{{arr_group[level][groupid]}}" type="hidden" />
                                <input id="default_qty_{{item['product_id']}}" value="{{item['default_qty']}}" type="hidden" />
                                
                                {% if item['default']==1 %}
                                    <input id="isdefault_{{item['product_id']}}" class="isdefault" value="{{item['default']}}" type="hidden" />
                                {% endif %}

                                <input id="islocked_{{item['product_id']}}" class="islocked" value="0" type="hidden" />
                                {% if item['group_type'] == 'Exc' and item['quantity']>0 %}
                                    <input name="{{item['option_group']}}" value="{{item['product_id']}}" type="hidden" />
                                {% endif %}
                             </div>
                             </div>
                        </div>
                    {% endfor %}
                </div>
            {% endfor %}
        </div>

    </div>
</div>
{% set n=n+1 %}
{% endif %}
{% endfor %}
{% endif %}

<input class="option_price_total" value="{% if option_price_total is defined %} {{option_price_total}} {% else %} 0 {% endif %}" type="hidden" />
<input id="popup_cart_id" value="{% if cart_id is defined %}{{cart_id}}{% endif %}" type="hidden" />
<input id="lock_default_when_no_choice" value="1" type="hidden" />
<input id="max_choice" value="{{ max_choice }}" type="hidden" />
<input id="choice_default_amout" value="{{ choice_default_amout }}" type="hidden" />
<input id="change_amout" value="0" type="hidden" />
<div class="message_box"></div>
<div class="description_default" style="display:none">{{description}}</div>
