<form action="" method="POST" accept-charset="utf-8" id="form_signin">
<div class="row main_wrap">
	<div class="col-md-8 col-md-offset-2">
		<div class="white_block">
			<div class="white_block_title">Reset your password</div>
			<p>Please input your new password. After reset password, please sign in again.</p>
			<div class="block">
				<p>New password<span class="red">*</span></p>
				<input name="password" class="form-control" type="password" required pattern=".{6,}" title="6 - 16 characters" maxlength="16">
				<p>Password is 6 - 16 characters</p>
			</div>
			<div class="block">
				<p>Re-new password<span class="red">*</span></p>
				<input name="re_password" class="form-control" type="password" required pattern=".{6,}" title="6 - 16 characters" maxlength="16">
				<p>Password is 6 - 16 characters</p>
			</div>
			<div class="block text-center">
				<button class="btn btn-danger btn-lg" type="submit">Submit</button>
			</div>
			<div id="status" style="color:red;font-weight: bold;display:{% if message is defined and message!='' %} block {%else%} none {% endif %}">{{ message }}</div>
		</div>
	</div>
</div>
</form>
