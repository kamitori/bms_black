<!DOCTYPE html>
<html class=" js no-touch turbolinks-progress-bar" xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraphprotocol.org/schema/" lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="ROBOTS" content="INDEX, FOLLOW">
		<style type="text/css">
			@font-face {
				font-family:FF-FallingSkyBd;
				src:url("{{baseURL}}/fonts/FallingSkyBd.otf?q=<?php echo uniqid(); ?>");
			}

			@font-face {
				font-family:FF-MinionPro-Regular;
				src:url("{{baseURL}}/fonts/MinionPro-Regular.otf?q=<?php echo uniqid(); ?>");
			}

			@font-face {
				font-family:FF-FallingSkyBlk;
				src:url("{{baseURL}}/fonts/FallingSkyBlk.otf?q=<?php echo uniqid(); ?>");
			}
			.popup_option{
				height: auto !important;
			}
		</style>
		{{ assets.outputCss() }}
	</head>
	<body style="background:transparent !important;">
		<div style="position:relative;">
			<div style="padding-left:2%; padding-right:2%;background:transparent !important;">
				<div class="productitem col-md-7">
					<div class="popup_title col-md-6">
						<h2 style="text-align:left">{{product['name']}}</h2>
						<p class="description_item"></p>
						<textarea class="note_product" style="display:none;"></textarea>
						<button class="button pizza-button width-auto add_note_product" onclick="addNoteProduct()" style="display:block;"> Add Note</button>
						<button class="button pizza-button width-auto hidden_note_product" onclick="hiddenNoteProduct()" style="display:none;"> Hidden Note</button>
						<button class="button pizza-button width-auto clear_note_product" onclick="clearNoteProduct()" style="display:none;">Clear Note</button>
					</div>
					<div class="popup_image col-md-6">
						<img id="img_{{product['_id']}}"src="{{product['image']}}" />
					</div>
				</div>
				<div class="popup_prices col-md-5 col-xs-12">
					<div class="op_description col-md-12">

					</div>
					<div class="popup_amount col-md-6 col-xs-6">
						<button class="btn down mainbt" onclick="downQty('popup_qty_main')">-</button>
						<input class="popup_qty popup_qty_main" type="text" onchange="onFocusQuantity(this)" onfocus="onFocusQuantity(this)" value="{% if quantity is defined%}{{quantity}}{%else%}1{%endif%}" id="popup_qty_main" />
						<button class="btn up mainbt" onclick="upQty('popup_qty_main')">+</button>
						<input id="sell_price_popup_qty_main" value="0" type="hidden" />
					</div>
					<div class="popup_price col-md-6 col-xs-6">
						{{dinhdangtien(product['sell_price'])}}
					</div>
					<div class="popup_add_bt col-md-6 col-xs-12">
						<input class="item_id" type="hidden" value="{{product['_id']}}" />
						<input class="cart_id" type="hidden" value="" />
						<input type="hidden" id="json_option" value="">
					</div>
					<div class="popup_control" style="display:none;">
						<button class="back_level" onclick="optionLevel(1)" style="display:block;">« Back option</button>
						<button class="next_level" onclick="optionLevel(2)" style="display:block;">Continue »</button>
					</div>
				</div>
				<div class="tabs mobile-hide">
					{{ partial('frontend/Categories/option_product') }}
				</div>
				<div class="tabs mobile-show">
					{{ partial('frontend/Categories/option_product_mobile') }}
				</div>			
			</div>
		</div>

		<input type="hidden" value="[1,2]" id="list_hours" />
		<script type="text/javascript">
			var appHelper = {
				baseURL: '{{ baseURL }}',
				JT_URL: '{{ JT_URL }}',
				POS_URL: '{{ POS_URL }}'
			};
		</script>
		{{ assets.outputJs() }}
		<script type="text/javascript">
			$(function(){
				calPrice();
			})
		</script>
	</body>
</html>
	