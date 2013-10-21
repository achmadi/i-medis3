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
						$url = site_url('master/wilayah/add');
						$title = "Tambah Wilayah";
					}
					else {
						$url = site_url('master/wilayah/edit/'.$data->id);
						$title = "Edit Wilayah";
					}
				?>
				<?php echo validation_errors(); ?>
				<form class="form-horizontal no-margin" id="wilayah_form" name="wilayah_form" method="post" action="<?php echo site_url('master/wilayah/add'); ?>">
					<div class="widget-header">
						<div class="title"><?php echo $title; ?></div>
						<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
					</div>
					<div class="widget-body">

						<div class="container-fluid">

							<div class="row-fluid">
							
								<div class="span6">
									
									<div class="control-group">
										<label class="control-label" for="nama">Nama Wilayah</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('nama', $data->nama);
											?>
											<input class="span12" type="text" id="nama" name="nama" maxlength="60" placeholder="Nama Wilayah" value="<?php echo $value; ?>">
											<?php echo form_error('nama'); ?>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="jenis">Jenis Wilayah</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('jenis', $data->jenis);
												$jenis_list[0] = "- Pilih Jenis Wilayah -";
											?>
											<select id="jenis" name="jenis">
											<?php
												foreach ($jenis_list as $key => $val) {
													if ($value == $key) {
														echo "<option value=\"{$key}\" selected=\"selected\">{$val}</option>";
													}
													else {
														echo "<option value=\"{$key}\">{$val}</option>";
													}
												}
											?>
											</select>
											<?php echo form_error('jenis'); ?>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="parent_id">Parent</label>
										<div class="controls">
											<?php
												$value = set_value('parent_id', $data->parent_id);
													$node = new stdClass();
													$node->id = 0;
													$node->nama = "Root";
													$first = array($node);
													$parent_list = array_merge($first, $parent_list);
											?>
											<select class="m-wrap medium" id="parent_id" name="parent_id" size="1" tabindex="1">
												<?php
													foreach ($parent_list as $val) {
														$j = $val->level;
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
				</form>
			</div>
		</div>
	</div>
</div>
