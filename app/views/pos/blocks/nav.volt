<div id="navbar" class="navbar-collapse collapse">
	<ul class="nav navbar-nav">
		{% for me in menu %}
			<li class="active"><a href="{{baseURL}}/pos/pos/menus/{{me['short_name']}}">{{me['name']}}</a></li>
		{% endfor %}
	</ul>
</div>