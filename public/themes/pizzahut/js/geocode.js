var arr_province = {
		"ON" : "Ontario",
		"QC" : "Québec",
		"NS" : "Nova Scotia",
		"NB" : "New Brunswick",
		"MB" : "Manitoba",
		"BC" : "British Columbia",
		"PE" : "Prince Edward Island",
		"SK" : "Saskatchewan",
		"AB" : "Alberta",
		"NB" : "Newfoundland and Labrador",
		"ON" : "Ontario",
	};
function getAddress(obj){
	var keycode = event.keyCode || event.which;
	if(keycode==13){
		$.ajax({
			url:appHelper.baseURL +'/carts/get-address',
			type:"POST",
			data:{
				postcode : $(obj).val().replace(" ","")
			},
			success:function(data){
				if(data.error==0){
					var html=""
					console.log(data.addresses);
					$.each(data.addresses.Items,function(key,address){
						html+= "<p onclick='chooseAddress(this)'>"+address['Text']+','+address['Description']+"</p>";
					})
					$(".address_suggest").html(html);
					$(".address_suggest").show();
				}
			}
		});
	}
}

function chooseAddress(obj){
	var address = $(obj).text();
	address = address.split(",");
	$("#address_1").val(address[0]);
	$("#city").val(address[1]);
	$("#postal_code").val(address[3]);
	$("#province").val(address[2].trim());
	$(".address_suggest").hide();
	$(".address_suggest").hide();
	$("#address_1").change();
}