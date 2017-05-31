<div class="col-md-12" style="margin-top: 25px;">
	<button class="btn btn-default pizbutton" onclick="showAddress()" style="margin-bottom:20px;">Add address</button>
	<form action="" id="frm_address" style="display:none;" style="margin-top: 20px;">
		<input type="hidden" name="key">
		<div class="columns col-md-6 row" style="margin-bottom:20px;">
            <div class=" col-md-4 col-xs-12" style="line-height:30px">
                <label class="group-label ng-binding">
                    Postal code <span class="ph-required">*</span>
                </label>
            </div>
            <div class="col-md-8 col-xs-12">
                <div class="control-group row ng-scope">
                    <div class="fld-ctr columns">
                        <input type="text" placeholder="Postal code" required="" name="zip_postcode" id="postal_code" class="form-control address_fields" value="">
                    </div>
                </div>
            </div>
        </div>
		<div class="columns col-md-6 row" style="margin-bottom:20px;">
            <div class=" col-md-4 col-xs-12" style="line-height:30px">
                <label class="group-label ng-binding">
                    Street adress<span class="ph-required">*</span>
                </label>
            </div>
            <div class="col-md-8 col-xs-12">
                <div class="control-group row ng-scope">
                    <div class="fld-ctr columns">
                        <input type="text" placeholder="Street adress" required="" name="address_1" id="street_address" class="form-control address_fields" value="">
                    </div>
                </div>
            </div>
        </div>
        <div class="columns col-md-6 row" style="margin-bottom:20px;">
            <div class=" col-md-4 col-xs-12" style="line-height:30px">
                <label class="group-label ng-binding">
                    City <span class="ph-required">*</span>
                </label>
            </div>
            <div class="col-md-8 col-xs-12">
                <div class="control-group row ng-scope">
                    <div class="fld-ctr columns">
                        <input type="text" placeholder="City" required="" name="town_city" id="city" class="form-control address_fields" value="">
                    </div>
                </div>
            </div>
        </div>
        <div class="columns col-md-6 row" style="margin-bottom:20px;">
            <div class=" col-md-4 col-xs-12" style="line-height:30px">
                <label class="group-label ng-binding">
                    Province <span class="ph-required">*</span>
                </label>
            </div>
            <div class="col-md-8 col-xs-12">
                <div class="control-group row ng-scope">
                    <div class="fld-ctr columns">
                        <select type="text" placeholder="Province" required="" name="province_state_id" id="province" class="form-control address_fields" value="">
                        	{% for province in arr_province %}
                        		<option value="{{province['key']}}" data-name="{{province['name']}}">{{province['name']}}</option>
                        	{% endfor %}
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="columns col-xs-12 row text-center" style="margin-bottom:20px;">
        	<button class="btn btn-default pizbutton" type="button" onclick="addAddress()">Save</button>
        </div>
	</form>
</div>
<div class="col-md-12">
	<h2>List address</h2>
	<div id="list-address">
	</div>
</div>

<style type="text/css" media="screen">
	.item-address{
		border: 1px solid #392113;
		padding: 15px;
		margin-bottom: 15px;
	}
</style>
