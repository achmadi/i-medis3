<script type="text/javascript">
	
	function select_pasien(id, unit_id) {
		var old_id = $("#pasien_id").val();
		if (old_id !== id) {
			var url = "";
			switch (parseInt(unit_id)) {
				case 1:
					url = "<?php echo site_url('pendaftaran_irj/get_pendaftaran_by_id'); ?>?pendaftaran_id=" + id;
					break;
				case 2:
					url = "<?php echo site_url('pendaftaran_irj/get_pasien_by_id'); ?>?id=" + id;
					break;
				case 3:
					url = "<?php echo site_url('pendaftaran_irj/get_pasien_by_id'); ?>?id=" + id;
					break;
			}
			$.getJSON(url, function(json) {
				$("#pasien_id").val(json.pendaftaran.pasien_id);
				$("#no_medrec").val(json.pendaftaran.no_medrec);
				$("#nama").text(json.pendaftaran.nama);
				$("#alamat").text(json.pendaftaran.alamat_jalan);
				$("#cara_bayar").text(json.pendaftaran.cara_bayar);
				$("#poliklinik").text(json.pendaftaran.poliklinik);
				$("#dokter").text(json.pendaftaran.dokter);
				/*
				var provinsi_id = json.pasien.provinsi_id;
				if (provinsi_id > 0) {
					$.ajax({
						type: "GET",
						url: "<?php echo site_url('pendaftaran_irj/get_provinsi') ?>",
						data: "provinsi_id=" + provinsi_id, 
						cache: false,
						beforeSend: function() {
							$('#loading_provinsi').show();
							$('#loading_provinsi').css("display", "inline");
						},
						success: function() {
							$('#loading_provinsi').hide();
						},
						complete: function($response, $status) {
							if ($status === "success" || $status === "notmodified") {
								$("#provinsi_id").html($response.responseText);
							}
						}
					});
				}
				var provinsi_id = json.pasien.provinsi_id;
				if (provinsi_id > 0) {
					$.ajax({
						type: "GET",
						url: "<?php echo site_url('pendaftaran_irj/get_kabupaten') ?>",
						data: "provinsi_id=" + provinsi_id + "&kabupaten_id=" + json.pasien.kabupaten_id, 
						cache: false,
						beforeSend: function() {
							$('#loading_kabupaten').show();
							$('#loading_kabupaten').css("display", "inline");
						},
						success: function() {
							$('#loading_kabupaten').hide();
						},
						complete: function($response, $status) {
							if ($status === "success" || $status === "notmodified") {
								$("#kabupaten_id").html($response.responseText);
							}
						}
					});
				}
				var kabupaten_id = json.pasien.kabupaten_id;
				if (kabupaten_id > 0) {
					$.ajax({
						type: "GET",
						url: "<?php echo site_url('pendaftaran_irj/get_kecamatan') ?>",
						data: "kabupaten_id=" + kabupaten_id + "&kecamatan_id=" + json.pasien.kecamatan_id,
						cache: false,
						beforeSend: function() {
							$('#loading_kecamatan').show();
							$('#loading_kecamatan').css("display", "inline");
						},
						success: function() {
							$('#loading_kecamatan').hide();
						},
						complete: function($response, $status) {
							if ($status === "success" || $status === "notmodified") {
								$("#kecamatan_id").html($response.responseText);
							}
						}
					});
				}
				$("#kodepos").val(json.pasien.kodepos);
				$("#telepon").val(json.pasien.telepon);
				$("#tempat_lahir").val(json.pasien.tempat_lahir);
				$("#tanggal_lahir").val(json.pasien.tanggal_lahir);
				var t = json.pasien.tanggal_lahir.split(/[- :]/);
				var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
				$("#disp_tanggal_lahir").val($.datepicker.formatDate('dd/mm/yy', d));
				
				var tgl = $('#tanggal_lahir').val().split(" ");
				var aDoB = tgl[0].split("-");
				var DoB_year   = aDoB[0];
				var DoB_month  = aDoB[1];
				var DoB_day    = aDoB[2];

				var curDate = new Date();
				
				var calcDate = new Date(DoB_year, DoB_month - 1, DoB_day);
				var dife = datediff2(curDate, calcDate);
				$("#umur_tahun").val(dife[0]);
				$("#umur_bulan").val(dife[1]);
				$("#umur_hari").val(dife[2]);
				
				$('#golongan_darah option[value="' + json.pasien.golongan_darah + '"]').attr('selected', 'selected');
				$('#agama_id option:eq(' + parseInt(json.pasien.agama_id) + ')').attr('selected', 'selected');
				$('#pendidikan_id option:eq(' + parseInt(json.pasien.pendidikan_id) + ')').attr('selected', 'selected');
				$('#pekerjaan_id option:eq(' + parseInt(json.pasien.pekerjaan_id) + ')').attr('selected', 'selected');
				$("#baru").val(0);
				$("#status_pasien").html("Pasien Lama");
				
				$("#generate_medrec").attr("disabled", true);
				*/
			});
		}
		$("#pasien_modal").modal("hide");
		return false;
	}
	
    $(document).ready(function() {
	
		$.ajax(function() {
			
		});
		
		function disabled_form(disabled) {
			$("#disp_tanggal").attr("disabled", disabled);
			$("#no_register").attr("disabled", disabled);
			$("#no_medrec").attr("disabled", disabled);
			$("#cari_pasien_button").attr("disabled", disabled);
			if (disabled) {
				$("#cari-pasien-btn").addClass("disabled");
			}
			else {
				$("#cari-pasien-btn").removeClass("disabled");
			}
		}
		
		var instalasi = $("#instalasi").val();
		switch (parseInt(instalasi)) {
			case 0:
				disabled_form(true);
				break;
			case 1:
				disabled_form(false);
				break;
			case 2:
				disabled_form(false);
				break;
			case 3:
				disabled_form(false);
				break;
		}
		$("#instalasi").change(function () {
			var instalasi = $("#instalasi").val();
			switch (parseInt(instalasi)) {
				case 0:
					disabled_form(true);
					break;
				case 1:
					disabled_form(false);
					break;
				case 2:
					disabled_form(false);
					break;
				case 3:
					disabled_form(false);
					break;
			}
		});
		
		function getISODateTime(d){
			var s = function(a,b){return(1e15+a+"").slice(-b);};

			if (typeof d === 'undefined'){
				d = new Date();
			};

			return d.getFullYear() + '-' +
				s(d.getMonth()+1,2) + '-' +
				s(d.getDate(),2) + ' ' +
				s(d.getHours(),2) + ':' +
				s(d.getMinutes(),2) + ':' +
				s(d.getSeconds(),2);
		}
		
		var disp_tanggal = $('#disp_tanggal').datepicker({
			format: 'dd/mm/yyyy'
		}).
		on('changeDate', function(ev) {
			var date = new Date(ev.date);
			date_str = getISODateTime(date);
			$('#tanggal').val(date_str);
			disp_tanggal.hide();
		}).data('datepicker');
		
		$("#no_medrec").blur(function() {
			var instalasi = $("#instalasi").val();
			var no_medrec = $("#no_medrec").val();
			if (no_medrec.length() > 0) {
				$.getJSON("<?php echo site_url('kasir/get_pasien_by_no_medrec'); ?>?instalasi=" + instalasi + "&no_medrec=" + no_medrec, function(json) {
					if (json.status === "ok") {
						$("#pasien_id").val(json.pendaftaran.pasien_id);
						$("#no_medrec").val(json.pendaftaran.no_medrec);
						$("#nama").text(json.pendaftaran.nama);
						$("#alamat").text(json.pendaftaran.alamat_jalan);
						$("#cara_bayar").text(json.pendaftaran.cara_bayar);
						$("#poliklinik").text(json.pendaftaran.poliklinik);
						$("#dokter").text(json.pendaftaran.dokter);
					}
					else {
						alert("No. Medrec tersebut tidak ditemukan!");
					}
				});
			}
		});
		
		$("#cari-pasien-btn").on("click", function () {
			if (!$(this).hasClass('disabled')) {
				var instalasi = $("#instalasi").val();		
				var url = $("#pasien_modal").data('remote') + '?instalasi=' + instalasi;
				$('#pasien_modal')
					.modal({
						remote: url,
						show: true
					});
			}
			return false;
		});
		
    });
	
</script>
<style type="text/css">
	.dashboard-wrapper {
		margin-bottom: 10px;
		padding: 10px;
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
	.datepicker {
		background: white;
	}
	
	/* begin pasien_modal */
    .dashboard-wrapper #pasien_modal .modal.fade {
         left: -25%;
          -webkit-transition: opacity 0.3s linear, left 0.3s ease-out;
             -moz-transition: opacity 0.3s linear, left 0.3s ease-out;
               -o-transition: opacity 0.3s linear, left 0.3s ease-out;
                  transition: opacity 0.3s linear, left 0.3s ease-out;
    }
    .dashboard-wrapper #pasien_modal .modal.fade.in {
        left: 50%;
    }
	.dashboard-wrapper #pasien_modal .modal-body {
        max-height: 500px;
    }
	#pasien_modal {
		width: 900px;
		margin-left: -450px;
		margin-right: -450px;
	}
	/* end pasien_modal */
</style>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<?php
					if ($is_new) {
						$url = site_url('kasir/kasir');
					}
					else {
						$url = site_url('kasir/kasir/update/'.$data->id);
					}
				?>
				<form class="form-horizontal no-margin" id="pembagian_jasa_form" name="pembagian_jasa_form" method="post" action="<?php echo $url; ?>">
					<div class="widget-header" style="height: 28px;">
						<div class="title" style="margin-top: 2px; margin-right: 8px;">Kasir</div>
						<?php
							$value = set_value('instalasi', $data->instalasi);
							$first = array(0 => '- Pilih Unit -');
							$instalasi_list = array_merge($first, $instalasi_list);
						?>
						<select id="instalasi" name="instalasi">
							<?php
								foreach ($instalasi_list as $key => $val) {
									if ($value == $key) {
										echo "<option value=\"{$key}\" selected=\"selected\">{$val}</option>";
									}
									else {
										echo "<option value=\"{$key}\">{$val}</option>";
									}
								}
							?>
						</select>
						<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
					</div>
					<div class="row-fluid" style="border-top: 0; border-bottom: 1px solid #E5E5E5;">
						<div class="span12">
							<div class="form-actions" style="padding-left: 5px;">
								<button class="btn pull-right" type="submit" id="batal" name="batal" value="Batal">Batal</button>
								<button class="btn btn-info pull-right" type="submit" id="simpan" name="simpan" value="Simpan" style="margin-right: 5px;">Simpan</button>
							</div>
						</div>
					</div>
					<div class="widget-body">

						<div class="container-fluid">

							<div class="row-fluid">
								<div class="span6">
									<div class="control-group">
										<label class="control-label" for="disp_tanggal">Tanggal</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('tanggal', $data->tanggal);
												$tanggal = strftime( "%d/%m/%Y", strtotime($value));
											?>
											<input type="hidden" id="tanggal" name="tanggal" value="<?php echo $value; ?>" />
											<input class="span6" type="text" id="disp_tanggal" name="disp_tanggal" data-date-format="mm/dd/yyyy" placeholder="" value="<?php echo $tanggal; ?>" />
											<?php echo form_error('tanggal'); ?>
										</div>
									</div>
								</div>
								<div class="span6">
									
									<div class="control-group">
										<label class="control-label" for="no_register">No. Register</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('no_register', $data->no_register);
											?>
											<input class="span6" type="text" id="no_register" name="no_register" placeholder="No. Register" value="<?php echo $value; ?>" />
										</div>
									</div>
									
								</div>
							</div>
							
							<div class="row-fluid">
								<div class="span6">
									
									<div class="control-group">
										<label class="control-label" for="no_medrec">No. Medrec</label>
										<div class="controls controls-row">
											<input class="span6" type="text" id="no_medrec" name="no_medrec" placeholder="No. Medrec" value="" autocomplete="off" />
											<a id="cari_pasien_button" href="#">
												<span id="cari-pasien-btn" class="add-on btn">Cari...</span>
											</a>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Nama</label>
										<div class="controls controls-row">
											<label id="nama" style="padding-top: 5px; color: #000;"></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Alamat</label>
										<div class="controls controls-row">
											<label id="alamat" style="padding-top: 5px; color: #000;"></label>
										</div>
									</div>
									
									
									<div class="control-group">
										<label class="control-label">Cara Pembayaran</label>
										<div class="controls controls-row">
											<label id="cara_bayar" style="padding-top: 5px; color: #000;"></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Poliklinik</label>
										<div class="controls controls-row">
											<label id="poliklinik" style="padding-top: 5px; color: #000;"></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Dokter</label>
										<div class="controls controls-row">
											<label id="dokter" style="padding-top: 5px; color: #000;"></label>
										</div>
									</div>
									
								</div>
								<div class="span6">
									
									
									
								</div>
							</div>
							
							<hr />
							<div class="row-fluid">
							
								<div class="span12">
									<table id="table_pembagian_jasa_detail" class="table table-condensed table-striped table-bordered table-hover no-margin">
										<thead>
											<tr>
												<th style="width:60%">Uraian</th>
												<th style="width:25%">Jumlah</th>
												<th style="width:15%">Actions</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
									<table class="table table-condensed table-striped table-bordered table-hover no-margin" style="border-top: 0;">
										<tbody>
											<tr>
												<td colspan="4" style="text-align: right; width:60%;">T O T A L&nbsp;&nbsp;</td>
												<td style="text-align: right; width:25%">
													<input type="hidden" id="total" name="total" value="" />
													<label id="label_total" style="text-align: right;"></label>
												</td>
												<td style="width:15%">&nbsp;</td>
											</tr>
										</tbody>
									</table>
									<table class="table table-condensed table-striped table-bordered table-hover no-margin" style="border-top: 0;">
										<tbody>
											<tr>
												<td colspan="2" class="hidden-phone">
													<button type="button" id="button_tambah" class="btn btn-primary btn-mini bottom-margin">Tambah</button>
												</td>
											</tr>
										</tbody>
									</table>
									<table style="display: none;">
										<tbody>
											<tr id="row_pembagian_jasa_detail_clone">
												<td>
													<input type="hidden" id="clone_pembagian_jasa_detail_id" />
													<input type="hidden" id="clone_tindakan_id" />
													<input type="hidden" id="clone_jasa_sarana" />
													<input type="hidden" id="clone_jasa_pelayanan" />
													<label id="clone_label_tindakan" style="display: none;"></label>
													<div id="clone_tindakan" class="span12" style="margin: 0;">
														<div class="controls controls-row" style="margin: 0;">
															<input class="span9" type="text" id="clone_disp_tindakan" autocomplete="off" />
															<button type="button" class="cari_tindakan span3 add-non btn" data-url="<?php echo site_url('pembagian_jasa/pembagian_jasa/'); ?>">Cari...</button>
														</div>
													</div>
												</td>
												<td>
													<input type="hidden" id="clone_dokter_id" />
													<label id="clone_label_dokter" style="display: none;"></label>
													<select id="clone_disp_dokter" style="display: block; width: 100%;" ></select>
												</td>
												<td>
													<input type="hidden" id="clone_harga_satuan" />
													<label id="clone_label_harga_satuan" style="display: none; text-align: right;"></label>
													<input type="text" id="clone_disp_harga_satuan" style="display: block; width: 94%; text-align: right;" />
												</td>
												<td>
													<input type="hidden" id="clone_quantity" />
													<label id="clone_label_quantity" style="display: none; text-align: right;"></label>
													<input type="text" id="clone_disp_quantity" style="display: block; width: 94%; text-align: right;" />
												</td>
												<td>
													<input type="hidden" id="clone_jumlah" />
													<label id="clone_label_jumlah" style="text-align: right;"></label>
												</td>
												<td style="text-align: center; vertical-align: middle;">
													<button type="button" id="button_uraian" class="btn btn-success btn-mini bottom-margin" data-url="<?php echo site_url('pembagian_jasa/pembagian_jasa/get_uraian'); ?>">Uraian</button>
													<button type="button" id="button_1" class="btn btn-primary btn-mini bottom-margin">Simpan</button>
													<button type="button" id="button_2" class="btn btn-primary btn-mini bottom-margin">Batal</button>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
						
							</div>
						</div>
					</div>
					<div class="row-fluid">
						<div class="span12">
							<div class="form-actions" style="text-align: right;">
								<button class="btn btn-info" type="submit" id="simpan" name="simpan" value="Simpan">Simpan</button>
								<button class="btn" type="submit" id="batal" name="batal" value="Batal">Batal</button>
							</div>
						</div>
					</div>
					<div id="table_penerima_jp_detail"></div>
					<table id="clone_table_penerima_jp_detail" style="display: none;">
						<thead>
							<tr>
								<th>Id</th>
								<th>tanggal</th>
								<th>nama</th>
								<th>pegawai_id</th>
								<th>tarif_langsung</th>
								<th>proporsi</th>
								<th>langsung</th>
								<th>balancing</th>
								<th>kebersamaan</th>
							</tr>
						</thead>
						<tbody></tbody>
						<tfoot></tfoot>
					</table>
					
					
					<?php
						$counter = count($data->details);
					?>
					<input type="hidden" id="counter" value="<?php echo $counter; ?>" />
					<div>
						<?php
							$value = set_value('id', $data->id);
						?>
						<input type="hidden" id="id" name="id" value="<?php echo $value; ?>" />
						<?php
							$value = set_value('pasien_id', $data->pasien_id);
						?>
						<input type="hidden" id="pasien_id" name="pasien_id" value="<?php echo $value; ?>" />
						<?php
							$value = set_value('version', $data->version);
						?>
						<input type="hidden" id="version" name="version" value="<?php echo $value; ?>" />
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="pasien_modal" data-remote="<?php echo site_url('kasir/kasir/browse_pasien'); ?>">
    <div class="modal-header">
       <a class="close" data-dismiss="modal">&times;</a>
       <h4>Daftar Pasien</h4>
    </div>
    <div class="modal-body"></div>
</div>
<div class="modal hide fade" id="tindakan_modal" data-remote="<?php echo site_url('pembagian_jasa/pembagian_jasa/browse_tindakan'); ?>">
	<input type="hidden" id="tindakan_counter" value="0" />
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4>Daftar Pelayanan</h4>
	</div>
	<div class="modal-body"></div>
</div>
<div class="modal fade" id="uraian_modal" data-remote="">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3>Rincian Tarif Pelayanan</h3>
	</div>
	<div class="modal-body">
	</div>
	<div class="modal-footer">
		<input type="hidden" id="dokter_value" value="" />
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		<button id="uraian_save_button" class="btn btn-primary">Save changes</button>
	</div>
</div>