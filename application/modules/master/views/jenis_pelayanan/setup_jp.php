<script type="text/javascript" charset="utf-8">
	
	$(document).ready(function() {
		
		$('#pemda').autoNumeric('init');
		$('#dibagikan').autoNumeric('init');
		$('#manajemen').autoNumeric('init');
		$('#sisa').autoNumeric('init');
		
		$('#pemda').bind('keyup', function() {
			var pemda = $('#pemda').val();
			pemda = pemda.replace(',', '.');
			var remain = parseFloat(100.00 - parseFloat(pemda));
			$('#dibagikan').val(remain);
		});
		
		$('#manajemen').bind('keyup', function() {
			var manajemen = $('#manajemen').val();
			manajemen = manajemen.replace(',', '.');
			var remain = parseFloat(100.00 - parseFloat(manajemen));
			$('#sisa').val(remain);
		});
		
		$('.proporsi').autoNumeric('init');
		$('.langsung').autoNumeric('init');
		$('.kebersamaan').autoNumeric('init');
		
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
			<div class="widget" style="border: none; margin-bottom: 10px;">
				<ul class="breadcrumb-beauty">
					<li><a href="<?php echo site_url('master/jenis_pelayanan'); ?>">Jenis Pelayanan</a></li>
					<li><a href="#">Setup Jasa Pelayanan <?php echo $data->nama; ?></a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<?php
					$url = site_url('master/jenis_pelayanan/setup_jp/'.$data->id);
					$title = "Edit Setup Jasa Pelayanan";
				?>
				<form class="form-horizontal no-margin" id="jasa_pelayanan_form" name="jasa_pelayanan_form" method="post" action="<?php echo $url; ?>">
					<div class="widget-header">
						<div class="title"><?php echo $title; ?></div>
						<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
					</div>
					<div class="widget-body">

						<div class="container-fluid">

							<div class="row-fluid">
							
								<div class="span12">
									
									<div class="control-group">
										<label class="control-label" for="pemda">Pemda</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('pemda', $data->pemda);
											?>
											<input class="span2" type="text" id="pemda" name="pemda" data-v-min="0.00" data-v-max="100.00" data-a-sep="." data-a-dec="," maxlength="6" value="<?php echo $value; ?>" style="text-align: right;" />
											<span class="help-inline ">%</span>
											<?php echo form_error('pemda'); ?>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="dibagikan">Jasa yang dibagikan</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('dibagikan', $data->dibagikan);
											?>
											<input class="span2" type="text" id="dibagikan" name="dibagikan" data-v-min="0.00" data-v-max="100.00" data-a-sep="." data-a-dec="," maxlength="6" value="<?php echo $value; ?>" style="text-align: right;" />
											<span class="help-inline ">%</span>
											<?php echo form_error('dibagikan'); ?>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label"></label>
										<div class="controls controls-row">
											
											<div class="control-group">
												<label class="control-label" for="manajemen" style="width: 80px;">Manajemen</label>
												<div class="controls controls-row" style="margin-left: 100px;">
													<?php
														$value = set_value('manajemen', $data->manajemen);
													?>
													<input class="span2" type="text" id="manajemen" name="manajemen" data-v-min="0.00" data-v-max="100.00" data-a-sep="." data-a-dec="," maxlength="6" value="<?php echo $value; ?>" style="text-align: right;" />
													<span class="help-inline ">%</span>
													<?php echo form_error('manajemen'); ?>
												</div>
											</div>
											
											<div class="control-group">
												<label class="control-label" for="sisa" style="width: 80px;">Sisa</label>
												<div class="controls controls-row" style="margin-left: 100px;">
													<?php
														$value = set_value('sisa', $data->sisa);
													?>
													<input class="span2" type="text" id="sisa" name="sisa" data-v-min="0.00" data-v-max="100.00" data-a-sep="." data-a-dec="," maxlength="6" value="<?php echo $value; ?>" style="text-align: right;" />
													<span class="help-inline ">%</span>
													<?php echo form_error('sisa'); ?>
												</div>
											</div>
											
										</div>
									</div>
									<hr />
									
								</div>
								
								<div class="span12" style="margin-left: 0;">
									<a class="btn btn-info btn-mini pull-right" href="<?php echo site_url('master/setup_komponen_jp?unit_id='.$data->id); ?>">Setup Komponen Jasa Pelayanan</a>
								</div>
								
								<div class="span12" style="margin-left: 0;">
									
									<table class="table table-condensed table-striped table-hover table-bordered pull-left" id="table_jenis_pelayanan_detail" style="margin-bottom: 0;">
										<thead>
											<tr>
												<th style="text-align: center;">Uraian</th>
												<th style="text-align: center; width: 115px;">Proporsi (%)</th>
												<th style="text-align: center; width: 115px;">Langsung (%)</th>
												<th style="text-align: center; width: 115px;">Kebersamaan (%)</th>
											</tr>
										</thead>
										<tbody>
											<?php
												$index = 0;
												foreach ($data->details as $row) {
													if ($row->jenis != 'Root') {
											?>
											<tr>
												<td>
													<input type="hidden" id="unit_detail_id_<?php echo $index; ?>" name="unit_detail_id[]" value="<?php echo $row->id; ?>"/>
													<?php echo $row->kelompok; ?>
												</td>
												<td>
													<input class="proporsi" type="text" id="proporsi_<?php echo $index; ?>" name="proporsi[]" value="<?php echo $row->proporsi; ?>" data-v-max="999.99" data-a-sep="." data-a-dec="," maxlength="6" value="0" style="width: 90%; display: block; text-align: right;" />
												</td>
												<td>
													<input class="langsung" type="text" id="langsung_<?php echo $index; ?>" name="langsung[]" value="<?php echo $row->langsung; ?>" data-v-max="999.99" data-a-sep="." data-a-dec="," maxlength="6" value="0" style="width: 90%; display: block; text-align: right;" />
												</td>
												<td>
													<input class="kebersamaan" type="text" id="kebersamaan_<?php echo $index; ?>" name="kebersamaan[]" value="<?php echo $row->kebersamaan; ?>" data-v-max="999.99" data-a-sep="." data-a-dec="," maxlength="6" value="0" style="width: 90%; display: block; text-align: right;" />
												</td>
											</tr>
											<?php
													}
												}
											?>
										</tbody>
									</table>
									<table class="table table-condensed table-striped table-bordered table-hover no-margin" style="border-top: 0;">
										<tbody>
											<tr>
												<td><strong>Jumlah</strong></td>
												<td style="width: 227px;"></td>
												<td style="width: 115px;"></td>
												<td style="width: 115px;"></td>
												<td style="width: 115px;"></td>
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