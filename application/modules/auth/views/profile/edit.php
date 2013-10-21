<style type="text/css">
	.dashboard-wrapper {
		margin-bottom: 10px;
	}
	.dashboard-wrapper .left-sidebar {
		margin-right: 0;
	}
	.dashboard-wrapper .left-sidebar .widget {
		margin-bottom: 0;
	}
	.dashboard-wrapper .left-sidebar .widget .widget-header {
		padding: 5px;
	}
	.dashboard-wrapper .left-sidebar .widget .widget-body {
		border-bottom: 0;
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
	hr {
		margin: 1px 0 5px;
	}
</style>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<?php
					$url = site_url('auth/profile/index/'.$data->id);
					$title = "Edit User Profile";
				?>
				<form class="form-horizontal no-margin" id="agama_form" name="agama_form" method="post" action="<?php echo $url; ?>">
					<div class="widget-header">
						<div class="title"><?php echo $title; ?></div>
						<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
					</div>
					<div class="widget-body">

						<div class="container-fluid">

							<div class="row-fluid">
							
								<div class="span6">
									
									<div class="control-group">
										<label class="control-label" for="nama">Nama</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('name', $data->name);
											?>
											<input class="span12" type="text" id="name" name="name" maxlength="100" placeholder="Nama Agama" value="<?php echo $value; ?>">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="username">Username</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('username', $data->username);
											?>
											<input class="span12" type="text" id="username" name="username" maxlength="255" placeholder="Username" value="<?php echo $value; ?>">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="email">Email</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('email', $data->email);
											?>
											<input class="span12" type="text" id="email" name="email" maxlength="255" placeholder="Email" value="<?php echo $value; ?>">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="password">Password</label>
										<div class="controls controls-row">
											<input class="span12" type="password" id="password" name="password" value="" />
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="email">Konfirmasi Password</label>
										<div class="controls controls-row">
											<input class="span12" type="password" id="confirm-password" name="confirm-password" value="" />
										</div>
									</div>
									
								</div>
							
							</div>
						</div>
					</div>
					<div class="row-fluid">
						<div class="span12">
							<div class="form-actions" style="text-align: right;">
								<button class="btn btn-info" type="submit" name="simpan" value="Simpan">Simpan</button>
								<button class="btn" type="submit" name="batal" value="Batal">Batal</button>
							</div>
						</div>
					</div>
					<input type="hidden" id="id" name="id" value="" />
				</form>
			</div>
		</div>
	</div>
</div>
