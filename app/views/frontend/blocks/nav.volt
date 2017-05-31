{% set menu_width = menu|length?100/categories|length:100/categories|length %}
<?php 
    $sum_str = 0;
    foreach ($categories as $key => $item) {
        $categories[$key]['width'] = $menu_width;
        /*if($key=="others"){
            unset($categories[$key]);
        }*/
    }
    foreach ($categories as $key => $item) {
        $sum_str += strlen($item['short_name']);
    }

    $categories = array_values($categories);
    $total_width=100;

    $arr_tmp_small = [];
    $sum_increase = 0;
    foreach ($categories as $key => $item) {
        $categories[$key]['width'] = (strlen($item['short_name'])/ $sum_str)*100;
        if(strlen($item['short_name']) >= 10  && strlen($item['short_name']) <= 16) {
                $new_width = strlen($item['short_name'])*1.2;
                $sum_increase+= $categories[$key]['width'] - strlen($item['short_name'])*1.2;
                $categories[$key]['width'] = $new_width;
        }else{
            $arr_tmp_small[] = $key;
        }
    }
    if(count($arr_tmp_small)){
        $average_increase = $sum_increase/count($arr_tmp_small);
    }

    foreach ($arr_tmp_small as $key => $value) {
        $categories[$value]['width']+=$average_increase;
    }

?>
<nav id="main_menu" class="navbar navbar-default">
    <div class="menu">
        <div class="navbar-header hidden">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation 1</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <!-- {% for item in menu %}
                    <li class="{% if active_id==item.id %} active {% endif %}" style="width:{{menu_width}}%">
                        <a href="{{ baseURL }}/{{ item.short_name }}">{{item.name}}</a>
                    </li>
                {% endfor %} -->
                {% for item in categories %}
                    <li class="{% if active_short_name==item['short_name'] %} active {% endif %}" data-link="{{ baseURL }}/{{ item['short_name'] }}" data-key="{{item['value']}}" data-linkkey="{{ item['short_name'] }}" id="menuleft_{{ item['short_name'] }}" style="width:{{item['width']}}%">
                        <a href="{{ baseURL }}/{{ item['short_name'] }}">{{item['name']}}</a>
                    </li>
                {% endfor %}

            </ul>
        </div>
    </div>
</nav>
<div style="position:relative;">
    <div id="combo_complete" style="display:none;right: 17%;color: red;position: fixed;z-index: 1000;">
        <a class="button pizza-button order-product-now-btn" href="/carts/check-out">Combo is completed. Checkout now </a>
    </div>
    <div id="combo_alert" style="display:{% if use_combo==0 %}none{% else %}block{% endif %};">
        <span class="comboitem" data-step="{{combo_step}}">{{step_description[combo_step]}}<br/>(step {{combo_step}}/3)</span> 
        <button type="button" class="btn btn-default close_combo_bt" style="display:{% if update_combo_id!="" %}none{% else %}inline-block{% endif %};margin-top: 10px;">Cancel Combo</button>
        {% for key,description in step_description %}
            <input type="hidden" id="step_description_{{key}}" value="{{description}}">
        {% endfor %}
    </div>

</div>