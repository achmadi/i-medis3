<style type="text/css">
	.dashboard-wrapper {
		margin-bottom: 10px;
	}
	.dashboard-wrapper .left-sidebar {
		margin-right: 0;
	}
</style>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget no-margin">
				<div class="widget-header">
					<div class="title">Edit Profile</div>
					<span class="tools">
						<a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a>
					</span>
				</div>
				<div class="widget-body">
					<div class="container-fluid">
						<div class="row-fluid">
							<div class="span3">
								<div class="thumbnail">
									<img alt="300x200" src="<?php echo base_url('assets/img/profile.png'); ?>">
									<div class="caption">
										<p class="no-margin">
											<a href="#" class="btn btn-info">Simpan</a>
											<a href="#" class="btn">Batal</a>
										</p>
									</div>
								</div>
							</div>
							<div class="span9">
								<?php echo validation_errors(); ?>
								<form class="form-horizontal no-margin" id="profile_form" name="profile_form" method="post" action="<?php echo site_url('auth/user/profile'); ?>">
									<h5>Login Information</h5>
									<hr>
									
									<div class="control-group">
										<label class="control-label" for="name">Nama</label>
										<div class="controls">
											<?php
												$value = set_value('name', $data->name);
											?>
											<input class="span12" type="text" id="name" name="name" placeholder="Nama" value="<?php echo $value; ?>" autocomplete="off" />
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="username">Username</label>
										<div class="controls">
											<?php
												$value = set_value('username', $data->username);
											?>
											<input class="span12" type="text" id="username" name="username" placeholder="Username" value="<?php echo $value; ?>" autocomplete="off" />
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="email">Email</label>
										<div class="controls">
											<?php
												$value = set_value('email', $data->email);
											?>
											<input class="span12" type="text" id="email" name="email" placeholder="Email" value="<?php echo $value; ?>" autocomplete="off" />
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="password">Password</label>
										<div class="controls">
											<input class="span12" type="password" id="password" name="password" placeholder="" value="" autocomplete="off" />
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="confirm_password">Konfirmasi Password</label>
										<div class="controls">
											<input class="span12" type="password" id="confirm_password" name="confirm_password" placeholder="" value="" autocomplete="off" />
										</div>
									</div>
									
									<div class="form-actions">
										<button type="submit" id="simpan" name="simpan" value="simpan" class="btn btn-info">Simpan</button>
										<button type="submit" id="batal" name="batal" value="batal" class="btn">Batal</button>
									</div>
									<div>
										<?php
											$value = set_value('id', $data->id);
										?>
										<input type="hidden" id="id" name="id" value="<?php echo $value; ?>" />
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>