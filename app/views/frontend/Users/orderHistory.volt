{% if orders is not empty %}
<div class="table-responsive">
	<table class="table table-hover">
	    <thead >
	      <tr class="active" style="height:70px;font-weight:800;">
	        <th width="40%">Order information<br /><br /></th>
	        <th width="10%">Total<br /><br /></th>
	        <th width="20%">Status<br /><br /></th>
	        <th class="text-center">Option<br /><br /></th>
	      </tr>
	    </thead>
	    <tbody>
	    	{% set run = 1 %}
	   		{% for item in orders %}
	   		{% set class_ = run%2 ? 'info' : 'success' %}
	   		{% set run=run+1 %}
	   			<tr class="{{class_}}">
			        <td>
			        	Order name: {{item['name']}} <br /><br />
			        	Order number : {{item['number']}} <br />
			        </td>
			        <td>{{dinhdangtien(item['total'])}}</td>
			        <td>{{item['status']}}</td>
			        <th>
			        	<a class="btn btn-primary" href="#orderDetail" onclick="vieworder('{{item['_id']}}')" )">View and Reorder item</a>
			        	<button class="btn btn-primary" onclick="reorder('{{item['_id']}}')">Reorder all item</button>
			        </th>
			    </tr>  
	   		{% endfor %}	          
	    </tbody>
  </table>
</div>
<style>
.remodal{
	padding-top:30px !important;
	width:100% !important;
}
</style>
<div data-remodal-id="orderDetail" role="dialog" aria-labelledby="modal2Title" aria-describedby="modal2Desc">
  <div>
    <h3 id="modal2Title" style="text-align:left;border-bottom:1px dotted;padding-bottom:10px;">Order Detail</h3>
    <hr/>
   	<div id="contents">
   		hello
   	</div>
  </div>
  <br>
  <div style="text-align:right"><button data-remodal-action="confirm" class="remodal-confirm">Exit</button></div>
</div>
{% else %}
	<div class="row welcome ng-scope">
	    <div class="col-md-12">
	        <h1 class="text-left ng-binding ng-scope">Hey There!</h1>
	        <div>
	            <p class="text-left ng-binding ng-scope">
	                Your order history is empty.
	            </p>
	        </div>
	    </div>    
	</div>
{% endif %}