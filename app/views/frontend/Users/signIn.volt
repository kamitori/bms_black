<form action="" method="POST" accept-charset="utf-8" id="form_signin" autocomplete="off">
<div class="row main_wrap">
	<div class="col-md-8">
		<div class="white_block">
			<div class="white_block_title">Sign in to your account</div>
			<div class="block">
				<p>Email address<span class="red">*</span></p>
				<input name="email" class="form-control" type="text" value="{% if u is defined %}{{u}}{%endif%}" placeholder="" autocomplete="off" >
				
				<input type="text" style="visibility:hidden;">
                <input type="password" style="visibility:hidden;">                
			</div>
			<div class="block">
				<p>Password<span class="red">*</span> &nbsp;&nbsp;<a href="forgot-password" class="mobile-hide">Forgot your password?</a></p>				
				<input type="text" style="display:none">
                <input type="password" style="display:none">
				<input name="password" class="form-control" value="{% if p is defined %}{{p}}{%endif%}" type="password" placeholder="" autocomplete="off" >

			</div>
			<div class="block mobile-show">
				<a href="forgot-password">Forgot your password?</a></p>
			</div>
			<div class="block mobile-hide">
				<p>
					<input name="remember" type="checkbox" name="" value="">
					Remember Me
				</p>
				<button class="btn btn-danger btn-lg" type="submit">Sign in</button>
				&nbsp;&nbsp;or&nbsp;&nbsp;
				<button class="btn btn-facebook btn-lg" type="button" onclick="checkLoginState()"><i class="fa fa-facebook"></i> Sign In with Facebook</button>
			</div>
			<div class="block mobile-show">
				<p>
					<input name="remember" type="checkbox" name="" value="">
					Remember Me
				</p>
				<button class="btn btn-danger btn-block" type="submit">Sign in</button>
				<br/>
				<button class="btn btn-facebook btn-block" type="button" onclick="checkLoginState()"><i class="fa fa-facebook"></i> Sign In with Facebook</button>
			</div>
			<div id="status" style="color:red;font-weight: bold;display:{% if message is defined and message!='' %} block {%else%} none {% endif %}">{{ message }}</div>
		</div>
		<br/>
	</div>
	<div class="col-md-4">
		<div class="right_title">
			With your account you can...
		</div>
		<ul class="right_list">
			<li>Save your favourites for faster ordering</li>
			<li>Speed through checkout with saved delivery addresses and payment information</li>
			<li>Get exclusive deals, right in your inbox</li>
		</ul>
	</div>
</div>
</form>

<div class="modal" id="modal_fb">
	<form action="/users/create-account" method="POST" accept-charset="utf-8" id="form_create_account">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Create Account</h4>
			</div>
			<div class="modal-body" style="background:#fff !important;">
				<div class="white_block" style="margin-top: 0px; ">
						<input type="hidden" name="first_name" id="first_name">
						<input type="hidden" name="last_name" id="last_name">
						<input type="hidden" name="email" id="email">
						<input type="hidden" name="re_email" id="re_email">
						<input type="hidden" name="facebook_id" id="facebook_id">
						<div class="block">
							<p>Password<span class="red">*</span></p>
							<input name="password" id="password"  required aria-required="true"  class="form-control" type="password" placeholder="Password">
						</div>
						<div class="block">
							<p>Retype password<span class="red">*</span></p>
							<input name="re_password" id="re_password"  required aria-required="true" onchange="check_reinput('password')" class="form-control" type="password" placeholder="Retype password">
						</div>
						<div class="block">
							<input type="checkbox" name="subscribe" value="">
							Email Opt In (by opting in you will receive emails, promotions and special offers from BanhMiSub.)
						</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-invert btn-lg" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-danger btn-lg">Create Account</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
	</form>
</div><!-- /.modal -->
<style>
	#modal_fb *{
		font-family: 'UnitedSans' !important;
	}
</style>
