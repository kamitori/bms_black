<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="">
		<meta name="author" content="Anvy Developers">
		<title>POS BANHMISUB.COM</title>
		{{ assets.outputCss() }}
	</head>
	<body>
		<div class="pos-content container-fluid fixwidth">
			<!-- Static navbar -->
			{{ partial('/blocks/pos-nav-menu') }}
			<div class="row pos-main">
				<div class="col-md-6">
					<div class="row cach-items fixmargin">
						<div class="cash-header">
							<form>
								<input type="text" value="" />
								<button class="button pizza-button searchbt" type="button">
			                        <span>Cash In/Out</span>
			                    </button>
							</form>
						</div>
						<div class="cash-content"></div>
						<div class="cash-footer"></div>
					</div>
				</div>
				<div class="col-md-6">
					<!-- Static navbar -->
					<nav id="main_menu" class="navbar navbar-default">
						<div class="menu">
							<div class="navbar-header hidden">
								<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
									<span class="sr-only">Toggle navigation 1</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
							</div>
							{{ partial('/blocks/nav') }}
						</div>
					</nav>
					<div class="row pos-product-items fixmargin">
						{{ListProducts}}
					</div>
					<div class="product-footer">
						
					</div>
				</div>
			</div>
		</div>

		{{ assets.outputJs() }}
	</body>
</html>