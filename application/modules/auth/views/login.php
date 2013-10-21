<style type="text/css">
	.dashboard-wrapper .left-sidebar {
		margin-right: 0;
	}
	.dashboard-wrapper .left-sidebar .widget {
		margin-bottom: 0;
	}
	.dashboard-wrapper .left-sidebar .widget .widget-header {
		padding: 5px;
	}
	.form-actions {
		margin-bottom: 0;
		margin-top: 0;
		padding: 5px;
	}
	.form-horizontal .control-group {
		margin-bottom: 4px;
	}
	
	input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"] {
		font-size: 12px;
	}
	label, input, button, select, textarea {
		font-size: 12px;
	}
	input[type="text"], 
	input[type="password"], 
	input[type="datetime"], 
	input[type="datetime-local"], 
	input[type="date"], 
	input[type="month"], 
	input[type="time"], 
	input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"] {
		padding: 2px 4px;
	}
</style>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<div class="widget-header">
					<div class="title">Login</div>
					<span class="tools">
						<a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a>
					</span>
				</div>
				<div class="widget-body">
					<div class="span3">&nbsp;</div>
					<div class="span6">
						<div class="sign-in-container">
							<form action="<?php echo site_url('auth/login'); ?>" class="login-wrapper" method="post">
								<div class="header">
									<div class="row-fluid">
										<div class="span12">
											<h3 style="color: #0C9ABB;">Login<img src="<?php echo base_url('assets/img/logo1.png'); ?>" alt="Logo" class="pull-right"></h3>
											<p>Isi form dibawah ini untuk login.</p>
										</div>
									</div>
								</div>
								<div class="content">
									<div class="row-fluid">
										<div class="span12">
											<input class="input span12" id="username" name="username" placeholder="Username" required="required" type="text" value="" />
										</div>
									</div>
									<div class="row-fluid">
										<div class="span12">
											<input class="input span12 password" id="password" name="password" placeholder="Password" required="required" type="password" />
										</div>
									</div>
								</div>
								<div class="actions">
									<input class="btn btn-info" name="Login" type="submit" value="Login" >
									<!--a class="link" href="#">Forgot Password?</a-->
									<div class="clearfix"></div>
								</div>
							</form>
						</div>
					</div>
					<div class="span3">&nbsp;</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
</div>