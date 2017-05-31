<div id="menu-mobile" class="row">
	
	{% if session_user == false %}
    
	<div class="col-xs-6">
		<a href="{{baseURL}}/users/create-account">Create Account</a>
	</div>
	<div class="col-xs-4 text-right">
		<a href="{{baseURL}}/users/sign-in">Sign in</a>
	</div>


    {% else %}
    <div class="col-xs-8">
    	<span>Hello {{session_user['first_name']}} {{session_user['last_name']}}</span>
    		&nbsp;&nbsp;&nbsp;
    </div>
    <div class="col-xs-4"><a class="btn btn-default pizbutton" href="/users/logout" role="button" style="margin-left: 0;">Logout</a></div>
    {% endif %}    
	
	<div class="col-xs-12 nopadding">
		<ul>
			<li style="margin-bottom: 20px;">
				<a href="#" class="parent_menu order_menu_mobile">
						<span><i class="fa fa-list"></i> &nbsp;&nbsp;Category</span>
				</a>
				<ul class="sub_menu" show="0">
					{% for item in categories %}
					<li title="{{item['name']}}" class="ng-scope">
					<a href="{{ baseURL }}/{{ item['short_name'] }}" class="ng-binding">{{item['name']}}</a>
					</li>
					{% endfor %}
				</ul>
			</li>
			
			<li>
			        {% set order=1 %}
			        {% for item in menufooter %}
			            {% if loop.first %}
			                {% set order=item.order_no %}
			            {% endif %}
			            {% if order != item.order_no %}
			                </li><li>
			            {% endif %}
			            {% set order=item.order_no %}
			                <a href="#" class="parent_menu">{{item.name}}</a>
			                    {% if submenufooter[item.id] is not empty %}
			                        <ul class="sub_menu" show="0">
			                            {% for subitem in submenufooter[item.id] %}
			                                <li title="{{subitem['name']}}" class="ng-scope">
			                                    <a {% if subitem['short_name'] == "find-a-banhmisub" %} data-toggle="modal" data-target="#myModal" href="#" {%else%} href="{{ baseURL }}/{{ subitem['type'] }}/{{ subitem['short_name'] }}" {% endif %} class="ng-binding">{{subitem['name']}}</a>
			                                </li>
			                            {% endfor %}
			                        </ul>
			                    {% endif %}
			        {% endfor %}
			    </li>
			<li title="Carts" class="ng-scope" style="font-size:1.5em;">
				<a href="{{ baseURL }}/carts/check-out">
					<p style="line-height:1.5em;">Your cart</p>
					<span style="line-height:1.5em;" class="total main_total" id="header_main_total">{{dinhdangtien(cart['main_total'])}}</span>
					<br/>
					<span style="line-height:1.5em;" class="basket-icon">
						<i class="glyphicon glyphicon-shopping-cart"></i>
						<span id="main_order_qty" class="count main_order_qty">{{cart['quantity']}}</span>             
					</span>
				</a>
                                </li>
		</ul>
	</div>
</div>