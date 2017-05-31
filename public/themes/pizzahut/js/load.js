$(function(){
	$(".tabs").delegate(".excludes", "click touchend",function(e){
		e.preventDefault();
		var group = $(this).data("group");console.log(group);
		var pid = $(this).data("pid");console.log(pid);		
		var default_id = $("[name='"+group+"']").val();
		$("[data-group='"+group+"']").removeClass('option_item_active');
		$("[data-pid='"+pid+"']").addClass('option_item_active');
		//console.log($("[data-pid='"+pid+"']"));
		$("[data-group-qty='"+group+"']").val(0).attr("value","0");
		$("input."+pid).val(1).attr("value","1");	
		if(group=='Size' && $("#cate_banhmisub").val()=='1'){ // thuoc banhmisub category			
			// mac dinh la` 11''
			var name = $("#name_"+pid).html();
			if(name=='11" Sub') {
				$("#cate_banhmisub_size").val(1);
			}else{
				$("#cate_banhmisub_size").val(0);
			}
		}
		if(pid==default_id && $('#od_'+default_id).length){
			$('#od_'+default_id).remove();
		}else if($('#od_'+default_id).length){
			$('#od_'+default_id).html($("#name_"+pid).html());
		}else{
			$('.op_description').append('<p id="od_'+default_id+'">'+$("#name_"+pid).html()+'</p>');
		}
		calPrice();
	});

	$(".tabs").delegate(".change_qty_img", "click touchend",function(e){
		e.preventDefault();		
		upQty($(this).attr("alt"));
	});

	$('.close_combo_bt').bind("touchend click", function(e){
		e.preventDefault();
		cancelCombo();
	});
	$('.next_group_bt').bind("touchend click", function(e){
		e.preventDefault();
		nextGroup();
	});
	$('.end_group_bt').bind("touchend click", function(e){
		e.preventDefault();
		endGroup();
	});

});	
