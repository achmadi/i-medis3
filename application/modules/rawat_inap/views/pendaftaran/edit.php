<script type="text/javascript">
			
	$(document).ready(function() {
        
        $('#no_medrec').focus();
        
		//$("#disp_tanggal").mask("99/99/9999");
		$("#tanggal").datepicker({
			format: "dd/mm/yy"
			//altField: "#tanggal",
			//altFormat: "yy-mm-dd"
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
</style>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<form class="form-horizontal no-margin">
					<div class="widget-header">
						<div class="title">Pendaftaran Rawat Jalan</div>
						<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
					</div>
					<div class="row-fluid" style="border-top: 0; border-bottom: 1px solid #E5E5E5;">
						<div class="span12">
							<div class="form-actions" style="text-align: right;">
								<button class="btn btn-info" type="submit" id="simpan" name="simpan" value="Simpan">Simpan</button>
								<button class="btn" type="submit" id="batal" name="batal" value="Batal">Batal</button>
							</div>
						</div>
					</div>
					<div class="widget-body">

						<div class="container-fluid">

							<div class="row-fluid">
								<div class="span6"></div>
								<div class="span6">
									
									<div class="control-group">
										<label class="control-label" for="date_range2">Tanggal Pendaftaran</label>
										<div class="controls controls-row">
											<input class="span6" type="text" id="tanggal" name="tanggal" placeholder="01/29/2013" />
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="name">No. Register</label>
										<div class="controls controls-row">
											<input class="span6" type="text" placeholder="No. Register">
										</div>
									</div>
									
								</div>
							</div>
							<hr />
							<div class="row-fluid">
							
								<div class="span6">
									
									<div class="control-group">
										<label class="control-label" for="no_medrec">No. Medrec</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('no_medrec', $data->no_medrec);
											?>
											<input class="span6" type="text" id="no_medrec" nama="no_medrec" placeholder="No. Medrec" value="<?php echo $value; ?>">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="nama">Nama</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('nama', $data->nama);
											?>
											<input class="span12" type="text" id="nama" name="nama" placeholder="Nama" value="<?php echo $value; ?>">
										</div>
									</div>

									<div class="control-group">
										<label class="control-label" for="jenis_kelamin1">Jenis Kelamin</label>
										<div class="controls controls-row">
											<label class="radio inline">
												<input type="radio" id="jenis_kelamin1" name="jenis_kelamin" value="1" <?php echo $value == 1 ? "checked=\"checked\"" : ""; ?>>Laki-laki
											</label>
											<label class="radio inline">
												<input type="radio" id="jenis_kelamin2" name="jenis_kelamin" value="2" <?php echo $value == 2 ? "checked=\"checked\"" : ""; ?>>Perempuan
											</label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="alamat">Alamat</label>
										<div class="controls controls-row">
											<textarea class="span12" id="alamat" name="alamat" placeholder="Alamat"></textarea>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="name">Tempat Lahir</label>
										<div class="controls controls-row">
											<input class="span12" type="text" placeholder="Tempat Lahir">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="name">Tanggal Lahir</label>
										<div class="controls controls-row">
											<input class="span6" type="text" placeholder="">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="name">Umur</label>
										<div class="controls controls-row">
											<div style="float: left;">
												<input class="span2" type="text" placeholder="">
											</div>
											<div style="float: left;">
												<label>Tahun</label>
											</div>
											<input class="span2" type="text" placeholder="">
											<label>Tahun</label>
											<input class="span2" type="text" placeholder="">
											<label>Tahun</label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="name">Agama</label>
										<div class="controls controls-row">
											<input class="span12" type="text" placeholder="First Name">
										</div>
									</div>
									
									
									<div class="control-group">
										<label class="control-label" for="agama_id">Agama</label>
										<div class="controls controls-row">
											<select id="agama_id" name="agama_id">
												<option selected="selected" value="">- Pilih Agama -</option>
												<option value="AL"> Alabama </option>
												<option value="AK"> Alaska </option>
												<option value="AZ"> Arizona </option>
												<option value="AR"> Arkansas </option>
											</select>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="name">Pendidikan</label>
										<div class="controls controls-row">
											<input class="span12" type="text" placeholder="First Name">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="name">Pekerjaan</label>
										<div class="controls controls-row">
											<input class="span12" type="text" placeholder="First Name">
										</div>
									</div>
								</div>
								
								<div class="span6">
									
									<div class="control-group">
										<label class="control-label" for="rujukan_id">Rujukan</label>
										<div class="controls controls-row">
											<select id="rujukan_id" name="rujukan_id">
												
											</select>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="cara_bayar_id">Cara Pembayaran</label>
										<div class="controls controls-row">
											<select id="cara_bayar_id" name="cara_bayar_id">
												
											</select>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="unit_id">Poliklinik</label>
										<div class="controls controls-row">
											<select id="unit_id" name="unit_id">
												
											</select>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="dokter_id">Dokter</label>
										<div class="controls controls-row">
											<select id="dokter_id" name="dokter_id">
												
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
								<button class="btn btn-info" type="submit" id="simpan" name="simpan" value="Simpan">Simpan</button>
								<button class="btn" type="submit" id="batal" name="batal" value="Batal">Batal</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
