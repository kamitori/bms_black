<!DOCTYPE html>
<html class=" js no-touch turbolinks-progress-bar" xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraphprotocol.org/schema/" lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="ROBOTS" content="INDEX, FOLLOW">
        <meta content="1055465601202070" property="fb:app_id">
        <meta name="description" content="{% if description is defined and description != '' %} {{description}} {% else %} {{META_DESCRIPTION}} {% endif %}">        
        <meta name="keywords" content="{{KEYWORDS}}">
        <meta name="author" content="Anvy Developers">

        <link rel='shortcut icon' type='image/x-icon' href='{{baseURL}}/themes/pizzahut/images/fav.ico'/>

        <meta property="og:url" content="{{ baseURL }}{{ router.getRewriteUri() }}">
        <meta property="og:image" content="{{baseURL}}/themes/pizzahut/images/default.png">
        <meta property="og:description" content="{% if description is defined and description != '' %} {{description}} {% else %} {{META_DESCRIPTION}} {% endif %}">
        <meta property="og:title" content="{% if title is defined and title != '' %} {{title}} {% else %} {{META_TITLE}} {% endif %}">
        <meta property="og:site_name" content="{% if title is defined and title != '' %} {{title}} {% else %} {{META_TITLE}} {% endif %}">
        <meta property="og:see_also" content="{{ baseURL }}">

        <meta itemprop="name" content="{% if title is defined and title != '' %} {{title}} {% else %} {{META_TITLE}} {% endif %}">
        <meta itemprop="description" content="{% if description is defined and description != '' %} {{description}} {% else %} {{META_DESCRIPTION}} {% endif %}">
        <meta itemprop="image" content="{{baseURL}}/themes/pizzahut/images/default.png">

        <meta name="twitter:card" content="summary">
        <meta name="twitter:url" content="{{ baseURL }}{{ router.getRewriteUri() }}">
        <meta name="twitter:title" content="{% if title is defined and title != '' %} {{title}} {% else %} {{META_TITLE}} {% endif %}">
        <meta name="twitter:description" content="{% if description is defined and description != '' %} {{description}} {% else %} {{META_DESCRIPTION}} {% endif %}">
        <meta name="twitter:image" content="{{baseURL}}/themes/pizzahut/images/default.png">

        <meta name="title" content="{% if title is defined and title != "" %} {{title}} {% else %} {{META_TITLE}} {% endif %}"/>
        <title>{% if title is defined and title != "" %} {{title}} {% else %} {{META_TITLE}} {% endif %}</title>

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
        </style>
        {{ assets.outputCss() }}
    </head>
    <body  ng-controller="dialogServiceTest">
        <div id="fb-root"></div>

        <div class="header">
            {{ partial('blocks/header') }}
            <div class="container">
                {{ partial('blocks/nav') }}
            </div>
        </div>
        <div class="cleafix"> </div>
        <div class="main-content container">
            <!-- Mobile Menu -->
            <div id="mobile" show="0">
                 {{ partial('blocks/menu_mobile') }}
            </div>
            <div id="desktop">
                    <!-- Static navbar -->
                        <div class="product_left">
                        {{ content() }}
                        </div>
                    <!-- Order by Group -->
                    {% if is_homepage is not defined %}
                    <div class="row ">
                        <div class="col-md-12">
                            <a href="http://group.banhmisub.com" target="_blank"><img src="{{baseURL}}/themes/pizzahut/images/banner10discount.gif" alt="Order by Group" style="width:100%;"></a>
                        </div>
                    </div>
                    {%endif%}
                    <!-- Footer -->
                        {{ partial('blocks/footer')}}
            </div>
        </div>
        <!-- /container -->
        {{ partial('blocks/search_product')}}

        <!-- Loading -->
        <div class="loading" style="display:none;">
            <div class="loading-message">
                <span class="logo"></span>
                <span>Loading menu...</span>
            </div>
        </div>

        <script type="text/javascript">
            var appHelper = {
                baseURL: '{{ baseURL }}',
                JT_URL: '{{ JT_URL }}',
                POS_URL: '{{ POS_URL }}'
            };
        </script>

        {{ assets.outputJs() }}
        {{ assets.outputJs('pageJS') }} 
        <input type="hidden" value="{{list_hours}}" id="list_hours" />
    </body>
</html>