function changeOrder(obj){
	var val = parseInt($(obj).val(),10);
	if(!val) val = 1;
	$(obj).val(val)
	$.ajax({
		url : '/admin/banners/saveOrders',
		type: 'POST',
		data:{
			id : $(obj).attr('data-id'),
			orders: val
		},
		beforeSend:function(){
			
		},	
		success:function(data){			
			data = $.parseJSON(data)			
			$("#orders_div").html(data.message);
			var clears = setTimeout(function(){
				$("#orders_div").html('');
				clearTimeout(clears);
			}, 3000)
		}        
	})
}