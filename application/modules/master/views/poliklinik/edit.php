<script type="text/javascript">
    $(document).ready(function() {
		
		$("#button_tambah").click(function() {
			var $tr = $('#row_dokter_clone').clone();
			
			var counter = parseInt($("#counter").val());
			counter++;
			$("#counter").val(counter);
			
			$tr.attr('id', '')
			   .attr('style', '');
			
			$tr.find('#clone_unit_dokter_id')
				.attr('id', 'unit_dokter_id_' + counter)
				.attr('name', 'unit_dokter_id[]')
				.attr('value', 'new_unit_dokter_id_' + counter);
			
			$tr.find('#clone_dokter_id')
				.attr('id', 'dokter_id_' + counter)
				.attr('name', 'dokter_id[]')
				.attr('value', 'new_dokter_id_' + counter);
			
			$tr.find('#clone_label_dokter')
				.attr('id', 'label_dokter_' + counter);
			
			$tr.find('#clone_disp_dokter')
				.attr('id', 'disp_dokter_' + counter);
			
			$.ajax({
				url: "<?php echo site_url('master/poliklinik/get_dokter'); ?>",
				data: "dokter_id=0", 
				success: function(html){
					$("#disp_dokter_" + counter).html(html);
				}
			});
			
			$("#table_dokter").find("tbody").append($tr);
			
			$("#button_1").attr('id', '').addClass('button_tambah_simpan').text('Simpan').button();
			$("#button_2").attr('id', '').addClass('button_tambah_batal').text('Batal').button();
			
			$(".button_edit").prop('disabled', true);
			$(".button_hapus").prop('disabled', true);
			
			$("#button_tambah").prop('disabled', true);
			
			return false;
		});
		
		$('#table_dokter').on('click', '.button_tambah_simpan', function() {
			$tr = $(this).parent().parent();
					
			var counter = getCounter($tr);
			
			var dokter_id = $tr.find('#disp_dokter_' + counter).val();
			$tr.find("#dokter_id_" + counter).val(dokter_id);
			var dokter = $("#disp_dokter_" + counter + " option[value='" + dokter_id + "']").text();
			$tr.find('#label_dokter_' + counter).text(dokter).show();
			$tr.find('#disp_dokter_' + counter).remove();
			
			$(".button_tambah_simpan").removeClass('button_tambah_simpan').addClass('button_edit');
			$(".button_edit").text('Edit');
			$(".button_tambah_batal").removeClass('button_tambah_batal').addClass('button_hapus');
			$(".button_hapus").text('Hapus');
			
			$(".button_edit").prop('disabled', false);
			$(".button_hapus").prop('disabled', false);
			
			$("#button_tambah").prop('disabled', false);
			return false;
		});
		
		$('#table_dokter').on('click', '.button_tambah_batal', function(event) {
			event.preventDefault;
			$(this).parent().parent().remove();
			
			$(".button_edit").prop('disabled', false);
			$(".button_hapus").prop('disabled', false);
			
			$("#button_tambah").prop('disabled', false);
			return false;
		});
		
		$('#table_dokter').on('click', '.button_edit', function() {
			$tr = $(this).parent().parent();
							
			var counter = getCounter($tr);
			
			var dokter_id = $tr.find('#dokter_id_' + counter).val();
			$tr.find('#label_dokter_' + counter).hide();
			$tr.children('td').eq(0).append('<select id="disp_dokter_' + counter + '" style="width: 100%;"></select>');
			
			$.ajax({
				url: "<?php echo site_url('master/poliklinik/get_dokter'); ?>",
				data: "dokter_id=" + dokter_id, 
				success: function(html){
					$("#disp_dokter_" + counter).html(html);
				}
			});
			
			$tr.find(".button_edit").removeClass('button_edit').addClass('button_edit_simpan');
			$tr.find(".button_edit_simpan").text('Simpan');
			$tr.find(".button_hapus").removeClass('button_hapus').addClass('button_edit_batal');
			$tr.find(".button_edit_batal").text('Batal');
			
			$(".button_edit").prop('disabled', true);
			$(".button_hapus").prop('disabled', true);
			
			$("#button_tambah").prop('disabled', true);

			return false;
		});
		
		$('#table_dokter').on('click', '.button_hapus', function(event) {
			event.preventDefault;
			$(this).parent().parent().remove();
			return false;
		});
		
		$('#table_dokter').on('click', '.button_edit_simpan', function(event) {
			$tr = $(this).parent().parent();
					
			var counter = getCounter($tr);
			
			var dokter_id = $tr.find('#disp_dokter_' + counter).val();
			$tr.find("#dokter_id_" + counter).val(dokter_id);
			var dokter = $("#disp_dokter_" + counter + " option[value='" + dokter_id + "']").text();
			$tr.find('#disp_dokter_' + counter).remove();
			$tr.find('#label_dokter_' + counter).text(dokter).show();
			
			$(".button_edit_simpan").removeClass('button_edit_simpan').addClass('button_edit');
			$(".button_edit").text('Edit');
			$(".button_edit_batal").removeClass('button_edit_batal').addClass('button_hapus');
			$(".button_hapus").text('Hapus');
			
			$(".button_edit").prop('disabled', false);
			$(".button_hapus").prop('disabled', false);
			
			$("#button_tambah").prop('disabled', false);
			return false;
		});
		
		$('#table_dokter').on('click', '.button_edit_batal', function(event) {
		
			$tr = $(this).parent().parent();
					
			var counter = getCounter($tr);
			
			$tr.find('#disp_dokter_' + counter).remove();
			$tr.find('#label_dokter_' + counter).show();
			
			$(".button_edit_simpan").removeClass('button_edit_simpan').addClass('button_edit');
			$(".button_edit").text('Edit');
			$(".button_edit_batal").removeClass('button_edit_batal').addClass('button_hapus');
			$(".button_hapus").text('Hapus');
			
			$(".button_edit").prop('disabled', false);
			$(".button_hapus").prop('disabled', false);
			
			$("#button_tambah").prop('disabled', false);
			return false;
		});
		
		function getCounter(tr) {
			var id = tr.find('input').attr('id');
			var aId = id.split('_');
			return parseInt(aId[aId.length - 1]);
		}

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
	.table {
		margin-bottom: 10px;
		width: 500px;
	}
</style>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<?php
					if ($is_new) {
						$url = site_url('master/poliklinik/add');
						$title = "Tambah Poliklinik";
					}
					else {
						$url = site_url('master/poliklinik/edit/'.$data->id);
						$title = "Edit Poliklinik";
					}
				?>
				<form class="form-horizontal no-margin" id="poliklinik_form" name="poliklinik_form" method="post" action="<?php echo site_url('master/poliklinik/add'); ?>">
					<div class="widget-header">
						<div class="title"><?php echo $title; ?></div>
						<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
					</div>
					<div class="widget-body">

						<div class="container-fluid">

							<div class="row-fluid">
							
								<div class="span6">
									
									<div class="control-group">
										<label class="control-label" for="no_medrec">Nama Poliklinik</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('nama', $data->nama);
											?>
											<input class="span12" type="text" id="nama" name="nama" maxlength="30" placeholder="Nama Poliklinik" value="<?php echo $value; ?>">
										</div>
									</div>
									
								</div>
								
								<div class="span12" style="margin-left: 0;">
									<hr />
									<table id="table_dokter" class="table table-condensed table-striped table-bordered table-hover no-margin">
										<thead>
											<tr>
												<th style="width: auto;">Nama</th>
												<th style="width: 120px;" class="hidden-phone">Actions</th>
											</tr>
										</thead>
										<tbody>
											<?php
												$i = 1;
												foreach ($data->dokters as $dokter) {
											?>
											<tr>
												<td>
													<input type="hidden" id="unit_dokter_id_<?php echo $i; ?>" name="unit_dokter_id[]" value="<?php echo $dokter->id; ?>" />
													<input type="hidden" id="dokter_id_<?php echo $i; ?>" name="dokter_id[]" value="<?php echo $dokter->pegawai_id; ?>" />
													<label id="label_dokter_<?php echo $i; ?>"><?php echo $dokter->pegawai; ?></label>
												</td>
												<td style="text-align: center; vertical-align: middle;">
													<button type="button" class="btn btn-primary btn-mini bottom-margin button_edit">Edit</button>
													<button type="button" class="btn btn-primary btn-mini bottom-margin button_hapus">Hapus</button>
												</td>
											</tr>
											<?php
													$i++;
												}
											?>
										</tbody>
									</table>
									<table class="table table-condensed table-striped table-bordered table-hover no-margin" style="border-top: 0;">
										<tbody>
											<tr>
												<td colspan="2" class="hidden-phone">
													<button type="button" id="button_tambah" class="btn btn-primary btn-mini bottom-margin">Tambah Dokter</button>
												</td>
											</tr>
										</tbody>
									</table>
									<table style="display: none;">
										<tbody>
											<tr id="row_dokter_clone">
												<td>
													<input type="hidden" id="clone_unit_dokter_id" />
													<input type="hidden" id="clone_dokter_id" />
													<label id="clone_label_dokter" style="display: none;"></label>
													<select id="clone_disp_dokter" style="width: 100%;"></select>
												</td>
												<td style="text-align: center; vertical-align: middle;">
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
								<button class="btn btn-info" type="submit" name="simpan" value="Simpan">Simpan</button>
								<button class="btn" type="submit" name="batal" value="Batal">Batal</button>
							</div>
						</div>
					</div>
					<?php
						$value = set_value('id', $data->id);
					?>
					<input type="hidden" id="id" name="id" value="<?php echo $value; ?>" />
					<?php
						$counter = count($data->dokters);
					?>
					<input type="hidden" id="counter" value="<?php echo $counter; ?>" />
				</form>
			</div>
		</div>
	</div>
</div>
