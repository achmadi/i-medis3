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
						$url = site_url('master/jenis_pelayanan/add');
						$title = "Tambah Jenis Pelayanan";
					}
					else {
						$url = site_url('master/jenis_pelayanan/edit/'.$data->id);
						$title = "Edit Jenis Pelayanan";
					}
				?>
				<form class="form-horizontal no-margin" id="jenis_form" name="jenis_form" method="post" action="<?php echo $url; ?>">
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
											<input class="span12" type="text" id="nama" name="nama" maxlength="30" placeholder="Nama Pelayanan" value="<?php echo $value; ?>">
											<?php echo form_error('nama'); ?>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="jenis">Jenis</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('jenis', $data->jenis);
												$first = array(0 => '- Pilih Jenis -');
												$jenis_list = array_merge($first, $jenis_list);
											?>
											<select id="jenis" name="jenis">
												<?php
													foreach ($jenis_list as $key => $val) {
														if ($value == $key) {
															echo "<option value=\"{$key}\" selected=\"selected\">{$val}</option>";
														} else {
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
