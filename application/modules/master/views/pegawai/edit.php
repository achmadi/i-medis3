<script type="text/javascript" charset="utf-8">
	
	$(document).ready(function() {
		
		var gedung_id = parseInt(<?php echo set_value('gedung_id', $data->gedung_id); ?>);
		if (gedung_id) {
			var unit_id = parseInt($("#unit_id").val());
			$.ajax({
				type		: "GET",
				url			: "<?php echo site_url('master/pegawai/get_gedung') ?>",
				data		: "unit_id=" + unit_id + "&gedung_id=" + gedung_id, 
				dataType	: "json",
				cache		: false,
				beforeSend	: function() {
					//$('#loading_kabupaten').show();
					//$('#loading_kabupaten').css("display", "inline");
				},
				success		: function() {
					//$('#loading_kabupaten').hide();
				},
				complete	: function($response, $status) {
					if ($status === "success" || $status === "notmodified") {
						if ($response.responseJSON.status === "OK") {
							$("#gedung_id").html($response.responseJSON.data);
							$("#gedung_id_section").show();
						}
						else {
							$("#gedung_id_section").hide();
						}
					}
				}
			});
		}
		else {
			$("#gedung_id_section").hide();
		}
		$("#unit_id").change(function() {
			var unit_id = parseInt($("#unit_id").val());
			var gedung_id = parseInt($("#gedung_id").val());
			if (unit_id) {
				$.ajax({
					type		: "GET",
					url			: "<?php echo site_url('master/pegawai/get_gedung') ?>",
					data		: "unit_id=" + unit_id + "&gedung_id=" + gedung_id, 
					dataType	: "json",
					cache		: false,
					beforeSend	: function() {
						//$('#loading_kabupaten').show();
						//$('#loading_kabupaten').css("display", "inline");
					},
					success		: function() {
						//$('#loading_kabupaten').hide();
					},
					complete	: function($response, $status) {
						if ($status === "success" || $status === "notmodified") {
							if ($response.responseJSON.status === "OK") {
								$("#gedung_id").html($response.responseJSON.data);
								$("#gedung_id_section").show();
							}
							else {
								$("#gedung_id_section").hide();
							}
						}
					}
				});
			}
			else {
				$("#gedung_id_section").hide();
			}
		});
		
		$('#indeks_langsung').autoNumeric('init');
		$('#indeks_basic').autoNumeric('init');
		$('#indeks_posisi').autoNumeric('init');
		$('#indeks_emergency').autoNumeric('init');
		$('#indeks_resiko').autoNumeric('init');
		$('#indeks_pendidikan').autoNumeric('init');
		$('#indeks_masa_kerja').autoNumeric('init');
		
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
						$url = site_url('master/pegawai/add');
						$title = "Tambah Pegawai";
					}
					else {
						$url = site_url('master/pegawai/edit/'.$data->id);
						$title = "Edit Pegawai";
					}
				?>
				<form class="form-horizontal no-margin" id="pegawai_form" name="pegawai_form" method="post" action="<?php echo site_url('master/pegawai/add'); ?>">
					<div class="widget-header">
						<div class="title"><?php echo $title; ?></div>
						<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
					</div>
					<div class="widget-body">

						<div class="container-fluid">

							<div class="row-fluid">
							
								<div class="span6">
									
									<div class="control-group">
										<label class="control-label" for="nip">Nip</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('nip', $data->nip);
											?>
											<input class="span12" type="text" id="nip" name="nip" maxlength="20" placeholder="NIP" value="<?php echo $value; ?>">
											<?php echo form_error('nip'); ?>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="nama">Nama</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('nama', $data->nama);
											?>
											<input class="span12" type="text" id="nama" name="nama" maxlength="60" placeholder="Nama Pegawai" value="<?php echo $value; ?>">
											<?php echo form_error('nama'); ?>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="no_rekening">No. Rekening</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('no_rekening', $data->no_rekening);
											?>
											<input class="span12" type="text" id="no_rekening" name="no_rekening" maxlength="20" placeholder="No. Rekening" value="<?php echo $value; ?>">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="npwp">NPWP</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('npwp', $data->npwp);
											?>
											<input class="span12" type="text" id="npwp" name="npwp" maxlength="20" placeholder="No. Rekening" value="<?php echo $value; ?>">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="jabatan">Jabatan</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('jabatan', $data->jabatan);
											?>
											<input class="span12" type="text" id="jabatan" name="jabatan" maxlength="60" placeholder="Jabatan" value="<?php echo $value; ?>">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="golongan_id">Golongan</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('golongan_id', $data->golongan_id);
												$golongan = new stdClass();
												$golongan->id = 0;
												$golongan->nama = "- Pilih Golongan -";
												$first = array($golongan);
												$golongan_list = array_merge($first, $golongan_list);
											?>
											<select id="golongan_id" name="golongan_id">
												<?php
													foreach ($golongan_list as $val) {
														if ($value == $val->id) {
															echo "<option value=\"{$val->id}\" selected=\"selected\">{$val->nama}</option>";
														} else {
															echo "<option value=\"{$val->id}\">{$val->nama}</option>";
														}
													}
												?>
											</select>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="kelompok_id">Kelompok</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('kelompok_id', $data->kelompok_id);
												$kelompok = new stdClass();
												$kelompok->id = 0;
												$kelompok->nama = "- Pilih Kelompok -";
												$first = array($kelompok);
												$kelompok_list = array_merge($first, $kelompok_list);
											?>
											<select id="kelompok_id" name="kelompok_id">
												<?php
													foreach ($kelompok_list as $val) {
														if ($value == $val->id) {
															echo "<option value=\"{$val->id}\" selected=\"selected\">{$val->nama}</option>";
														} else {
															echo "<option value=\"{$val->id}\">{$val->nama}</option>";
														}
													}
												?>
											</select>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="unit_id">Unit Kerja</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('unit_id', $data->unit_id);
												$unit = new stdClass();
												$unit->id = 0;
												$unit->nama = "- Pilih Unit Kerja -";
												$first = array($unit);
												$unit_list = array_merge($first, $unit_list);
											?>
											<select id="unit_id" name="unit_id">
												<?php
													foreach ($unit_list as $val) {
														if ($value == $val->id) {
															echo "<option value=\"{$val->id}\" selected=\"selected\">{$val->nama}</option>";
														} else {
															echo "<option value=\"{$val->id}\">{$val->nama}</option>";
														}
													}
												?>
											</select>
										</div>
									</div>
									
									<div id="gedung_id_section" class="control-group">
										<label class="control-label" for="gedung_id">Ruang</label>
										<div class="controls controls-row">
											<select id="gedung_id" name="gedung_id">
											</select>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="indeks_langsung">Indeks Langsung</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('indeks_langsung', $data->indeks_langsung);
											?>
											<input class="span4" type="text" id="indeks_langsung" name="indeks_langsung" data-v-min="0.00" data-v-max="100.00" data-a-sep="." data-a-dec="," maxlength="6" value="<?php echo $value; ?>" style="text-align: right;" />
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="indeks_basic">Indeks Basic</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('indeks_basic', $data->indeks_basic);
											?>
											<input class="span4" type="text" id="indeks_basic" name="indeks_basic" data-v-min="0" data-v-max="99" data-a-sep="." data-a-dec="," maxlength="2" value="<?php echo $value; ?>" style="text-align: right;" />
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="indeks_posisi">Indeks Posisi</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('indeks_posisi', $data->indeks_posisi);
											?>
											<input class="span4" type="text" id="indeks_posisi" name="indeks_posisi" data-v-min="0" data-v-max="99" data-a-sep="." data-a-dec="," maxlength="2" value="<?php echo $value; ?>" style="text-align: right;" />
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="indeks_emergency">Indeks Emergency</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('indeks_emergency', $data->indeks_emergency);
											?>
											<input class="span4" type="text" id="indeks_emergency" name="indeks_emergency" data-v-min="0" data-v-max="99" data-a-sep="." data-a-dec="," maxlength="2" value="<?php echo $value; ?>" style="text-align: right;" />
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="indeks_resiko">Indeks Resiko</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('indeks_resiko', $data->indeks_resiko);
											?>
											<input class="span4" type="text" id="indeks_resiko" name="indeks_resiko" data-v-min="0" data-v-max="99" data-a-sep="." data-a-dec="," maxlength="2" value="<?php echo $value; ?>" style="text-align: right;" />
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="indeks_pendidikan">Indeks Pendidikan</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('indeks_pendidikan', $data->indeks_pendidikan);
											?>
											<input class="span4" type="text" id="indeks_pendidikan" name="indeks_pendidikan" data-v-min="0" data-v-max="99" data-a-sep="." data-a-dec="," maxlength="2" value="<?php echo $value; ?>" style="text-align: right;" />
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="indeks_masa_kerja">Indeks Masa Kerja</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('indeks_masa_kerja', $data->indeks_masa_kerja);
											?>
											<input class="span4" type="text" id="indeks_masa_kerja" name="indeks_masa_kerja" data-v-min="0" data-v-max="99" data-a-sep="." data-a-dec="," maxlength="2" value="<?php echo $value; ?>" style="text-align: right;" />
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
