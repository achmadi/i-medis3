<script type="text/javascript">
	
	$(document).ready(function() {
        
		var jenis = '<?php echo set_value('jenis', $data->jenis); ?>';
		switch (jenis) {
			case 'Kelompok':
				$('#disp_bmhp').hide();
				$('#disp_sarana').hide();
				$('#disp_yan').hide();
				$('#disp_medik').hide();
				$('#disp_anest').hide();
				break;
			case 'Rincian':
				$('#disp_bmhp').show();
				$('#disp_sarana').show();
				$('#disp_yan').show();
				$('#disp_medik').show();
				$('#disp_anest').show();
				break;
		}
		$('#jenis1').bind('click', function() {
			$('#disp_bmhp').hide();
			$('#disp_sarana').hide();
			$('#disp_yan').hide();
			$('#disp_medik').hide();
			$('#disp_anest').hide();
		});
		$('#jenis2').bind('click', function() {
			$('#disp_bmhp').show();
			$('#disp_sarana').show();
			$('#disp_yan').show();
			$('#disp_medik').show();
			$('#disp_anest').show();
		});
		
		$('#bmhp').autoNumeric('init');
		$('#sarana').autoNumeric('init');
		$('#yan').autoNumeric('init');
		$('#medik').autoNumeric('init');
		$('#anest').autoNumeric('init');
		
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

<?php
	//message, error, notice
	$notification = $this->session->flashdata('notification');
	$notification_type = $notification['type'];
	$notification_title = ucfirst($notification['type']);;
	$notification_message = $notification['message'];
	if ($notification_message) {
?>
<div class="alert alert-block alert-<?php echo $notification_type; ?> fade in">
	<button data-dismiss="alert" class="close" type="button">×</button>
	<h4 class="alert-heading"><?php echo $notification_title; ?>!</h4>
	<p><?php echo $notification_message; ?></p>
</div>
<?php
	}
?>

<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<?php
					if ($is_new) {
						$url = site_url('master/komponen_tarif_askes/add?kelompok_pelayanan_askes_id='.$kelompok_pelayanan_askes_id);
						$title = "Tambah Komponen Tarif Askes";
					}
					else {
						$url = site_url('master/komponen_tarif_askes/edit/'.$data->id.'?kelompok_pelayanan_askes_id='.$kelompok_pelayanan_askes_id);
						$title = "Edit Komponen Tarif Askes";
					}
				?>
				<form class="form-horizontal no-margin" id="komponen_tarif_askes_form" name="komponen_tarif_askes_form" method="post" action="<?php echo $url; ?>">
					<div class="widget-header">
						<div class="title"><?php echo $title; ?></div>
						<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
					</div>
					<div class="widget-body">

						<div class="container-fluid">

							<div class="row-fluid">
							
								<div class="span7">
									
									<div class="control-group">
										<label class="control-label" for="nama">Nama Pelayanan</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('nama', $data->nama);
											?>
											<input class="span12" type="text" id="nama" name="nama" maxlength="60" placeholder="Nama Pelayanan" value="<?php echo $value; ?>">
											<?php echo form_error('nama'); ?>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="jenis1">Jenis</label>
										<div class="controls">
											<?php
												$value = set_value('jenis', $data->jenis);
											?>
											<label class="radio inline">
												<input type="radio" id="jenis1" name="jenis" value="Kelompok" <?php echo $value == 'Kelompok' ? "checked=\"checked\"" : ""; ?> />Kelompok
											</label>
											<label class="radio inline">
												<input type="radio" id="jenis2" name="jenis" value="Rincian" <?php echo $value == 'Rincian' ? "checked=\"checked\"" : ""; ?> />Rincian
											</label>  
										</div>
									</div>
									
									<div class="control-group">
										<div class="controls">
											<label class="checkbox">
												<?php
													$value = set_value('paket', $data->paket);
												?>
												<input type="checkbox" id="paket" name="paket" value="paket" >Paket
											</label>
										</div>
									</div>
									
									<div id="disp_bmhp" class="control-group">
										<label class="control-label" for="bmhp">BMHP</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('bmhp', $data->bmhp);
											?>
											<input class="span3" type="text" id="bmhp" name="bmhp" data-v-max="999999999999.99" data-v-min="0.00" data-a-dec="," data-a-sep="." maxlength="15" value="<?php echo $value; ?>" style="text-align: right;" />
											<span class="help-inline ">%</span>
											<?php echo form_error('bmhp'); ?>
										</div>
									</div>
									
									<div id="disp_sarana" class="control-group">
										<label class="control-label" for="sarana">Sarana</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('sarana', $data->sarana);
											?>
											<input class="span3" type="text" id="sarana" name="sarana" data-v-max="999999999999.99" data-v-min="0.00" data-a-dec="," data-a-sep="." maxlength="15" value="<?php echo $value; ?>" style="text-align: right;" />
											<span class="help-inline ">%</span>
											<?php echo form_error('sarana'); ?>
										</div>
									</div>
									
									<div id="disp_yan" class="control-group">
										<label class="control-label" for="yan">Yan</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('yan', $data->yan);
											?>
											<input class="span3" type="text" id="yan" name="yan" data-v-max="999999999999.99" data-v-min="0.00" data-a-dec="," data-a-sep="." maxlength="15" value="<?php echo $value; ?>" style="text-align: right;" />
											<span class="help-inline ">%</span>
											<?php echo form_error('yan'); ?>
										</div>
									</div>
									
									<div id="disp_medik" class="control-group">
										<label class="control-label" for="yan">Medik</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('medik', $data->medik);
											?>
											<input class="span3" type="text" id="medik" name="medik" data-v-max="999999999999.99" data-v-min="0.00" data-a-dec="," data-a-sep="." maxlength="15" value="<?php echo $value; ?>" style="text-align: right;" />
											<span class="help-inline ">%</span>
											<?php echo form_error('medik'); ?>
										</div>
									</div>
									
									<div id="disp_anest" class="control-group">
										<label class="control-label" for="yan">Anest</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('anest', $data->anest);
											?>
											<input class="span3" type="text" id="anest" name="anest" data-v-max="999999999999.99" data-v-min="0.00" data-a-dec="," data-a-sep="." maxlength="15" value="<?php echo $value; ?>" style="text-align: right;" />
											<span class="help-inline ">%</span>
											<?php echo form_error('anest'); ?>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="parent_id">Parent</label>
										<div class="controls">
											<?php
												$value = set_value('parent_id', $data->parent_id);
												if (!$parent_list) {
													$root = new stdClass();
													$root->id = 0;
													$root->nama = 'Root';
													$parent_list = array($root);
												}
											?>
											<select class="m-wrap medium" id="parent_id" name="parent_id" size="1" tabindex="1">
												<?php
													foreach ($parent_list as $val) {
														if ($value == $val->id)
															echo "<option value=\"{$val->id}\" selected=\"selected\">{$val->nama}</option>";
														else
															echo "<option value=\"{$val->id}\">{$val->nama}</option>";
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
					<input type="hidden" id="old_parent_id" name="old_parent_id" value="<?php echo $data->old_parent_id; ?>" />
				</form>
			</div>
		</div>
	</div>
</div>
