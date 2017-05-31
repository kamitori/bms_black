<div class="container" id="top">

    <div class="row">
        <div class="col-md-3 col-sm-4">
            <div class="accountbox">
                {% if session_user == false %}
                <a class="btn btn-default pizbutton" href="/users/create-account" role="button">Create account</a>
                <a class="btn btn-default pizbutton" href="/users/sign-in" role="button">Sign in</a>
                {% else %}
                <span>Hello {{session_user['first_name']}} {{session_user['last_name']}}</span>
                &nbsp;&nbsp;&nbsp;
                <a class="btn btn-default pizbutton" href="/users/logout" role="button">Logout</a>
                <p><a href="/users/" style="color:#9f3122 !important;">My Account</a></p>
                {% endif %}
            </div>
            <span class="langbox">
            <!--
            <a href="#" class="white lang">English</a>
            <a href="#" class="white lang">Français</a>
            -->
            </span>
        </div>
        <div class="mobile-show nopadding toggle-menu-mobile" onclick="toggle_menu_mobile();">
		<button type="button" class="navbar-toggle">
			<i class="fa fa-bars" style="font-size: 150%;"></i>
		</button>
	</div>
	<div class="col-md-6 col-xs-9" id="logo">
		<h2>
            <a href="{{baseURL}}" class="ng-binding"><img src="{{baseURL}}/themes/pizzahut/images/logo2.png" alt="Banh Mi SUB Asian Fusion Vietnamese Sub Restaurant in Calgary" id="headerLogo"></a>
        </h2>
	</div>
        <div class="col-md-3 col-sm-2 show-for-medium-up count-total-wrapper">
            <a href="/carts/check-out">
                <span class="total main_total" id="header_main_total">{{dinhdangtien(cart['main_total'])}}</span>
                <span class="basket-icon">
                    <i class="glyphicon glyphicon-shopping-cart"></i>
                    <span id="main_order_qty" class="count main_order_qty">{{cart['quantity']}}</span>             
                </span>
            </a>

			<div class="notification">
	           
			</div>
			
		</div>
	</div>
    <!-- <div style="margin-bottom:3px;" class="fb-like" data-href="https://www.facebook.com/banhmisub" data-layout="standard" data-action="like" data-size="large" data-show-faces="false" data-share="false"></div> -->
    
</div>

<div class="container">
    <div id="group_alert" style="display:{% if use_group==0 %}none{% else %}block{% endif %};">
        Selecting Menu Item for <span class="user_name_group">{% if user_name is defined %}{{user_name}}{%endif%}</span>
        <button type="button" class="btn btn-default next_group_bt" style="display:inline-block;margin-top: -8px;">Next Guest</button>
        <button type="button" class="btn btn-warning end_group_bt" style="display:inline-block;margin-top: -8px;">Complete Order By Group</button>
    </div>
</div>
<input id="order_group_discount_condition" type="hidden" value="{{ORDER_GROUP_DISCOUNT_CONDITION}}">
