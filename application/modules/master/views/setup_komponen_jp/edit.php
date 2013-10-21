<script type="text/javascript">
	
	$(document).ready(function() {
        

		
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
						$url = site_url('master/setup_komponen_jp/add?unit_id='.$unit_id);
						$title = "Tambah Setup Komponen Jasa Pelayanan";
					}
					else {
						$url = site_url('master/setup_komponen_jp/edit/'.$data->id.'?unit_id='.$unit_id);
						$title = "Edit Setup Komponen Jasa Pelayanan";
					}
				?>
				<form class="form-horizontal no-margin" id="setup_komponen_jp_form" name="setup_komponen_jp_form" method="post" action="<?php echo $url; ?>">
					<div class="widget-header">
						<div class="title"><?php echo $title; ?></div>
						<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
					</div>
					<div class="widget-body">

						<div class="container-fluid">

							<div class="row-fluid">
							
								<div class="span7">
									
									<div class="control-group">
										<label class="control-label" for="kelompok_id">Nama Kelompok</label>
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
										<label class="control-label" for="parent_id">Parent</label>
										<div class="controls">
											<?php
												$value = set_value('parent_id', $data->parent_id);
											?>
											<select class="m-wrap medium" id="parent_id" name="parent_id" size="1" tabindex="1">
												<?php
													foreach ($parent_list as $val) {
														if ($val->jenis == 'Root')
															$val->nama = 'Root';
														$j = $val->level - 1;
														$indent = '';
														for ($i = 0; $i < $j; $i++) {
															$indent .= '- ';
														}
														$nama = $indent.$val->nama;
														if ($value == $val->id)
															echo "<option value=\"{$val->id}\" selected=\"selected\">{$nama}</option>";
														else
															echo "<option value=\"{$val->id}\">{$nama}</option>";
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
					<input type="hidden" id="version" name="version" value="<?php echo $data->version; ?>" />
					<input type="hidden" id="unit_id" name="unit_id" value="<?php echo $unit_id; ?>" />
				</form>
			</div>
		</div>
	</div>
</div>
