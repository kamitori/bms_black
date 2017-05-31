var total = $('.temp-li').length -1;
var i = 1;
$('.banner-header-holder').hover(function () {
	$('.banner-control-bar-prev').show();
	$('.banner-control-bar-next').show();
}, function () {
	$('.banner-control-bar-prev').hide();
	$('.banner-control-bar-next').hide();
});

$('#banner_div').data('attr-url', $("#banner_div div a.transparent").data('attr-url'));
$('#banner_div').data('attr-target', $("#banner_div div a.transparent").data('attr-target'));
$('#banner_div div a').attr('href', $("#banner_div div a.transparent").data('attr-url'));

$('.banner-slide-indicator > ul > li').click(function () {
	var id = parseInt($(this).data('banner-indicator'));
	var htmlElement = $(this);
	bannerSlider(id, htmlElement);
	i = id;
});
$('.bannerImages a ').on('click',function(){	
	var temp = $('.bannerImages a.transparent').attr('data-url');
	var p_id = $('.bannerImages a.transparent').attr('data-key');
	if(p_id){
		addCart(p_id);
	}else{
		window.location.href = temp;
	}
});
$('.SliderImages a').on('click',function(){	
	var temp = $(this).attr('data-url');
	var p_id = $(this).attr('data-key');
	if(p_id){
		addCart(p_id);
	}else{
		window.location.href = temp;
	}
});
$('.banner-control-bar-prev a').click(function () {
	var id = parseInt($('.banner-control-bar-prev').data('banner-indicator'));
	var htmlElement = $('#indicator_' + id);
	bannerSlider(id, htmlElement);
	i = id;	
});

$('.banner-control-bar-next a').click(function () {
	var id = parseInt($('.banner-control-bar-next').data('banner-indicator'));
	var htmlElement = $('#indicator_' + id);
	bannerSlider(id, htmlElement);
	i = id;
});
// bannerSlider(i, $('#indicator_0'));
runsiler();
function runsiler(){	
	setInterval(function () {
		if (i > total) {
			i = 0;
		}
		// alert(i)
		bannerSlider(i, $('#indicator_' + i));
		i++;
	}, 3000);
}
function bannerSlider(i, htmlElement){
   $('.banner-slide-indicator > ul > li.active').removeClass('active');
   htmlElement.addClass('active');
   $("#banner_div div a").removeClass("transparent");
   $('.bannerTextDiv').removeClass('transparent');
   var newImage = $('.banner-slide-indicator > ul > li.active').index();
   $("#banner_div div a").eq(newImage).addClass("transparent");
   $('#bannertext_' + i).addClass("transparent");
   $('.banner-control-bar-prev').data('banner-indicator', (i == 0) ? total : i - 1);
   $('.banner-control-bar-next').data('banner-indicator', (i == total) ? 0 : i + 1);
   var titleText = $('#bannertext_' + i).children('p.titleText').text();
   if (titleText == '') {
	   $('.bannerTextBand').removeClass("transparent");
   }
   else {
	   $('.bannerTextBand').addClass("transparent");
   }
   $('#banner_div').data('attr-url', $("#banner_div div a.transparent").data('attr-url'));
   $('#banner_div').data('attr-target', $("#banner_div div a.transparent").data('attr-target'));
   $('#banner_div div a').attr('href', $("#banner_div div a.transparent").data('attr-url'));
}