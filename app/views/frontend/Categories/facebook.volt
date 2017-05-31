<html class=" js no-touch turbolinks-progress-bar" xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraphprotocol.org/schema/" lang="en">
   <head>
      <title>Vote for your favorite banhmusub</title>
      <link rel="stylesheet" media="screen" href="//fonts.googleapis.com/css?family=Lato:700">
      <link rel="stylesheet" media="all" href="/public/css/temp.css">
      <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
      <link rel='shortcut icon' type='image/x-icon' href='{{baseURL}}/themes/pizzahut/images/fav.ico'/>
      <!-- / Mobile meta tags -->
      <meta content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" name="viewport">
      <meta content="yes" name="apple-mobile-web-app-capable">
      <meta content="Contests for Facebook" property="og:site_name">
      <meta content="190076381016644" property="fb:app_id">
      <meta content="en_US" property="og:locale">
      <meta content="my-contests:form" property="og:type">
      <meta content="Vote for the cutest cat" property="og:title">
      <meta content="Here are the item of banhmisub. Vote for your favorite now!" name="description">
      <!-- post thum -->
      <meta content="http://banhmisub.com/images/banners/coffee_06-05-16.25-05-16.jpg" property="og:image">
      <meta content="1200" property="og:image:width">
      <meta content="900" property="og:image:height">
      <style>html.turbolinks-progress-bar::before {
         content: '';
         position: fixed;
         top: 0;
         left: 0;
         z-index: 2000;
         background-color: #0076ff;
         height: 3px;
         opacity: 0.99;
         width: 0%;
         transition: width 300ms ease-out, opacity 150ms ease-in;
         transform: translate3d(0,0,0);
         }
      </style>
      <style id="fit-vids-style">.fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}</style>
   </head>
   <body class="new canvas desktop premium contest en" id="forms">
      <div class=" fb_reset" data-app-id="190076381016644" data-sdk-locale="en_US" data-tld="contest">
         <div style="position: absolute; top: -10000px; height: 0px; width: 0px;">
            <div>
               <!-- iframe -->
            </div>
         </div>
      </div>
      <div id="main">
         <div class="inner"></div>
         <div class="inner" id="poll-content">
            <h1 style="color: #3B5998;">
               Vote for the your favorite banhmisub
            </h1>
            <form class="questionnaire fluid" data-type="json" data-error-msg="An unexpected error occurred. Please try to submit the form again in a few moments." data-require-login="true" data-logged-in="false" data-extra-auth-permissions="" id="new_form" action="" accept-charset="UTF-8" data-remote="true" method="post">
               <input name="utf8" value="✓" type="hidden">
               <label class="rich-content" id="q970008_label">
                  <div class="label-text mandatory">Which one of these is your favorite?</div>
               </label>
               <div class="choices">                  

                  	{% for product in products %}
	                <div class="choice choice-3428874 full-width-choice outlinable">
	                    <div class="control">
	                        <div style="position: relative;" class="iradio_minimal"><input style="position: absolute; opacity: 0;" value="3428874" name="form[answers_attributes][0][choice_id]" id="form_answers_attributes_0_choice_id_{{product['id']}}" type="radio"><ins style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;" class="iCheck-helper"></ins></div>
	                    </div>
	                    <label class="radio rich-content" for="form_answers_attributes_0_choice_id_{{product['id']}}">
	                        <div class="image_field">
	                           <img class="tiny user-img" src="{% if product['image'] is defined and product['image'] != '' %}{{ product['image'] }}{%else%}{{baseURL}}/themes/pizzahut/images/default.png{%endif%}" alt="{{product['name']}}" alt="{{ product['name'] }}">
	                           <div class="caption"></div>
	                        </div>
	                    </label>
	                  </div>
	                  {% endfor %}

               </div>
         </div>
         </form>         
      </div>
      </div>      
   </body>
</html>