<script type="text/javascript">
	
	$(document).ready(function() {
		
		var provinsi_id = "<?php echo $data->provinsi_id; ?>";
		if (provinsi_id > 0) {
			$.ajax({
				type: "GET",
				url: "<?php echo site_url('master/options/get_kabupaten'); ?>",
				data: "provinsi_id=" + provinsi_id + "&kabupaten_id=" + <?php echo $data->kabupaten_id; ?>, 
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
		$("#provinsi_id").change(function() {
			var provinsi_id = $("#provinsi_id").val();
			if (provinsi_id) {
				$.ajax({
					type: "GET",
					url: "<?php echo site_url('master/options/get_kabupaten') ?>",
					data: "provinsi_id=" + provinsi_id, 
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
							$("#kecamatan_id").html('<option value="0" selected="selected">- Pilih Kecamatan -</option>');
							$("#kelurahan_id").html('<option value="0" selected="selected">- Pilih Kelurahan/Desa -</option>');
						}
					}
				});
			}
		});
		
		var kabupaten_id = <?php echo $data->kabupaten_id; ?>;
		if (kabupaten_id > 0) {
			$.ajax({
				type: "GET",
				url: "<?php echo site_url('master/options/get_kecamatan') ?>",
				data: "kabupaten_id=" + kabupaten_id + "&kecamatan_id=" + <?php echo $data->kecamatan_id; ?>,
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
		$("#kabupaten_id").change(function() {
			var kabupaten_id = $("#kabupaten_id").val();
			if (kabupaten_id) {
				$.ajax({
					type: "GET",
					url: "<?php echo site_url('master/options/get_kecamatan') ?>",
					data: "kabupaten_id=" + kabupaten_id, 
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
							$("#kelurahan_id").html('<option value="0" selected="selected">- Pilih Kelurahan/Desa -</option>');
						}
					}
				});
			}
		});
		
		var kecamatan_id = <?php echo $data->kecamatan_id; ?>;
		if (kecamatan_id > 0) {
			$.ajax({
				type: "GET",
				url: "<?php echo site_url('master/options/get_kelurahan') ?>",
				data: "kecamatan_id=" + kecamatan_id + "&kelurahan_id=" + <?php echo $data->kelurahan_id; ?>,
				cache: false,
				beforeSend: function() {
					$('#loading_kelurahan').show();
					$('#loading_kelurahan').css("display", "inline");
				},
				success: function() {
					$('#loading_kelurahan').hide();
				},
				complete: function($response, $status) {
					if ($status === "success" || $status === "notmodified") {
						$("#kelurahan_id").html($response.responseText);
					}
				}
			});
		}
		$("#kecamatan_id").change(function() {
			var kecamatan_id = $("#kecamatan_id").val();
			if (kecamatan_id) {
				$.ajax({
					type: "GET",
					url: "<?php echo site_url('master/options/get_kelurahan') ?>",
					data: "kecamatan_id=" + kecamatan_id, 
					cache: false,
					beforeSend: function() {
						$('#loading_kelurahan').show();
						$('#loading_kelurahan').css("display", "inline");
					},
					success: function() {
						$('#loading_kelurahan').hide();
					},
					complete: function($response, $status) {
						if ($status === "success" || $status === "notmodified") {
							$("#kelurahan_id").html($response.responseText);
						}
					}
				});
			}
		});
		
		function generateBarcode(){
			var value = $("#barcode_value").val();
			var btype = $("input[name=btype]:checked").val();
			var renderer = $("input[name=renderer]:checked").val();
			
			var quietZone = false;
			if ($("#quietzone").is(':checked') || $("#quietzone").attr('checked')){
				quietZone = true;
			}

			var settings = {
				output			: renderer,
				bgColor			: $("#backcolor").val(),
				color			: $("#forecolor").val(),
				barWidth		: $("#bar_width").val(),
				barHeight		: $("#bar_height").val(),
				moduleSize		: $("#module_size").val(),
				posX			: $("#pos_x").val(),
				posY			: $("#pos_y").val(),
				addQuietZone	: $("#quiet_zone_size").val()
			};
			if ($("#rectangular").is(':checked') || $("#rectangular").attr('checked')) {
				value = {code:value, rect: true};
			}
			if (renderer == 'canvas'){
				clearCanvas();
				$("#barcodeTarget").hide();
				$("#canvasTarget").show().barcode(value, btype, settings);
			} else {
				$("#canvasTarget").hide();
				$("#barcodeTarget").html("").show().barcode(value, btype, settings);
			}
		}
		
		function showConfig1D() {
			$('#barcode_1d_1').show();
			$('#barcode_1d_2').show();
			
			$('#barcode_2d_1').hide();
			$('#barcode_2d_2').hide();
			$('#barcode_2d_3').hide();
		}

		function showConfig2D() {
			$('#barcode_1d_1').hide();
			$('#barcode_1d_2').hide();
			
			$('#barcode_2d_1').show();
			$('#barcode_2d_2').show();
			$('#barcode_2d_3').show();
		}
		
		function showMiscCanvas(show_canvas) {
			if (show_canvas) {
				$('#misc_canvas_1').show();
				$('#misc_canvas_2').show();
			}
			else {
				$('#misc_canvas_1').hide();
				$('#misc_canvas_2').hide();
			}
		}
		
		function clearCanvas(){
			var canvas = $('#canvasTarget').get(0);
			var ctx = canvas.getContext('2d');
			ctx.lineWidth = 1;
			ctx.lineCap = 'butt';
			ctx.fillStyle = '#FFFFFF';
			ctx.strokeStyle  = '#000000';
			ctx.clearRect (0, 0, canvas.width, canvas.height);
			ctx.strokeRect (0, 0, canvas.width, canvas.height);
		}
		
		$('#generate_barcode').click(function() {
			generateBarcode();
		});
		
		$(function() {
			if ($(this).attr('id') == 'datamatrix') showConfig2D(); else showConfig1D();
			$('input[name=btype]').click(function(){
				if ($(this).attr('id') == 'datamatrix') showConfig2D(); else showConfig1D();
			});
			if ($(this).attr('id') == 'canvas') showMiscCanvas(true); else showMiscCanvas(false);
			$('input[name=renderer]').click(function(){
				if ($(this).attr('id') == 'canvas') showMiscCanvas(true); else showMiscCanvas(false);
			});
			generateBarcode();
		});
		
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
				<div class="widget-header">
					<div class="title">Options</div>
					<span class="tools">
						<a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a>
					</span>
				</div>
				<div class="widget-body">
					<form class="form-horizontal no-margin" id="options_form" name="options_form" method="post" action="<?php echo site_url('master/options'); ?>">
						<ul class="nav nav-tabs no-margin myTabBeauty">
							<li class="">
								<a data-toggle="tab" href="#umum">Umum</a>
							</li>
							<li class="active">
								<a data-toggle="tab" href="#barcode">Barcode</a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#lain_lain">Lain-lain</a>
							</li>
						</ul>
						<div class="tab-content" id="myTabContent">
							<div id="umum" class="tab-pane fade">
								
								<div class="span6">
								
									<div class="control-group">
										<label class="control-label" for="provinsi_id">&nbsp;</label>
										<div class="controls controls-row">
											<div class="span5">
												<label>Provinsi</label>
											</div>
											<div class="span7">
												<?php
													$value = $data->provinsi_id;
													$provinsi = new stdClass();
													$provinsi->id = 0;
													$provinsi->nama = "- Pilih Provinsi -";
													$first = array($provinsi);
													$provinsi_list = array_merge($first, $provinsi_list);
												?>
												<select class="span12" id="provinsi_id" name="provinsi_id">
													<?php
														foreach ($provinsi_list as $val) {
															if ($value == $val->id) {
																echo "<option value=\"{$val->id}\" selected=\"selected\">{$val->nama}</option>";
															} else {
																echo "<option value=\"{$val->id}\">{$val->nama}</option>";
															}
														}
													?>
												</select>
												<img id="loading_provinsi" alt="loading" src="<?php echo base_url('assets/img/loading.gif'); ?>" style="position: absolute; margin: 5px; display: none;" />
											</div>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="kabupaten_id">&nbsp;</label>
										<div class="controls controls-row">
											<div class="span5">
												<label>Kabupaten/Kota</label>
											</div>
											<div class="span7">
												<select class="span12" id="kabupaten_id" name="kabupaten_id">
													<option value="0">- Pilih Kabupaten/Kota -</option>
												</select>
												<img id="loading_kabupaten" alt="loading" src="<?php echo base_url('assets/img/loading.gif'); ?>" style="position: absolute; margin: 5px; display: none;" />
											</div>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="kecamatan_id">&nbsp;</label>
										<div class="controls controls-row">
											<div class="span5">
												<label>Kecamatan</label>
											</div>
											<div class="span7">
												<select class="span12" id="kecamatan_id" name="kecamatan_id">
													<option value="0">- Pilih Kecamatan -</option>
												</select>
												<img id="loading_kecamatan" alt="loading" src="<?php echo base_url('assets/img/loading.gif'); ?>" style="position: absolute; margin: 5px; display: none;" />
											</div>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="kelurahan_id">&nbsp;</label>
										<div class="controls controls-row">
											<div class="span5">
												<label>Kelurahan/Desa</label>
											</div>
											<div class="span7">
												<select class="span12" id="kelurahan_id" name="kelurahan_id">
													<option value="0">- Pilih Kelurahan/Desa -</option>
												</select>
												<img id="loading_kelurahan" alt="loading" src="<?php echo base_url('assets/img/loading.gif'); ?>" style="position: absolute; margin: 5px; display: none;" />
											</div>
										</div>
									</div>
								
								</div>
							
							</div>
							<div id="barcode" class="tab-pane fade active in">
								
								<div class="span4">
									
									<h5 style="margin-top: 0; margin-bottom: 0;">Type</h5>
									<hr>
									
									<div class="control-group">
										<label class="control-label" style="width: 0;"></label>
										<div class="controls" style="margin-left: 0;">
											<label class="radio">
												<input type="radio" id="ean8" name="btype" value="ean8" <?php echo $data->barcode_btype == "ean8" ? "checked=\"checked\"" : ""; ?> />EAN 8
											</label>
											<label class="radio">
												<input type="radio" id="ean13" name="btype" value="ean13" <?php echo $data->barcode_btype == "ean13" ? "checked=\"checked\"" : ""; ?> />EAN 13
											</label>
											<label class="radio">
												<input type="radio" id="upc" name="btype" value="upc" <?php echo $data->barcode_btype == "upc" ? "checked=\"checked\"" : ""; ?> />UPC
											</label>
											<label class="radio">
												<input type="radio" id="std25" name="btype" value="std25" <?php echo $data->barcode_btype == "std25" ? "checked=\"checked\"" : ""; ?> />standard 2 of 5 (industrial)
											</label>
											<label class="radio">
												<input type="radio" id="int25" name="btype" value="int25" <?php echo $data->barcode_btype == "int25" ? "checked=\"checked\"" : ""; ?> />interleaved 2 of 5
											</label>
											<label class="radio">
												<input type="radio" id="code11" name="btype" value="code11" <?php echo $data->barcode_btype == "code11" ? "checked=\"checked\"" : ""; ?> />code 11
											</label>
											<label class="radio">
												<input type="radio" id="code39" name="btype" value="code39" <?php echo $data->barcode_btype == "code39" ? "checked=\"checked\"" : ""; ?> />code 39
											</label>
											<label class="radio">
												<input type="radio" id="code93" name="btype" value="code93" <?php echo $data->barcode_btype == "code93" ? "checked=\"checked\"" : ""; ?> />code 93
											</label>
											<label class="radio">
												<input type="radio" id="code128" name="btype" value="code128" <?php echo $data->barcode_btype == "code128" ? "checked=\"checked\"" : ""; ?> />code 128
											</label>
											<label class="radio">
												<input type="radio" id="codabar" name="btype" value="codabar" <?php echo $data->barcode_btype == "codabar" ? "checked=\"checked\"" : ""; ?> />codabar
											</label>
											<label class="radio">
												<input type="radio" id="msi" name="btype" value="msi" <?php echo $data->barcode_btype == "msi" ? "checked=\"checked\"" : ""; ?> />MSI
											</label>
											<label class="radio">
												<input type="radio" id="datamatrix" name="btype" value="datamatrix" <?php echo $data->barcode_btype == "datamatrix" ? "checked=\"checked\"" : ""; ?> />Data Matrix
											</label>
										</div>
									</div>

								</div>
								
								<div class="span4">
									
									<h5 style="margin-top: 0; margin-bottom: 0;">Misc</h5>
									<hr>
									
									<div class="control-group">
										<label class="control-label" for="backcolor" style="width: 110px;">Background</label>
										<div class="controls controls-row" style="margin-left: 130px;">
											<input type="text" id="backcolor" name="backcolor" value="#FFFFFF" class="span12" />
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="forecolor" style="width: 110px;">Forecolor</label>
										<div class="controls controls-row" style="margin-left: 130px;">
											<input type="text" id="forecolor" name="forecolor" value="#000000" class="span12" />
										</div>
									</div>
									
									<div id="barcode_1d_1" class="control-group">
										<label class="control-label" for="bar_width" style="width: 110px;">Bar Width</label>
										<div class="controls controls-row" style="margin-left: 130px;">
											<input type="text" id="bar_width" name="bar_width" value="1" class="span12" />
										</div>
									</div>
									
									<div id="barcode_1d_2" class="control-group">
										<label class="control-label" for="bar_height" style="width: 110px;">Bar Height</label>
										<div class="controls controls-row" style="margin-left: 130px;">
											<input type="text" id="bar_height" name="bar_height" value="50" class="span12" />
										</div>
									</div>
									
									<div id="barcode_2d_1" class="control-group">
										<label class="control-label" for="module_size" style="width: 110px;">Module Size</label>
										<div class="controls controls-row" style="margin-left: 130px;">
											<input type="text" id="module_size" name="module_size" value="5" class="span12" />
										</div>
									</div>
									
									<div id="barcode_2d_2" class="control-group">
										<label class="control-label" for="quiet_zone_size" style="width: 110px;">Quiet Zone Modules</label>
										<div class="controls controls-row" style="margin-left: 130px;">
											<input type="text" id="quiet_zone_size" name="quiet_zone_size" value="1" class="span12" />
										</div>
									</div>
									
									<div id="barcode_2d_3" class="control-group">
										<label class="control-label" for="rectangular" style="width: 110px;">Form</label>
										<div class="controls" style="margin-left: 130px;">
											<label class="checkbox">
												<input type="checkbox" id="rectangular" name="rectangular" value="1" />Rectangular
											</label>
										</div>
									</div>
																
									<div id="misc_canvas_1" class="control-group">
										<label class="control-label" for="pos_x" style="width: 110px;">X</label>
										<div class="controls controls-row" style="margin-left: 130px;">
											<input type="text" id="pos_x" name="pos_x" value="10" class="span12" />
										</div>
									</div>
									
									<div id="misc_canvas_2" class="control-group">
										<label class="control-label" for="pos_y" style="width: 110px;">Y</label>
										<div class="controls controls-row" style="margin-left: 130px;">
											<input type="text" id="pos_y" name="pos_y" value="20" class="span12" />
										</div>
									</div>
									
								</div>
								
								<div class="span4">
									
									<h5 style="margin-top: 0; margin-bottom: 0;">Format</h5>
									<hr>
									
									<div class="control-group">
										<label class="control-label" style="width: 0;"></label>
										<div class="controls" style="margin-left: 0;">
											<label class="radio">
												<input type="radio" id="css" name="renderer" value="css" <?php echo $data->barcode_renderer == "css" ? "checked=\"checked\"" : ""; ?> />CSS
											</label>
											<label class="radio">
												<input type="radio" id="bmp" name="renderer" value="bmp" <?php echo $data->barcode_renderer == "bmp" ? "checked=\"checked\"" : ""; ?> />BMP (not usable in IE)
											</label>
											<label class="radio">
												<input type="radio" id="svg" name="renderer" value="svg" <?php echo $data->barcode_renderer == "svg" ? "checked=\"checked\"" : ""; ?> />SVG (not usable in IE)
											</label>
											<label class="radio">
												<input type="radio" id="canvas" name="renderer" value="canvas" <?php echo $data->barcode_renderer == "canvas" ? "checked=\"checked\"" : ""; ?> />Canvas (not usable in IE)
											</label>
										</div>
									</div>
									
								</div>
								
								<div class="clearfix"></div>
								
								<div class="span4" style="margin-left: 0;">
									<button id="generate_barcode" class="btn btn-small btn-success btn-block" type="button">Preview</button>
								</div>
								
								<div class="clearfix"></div>
								
								<div class="span4" style="margin-left: 0;">
									<div class="control-group">
										<label class="control-label" for="barcode_value" style="width: 110px;">Kode</label>
										<div class="controls controls-row" style="margin-left: 130px;">
											<input type="text" id="barcode_value" name="barcode_value" value="123456" class="span12" />
										</div>
									</div>
								</div>
								
								<div class="clearfix"></div>
								
								<div class="span4">
									<div id="barcodeTarget" class="barcodeTarget"></div>
									<canvas id="canvasTarget" width="150" height="150"></canvas>
								</div>
								
							</div>
							<div id="lain_lain" class="tab-pane fade">

							</div>
							<div class="row-fluid">
								<div class="span12">
									<div class="form-actions" style="text-align: right;">
										<button class="btn btn-info" type="submit" name="simpan" value="Simpan">Simpan</button>
										<button class="btn" type="submit" name="batal" value="Batal">Batal</button>
									</div>
								</div>
							</div>
					
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
