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
						$url = site_url('master/kelompok_pegawai/add');
						$title = "Tambah Kelompok Pegawai";
					}
					else {
						$url = site_url('master/kelompok_pegawai/edit/'.$data->id);
						$title = "Edit Kelompok Pegawai";
					}
				?>
				<form class="form-horizontal no-margin" id="kelompok_pegawai_form" name="kelompok_pegawai_form" method="post" action="<?php echo site_url('master/kelompok_pegawai/add'); ?>">
					<div class="widget-header">
						<div class="title"><?php echo $title; ?></div>
						<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
					</div>
					<div class="widget-body">

						<div class="container-fluid">

							<div class="row-fluid">
							
								<div class="span6">
									
									<div class="control-group">
										<label class="control-label" for="no_medrec">Nama Kelompok Pegawai</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('nama', $data->nama);
											?>
											<input class="span12" type="text" id="nama" name="nama" maxlength="60" placeholder="Nama Kelompok Pegawai" value="<?php echo $value; ?>">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="jenis">Jenis</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('jenis', $data->jenis);
												$first = array();
												$first = array(0 => '- Pilih Jenis Kelompok -');
												$jenis_list = array_merge($first, $jenis_list);
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
