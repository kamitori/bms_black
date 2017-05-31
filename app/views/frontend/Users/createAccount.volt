<form action="" method="POST" accept-charset="utf-8" id="form_create_account">
	<div class="row main_wrap">
		<div class="main_wrap_title">Create an account for faster ordering & special offers</div>
		<div class="col-md-9">
			<div>
				<span class="ball">1</span>
				<span style="font-size: 120%; font-family: 'UnitedSans';">Tell us about yourself <span style="color:#fed75f;" class="required">*required</span></span>
			</div>
			<div class="white_block">
				<div class="block">
					<div id="status" style="color:red;font-weight: bold;display:{% if message is defined and message!='' %} block {%else%} none {% endif %}">{{ message }}</div>
				</div>
				<div class="block">
					<p>Full name<span class="red">*</span></p>
					<input name="first_name" required aria-required="true" maxlength="50" class="form-control" type="text" placeholder="First name">
					<input name="last_name" required aria-required="true" maxlength="50" style="margin-top:20px;" class="form-control" type="text" placeholder="Last name">
				</div>
				<div class="block">
					<p>Email address<span class="red">*</span></p>
					<input type="email" onchange="check_user(this)" required aria-required="true" maxlength="100"  name="email" id="email" class="form-control" placeholder="e.g. you@account.com">
				</div>	
				<div class="block">
					<p>Phone number</p>
					<input type="phone" placeholder="xxx-xxx-xxxx"  name="phone" id="order_phone" class="form-control" maxlength="12">
				</div>			
			</div>

			<div style="margin-top:40px;">
				<span class="ball">2</span>
				<span style="font-size: 120%; font-family: 'UnitedSans';">Secure your account <span style="color:#fed75f" class="required" >*required</span></span>
			</div>
			<div class="white_block">
				<div class="block">
					<p>Password<span class="red">*</span></p>
					<input name="password" id="password"  required aria-required="true"  class="form-control" type="password" placeholder="Password">
				</div>
				<div class="block">
					<p>Retype password<span class="red">*</span></p>
					<input name="re_password" id="re_password"  required aria-required="true" onchange="check_reinput('password')" class="form-control" type="password" placeholder="Retype password">
				</div>
				<!-- <div class="block">
					<p>Security answer<span class="red">*</span></p>
					<select name="" id="" class="select2 form-control" data-placeholder="--Select question--">
						<option value=""></option>
						<option value="1">Lorem ipsum dolor sit amet.</option>
						<option value="2">Aspernatur cupiditate itaque dolor? Amet.</option>
						<option value="3">Voluptatem velit deleniti facere mollitia.</option>
						<option value="4">Dignissimos doloremque, quidem nemo deserunt.</option>
					</select>
				</div>
				<div class="block">
					<p>Security answer<span class="red">*</span></p>
					<input class="form-control" type="text" placeholder="Answer">
				</div> -->
				<div class="block" style="text-align: justify;">
					I agree to the <a href="/pages/terms-of-use">Terms of Use</a> and understand that my information will be used as described in the <a href="/pages/privacy-policy">BanhMiSub Privacy Policy</a>.
				</div>
				<div class="block mobile-hide">
					<button class="btn btn-lg btn-danger" type="submit">Create account</button>
					&nbsp;&nbsp;&nbsp;
					<button class="btn btn-lg btn-default" type="reset">Cancel</button>
				</div>
				<div class="block mobile-show">
					<button class="btn btn-block btn-danger" type="submit">Create account</button>
					<br/>
					<button class="btn btn-block btn-default" type="reset">Cancel</button>
				</div>
			</div>
		</div>
		<div class="col-md-3">
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