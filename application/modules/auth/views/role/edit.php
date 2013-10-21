<script type="text/javascript">
    $(document).ready(function() {
		
		//

    });
</script>
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
	.table {
		margin-bottom: 10px;
		width: 500px;
	}
</style>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<?php
					if ($is_new) {
						$url = site_url('auth/role/add');
						$title = "Tambah Role";
					}
					else {
						$url = site_url('auth/role/edit/'.$data->id);
						$title = "Edit Role";
					}
				?>
				<form class="form-horizontal no-margin" id="role_form" name="role_form" method="post" action="<?php echo $url; ?>">
					<div class="widget-header">
						<div class="title"><?php echo $title; ?></div>
						<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
					</div>
					<div class="widget-body">

						<div class="container-fluid">

							<div class="row-fluid">
							
								<div class="span6">
									
									<div class="control-group">
										<label class="control-label" for="nama">Nama Role</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('nama', $data->nama);
											?>
											<input class="span12" type="text" id="nama" name="nama" maxlength="30" placeholder="Nama Role" value="<?php echo $value; ?>">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="jenis">Jenis</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('jenis', $data->jenis);
												$empties = array(0 => "- Pilih Jenis Role -");
												$jenis_list = array_merge($empties, $jenis_list);
											?>
											<select id="jenis" name="jenis">
											<?php
												foreach ($jenis_list as $key => $val) {
													if ($value == $key) {
														echo "<option value=\"{$key}\" selected=\"selected\">{$val}</option>";
													}
													else {
														echo "<option value=\"{$key}\">{$val}</option>";
													}
												}
											?>
											</select>
											<?php echo form_error('jenis'); ?>
										</div>
									</div>
									
								</div>
								
								<div class="clearfix"></div>
								
								<div class="span12">
									<table class="table table-condensed table-striped table-bordered table-hover no-margin">
										<thead>
											<tr>
												<th style="width:5%"><input type="checkbox" class="no-margin" /></th>
												<th style="width:55%">Module</th>
												<th style="width:40%" class="hidden-phone">Access Rights</th>
											</tr>
										</thead>
										<tbody>
											<?php
												foreach ($module_list as $module) {
											?>
											<tr>
												<td>
													<input type="checkbox" class="no-margin" name="module[]" value="<?php echo $module->id; ?>" />
												</td>
												<td><span class="name"><?php echo $module->nama; ?></span></td>
												<td class="hidden-phone">
													<div class="btn-group">
														<button data-toggle="dropdown" class="btn btn-mini dropdown-toggle">Actions <span class="caret"></span></button>
														<ul class="dropdown-menu">
															<li><a href="#">Edit</a></li>
															<li><a href="#">Delete</a></li>
														</ul>
													</div>
												</td>
											</tr>
											<?php
												}
											?>
										</tbody>
									</table>
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
					<?php
						//$counter = count($data->dokters);
						$counter = 0
					?>
					<input type="hidden" id="counter" value="<?php echo $counter; ?>" />
				</form>
			</div>
		</div>
	</div>
</div>
