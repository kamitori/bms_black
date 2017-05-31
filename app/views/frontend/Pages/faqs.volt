<div class="col-md-12 top20 brown_block">
	<h1 class="text-left">FAQs - Frequently Asked Questions</h1>
	{% for item in content %}
		<div class="bottom15">
			<div class="col-md-12">
				<h3 class="text-left">
					<a href="#{{item['short_name']}}">{{item['name']}}</a>
				</h3>
			</div>
			<div class="col-md-12 text-left" name="{{item['short_name']}}" style=" border-bottom:1px solid #392113; padding-bottom:15px;">
				{{item['answer']}}
			</div>
		</div>
	{% endfor %}

</div>
