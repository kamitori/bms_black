<div class="row">
    <div class="col-md-12">
        <hr>
    </div>
</div>
<div class="row menufooter" style="background: rgba(19, 18, 17, 0.28);margin: 25px 0 0;">
    <div class="col-md-2 fitem col-xs-2 mobile-hide">
        <div>
            <h4>Order</h4>
        </div>
        <div class="ng-scope">
            <ul class="subnav" id="navigation">
                {% for item in categories %}
                    <li title="{{item['name']}}" class="ng-scope">
                        <a href="{{ baseURL }}/{{ item['short_name'] }}" class="ng-binding">{{item['name']}}</a>
                    </li>
                {% endfor %}
            </ul>
        </div>
    </div>

    <div class="col-md-2 fitem col-xs-2 mobile-hide">
        {% set order=1 %}
        {% for item in menufooter %}
            {% if loop.first %}
                {% set order=item.order_no %}
            {% endif %}
            {% if order != item.order_no %}
                </div><div class="col-md-2 fitem col-xs-2 mobile-hide">
            {% endif %}
            {% set order=item.order_no %}
                <div><h4>{{item.name}}</h4></div>
                <div class="ng-scope">
                    {% if submenufooter[item.id] is defined %}
                        <ul class="subnav" id="navigation">
                            {% for subitem in submenufooter[item.id] %}
                                <li title="{{subitem['name']}}" class="ng-scope">
                                    <a {% if subitem['short_name'] == "find-a-banhmisub" %} data-toggle="modal" data-target="#myModal" href="#" {%else%} href="{{ baseURL }}/{{ subitem['type'] }}/{{ subitem['short_name'] }}" {% endif %}class="ng-binding">{{subitem['name']}}</a>
                                </li>
                            {% endfor %}
                        </ul>
                    {% endif %}
                </div>
        {% endfor %}
    </div>
    <div class="col-md-4 fitem col-xs-4">
        <h4>Connect with Banh Mi Sub</h4>
        <div class="row social_footer">
            <a href="https://www.facebook.com/banhmisub" target="_blank">
                <div class="col-xs-3">
                    <span id="fb_icon"></span>
                </div>
                <div class="col-xs-9">
                    Like us on Facebook
                </div>
            </a>
        </div>
        <div class="row social_footer">
            <a href="https://twitter.com/banhmisub" target="_blank">
                <div class="col-xs-3">
                    <span id="tw_icon"></span>
                </div>
                <div class="col-xs-9">
                    Follow us on Twitter
                </div>
            </a>
        </div>
        <div class="row social_footer">
            <a href="#" target="_blank">
                <div class="col-xs-3">
                    <span id="yt_icon"></span>
                </div>
                <div class="col-xs-9">
                    Subscribe to us on YouTube
                </div>
            </a>
        </div>
    </div>
<div class="col-md-12">
    <div class="col-md-6">
        <p style="color: #fff;">* We accept Cash, Visa, Mastercard & Interac</p>
        <p style="color: #fff;">** Tax not included in prices</p>
    </div>
    <div class="col-md-6">
        <img class="pull-right" src="{{baseURL}}/themes/pizzahut/images/secure_website_shopping-BMS-01.svg" alt="Order by Group" style="height:50px;">
    </div>
</div>
</div>
<div class="row">
    <div class="col-md-12 textfooter">
       {{ configs['about_footer'] }}
    </div>
</div>
<a href="#top" class="cd-top cd-is-visible cd-fade-out">Top</a>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDFoM1wphoUij3RFvWiLjKKrgHpRgASQ0w"></script>
