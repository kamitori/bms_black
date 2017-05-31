<!DOCTYPE html>
<html>
<head>
<style type="text/css">
/*html{
	width:568px;
}*/
body::-webkit-scrollbar {
	width: 4px;
	margin-left: 5px;
}
 
body::-webkit-scrollbar-track {
	-webkit-box-shadow: inset 0 0 6px #aaa;
}
 
body::-webkit-scrollbar-thumb {
  background-color: #A03021;
  outline: 1px solid #A03021;
}
@font-face {
	font-family: 'Avenir Next LT Pro Bold';
	src: url('{{baseURL}}/fonts/Avenir/AvenirNextLTPro-Bold.woff') format('woff');
	font-weight: normal;
	font-style: normal;
}
*{
	font-family: Arial;
	font-size: 20px;
}
table{
	width: 100%;
	padding: 0;
	margin: 0;
	/*border-top:2px dashed #000;*/
	/*border-bottom:2px dashed #000;*/
}
td{
	padding:1%;
}
thead>tr>td{
	background: #DAD8D8;
	font-weight: bold;
	padding:2%;
}
p{
	margin-top:0.5em;
	margin-bottom:0.5em;
}
tr>td:nth-last-child(2), tr>td:nth-last-child(3){
	text-align: right;
}
tr>td:last-child{
	/*font-weight: bold;*/
	text-align: right;
	padding-right: 5px;
	width: 15%;
}
tr>td:first-child{
	width:10%;
	padding-right: 5%;
}
.bold{
	font-weight: bold;
}
.center{
	text-align: center !important;
}
.left7p{
	padding-left: 7%;
}
.left40p{
	padding-left: 40%;
}
.text-left{
	text-align: left !important;
}
.font130p{
	font-size: 130%;
}
.line td{
	border-top: 1px dashed #000;
}
tr>td.name{
	text-align: left !important;
	/*font-weight: 600;*/
}
.big{
	font-size: 110%;
}
.note{
	font-style: italic;
}
.bgbox{
	position: absolute;
	position: fixed;
	top: 0;
	height: 60px;
	background: white;
	width: 100%;
}
.bgbox button{
	font-size: 18px;
	color: white;
	margin:2% 2% 2% 35%;
	float: left;
	
	font-family: 'Avenir Next LT Pro Bold';
	text-transform: uppercase;
	border-color: #ccc;
	display: inline-block;
	padding: 6px 12px;
	outline: none!important;
	font-weight: 400;
	line-height: 1.42857143;
	text-align: center;
	white-space: nowrap;
	vertical-align: middle;
	-ms-touch-action: manipulation;
	touch-action: manipulation;
	cursor: pointer;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
	border: 1px solid transparent;
	border-radius: 4px;
	background: rgb(66,62,63);
	background: -moz-linear-gradient(top, rgba(66,62,63,1) 0%, rgba(51,47,48,1) 53%, rgba(31,28,29,1) 100%);
	background: -webkit-linear-gradient(top, rgba(66,62,63,1) 0%,rgba(51,47,48,1) 53%,rgba(31,28,29,1) 100%);
	background: linear-gradient(to bottom, rgba(66,62,63,1) 0%,rgba(51,47,48,1) 53%,rgba(31,28,29,1) 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#423e3f', endColorstr='#1f1c1d',GradientType=0 );
}
#logo{
	display: block;
	margin: auto;
	margin-top: 75px;
	margin-bottom: 15px;
	width: 95%;
}
.time,.user,.pos{
	display:inline-block;
}
.pos{
	text-align: left;
}
.code{
	text-align: right;
}
.user{
	text-align: left;
}
.time{
	text-align: right;
}
.twocol{
	width: 50%;
	float: left;
	margin-left: 0;
	margin-right: 0;
	padding-right: 0;
	padding-left: 0;
	margin-bottom: 10px;
}
.address{
	text-align: center;
}
.address span{
	display: block;
}
.paid{
	margin-bottom: 25px;
	margin-top: 15px;
}
.cross{
	margin-bottom: 20px;
	margin-top: 20px;
	clear:both;
	position: relative;
	height: 10px;
	text-align: center;
}
.cross img{
	width:100%;
	max-height: 90%;
}
.cross .square{
	width: 6px;
	height: 6px;
	margin-left: 2px;
	float: left;
	padding: 0;
	border: 1px solid #000;
	transform: rotate(45deg);
}
.order-id{
	width: 100%;
	font-size: 1.5em;
	text-align: center;
	font-weight: 600;
}
.paid-detail{
	width: 100%;
	float: left;
	padding-bottom: 50px;
	border-bottom: 1px solid #000;
}
.paid-detail .left{
	width: 50%;
	text-align: left;
	float: left;
	display: block;
	font-weight: bold;
}
.paid-detail .right{
	width: 50%;
	text-align: right;
	float: left;
	display: block;
	margin-bottom: 10px;
	font-weight: bold;
}
.thanks{
	padding-top: 25px;
	padding-bottom: 10px;
	clear: both;
}
.main_hidden{
	display: none;
}
body{
	overflow-x:hidden;
}

@media print{    
	.no-print, .no-print *{
		display: none !important;
	}
	#logo{
		margin-top: 0 !important;
	}
	footer {page-break-after: always;}
	*{
		font-family: Arial;
		font-size: 12px;
	}
	.cross{
		margin-bottom: 10px;
		margin-top: 10px;
	}
	.paid-detail{
		padding-bottom: 10px;
	}
	.thanks{
		padding-top: 10px;
	}
	.main_hidden{
		display: block!important;
	}
	html{
	        page-break-inside: avoid;
	}
}
</style>
</head>
<body>
<p>Hello Production Team,</p>
<p>We have just received an online order <a href="http://jt.banhmisub.com/salesorders/entry/{{_id}}">{{code}}</a>. Please check the information below:</p>
<br/>

{% if baker is defined %}
<p>Customer: <b>{{full_name}}</b></p>
<p>Email: <b>{{emailInfo}}</b></p>
<p>Phone number: <b>{{phone}}</b></p>
{% endif %}

<br/>
<table cellpadding="0" cellspacing="0">
	{% set comid = -1 %}
	{% set uid = -1 %}
	{% for index, item in cart['items'] %}
	{% if item['combo_id'] is defined and comid != item['combo_id'] %}
	{% set comid = item['combo_id'] %}
		<tr>
			<td>
				{{ combo_list[item['combo_id']]['quantity'] }}
			</td>
			<td class="name">
				<p class="big">COMBO{{item['combo_id']+1}}</p>
			</td>
		</tr>
	{% endif %}

	<tr>
		<td class="bold">
			{% if item['combo_id'] is not defined %}
			{{ item['quantity'] }}
			{% endif %}
		</td>
		<td class="name">
			<p class="big">{{ item['name'] }} {% if item['combo_id'] is defined %}({{ item['quantity'] }}){% endif %}</p>
			 {% if item['options']|length >0 %}
				{% for opp in item['options'] %}
					{% if opp['is_change']==1 %}
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
			<p class="note">{% if item['note'] is defined and item['note'] != '' %}({{ item['note'] }}){% endif %}</p>
		</td>
	</tr>
	{% endfor %}		
	<tr>
		<td colspan="3" style="text-align:left;font-weight:200;">
			{% if cart['note'] is defined %}
				Note:{{cart['note']}}
			{% endif %} 
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
</table>
<div class="twocol time">Date and Time order: {{time}}</div>
<div class="twocol time">Date and time of Pick up:: {{datetime_pickup}}</div>

<div class="cross">
	<img src="{{baseURL}}/themes/pizzahut/images/cross.png" alt="">
</div>

<div class="center bold thanks">
	<p>Thank you and keep on smilling :)</p>
	<p>BANH MI SUB</p>
</div>
</body>
<!-- <footer></footer> -->
</html>