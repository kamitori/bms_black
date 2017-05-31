
function setCombo(product_id){
    // $("#category_title").css("display","none");
    $("#combo_alert").css("display","block");
    $(".comboitem").attr("data-step",'1');
    $(".comboitem").html($("#step_description_1").val()+"<br/>(step 1/3)");
    var  combo_sales = parseFloat($("#combosales_"+product_id).val());
    $.ajax({
            method: "POST",
            url: appHelper.baseURL + '/carts/begin-combo/',
            data: {product_id:product_id,combo_sales:combo_sales},
            success:function(html){
                openCate('Banh Mi SUBS',appHelper.baseURL+'/banh_mi_subs');
                $(".navbar-nav li").removeClass("active");
                $("#menuleft_banh_mi_subs").addClass("active");
                $("#category_title").html('Banh Mi SUBS');
            }
        });
}
function cancelCombo(){
    $("#category_title").css("display","block");
    $("#combo_alert").css("display","none");
    $(".comboitem").attr("data-step",'0');
    $(".comboitem").html($("#step_description_0").val()+" (step 0/3)");
    $.ajax({
        method: "POST",
        url: appHelper.baseURL + '/carts/cancel-combo/',
        success:function(html){
        }
    });
}
function endCombo(){
    $("#category_title").css("display","block");
    $("#combo_alert").css("display","none");
    $("#combo_complete").css("display","block");
    $(".comboitem").attr("data-step",'0');
    $(".comboitem").html($("#step_description_0").val()+"(step 0/3)");
    $.ajax({
        method: "POST",
        url: appHelper.baseURL + '/carts/stop-combo/',
        success:function(html){

        }
    });
}
function qtyAddCombo(n,ids,cartKey){
    var comboqty = parseInt($("#popup_qtycombo_"+ids).val());
    if(n==0)
        comboqty--;
    else
        comboqty++;
    console.log(comboqty);
    if(comboqty>0){
        $.ajax({
            method: "POST",
            url: appHelper.baseURL + '/carts/addqty-combo/',
            data:{cartKey:cartKey, comboqty:comboqty},
            success:function(html){
                /*reload_cart(function(){
                });*/
                location.href = '/carts/check-out';
            }
        });
    }
}
function deleteCombo(combo_id) {
    confirm_h('Alert','Do you want to delete this item','default',function(){
        $.ajax({
                url: appHelper.baseURL +'/carts/remove-combo',
                type: 'POST',
                data: {
                    combo_id: combo_id
                },
                success: function(result) {
                    // reload_cart(function(html){});
                    if (result.error == 0) {
                        location.href = '/carts/check-out';
                    } else if (result.error == 1) {
                        alert(result.message);
                    }
                }
            });
      }, function(){
        console.log('denied');
    });
}

function changeCombo(cartKey,combo_step){
    confirm_h('Alert','Do you want to CHANGE this item','default',function(){
        $.ajax({
                url: appHelper.baseURL +'/carts/change-combo',
                type: 'POST',
                data: {
                    cartKey:cartKey
                },
                success: function(result) {
                    // reload_cart(function(html){
                    //     swith_cart(html,1);
                    // });
                    $("#category_title").css("display","none");

                    $('.close_combo_bt').hide();
                    $("#combo_alert").css("display","block");
                    var des = $("#step_description_"+combo_step).val();
                    $(".comboitem").attr("data-step",combo_step);
                    $(".comboitem").html(des+" (step "+combo_step+"/3)");
                    //open cate
                    $('.menu-item').removeClass("active");
                    if(combo_step=='1'){
                        openCate('Banh Mi SUBS',appHelper.baseURL+'/banh_mi_subs');
                        $("#menuleft_banh_mi_subs").addClass("active");
                        $("#category_title").html('Banh Mi SUBS');
                    }
                    if(combo_step=='2'){
                        openCate('Appetizers',appHelper.baseURL+'/appetizers');
                        $("#menuleft_appetizers").addClass("active");
                        $("#category_title").html('Appetizers');
                        console.log(appHelper.baseURL+'/appetizers');
                    }
                    if(combo_step=='3'){
                       openCate('Drinks',appHelper.baseURL+'/drinks');
                       $("#menuleft_drinks").addClass("active");
                       $("#category_title").html('Drinks');
                    }
                    $("#footer_cart").css("height","0px");
                }
            });
      }, function(){
        console.log('denied');
    });
}
function deleteGroupByUser(user_id) {
    confirm_h('Alert','Do you want to delete this item','default',function(){
        $.ajax({
                url: appHelper.baseURL +'/carts/remove-user-group',
                type: 'POST',
                data: {
                    user_id: user_id
                },
                success: function(result) {
                    if (result.error == 0) {
                        location.href = '/carts/check-out';
                    } else if (result.error == 1) {
                        alert(result.message);
                    }
                }
            });
      }, function(){
        console.log('denied');
    });
}

function changeGroupByUser(cartKey,user_id){
    confirm_h('Alert','Do you want to CHANGE this item','default',function(){
        $.ajax({
                url: appHelper.baseURL +'/carts/change-user-group',
                type: 'POST',
                data: {
                    cartKey:cartKey,
                    user_id:user_id
                },
                success: function(result) {
                    location.href = '/carts/check-out';
                }
            });
      }, function(){
        console.log('denied');
    });
}
function setGroup(){
    var product_id = $("#begin_choice_product").attr("data-proid");
    var username = $("#username_1").val();
    $.ajax({
            method: "POST",
            url: appHelper.baseURL + '/carts/begin-group/',
            data: {product_id:product_id,username:username},
            success:function(result){
                if(result.next_uid!=undefined){
                    $(".username").attr("value","Name of Guest "+result.next_uid);
                    $(".username").val("Name of Guest "+result.next_uid);
                    $(".username").attr("placeholder","Name of Guest "+result.next_uid);
                }
                $("#category_title").css("display","none");
                $("#group_alert").css("display","block");
                // $(".user_name_group").html('Selecting Menu Item for '+username);
                $(".user_name_group").html(' '+username);
                $('#use-group-modal').modal('toggle');
            }
        });
}
function nextGroup(){
    $.ajax({
            method: "POST",
            url: appHelper.baseURL + '/carts/next-group/',
            data: [],
            success:function(result){
                if(result.next_uid!=undefined){
                    $(".username").attr("value","Name of Guest "+result.next_uid);
                    $(".username").val("Name of Guest "+result.next_uid);
                    $(".username").attr("placeholder","Name of Guest "+result.next_uid);
                }
                $('#use-group-modal').modal('toggle');
            }
        });
}
function endGroup(){
    $.ajax({
            method: "POST",
            url: appHelper.baseURL + '/carts/end-group/',
            data: [],
            success:function(html){
                $("#category_title").css("display","block");
                $("#group_alert").css("display","none");
            }
        });
}

function vieworder(str){
    $.ajax({
        url: appHelper.baseURL + "/carts/view-order-detail",
        method: "POST",
        data: {str:str},
        success: function(result) {            
            $("#contents").html(result.message);
            $('[data-remodal-id=orderDetail]').remodal({});
        }
    });
}
function reorderThisItem(key,pkey){
    $.ajax({
        url: appHelper.baseURL + "/carts/re-order-item",
        method: "POST",
        data: {str:key,pstr:pkey},
        success: function(result) {            
            if (result.error == 0) {
                $('.main_total').text("$"+result.cart.main_total);
                $("#main_order_qty").text(result.cart.quantity);
                alertB('Reorder success. Thank you for your order');
            } else if (result.error == 1) {
                alert(result.message);
            }
        }
    });
}
function reorder(str){
    $.ajax({
        url: appHelper.baseURL + "/carts/re-order",
        method: "POST",
        data: {str:str},
        success: function(result) {            
            if (result.error == 0) {
                $('.main_total').text("$"+result.cart.main_total);
                $("#main_order_qty").text(result.cart.quantity);
                alertB('Reorder success. Thank you for your order');
            } else if (result.error == 1) {
                alert(result.message);
            }
        }
    }); 
}
function removePromo(){
    $.ajax({
        url: appHelper.baseURL + '/carts/remove-promo',
        type: 'POST',
        success: function(data){
            if(data.new_main_total!=undefined){
                $("#header_main_total").text("$"+data.new_main_total);
                $("#total_amount").text("$"+data.new_main_total);
                $("#tax_data").text("$"+data.tax);
                $("#sub_total").text("$"+data.new_sub_total);
                $('.bt_apply_promo').html('<button class="btn btn-pizzahut btn-danger" type="button" onclick="applyPromo()">Submit</button>');
                $("#promo_code").val('');
                $("#promo_code").removeAttr("disabled");
                $("#current_promo").html('');
            }
        }
    });
}
function applyPromo(){
    var coupon = $("#promo_code").val();
    $.ajax({
        url: appHelper.baseURL + '/carts/apply-promo',
        type: 'POST',
        data:{
            coupon:coupon,
        },
        success: function(data) {
            if(data.error==0){
                $("#current_promo").html(data.message);
                $('#promo_code').attr("disabled","disabled");
                $('.bt_apply_promo').html('<button class="btn btn-pizzahut btn-danger" type="button" onclick="removePromo()">Remove</button>');
                $("#sub_total").text("$"+data.total);
                $("#header_main_total").text("$"+data.new_val);
                $("#total_amount").text("$"+data.new_val);
                $("#tax_data").text("$"+data.new_tax);   
                $("#error_promo").text('');
            }else{
                $("#error_promo").text(data.message);
                $("#promo_value").text('');
            }
        }
    });
}
function addCustomCart(product_id) {

    if(product_id == undefined) product_id = $('.popup_prices .item_id').val();

    var data = getProductData(product_id);
    data.main.image = $('#product-item-'+product_id+' img').attr('src');
    $.ajax({
        url: appHelper.baseURL + "/carts/add-cart",
        method: "POST",
        data: data,
        success: function(result) {
            //console.log(result);
            if (result.error == 0) {

                /*$('.cart-total').text(result.cart.quantity);
                $("#main_order_qty").val(result.cart.quantity);
                $('.main_total').text("$"+result.cart.main_total);
                addSmallItem(result.product);

                reload_cart(function(html){

                });*/

                $('.main_total').text("$"+result.cart.main_total);                
                $(".main_order_qty").text(result.cart.quantity);                
                if(result.is_use_group==1){
                    $("#category_title").css("display","none");
                    $("#group_alert").css("display","block");
                }else if(result.combo_step!=undefined && parseInt(result.combo_step)<4){
                    //after add product
                    var des = $("#step_description_"+result.combo_step).val();
                    $(".comboitem").attr("data-step",result.combo_step);
                    $(".comboitem").html(des+" (step "+result.combo_step+"/3)");
                    //open cate
                    $('.menu-item').removeClass("active");

                    if(result.combo_step=='0'){
                        $("#category_title").css("display","block");
                        $("#combo_alert").css("display","none");
                        $(".comboitem").attr("data-step",'0');
                        $(".comboitem").html($("#step_description_0").val()+"(step 0/3)");
                        // $(".bgpopup").css("display", "none");
                        // return true;
                    }
                    if(result.combo_step=='2'){
                        openCate('Appetizers',appHelper.baseURL+'/appetizers');
                        $("#menuleft_appetizers").addClass("active");
                        $("#category_title").html('Appetizers');
                        // console.log(appHelper.baseURL+'/appetizers');
                    }
                    if(result.combo_step=='3'){
                       openCate('Drinks',appHelper.baseURL+'/drinks');
                       $("#menuleft_drinks").addClass("active");
                       $("#category_title").html('Drinks');
                    }

                }else if(parseInt(result.combo_step)>3){//endcombo
                    endCombo();
                    /*reload_cart(function(html){
                        swith_cart(html,1);
                    });*/
                }                

                $('#modalProductOptions').modal('hide');
                // alertB('Item added.');

            } else if (result.error == 1) {
                alert(result.message);
            }
        }
    });        
    
}

function alr(str){
    alert(str);return false;
}

function addCart(id) {
    
    var combo_id = $("#iscombo_"+id).val();

    // if($('#custom_'+id).val() != 1 && combo_id != 1)
    // {
    //     //case standard
    //     addCustomCart(id);
    // }
    // else
    // {
        //case custom        
        var data = {};
        var item = "#product-item-" + id;

        data.id = id;
        
        var name = $(item).find('h2');
        if(name.length > 0)
        {
            data.name = $(item + " h2").html();
        }
        else
        {
            data.name = $(item + " h3").html();   
        }        
        var str = $(item + " .product_item_sellprice").html();
        data.price = parseFloat(str.replace("$", ''));
        data.image = $(item + " img").attr('src');
        data.quantity = 1;
        data.description = $(item + " .description_item").html();
        data.product_desciption = $(item + " .product_desciption").html();
        data.combo = combo_id;
        data.isgroup = $("#isordergroup_"+id).val();

        if(data.isgroup==1){
            
            $('#use-group-modal').modal('toggle');
            $("#username_1").focus();
            $("#begin_choice_product").attr("data-proid",id);       

        }else if(data.combo==1){
            setCombo(id);
        }else{
            //open_footer();
            $('.popup_qty').val(1);
            $("#modalProductOptionsContent .item_id").val(data.id);
            $("#modalProductOptionsContent .popup_title h2").html(data.name);
            $("#modalProductOptionsContent .popup_title .description_item").html(data.product_desciption);
            $(".popup_price").html("$" + data.price);
            $("#sell_price_popup_qty_main").val(data.price);
            $("#modalProductOptionsContent .popup_image img").attr("src", data.image).load();        
            $('.op_description').html("");
            $('.note_product').val('');
            $(".popup_add_cart").css("display", "block");
            $(".popup_update_cart").css("display", "none");
            clearNoteProduct();

            if($(window).width()>768){
                $.ajax({
                    method: "POST",
                    url: appHelper.baseURL + '/products/option/' + data.id,
                    success:function(html){
                        $(".tabs").html(html);
                        calPrice();
                        if($(".popup_scroller_1 .option_item").length){
                            var popup_scroller_1 = new IScroll('.popup_option', {
                                snap: '.option_item'
                            });
                            setTimeout(function() {
                                popup_scroller_1.refresh();
                                popup_scroller_1.scrollTo(0,0);
                            }, 0);
                        }
                        if($(".popup_scroller_2 .option_item").length){
                            var popup_scroller_2 = new IScroll('.popup_option', {
                                snap: '.option_item'
                            });
                            setTimeout(function() {
                                popup_scroller_2.refresh();
                                popup_scroller_2.scrollTo(0,0);
                            }, 0);
                        }
                        
                        
                        setTimeout(function() {
                            LockAllDefault();
                            //add description default
                            // addDescriptionDefault();
                            var description_default = $('.description_default').html();
                            $('.op_description').html(description_default);
                            var height_option=0;
                            height_option = $("#modalProductOptions .modal-content").height()  - $("#modalProductOptions .product_tab").height();
                            $(".popup_option").height(height_option);
                            $('#modalProductOptions').modal('toggle');
                        }, 100);                        
                    }
                });
            }else{
                $.ajax({
                method: "POST",
                url: appHelper.baseURL + '/products/option-mobile/' + data.id,
                success:function(html){
                    $(".tabs").html(html);
                    if($(".popup_option").length>0){
                        $(".popup_add_cart_mobile").addClass("mobile-show").removeClass("mobile-hide");
                    }else{
                        $(".popup_add_cart_mobile").addClass("mobile-hide").removeClass("mobile-show");
                    }
                    //calPrice();
                    if($(".popup_scroller_1 .option_item").length){
                        var popup_scroller_1 = new IScroll('.popup_option', {
                            snap: '.option_item'
                        });
                        setTimeout(function() {
                            popup_scroller_1.refresh();
                            popup_scroller_1.scrollTo(0,0);
                        }, 0);
                    }
                    if($(".popup_scroller_2 .option_item").length){
                        var popup_scroller_2 = new IScroll('.popup_option', {
                            snap: '.option_item'
                        });
                        setTimeout(function() {
                            popup_scroller_2.refresh();
                            popup_scroller_2.scrollTo(0,0);
                        }, 0);
                    }
                    
                    
                    setTimeout(function() {
                        LockAllDefault();
                        //add description default
                        // addDescriptionDefault();
                        var description_default = $('.description_default').html();
                        $('.op_description').html(description_default);
                        var height_option=0;
                        height_option = $("#modalProductOptions .modal-content").height()  - $("#modalProductOptions .product_tab").height();
                        $(".popup_option").height(height_option);
                        $('#modalProductOptions').modal('toggle');
                    }, 100);         
                }
            });
            }
        }
    // }    

}

function getProductData(product_id){
    var parent_class = '.tabs.mobile-hide ';
    if($('.tabs.mobile-show').is(":visible"))
    {
        parent_class = '.tabs.mobile-show ';
    }

    if(product_id == undefined) product_id = $('.popup_prices .item_id').val();

    var data = {
            'main': {
                '_id': product_id,
                'quantity': $('.popup_prices .popup_qty').val(),
                'note':$(".note_product").val()
            },
            'size':$("#cate_banhmisub_size").val(),
            'category_sub':$("#cate_banhmisub").val(),
            'image':$("#img_"+product_id).attr("src"),
            'options': []
        };
    
    $(parent_class+' .popup_option .popup_qty').each(function() {
        var qty = $(this).val();
        var _id = $(this).attr('id');
        var isdefault = 0;
        if($("#isdefault_"+_id).length){
            isdefault = 1;
        }
        data.options.push({
            '_id': _id,
            'quantity': qty,
            'name': $("#name_"+_id).html(),
            'image':$("#img_"+_id).attr("src"),
            'group_id':$("#group_id_"+_id).val(),
            'group_name':$("#group_name_"+_id).val(),
            'group_type':$(this).data('group-type'),
            'group_order':$(this).data('group-order'),
            'option_group':$(this).data('group-qty'),
            'option_type':$(this).data('option-type'),
            'level':$(this).data('group-level'),
            'isfinish':$(this).data('group-finish').toString(),
            'default_qty':$("#default_qty_"+_id).val(),
            'default':isdefault
        });
    });
    // console.log(data);
    return data;
}

function addSmallItem(item) {

    if ($('#item-'+ item.cartKey).length) {
        $('#item-'+ item.cartKey + ' .item-quantity').text(item.quantity);
        $('#item-'+ item.cartKey + ' .item-sell-price').text('$'+ item.sell_price);
    } else {
        var html = '<div class="product-items col-md-2 col-sm-4" id="item-'+ item.cartKey +'">' +
                    '<div class="product_item box-shapdow-item">' +
                        '<h2>'+ item.name +'</h2><img src="'+ item.image +'" alt="'+ item.name +'">' +
                        '<div class="product_item_desc">' +
                            '<p class="description_item">'+ item.description +'</p><span class="off_in_small"></span><span class="product_item_price item-sell-price">$'+ item.sell_price +'</span><span class="product_item_qty"><strong class="item-quantity">'+ item.quantity +'</strong>pcs</span></div>' +
                        '<div class="remove_to_cart close_bg" onclick="removeCart(\''+ item.cartKey +'\');"><i class="fa fa-times"></i></div>' +
                    '</div>' +
                '</div>';
        $('#footer_cart').prepend(html);
    }
}

function reload_cart(callBack){
    $.ajax({
        method: "POST",
        url: appHelper.baseURL + "/carts/viewcart",
        data: {type:""},
        success:function(html){
            $(".cartbox").html(html);
            setTimeout(function(){
                // $(".rerotate .main_total").text($(".cartbox_price .main_total").text());
                // $(".cart-total").text($("#main_order_qty").val());
                // $("#cart-note").text($(".order_notes").text());
            },500);
            var h = $(window).height();
            var bot = h-42;
            $("#cartbox").css("height",bot+"px");
            if($('.cartbox_item').length){
                var cartbox = new IScroll('#cartbox',{snap: '.cartbox_item'});
                setTimeout(function(){
                    cartbox.refresh();
                    cartbox.scrollTo(0,0);
                }, 0);
            }
            // $("#cash_tend").val('');
            // $(".change_due").html('0.00');
            callBack(html);
        }
    });
    // $.ajax({
    //     method: "POST",
    //     url: appHelper.baseURL + "/carts/small-cart",
    //     success:function(html){
    //         $("#footer_cart").html(html);
    //     }
    // });
}

function clear_cart(){
    confirm_h('Confirm','<div class="noteja">Do you want to empty this cart? </div>','default',function(){
        $.ajax({
            url: appHelper.baseURL + '/carts/clear-cart',
            type: 'POST',
            data:{
            },
            success: function(result) {
                location.href = '/carts/check-out';
            }
        });
    },function(){
    });
}

function addNoteProduct(){
    $(".note_product").css("display","block");
    $(".add_note_product").css("display","none");
    // $(".hidden_note_product").css("display","block");
    $(".clear_note_product").css("display","block");
    $(".note_product").focus();
}
function clearNoteProduct(){
    $(".note_product").val('');
    $(".note_product").css("display","none");
    $(".add_note_product").css("display","block");
    $(".clear_note_product").css("display","none");
}

function updateCartNote(textarea){
    var note = $(textarea).val();
    $.ajax({
        url: appHelper.baseURL + '/carts/update-note',
        type: 'POST',
        data: {
            note: note
        },
        success: function (result) {
            $(".order_notes").text(note);
            if (result.error === 0) {
                $('#cart-note-modal').modal('hide');
            }
        }
    })
}

function calPrice() {
    if (typeof calProcess != 'undefined'){
        clearTimeout(calProcess);
    }
    calProcess = setTimeout(function(){
        $.ajax({
            url: appHelper.baseURL + '/products/calculate',
            data: getProductData(),
            type: 'POST',
            success: function(result) {
                if (result.error === 0) {
                    $('.popup_price').html("$" + result.total);
                    $('#sell_price_popup_qty_main').val(result.total);
                    if($("#json_option").length>0){
                        var json_option = JSON.stringify(getProductDataForAndroid());
                        $("#json_option").val(json_option);
                    }
                } else if (result.error === 1){
                    alert(result.message);
                }
                calProcess = undefined;
            }
        });
    }, 300);
}

function addDescriptionDefault(){
    $('.isdefault').each(function(){
        var idd = $(this).attr('id');
        idd = idd.replace("isdefault_","");
        if($('#default_qty_'+idd).val()==1){    
            updateDescription(idd,1);
        }
    });
}
function LockAllDefault(){
    $('.isdefault').each(function(){
        var idd = $(this).attr('id');
        idd = idd.replace("isdefault_","");
        $('#'+idd).attr("disabled", true);
        $('#islocked_'+idd).attr("value", 1);
        $('#islocked_'+idd).val(1);
    });
}
function unLockAllDefault(){
    $('.isdefault').each(function(){
        var idd = $(this).attr('id');
        idd = idd.replace("isdefault_","");
        $('#'+idd).attr("disabled", false);
        $('#islocked_'+idd).attr("value", 0);
        $('#islocked_'+idd).val(0);
    });
}
function removeValuesDefault(){
    var max = parseInt($('#max_choice').val());
    $('.isdefault').each(function(){
        if($(this).val()==1){
            var idd = $(this).attr('id');
            idd = idd.replace("isdefault_","");
            $('#'+idd).val(0);
            $("#ximg_"+idd).css("display","block");
        }
    });
}
function resetValuesDefault(){
    $('.isdefault').each(function(){
        var idd = $(this).attr('id');
        idd = idd.replace("isdefault_","");
        var dfv = $('#default_qty_'+idd).val()
        $('#'+idd).val(dfv);
        if(dfv==0){
            $("#ximg_"+idd).css("display","block");
        }else{
            $("#ximg_"+idd).css("display","none");
        }
    });
    var description_default = $('.description_default').html();
    $('.op_description').html(description_default);
}

function upQty(id,type,_notbutton){

    var parent_class = '.tabs.mobile-hide';
    if($('.tabs.mobile-show').is(":visible"))
    {
        parent_class = '.tabs.mobile-show';
    }

    // if($('#islocked_'+id, parent_class).val()==1){
    //     return false;
    // }
    var qty = parseInt($('#'+id, parent_class).val());
    if(!qty) qty = parseInt($('#'+id).val());
    //count Yes
    var max = parseInt($('#max_choice').val());
    var oldsum = 0;

    if($("select[data-group-type='Inc']", parent_class).length>0)
    $("select[data-group-type='Inc']", parent_class).each(function(){
        if(parseInt($(this).val())>0){
           oldsum++;
        }
    });
    if(qty==0)
        oldsum++;
    if(max>0 && oldsum>max)
        return alertMax();

    if(oldsum==1 && max>0 && !$('#'+id).hasClass("popup_qty_main")){
        return alertMin();
    }
    //end count Yes
    if(isNaN(qty)){
        $('.'+id).val(1);
        updateDescription(id,1);
    }else if($('.'+id).data('group-finish')==1 && qty==document.getElementById(id).options.length-1){        
        $('.'+id).val(0);
        updateDescription(id,0);
    }else{        
        if(_notbutton) $('.'+id).val(qty);
        else $('.'+id).val(qty+1);        
        updateDescription(id,qty+1);
    }
    if(type=='update_cart'){
        upload_option_qty(id.replace("fc_",""),qty+1);
    }
    else
        calPrice();
}
function onFocusQuantity(obj){    
    calPrice() ;

}
function downQty(id,type){

    var parent_class = '.tabs.mobile-hide ';
    if($('.tabs.mobile-show').is(":visible"))
    {
        parent_class = '.tabs.mobile-show ';
    }

    // if($('#islocked_'+id, parent_class).val()==1){
    //     return false;
    // }
    var qty = parseInt($(parent_class+'#'+id).val());

    if(!qty) qty = parseInt($('#'+id).val());

    //count Yes
    var max = parseInt($('#max_choice').val());
    var oldsum = 0;

    if($("select[data-group-type='Inc']", parent_class).length>0)
    $("select[data-group-type='Inc']", parent_class).each(function(){
        if(parseInt($(this).val())>0){
           oldsum++;
        }
    });
    if(qty==0)
        oldsum++;
    if(max>0 && oldsum>max)
        return alertMax();

     if(oldsum==1 && max>0){
        return alertMin();
    }
    //end count Yes
    
    if(isNaN(qty)){
        $('#'+id).val('1');
        updateDescription(id,1);
    }else if(qty>0){
        if($("#"+id).attr("data-group-qty")){
              qty = qty -1;
        }else{
            if(qty==1) qty=1;
            else qty = qty -1;
        }
        $('#'+id).val(qty);
        updateDescription(id,qty);
    }
    if(type=='update_cart'){
        upload_option_qty(id.replace("fc_",""),qty-1);
    }
    else
        calPrice();
}
function changeQty(id,type){
    var parent_class = '.tabs.mobile-hide';
    if($('.tabs.mobile-show').is(":visible"))
    {
        parent_class = '.tabs.mobile-show';
    }
    if($('#islocked_'+id, parent_class).val()==1){
        return false;
    }
    var qty = parseInt($('#'+id, parent_class).val());
    if(!qty) qty = parseInt($('#'+id).val());
    //count Yes
    var max = parseInt($('#max_choice').val());
    var oldsum = 0;
    if($("select[data-group-type='Inc']", parent_class).length>0)
    $("select[data-group-type='Inc']", parent_class).each(function(){
        if(parseInt($(this).val())>0){
           oldsum++;
        }
    });

    if(qty==0)
        oldsum++;
    if(max>0 && oldsum>max)
        return alertMax();
    //end count Yes 
    if(isNaN(qty)){
        $('#'+id).val('1');
        updateDescription(id,1);
    }else if(qty>-1){
        updateDescription(id,qty);
    }
    if(type=='update_cart'){
        upload_option_qty(id.replace("fc_",""),qty);
    }
    else
        calPrice();
}
function alertMax(){
    $(".message_box").html("ADD MAXIMUM "+$('#max_choice').val()+" OPTION(S)");
    
    setTimeout(function() {
        $(".message_box").animate({color: "white"});
        $(".message_box").html("");
        $(".message_box").animate({color: "red"});
    }, 5000);
}

function alertMin(){
    $(".message_box").html("ADD AT LEAST 1 OPTION");
    
    setTimeout(function() {
        $(".message_box").animate({color: "white"});
        $(".message_box").html("");
        $(".message_box").animate({color: "red"});
    }, 5000);
}

function openCate(cate_name, link) {
    $.ajax({
        method: "POST",
        url: link,
        data: {
            cate_name: cate_name
        },
        success:function(html){
            $(".product_left").html(html);

            $(".drapbox").hide();
            // init_height();
            // $(".popup_option").height(328);
            // if($("#product_scroll .product-items").length){
            //     var product_scroll = new IScroll('#product_scroll', {
            //         snap: '.product-items'
            //     });
            //     $(".navbar-brand").html();
            //         setTimeout(function() {
            //         product_scroll.refresh();
            //         product_scroll.scrollTo(0,0);
            //     }, 0);
            // }
            setHeightListProduct();
        }
    });
}

function updateDescription(id,qty){
    var parent_class = '.tabs.mobile-hide ';
    if($('.tabs.mobile-show').is(":visible"))
    {
        parent_class = '.tabs.mobile-show ';
    }

    if(id=='popup_qty_main')
        return false;
    
    var is_default = 0;
    var havex = 1;
    if($('#isdefault_'+id).length){
        is_default = 1;
    }

    var default_qty = parseInt($("#default_qty_"+id).val());
    var lock_all_default = parseInt($("#lock_default_when_no_choice").val());
    var change_amout = parseInt($("#change_amout").val());

    //default Yes
    var default_yes = 0;
    if(is_default==1 && qty==0)
        default_yes = 1;

    //neu qty=default va co hien trong description thi remove
    if(qty==default_qty && $('#od_'+id).length && is_default==0){
        $('#od_'+id).remove();
        havex = -1;
    //neu qty=default va khong hien trong description thi ko lam gi
    }else if(qty==default_qty && is_default==0){
        console.log("nothing");
        havex = 0;

    }else if(is_default==1 && qty==0 && $('#od_'+id).length){
        $('#od_'+id).remove();

    //hien qty khac default va va thuoc nhom finish & hien trong description
    }else if($('#od_'+id).length && $("."+id).data('group-finish')==1 && default_yes == 0){
        var lable ='';
        $("."+id+" option:selected").each(function(){            
            if($(this).text()) lable = $(this).text();            
        });
        $('#od_'+id).html($("#name_"+id).html()+' (<b>'+lable+'</b>)');
    
    //hien qty khac default & hien trong description
    }else if($('#od_'+id).length && default_yes == 0){
        $('#od_'+id).html('<b>'+qty+"</b> "+$("#name_"+id).html());
    
    //hien qty khac default va va thuoc nhom finish
    }else if($("."+id).data('group-finish')==1 && default_yes == 0){
        var lable = '';
        $(parent_class+"."+id+" option:selected").each(function(){            
            if($(this).text()) lable = $(this).text();       
        });
        $('.op_description').prepend('<p id="od_'+id+'">'+$("#name_"+id).html()+' (<b>'+lable+'</b>) </p>');

    //hien qty khac default
    }else if(default_yes == 0){
        $('.op_description').prepend('<p id="od_'+id+'"><b>'+qty+"</b> "+$("#name_"+id).html()+'</p>');

    }    
    //gach cheo hinh        
    if(qty==0){        
        $(".ximg_"+id).css("display","block");
    }else{
        $(".ximg_"+id).css("display","none");
    }      
    if(is_default==1)
        havex = 0;
    change_amout += havex;
    //co thay doi
    if(change_amout>0){
        // nhung hien tai khong thay doi defaul
        if(is_default==0 && lock_all_default==1){  
            $("#lock_default_when_no_choice").val(0);//mo khoa cac default
            //removeValuesDefault();
            unLockAllDefault();
        }
    }else{
        if(is_default==0){
            resetValuesDefault();
            LockAllDefault();
            $("#lock_default_when_no_choice").val(1);//khoa cac default
        }       
    }
    $("#change_amout").val(change_amout);
}
function upload_option_qty(id,qty){
    $.ajax({
        url: appHelper.baseURL + '/carts/update-quantity',
        type: 'POST',
        data: {
            cartKey: id,
            quantity: qty
        },
        success: function(result) {
            if (result.error == 0) {
                $("#price_fc_"+id).text(result.product.total);
                $('.cart-total').text(result.cart.quantity);
                $("#main_order_qty").val(result.cart.quantity);
                $('.money').text("$"+result.cart.total);
                $('.taxval').text("$"+result.cart.tax);
                $('.main_total').text("$"+result.cart.main_total);
            } else if (result.error == 1) {
                console.log(result.message);
            }
        }
    });
}

function optionLevel(n){
    $('.level_box').css("display","none");
    $('.level_'+n).css("display","block");
}

function editCart(id,product_id){
	var data = {};
	var item = "#cartbox_" + id;
	data.id = product_id;
	data.name = $(item + " .name").html();
	var str = $(item + " .price_one").html();
	data.price = parseFloat(str.replace("$", ''));
	data.quantity = $(item + " .quantity input").val();
	data.image = $(item+" .image img").attr("src");
	data.product_desciption = $(item+" .description").html();
	data.note = $(item+" .note").html();
	data.option = $(item+" .options").html();

	$('.popup_qty').val(data.quantity);
	$("#modalProductOptionsContent .item_id").val(data.id);
	$("#modalProductOptionsContent .cart_id").val(id);
	$("#modalProductOptionsContent .popup_title h2").html(data.name);
	$("#modalProductOptionsContent .popup_title .description_item").html(data.product_desciption);
	$("#modalProductOptionsContent .popup_title .op_description").html(data.option);
	$(".popup_price").html("$" + data.price);
	$("#sell_price_popup_qty_main").val(data.price);
	$("#modalProductOptionsContent .popup_image img").attr("src", data.image).load();        
	$('.op_description').html("");
	$('.note_product').val(data.note.replace("(","").replace(")",""));
	$(".popup_add_cart").css("display", "none");
	$(".popup_update_cart").css("display", "block");
	addNoteProduct();

	if($(window).width()>768){
		$.ajax({
			method: "POST",
			url: appHelper.baseURL + '/products/opcart/' + id,
			success:function(html){
				$(".tabs").html(html);
				calPrice();
				if($(".popup_scroller_1 .option_item").length){
					var popup_scroller_1 = new IScroll('.popup_option', {
						snap: '.option_item'
					});
					setTimeout(function() {
						popup_scroller_1.refresh();
						popup_scroller_1.scrollTo(0,0);
					}, 0);
				}
				if($(".popup_scroller_2 .option_item").length){
					var popup_scroller_2 = new IScroll('.popup_option', {
						snap: '.option_item'
					});
					setTimeout(function() {
						popup_scroller_2.refresh();
						popup_scroller_2.scrollTo(0,0);
					}, 0);
				}
				
				
				setTimeout(function() {
					LockAllDefault();
					$('.op_description').html(data.option);
					var height_option=0;
					height_option = $("#modalProductOptions .modal-content").height()  - $("#modalProductOptions .product_tab").height();
					$(".popup_option").height(height_option);
					$('#modalProductOptions').modal('toggle');
				}, 100);
				
			}
		});
	}else{
		$.ajax({
			method: "POST",
			url: appHelper.baseURL + '/products/opcart/' + id,
			success:function(html){
				$(".tabs").html(html);
				if($(".popup_option").length>0){
					$(".popup_add_cart_mobile").addClass("mobile-show").removeClass("mobile-hide");
				}else{
					$(".popup_add_cart_mobile").addClass("mobile-hide").removeClass("mobile-show");
				}
				//calPrice();
				if($(".popup_scroller_1 .option_item").length){
					var popup_scroller_1 = new IScroll('.popup_option', {
						snap: '.option_item'
					});
					setTimeout(function() {
						popup_scroller_1.refresh();
						popup_scroller_1.scrollTo(0,0);
					}, 0);
				}
				if($(".popup_scroller_2 .option_item").length){
					var popup_scroller_2 = new IScroll('.popup_option', {
						snap: '.option_item'
					});
					setTimeout(function() {
						popup_scroller_2.refresh();
						popup_scroller_2.scrollTo(0,0);
					}, 0);
				}
				
				
				setTimeout(function() {
					LockAllDefault();
					//add description default
					// addDescriptionDefault();
					var description_default = $('.description_default').html();
					$('.op_description').html(description_default);
					var height_option=0;
					height_option = $("#modalProductOptions .modal-content").height()  - $("#modalProductOptions .product_tab").height();
					$(".popup_option").height(height_option);
					$('#modalProductOptions').modal('toggle');
				}, 100);         
			}
		});
	}
}

function updateCart() {
    $('.popup_item').hide();
    var data = getProductData();
    data.main.image = $('.popup_image img').attr('src');
    data.cart_id = $('#modalProductOptionsContent .cart_id').val();
    $.ajax({
        url: appHelper.baseURL + "/carts/update-cart",
        method: "POST",
        data: data,
        success: function(result) {
            if (result.error == 0) {
                $('.cart-total').html(result.cart.quantity);
                $("#main_order_qty").val(result.cart.quantity);
                $('.main_total').html('$' + result.cart.main_total);
                location.reload();
            } else if (result.error == 1) {
                alert(result.message);
            }
        }
    });
}

function applyCoupon(){
    var coupon = $("#coupon_code").val();
     $.ajax({
        url: appHelper.baseURL + '/carts/apply-coupon',
        type: 'POST',
        data:{
            coupon:coupon,
        },
        success: function(data) {
            var t = data.message;
            if(t.indexOf("Discount")==0){
                $("#coupon_value").text(data.message);
                $("#error_coupon").text('');
                $('#coupon_code').attr("disabled","disabled");
                $('.bt_apply_voucher').html('<button class="btn btn-pizzahut btn-danger" type="button" onclick="removeCoupon()">Remove</button>');
                $("#voucher_amount").text("-$"+data.voucher_value);
                $("#header_main_total").text("$"+data.new_main_total);
                $("#total_amount").text("$"+data.new_main_total);
                $("#tax_data").text("$"+data.tax);
            }
            else{
                $("#error_coupon").text(data.message);
                $("#coupon_value").text('');
            }
            $("#error_coupon").show();
        }
    });
}

function  removeCoupon(){
    $.ajax({
        url: appHelper.baseURL + '/carts/remove-coupon',
        type: 'POST',
        success: function(data){
            if(data.new_main_total!=undefined){
                $("#voucher_amount").text("-$"+'0.00');
                $("#header_main_total").text("$"+data.new_main_total);
                $("#total_amount").text("$"+data.new_main_total);
                $("#tax_data").text("$"+data.tax);
                $('.bt_apply_voucher').html('<button class="btn btn-pizzahut btn-danger" type="button" onclick="applyCoupon()">Submit</button>');
                $("#coupon_code").val('');
                $("#coupon_value").text('');
                $("#coupon_code").removeAttr("disabled");
            }
        }
    });
}

function getProductDataForAndroid(){
    var data_return = getProductData();
    data_return['_id'] = {
        "$id":data_return['main']['_id']
    }
    data_return['name'] = $(".popup_title h2").text();
    data_return['note'] = $(".note_product").val();
    data_return['description'] = $(".description_item").text();
    data_return['image'] = $("#img_"+data_return['main']['_id']).attr("src"),
    data_return['quantity'] = data_return['main']['quantity'];
    data_return['total'] = parseFloat($(".popup_price").text().replace("$",""));
    data_return['sell_price'] = data_return['total'] / data_return['quantity'];
    data_return["is_banhmisub_category"] = data_return['category_sub']!=""?parseInt(data_return['category_sub']):0;
    data_return["is_11_inch"] = data_return['size']?parseInt(data_return['size']):0;
    data_return['main']={};
     return data_return;
}

function submitPayment(){
    var method  = $("#cash").is(':checked');
    if(method){
        $("#cardtype").val(" ");
        $("#nameoncard").val(" ");
        $("#cardnumber").val(" ");
        $("#expmonth").val(" ");
        $("#expyear").val(" ");
        $("#cvd").val(" ");
        $("#form_payment").submit();
    }else{
        ValidateForm($("#form_payment").get(0));
    }

}