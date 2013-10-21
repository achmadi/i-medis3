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
						$url = site_url('master/bed/add?gedung_id='.$gedung->id.'&ruangan_id='.$ruangan->id);
						$title = "Tambah Bed";
					}
					else {
						$url = site_url('master/bed/edit/'.$data->id.'?gedung_id='.$gedung->id.'&ruangan_id='.$ruangan->id);
						$title = "Edit Bed";
					}
				?>
				<form class="form-horizontal no-margin" id="ruangan_form" name="ruangan_form" method="post" action="<?php echo $url; ?>">
					<div class="widget-header">
						<div class="title"><?php echo $title; ?></div>
						<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
					</div>
					<div class="widget-body">

						<div class="container-fluid">

							<div class="row-fluid">
							
								<div class="span6">
									
									<div class="control-group">
										<label class="control-label">Nama Gedung</label>
										<div class="controls controls-row">
											<label class="span12" style="padding-top: 5px;"><?php echo $gedung->nama; ?></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Nama Ruangan</label>
										<div class="controls controls-row">
											<label class="span12" style="padding-top: 5px;"><?php echo $ruangan->nama; ?></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="nama">Nama Bed</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('nama', $data->nama);
											?>
											<input class="span12" type="text" id="nama" name="nama" maxlength="30" placeholder="Nama Bed" value="<?php echo $value; ?>">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="kelas_id">Kelas</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('kelas_id', $data->kelas_id);
												$kelas = new stdClass();
												$kelas->id = 0;
												$kelas->nama = "- Pilih Kelas -";
												$empties = array($kelas);
												$kelas_list = array_merge($empties, $kelas_list);
											?>
											<select id="kelas_id" name="kelas_id">
											<?php
												foreach ($kelas_list as $val) {
													if ($value == $val->id) {
														echo "<option value=\"{$val->id}\" selected=\"selected\">{$val->nama}</option>";
													}
													else {
														echo "<option value=\"{$val->id}\">{$val->nama}</option>";
													}
												}
											?>
											</select>
											<?php echo form_error('kelas_id'); ?>
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
					<input type="hidden" id="gedung_id" name="gedung_id" value="<?php echo $gedung->id; ?>" />
					<input type="hidden" id="ruangan_id" name="ruangan_id" value="<?php echo $ruangan->id; ?>" />
				</form>
			</div>
		</div>
	</div>
</div>
