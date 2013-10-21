<script type="text/javascript">
	
	<?php
		if ($print) {
	?>
		print();
	<?php
		}
	?>
	
	function print() {
		window.open("<?php echo site_url('laboratorium/pendaftaran/print_form_001?id='.$print_id); ?>", "Print Ringkasan Pasien Masuk/Keluar", "scrollbars=1, height=300, width=300");
	}
	
	function DaysInMonth2(Y, M) {
		with (new Date(Y, M, 1, 12)) {
			setDate(0);
			return getDate();
		}
	}

	function datediff2(date1, date2) {
		var y1 = date1.getFullYear(), m1 = date1.getMonth(), d1 = date1.getDate(),
			y2 = date2.getFullYear(), m2 = date2.getMonth(), d2 = date2.getDate();
		if (d1 < d2) {
			m1--;
			d1 += DaysInMonth2(y2, m2);
		}
		if (m1 < m2) {
			y1--;
			m1 += 12;
		}
		return [y1 - y2, m1 - m2, d1 - d2];
	}
	
	function umur_descr(thn, bln, hr) {
		if (parseInt(thn) > 0) {
			return thn + " th " + bln + " bl " + hr + " hr";
		}
		else if (parseInt(bln) > 0) {
			return bln + " bl " + hr + " hr";
		}
		else if (parseInt(hr) > 0) {
			return hr + " hr";
		}
	}
	
	function fill_form(url) {
		$.getJSON(url, function(json) {
			$("#no_medrec").val(json.pendaftaran.no_medrec);
			$("#nama").text(json.pendaftaran.nama);
			$("#jenis_kelamin").text(parseInt(json.pendaftaran.jenis_kelamin) === 1 ? "Laki-laki" : "Perempuan");
			var alamat = json.pendaftaran.alamat_jalan + " " + json.pendaftaran.kecamatan + " " + json.pendaftaran.kelurahan + " " + json.pendaftaran.kodepos;
			$("#alamat").text(alamat);
			$("#telepon").text(json.pendaftaran.telepon);
			$("#tempat_tanggal_lahir").text(json.pendaftaran.tempat_lahir + ", " + json.pendaftaran.tanggal_lahir);
			var umur = umur_descr(json.pendaftaran.umur_tahun, json.pendaftaran.umur_bulan, json.pendaftaran.umur_hari);
			$("#umur").text(umur);
			$("#agama").text(json.pendaftaran.agama);
			$("#pendidikan").text(json.pendaftaran.pendidikan);
			$("#pekerjaan").text(json.pendaftaran.pekerjaan);
			$("#rujukan").text(json.pendaftaran.pekerjaan);
			$("#cara_bayar").text(json.pendaftaran.rujukan);
			$("#dokter").text(json.pendaftaran.dokter);
			
			$("#pasien_id").val(json.pendaftaran.pasien_id);
			$("#pendaftaran_id").val(json.pendaftaran.id);
			$("#umur_tahun").val(json.pendaftaran.umur_tahun);
			$("#umur_bulan").val(json.pendaftaran.umur_bulan);
			$("#umur_hari").val(json.pendaftaran.umur_hari);
			$("#rujukan_id").val(json.pendaftaran.rujukan_id);
			$("#cara_bayar_id").val(json.pendaftaran.cara_bayar_id);
			$("#dokter_id").val(json.pendaftaran.dokter_id);
		});
	}
	
	function select_pasien(id, instalasi) {
		var old_id = $("#pasien_id").val();
		if (old_id !== id) {
			var url;
			switch (instalasi) {
				case 1:
					$('#asal_pasien1').attr('checked', true);
					url = "<?php echo site_url('pendaftaran_irj/get_pendaftaran_by_id'); ?>" + "?pendaftaran_id=" + id;
					break;
				case 2:
					$('#asal_pasien2').attr('checked', true);
					url = "<?php echo site_url('pendaftaran_igd/get_pendaftaran_by_id'); ?>" + "?pendaftaran_id=" + id;
					break;
			}
			fill_form(url);
		}
		$("#pasien_modal").modal("hide");
	}
			
	$(document).ready(function() {
        
		if (parseInt($("#pasien_id").val()) !== 0) {
			var id = $("#pasien_id").val();
			var asal_pasien = "<?php echo set_value('asal_pasien', $data->asal_pasien); ?>";
			var url;
			switch (parseInt(asal_pasien)) {
				case 1:
					url = "<?php echo site_url('pendaftaran_irj/get_pendaftaran_by_id'); ?>" + "?pendaftaran_id=" + id;
					break;
				case 2:
					url = "<?php echo site_url('pendaftaran_igd/get_pendaftaran_by_id'); ?>" + "?pendaftaran_id=" + id;
					break;
				case 3:
					url = "<?php echo site_url('pendaftaran_igd/get_pendaftaran_by_id'); ?>" + "?pendaftaran_id=" + id;
					break;
			}
			$.getJSON(url, function(json) {
				$("#nama").text(json.pendaftaran.nama);
				$("#jenis_kelamin").text(parseInt(json.pendaftaran.jenis_kelamin) === 1 ? "Laki-laki" : "Perempuan");
				var alamat = json.pendaftaran.alamat_jalan + " " + json.pendaftaran.kecamatan + " " + json.pendaftaran.kelurahan + " " + json.pendaftaran.kodepos;
				$("#alamat").text(alamat);
				$("#telepon").text(json.pendaftaran.telepon);
				$("#tempat_tanggal_lahir").text(json.pendaftaran.tempat_lahir + ", " + json.pendaftaran.tanggal_lahir);
				$("#umur").text(json.pendaftaran.umur_tahun + " th " + json.pendaftaran.umur_bulan + " bln " + json.pendaftaran.umur_hari + " hr");
				$("#agama").text(json.pendaftaran.agama);
				$("#pendidikan").text(json.pendaftaran.pendidikan);
				$("#pekerjaan").text(json.pendaftaran.pekerjaan);
				$("#rujukan").text(json.pendaftaran.pekerjaan);
				$("#cara_bayar").text(json.pendaftaran.rujukan);
				$("#dokter").text(json.pendaftaran.dokter);
			});
		}
		
        function getISODateTime(d){
			var s = function(a,b) { return(1e15 + a + "").slice(-b); };

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
	   
	   function getISODate(d){
			var s = function(a,b) { return(1e15 + a + "").slice(-b); };

			if (typeof d === 'undefined'){
				d = new Date();
			};

			return d.getFullYear() + '-' +
				s(d.getMonth()+1,2) + '-' +
				s(d.getDate(),2);
		}
	   
		var disp_tanggal = $('#disp_tanggal').datepicker({
			format: 'dd/mm/yyyy'
		}).
		on('changeDate', function(ev) {
			var date = new Date(ev.date);
			var date_str = getISODateTime(date);
			$('#tanggal').val(date_str);
			disp_tanggal.hide();
		}).data('datepicker');
		
		$("#no_medrec").keypress(function(event) {
			if (event.which === 13) {
				event.preventDefault();
				var no_medrec = $("#no_medrec").val();
				var url;
				if ($('#asal_pasien1').attr('checked')) {
					url = "<?php echo site_url('pendaftaran_irj/get_pendaftaran_by_no_medrec'); ?>?no_medrec=" + no_medrec;
				}
				if ($('#asal_pasien2').attr('checked')) {
					url = "<?php echo site_url('pendaftaran_igd/get_pendaftaran_by_no_medrec'); ?>?no_medrec=" + no_medrec;
				}
				fill_form(url);
			}
		});
		
		$("#no_medrec").blur(function(event) {
			var no_medrec = $("#no_medrec").val();
			//
		});
		
		$("#cari_pasien_button").on("click", function () {
			
			var instalasi = $("input[name='asal_pasien']:checked").val();
			
			var url = $("#pasien_modal").data('remote') + '?instalasi=' + instalasi;
			$("#pasien_modal").removeData ('modal');
			$('#pasien_modal')
				.modal({
					remote: url,
					show: true
				});
			return false;
		});
		
		var gedung_id = $("#gedung_id").val();
		if (gedung_id > 0) {
			$.ajax({
				type: "GET",
				url: "<?php echo site_url('tp2ri/pendaftaran/get_ruangan') ?>",
				data: "gedung_id=" + gedung_id + "&ruangan_id=" + <?php echo set_value('ruangan_id', $data->ruangan_id); ?>, 
				cache: false,
				beforeSend: function() {
					$('#loading_ruangan').show();
					$('#loading_ruangan').css("display", "inline");
				},
				success: function() {
					$('#loading_ruangan').hide();
				},
				complete: function($response, $status) {
					if ($status === "success" || $status === "notmodified") {
						$("#ruangan_id").html($response.responseText);
					}
				}
			});
		}
		$("#gedung_id").change(function() {
			var gedung_id = $("#gedung_id").val();
			if (gedung_id) {
				$.ajax({
					type: "GET",
					url: "<?php echo site_url('tp2ri/pendaftaran/get_ruangan') ?>",
					data: "gedung_id=" + gedung_id, 
					cache: false,
					beforeSend: function() {
						$('#loading_ruangan').show();
						$('#loading_ruangan').css("display", "inline");
					},
					success: function() {
						$('#loading_ruangan').hide();
					},
					complete: function($response, $status) {
						if ($status === "success" || $status === "notmodified") {
							$("#ruangan_id").html($response.responseText);
							$("#bed_id").html('<option value="0" selected="selected">- Pilih Ruangan -</option>');
						}
					}
				});
			}
		});
		
		var ruangan_id = $("#ruangan_id").val();
		if (ruangan_id > 0) {
			$.ajax({
				type: "GET",
				url: "<?php echo site_url('tp2ri/pendaftaran/get_bed') ?>",
				data: "ruangan_id=" + ruangan_id + "&bed_id=" + <?php echo set_value('bed_id', $data->bed_id); ?>,
				cache: false,
				beforeSend: function() {
					$('#loading_bed').show();
					$('#loading_bed').css("display", "inline");
				},
				success: function() {
					$('#loading_bed').hide();
				},
				complete: function($response, $status) {
					if ($status === "success" || $status === "notmodified") {
						$("#bed_id").html($response.responseText);
					}
				}
			});
		}
		$("#ruangan_id").change(function() {
			var ruangan_id = $("#ruangan_id").val();
			if (ruangan_id) {
				$.ajax({
					type: "GET",
					url: "<?php echo site_url('tp2ri/pendaftaran/get_bed') ?>",
					data: "ruangan_id=" + ruangan_id, 
					cache: false,
					beforeSend: function() {
						$('#loading_bed').show();
						$('#loading_bed').css("display", "inline");
					},
					success: function() {
						$('#loading_bed').hide();
					},
					complete: function($response, $status) {
						if ($status == "success" || $status == "notmodified") {
							$("#bed_id").html($response.responseText);
						}
					}
				});
			}
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
	.form-horizontal .form-actions {
		padding-left: 5px;
	}
	
	#myModal .modal-body {
		max-height: 800px;
		height: 420px;
	}
	#myModal {
		width: 1000px; /* SET THE WIDTH OF THE MODAL */
		margin: 0 0 0 -500px; /* CHANGE MARGINS TO ACCOMODATE THE NEW WIDTH (original = margin: -250px 0 0 -280px;) */
	}
	
	.modal-body #dt_pasien .dataTables_length {
		float: left;
	}
	
	.modal-body #dt_pasien .dataTables_info {
		float: left;
		margin-bottom: 5px;
	}
	
	.modal-body #dt_pasien .dataTables_filter {
		float: right;
	}
	
	.modal-body #dt_pasien .dataTables_paginate {
		float: right;
		margin: 5px 0;
	}
	
	.modal-body #dt_pasien .dataTables_paginate .first, 
	.modal-body #dt_pasien .dataTables_paginate .previous, 
	.modal-body #dt_pasien .dataTables_paginate .next, 
	.modal-body #dt_pasien .dataTables_paginate .paginate_active, 
	.modal-body #dt_pasien .dataTables_paginate .last, 
	.modal-body #dt_pasien .dataTables_paginate .paginate_button {
		background-color: #E6E6E6;
		background-image: -moz-linear-gradient(center top , #F2F2F2, #E6E6E6);
		border-bottom: 1px solid #D9D9D9;
		border-left: 1px solid #D9D9D9;
		border-top: 1px solid #D9D9D9;
		padding: 7px 10px;
	}
	
	#test_modal .modal-body {
		max-height: 800px;
		height: 420px;
	}
	#test_modal {
		width: 1000px; /* SET THE WIDTH OF THE MODAL */
		margin: 0 0 0 -500px; /* CHANGE MARGINS TO ACCOMODATE THE NEW WIDTH (original = margin: -250px 0 0 -280px;) */
	}
	
	#test_modal .dataTables_length {
		float: left;
	}
	
	 #test_modal .dataTables_info {
		float: left;
		margin-bottom: 5px;
	}
	
	#test_modal .dataTables_filter {
		float: right;
	}
	
	#test_modal .dataTables_paginate {
		float: right;
		margin: 5px 0;
	}
	
	#test_modal .dataTables_paginate .first, 
	#test_modal .dataTables_paginate .previous, 
	#test_modal .dataTables_paginate .next, 
	#test_modal .dataTables_paginate .paginate_active, 
	#test_modal .dataTables_paginate .last, 
	#test_modal .dataTables_paginate .paginate_button {
		background-color: #E6E6E6;
		background-image: -moz-linear-gradient(center top , #F2F2F2, #E6E6E6);
		border-bottom: 1px solid #D9D9D9;
		border-left: 1px solid #D9D9D9;
		border-top: 1px solid #D9D9D9;
		padding: 7px 10px;
	}
	
	#pasien_modal .modal-body {
        max-height: 800px;
    }
	
	#pasien_modal {
		width: 800px;
		margin-left: -400px;
		margin-right: -400px;
	}
	
	ul.dynatree-container {
		border: none;
	}
	
</style>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<?php
					if ($is_new) {
						$url = site_url('laboratorium/pendaftaran');
					}
					else {
						$url = site_url('laboratorium/pendaftaran/edit/'.$data->pendaftaran_id);
					}
				?>
				<form class="form-horizontal no-margin" id="pendaftaran_form" name="pendaftaran_form" method="post" action="<?php echo $url; ?>">
					<div class="widget-header">
						<div class="title">Pendaftaran Laboratorium</div>
						<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
					</div>
					<div class="row-fluid" style="border-top: 0; border-bottom: 1px solid #E5E5E5;">
						<div class="span12">
							<div class="form-actions">
								<button class="btn pull-right" type="submit" id="batal" name="batal" value="Batal" style="margin-left: 4px;">Batal</button>
								<button class="btn btn-info pull-right" type="submit" id="simpan" name="simpan" value="Simpan">Simpan</button>
							</div>
						</div>
					</div>
					<div class="widget-body">

						<div class="container-fluid">

							<div class="row-fluid">
								<div class="span6">
									<div class="control-group">
										<label class="control-label" for="disp_tanggal">Tanggal Pendaftaran</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('tanggal', $data->tanggal);
												$tanggal = strftime( "%d/%m/%Y", strtotime($value));
											?>
											<input type="hidden" id="tanggal" name="tanggal" value="<?php echo $value; ?>" />
											<input class="span6" type="text" id="disp_tanggal" nama="disp_tanggal" data-date-format="mm/dd/yyyy" placeholder="" value="<?php echo $tanggal; ?>" />
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
											<input class="span6" type="text" id="no_register" name="no_register" placeholder="No. Register" value="<?php echo $value; ?>" autocomplte="off" />
											<?php echo form_error('no_register'); ?>
										</div>
									</div>
								</div>
							</div>
							<hr />
							<div class="row-fluid">
							
								<div class="span6">
									
									<div class="control-group">
										<label class="control-label" for="asal_pasien">Asal Pasien</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('asal_pasien', $data->asal_pasien);
											?>
											<label class="radio">
												<input type="radio" id="asal_pasien1" name="asal_pasien" value="1" <?php echo $value == 1 ? "checked=\"checked\"" : ""; ?>>Rawat Jalan
											</label>
											<label class="radio">
												<input type="radio" id="asal_pasien2" name="asal_pasien" value="2" <?php echo $value == 2 ? "checked=\"checked\"" : ""; ?>>Rawat Darurat
											</label>
											<label class="radio">
												<input type="radio" id="asal_pasien3" name="asal_pasien" value="3" <?php echo $value == 3 ? "checked=\"checked\"" : ""; ?>>Rawat Inap
											</label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="no_medrec">No. Medrec</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('no_medrec', $data->no_medrec);
											?>
											<input class="span6" type="text" id="no_medrec" name="no_medrec" placeholder="No. Medrec" value="<?php echo $value; ?>" autocomplte="off" />
											<a id="cari_pasien_button" href="#">
												<span id="toggle-btn" class="add-on btn">Cari...</span>
											</a>
											<?php echo form_error('no_medrec'); ?>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Nama</label>
										<div class="controls controls-row">
											<label id="nama" style="padding-top: 5px; color: #000;"></label>
										</div>
									</div>

									<div class="control-group">
										<label class="control-label">Jenis Kelamin</label>
										<div class="controls controls-row">
											<label id="jenis_kelamin" style="padding-top: 5px; color: #000;"></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Alamat</label>
										<div class="controls controls-row">
											<label id="alamat" style="padding-top: 5px; color: #000;"></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Telepon</label>
										<div class="controls controls-row">
											<label id="telepon" style="padding-top: 5px; color: #000;"></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Tempat/Tanggal Lahir</label>
										<div class="controls controls-row">
											<label id="tempat_tanggal_lahir" style="padding-top: 5px; color: #000;"></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Umur</label>
										<div class="controls controls-row">
											<label id="umur" style="padding-top: 5px; color: #000;"></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Agama</label>
										<div class="controls controls-row">
											<label id="agama" style="padding-top: 5px; color: #000;"></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Pendidikan</label>
										<div class="controls controls-row">
											<label id="pendidikan" style="padding-top: 5px; color: #000;"></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Pekerjaan</label>
										<div class="controls controls-row">
											<label id="pekerjaan" style="padding-top: 5px; color: #000;"></label>
										</div>
									</div>
									
								</div>
								
								<div class="span6">
									
									<div class="control-group">
										<label class="control-label">Rujukan</label>
										<div class="controls controls-row">
											<label id="rujukan" style="padding-top: 5px; color: #000;"></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Cara Pembayaran</label>
										<div class="controls controls-row">
											<label id="cara_bayar" style="padding-top: 5px; color: #000;"></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Dokter</label>
										<div class="controls controls-row">
											<label id="dokter" style="padding-top: 5px; color: #000;"></label>
										</div>
									</div>
									
									<br>
									<h5>Bed</h5>
									<hr>
									
									<div class="control-group">
										<label class="control-label" for="gedung_id">Gedung/Bangsal</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('gedung_id', $data->gedung_id);
												$first = array();
												$gedung = new stdClass();
												$gedung->id = 0;
												$gedung->nama = "- Pilih Gedung/Bangsal -";
												$first[] = $gedung;
												$gedung_list = array_merge($first, $gedung_list);
											?>
											<select id="gedung_id" name="gedung_id">
												<?php
													foreach ($gedung_list as $val) {
														if ($value == $val->id) {
															echo "<option value=\"{$val->id}\" selected=\"selected\">{$val->nama}</option>";
														} else {
															echo "<option value=\"{$val->id}\">{$val->nama}</option>";
														}
													}
												?>
											</select>
											<?php echo form_error('gedung_id'); ?>
										</div>
									</div>
									
									<div id="ruangan" class="control-group">
										<label class="control-label" for="ruangan_id">Ruangan/Kamar</label>
										<div class="controls controls-row">
											<select id="ruangan_id" name="ruangan_id">
												<option value="0" selected="selected">- Pilih Ruangan/Kamar -</option>
											</select>
											<?php echo form_error('ruangan_id'); ?>
											<img id="loading_ruangan" alt="loading" src="<?php echo base_url('assets/img/loading.gif'); ?>" style="position: absolute; margin: 5px; display: none;" />
										</div>
									</div>
									
									<div id="bed" class="control-group">
										<label class="control-label" for="bed_id">Bed</label>
										<div class="controls controls-row">
											<select id="bed_id" name="bed_id">
												<option value="0" selected="selected">- Pilih Bed -</option>
											</select>
											<?php echo form_error('bed_id'); ?>
											<img id="loading_bed" alt="loading" src="<?php echo base_url('assets/img/loading.gif'); ?>" style="position: absolute; margin: 5px; display: none;" />
										</div>
									</div>
			
								</div>
							
							</div>
							
							<hr />
							
							<div class="row-fluid">
								
								<div class="span12">
								
									<br>
									<h5>Jenis Pelayanan</h5>
									<hr>

									<div class="control-group">
										<label class="control-label" style="width: 0;"></label>
										<div class="controls controls-row" style="margin-left: 0; height: 200px; overflow: auto; border: 1px solid #CCCCCC; background-color: #FFFFFF;">
											<div id="tree"></div>
										</div>
									</div>
								
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
					<div>
						<?php
							$value = set_value('pendaftaran_id', $data->pendaftaran_id);
						?>
						<input type="hidden" id="pendaftaran_id" name="pendaftaran_id" value="<?php echo $value; ?>" />
						<?php
							$value = set_value('pasien_id', $data->pasien_id);
						?>
						<input type="hidden" id="pasien_id" name="pasien_id" value="<?php echo $value; ?>" />
						<input type="hidden" id="permintaan_pemeriksaan" name="permintaan_pemeriksaan" value="" />
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="pasien_modal" data-remote="<?php echo site_url('laboratorium/pendaftaran/browse_pasien'); ?>">
    <div class="modal-header">
       <a class="close" data-dismiss="modal">&times;</a>
       <h4>Daftar Pasien</h4>
    </div>
    <div class="modal-body"></div>
</div>