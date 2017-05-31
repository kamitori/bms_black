<form action="" method="POST" accept-charset="utf-8" id="form_signin">
<div class="row main_wrap">
	<div class="col-md-8 col-md-offset-2">
		<div class="white_block">
			<div class="white_block_title">Reset your password</div>
			<p>If you've forgotten your password, we'll send you an email to reset your password.</p>
			<div class="block">
				<p>Email address<span class="red">*</span></p>
				<input name="email" class="form-control" type="email" placeholder="Your email" required>
			</div>
			<div class="block text-center">
				<button class="btn btn-danger btn-lg" type="submit">Submit</button>
			</div>
			<div id="status" style="color:red;font-weight: bold;display:{% if message is defined and message!='' %} block {%else%} none {% endif %}">{{ message }}</div>
		</div>
	</div>
</div>
</form>
