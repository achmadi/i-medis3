<script type="text/javascript" charset="utf-8">
	
	$(document).ready(function() {
		
		$('#pajak').autoNumeric('init');
		
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
</style>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<?php
					if ($is_new) {
						$url = site_url('master/golongan_pegawai/add');
						$title = "Tambah Golongan Pegawai";
					}
					else {
						$url = site_url('master/golongan_pegawai/edit/'.$data->id);
						$title = "Edit Golongan Pegawai";
					}
				?>
				<form class="form-horizontal no-margin" id="golongan_pegawai_form" name="golongan_pegawai_form" method="post" action="<?php echo site_url('master/golongan_pegawai/add'); ?>">
					<div class="widget-header">
						<div class="title"><?php echo $title; ?></div>
						<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
					</div>
					<div class="widget-body">

						<div class="container-fluid">

							<div class="row-fluid">
							
								<div class="span6">
									
									<div class="control-group">
										<label class="control-label" for="no_medrec">Nama Golongan Pegawai</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('nama', $data->nama);
											?>
											<input class="span12" type="text" id="nama" name="nama" maxlength="5" placeholder="Nama Golongan Pegawai" value="<?php echo $value; ?>">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="golongan">Golongan</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('golongan', $data->golongan);
												$empties = array(0 => "- Pilih Golongan -");
												$golongan_list = array_merge($empties, $golongan_list);
											?>
											<select id="golongan" name="golongan">
											<?php
												foreach ($golongan_list as $key => $val) {
													if ($value == $key) {
														echo "<option value=\"{$key}\" selected=\"selected\">{$val}</option>";
													}
													else {
														echo "<option value=\"{$key}\">{$val}</option>";
													}
												}
											?>
											</select>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="pajak">Pajak</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('pajak', $data->pajak);
											?>
											<input class="span4" type="text" id="pajak" name="pajak" data-v-min="0.00" data-v-max="100.00" data-a-sep="." data-a-dec="," maxlength="6" value="<?php echo $value; ?>" style="text-align: right;" />
											<span class="help-inline ">%</span>
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
					<?php
						$value = set_value('id', $data->id);
					?>
					<input type="hidden" id="id" name="id" value="<?php echo $value; ?>" />
				</form>
			</div>
		</div>
	</div>
</div>
