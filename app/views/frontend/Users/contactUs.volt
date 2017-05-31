<form action="" method="POST" accept-charset="utf-8" id="form_contact_us" name="form_contact_us" onsubmit="return submitContact();">
	<div class="row main_wrap">
		<div class="main_wrap_title" style="margin-bottom:0;">Contact us</div>
		<div class="col-md-9 col-xs-12">
			<div class="white_block">
				<div class="block">
					<p>Full name<span class="red">*</span></p>
					<input name="contact_name" required aria-required="true" maxlength="150" class="form-control" type="text" placeholder="Your Name">					
				</div>
				<div class="block">
					<p>Email address<span class="red">*</span></p>
					<input type="email" required aria-required="true" maxlength="200"  name="contact_email" id="email" class="form-control" placeholder="e.g. you@account.com">
				</div>				
				<div class="block">
					<p>Phone number<span class="red">*</span></p>
					<input required aria-required="true" name="contact_phone" maxlength="20" class="form-control" type="tel" title="Phone must be 10 numbers" pattern="\d{3}[\-]?\d{3}[\-]?\d{4}$" placeholder="xxx-xxx-xxxx" maxlength="12">
				</div>
				<div class="block">
					<p>Primary interest<span class="red">*</span></p>
					<div class="row">
						<div class="col-md-12">
							<select required aria-required="true" name="primary_interest" id="primary_interest" class="select2 form-control" data-placeholder="--Primary interest--">
								<option value="general" selected>General</option>
								<option value="catering"  >Catering</option>
								<option value="metting">Meeting Request</option>							
								<option value="startup">Startup</option>
							</select>							
						</div>						
					</div>
				</div>
				<div class="block">
					<p>Your message</p>
					<div class="row">
						<div class="col-md-12">
							<textarea class="form-control" name="message" required style="min-height:200px;" value=""></textarea>						
						</div>						
					</div>
				</div>
				<div class="block">
					<p>Captcha<span class="red">*</span></p>
					<p><canvas id="canvas_captcha" width="400" style="border: 1px solid #999;"></canvas> <button class="btn btn-xs" title="Renew captcha code" onclick="createCaptcha()" type="button"><i class="fa fa-refresh"></i></button></p>
					<input type="text" placeholder="Captcha code" required name="captcha_code" id="captcha_code" class="form-control required_field" maxlength="5" required title="Wrong capcha">
					<span class="error_captcha" style="display:block; color:red;"></span>
						
				</div>
				<div class="block">
					<button class="btn btn-lg btn-danger">Send</button>
					&nbsp;&nbsp;&nbsp;
					<button class="btn btn-lg btn-default" type="reset">Cancel</button>					
				</div>
			</div>
		</div>
		<div class="col-md-3 col-xs-12 contact-us" style="text-align:center; font-family:FF-FallingSkyBd,'Falling Sky',sans-serif !important">
			<p style="font-size:1.3em; line-height:1.3em;">{% if summary is defined %} {{ summary }} {% endif %}</p>
			<p style="font-size:1.1em; line-height:1.1em;">{% if content is defined %} {{ content }} {% endif %}</p>
		</div>
	</div>
</form>