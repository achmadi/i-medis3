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
		
		$("#button_tambah").click(function() {
			var $tr = $('#row_remunerasi_detail_clone').clone();
			
			var counter = parseInt($("#counter").val());
			counter++;
			$("#counter").val(counter);
			
			$tr.attr('id', '')
			   .attr('style', '');
	   
			$tr.find('#clone_remunerasi_detail_id')
				.attr('id', 'remunerasi_detail_id_' + counter)
				.attr('name', 'remunerasi_detail_id[]')
				.attr('value', 'new_remunerasi_detail_id_' + counter);
			
			$tr.find('#clone_penerima_jp_id')
				.attr('id', 'penerima_jp_id_' + counter)
				.attr('name', 'penerima_jp_id[]')
				.attr('value', '');
			
			$tr.find('#clone_label_uraian')
				.attr('id', 'label_uraian_' + counter);
			
			$tr.find('#clone_disp_uraian')
				.attr('id', 'disp_uraian_' + counter);
		
			$.ajax({
				url: "<?php echo site_url('master/remunerasi_jp/get_penerima_jp'); ?>",
				data: "penerima_jp_id=0", 
				success: function(html){
					$("#disp_uraian_" + counter).html(html);
				}
			});
			
			$tr.find('#clone_proporsi')
				.attr('id', 'proporsi_' + counter)
				.attr('name', 'proporsi[]')
				.attr('value', '0');
			
			$tr.find('#clone_label_proporsi')
				.attr('id', 'label_proporsi_' + counter);
			
			$tr.find('#clone_disp_proporsi')
				.attr('id', 'disp_proporsi_' + counter)
				.autoNumeric('init');
		
			$tr.find('#clone_langsung')
				.attr('id', 'langsung_' + counter)
				.attr('name', 'langsung[]')
				.attr('value', '0');
				
			$tr.find('#clone_label_langsung')
				.attr('id', 'label_langsung_' + counter);
			
			$tr.find('#clone_disp_langsung')
				.attr('id', 'disp_langsung_' + counter)
				.autoNumeric('init');
			
			$tr.find('#clone_balancing')
				.attr('id', 'balancing_' + counter)
				.attr('name', 'balancing[]')
				.attr('value', '0');
			
			$tr.find('#clone_label_balancing')
				.attr('id', 'label_balancing_' + counter);
			
			$tr.find('#clone_disp_balancing')
				.attr('id', 'disp_balancing_' + counter)
				.autoNumeric('init');
		
			$tr.find('#clone_kebersamaan')
				.attr('id', 'kebersamaan_' + counter)
				.attr('name', 'kebersamaan[]')
				.attr('value', '0');
			
			$tr.find('#clone_label_kebersamaan')
				.attr('id', 'label_kebersamaan_' + counter);
			
			$tr.find('#clone_disp_kebersamaan')
				.attr('id', 'disp_kebersamaan_' + counter)
				.autoNumeric('init');
			
			$("#table_remunerasi_detail").find("tbody").append($tr);
			
			$("#button_1").attr('id', '').addClass('button_tambah_simpan').text('Simpan').button();
			$("#button_2").attr('id', '').addClass('button_tambah_batal').text('Batal').button();
			
			$(".button_edit").prop('disabled', true);
			$(".button_hapus").prop('disabled', true);
			
			$("#button_tambah").prop('disabled', true);
			
			return false;
		});
		
		$('#table_remunerasi_detail').on('click', '.button_tambah_simpan', function() {
			$tr = $(this).parent().parent();
			
			var counter = getCounter($tr);
			
			var penerima_jp_id = $tr.find('#disp_uraian_' + counter).val();
			$tr.find("#penerima_jp_id_" + counter).val(penerima_jp_id);
			var penerima_jp = $("#disp_uraian_" + counter + " option[value='" + penerima_jp_id + "']").text();
			$tr.find('#label_uraian_' + counter).text(penerima_jp).show();
			$tr.find('#disp_uraian_' + counter).remove();
			
			var proporsi = $("#disp_proporsi_" + counter).val();
			$tr.find('#disp_proporsi_' + counter).remove();
			$tr.find('#proporsi_' + counter).val(proporsi);
			$tr.find('#label_proporsi_' + counter).text(proporsi).show();
			
			var langsung = $("#disp_langsung_" + counter).val();
			$tr.find('#disp_langsung_' + counter).remove();
			$tr.find('#langsung_' + counter).val(langsung);
			$tr.find('#label_langsung_' + counter).text(langsung).show();
			
			var balancing = $("#disp_balancing_" + counter).val();
			$tr.find('#disp_balancing_' + counter).remove();
			$tr.find('#balancing_' + counter).val(balancing);
			$tr.find('#label_balancing_' + counter).text(balancing).show();
			
			var kebersamaan = $("#disp_kebersamaan_" + counter).val();
			$tr.find('#disp_kebersamaan_' + counter).remove();
			$tr.find('#kebersamaan_' + counter).val(kebersamaan);
			$tr.find('#label_kebersamaan_' + counter).text(kebersamaan).show();
			
			$(".button_tambah_simpan").removeClass('button_tambah_simpan').addClass('button_edit');
			$(".button_edit").text('Edit');
			$(".button_tambah_batal").removeClass('button_tambah_batal').addClass('button_hapus');
			$(".button_hapus").text('Hapus');
			
			$(".button_edit").prop('disabled', false);
			$(".button_hapus").prop('disabled', false);
			
			$("#button_tambah").prop('disabled', false);
			return false;
		});
		
		$('#table_remunerasi_detail').on('click', '.button_tambah_batal', function(event) {
			event.preventDefault;
			$(this).parent().parent().remove();
			
			$(".button_edit").prop('disabled', false);
			$(".button_hapus").prop('disabled', false);
			
			$("#button_tambah").prop('disabled', false);
			
			var counter = parseInt($("#counter").val());
			counter--;
			$("#counter").val(counter);
			
			return false;
		});
		
		$('#table_remunerasi_detail').on('click', '.button_edit', function() {
			$tr = $(this).parent().parent();
							
			var counter = getCounter($tr);
			
			var uraian = $tr.find('#uraian_' + counter).val();
			$tr.find('#label_uraian_' + counter).hide();
			$tr.children('td').eq(0).append('<input type="text" id="disp_uraian_' + counter + '" value="' + uraian + '" style="width: 98%;" />');
			
			var proporsi = $tr.find('#proporsi_' + counter).val();
			$tr.find('#label_proporsi_' + counter).hide();
			$tr.children('td').eq(1).append('<input type="text" id="disp_proporsi_' + counter + '" data-v-max="999.99" data-a-sep="." data-a-dec="," maxlength="6" value="' + proporsi + '" style="width: 115px; text-align: right;" />');
			
			$tr.find(".button_edit").removeClass('button_edit').addClass('button_edit_simpan');
			$tr.find(".button_edit_simpan").text('Simpan');
			$tr.find(".button_hapus").removeClass('button_hapus').addClass('button_edit_batal');
			$tr.find(".button_edit_batal").text('Batal');
			
			$(".button_edit").prop('disabled', true);
			$(".button_hapus").prop('disabled', true);
			
			$("#button_tambah").prop('disabled', true);

			return false;
		});
		
		$('#table_remunerasi_detail').on('click', '.button_hapus', function(event) {
			event.preventDefault;
			$(this).parent().parent().remove();
			return false;
		});
		
		$('#table_remunerasi_detail').on('click', '.button_edit_simpan', function(event) {
			$tr = $(this).parent().parent();
					
			var counter = getCounter($tr);
			
			var uraian = $tr.find('#disp_uraian_' + counter).val();
			$tr.find('#disp_uraian_' + counter).remove();
			$tr.find("#uraian_" + counter).val(uraian);
			$tr.find('#label_uraian_' + counter).text(uraian).show();
			
			var proporsi = $tr.find('#disp_proporsi_' + counter).val();
			$tr.find('#disp_proporsi_' + counter).remove();
			$tr.find("#proporsi_" + counter).val(proporsi);
			$tr.find('#label_proporsi_' + counter).text(proporsi).show();
			
			$(".button_edit_simpan").removeClass('button_edit_simpan').addClass('button_edit');
			$(".button_edit").text('Edit');
			$(".button_edit_batal").removeClass('button_edit_batal').addClass('button_hapus');
			$(".button_hapus").text('Hapus');
			
			$(".button_edit").prop('disabled', false);
			$(".button_hapus").prop('disabled', false);
			
			$("#button_tambah").prop('disabled', false);
			return false;
		});
		
		$('#table_remunerasi_detail').on('click', '.button_edit_batal', function(event) {
		
			$tr = $(this).parent().parent();
					
			var counter = getCounter($tr);
			
			$tr.find('#disp_uraian_' + counter).remove();
			$tr.find('#label_uraian_' + counter).show();
			
			$tr.find('#disp_proporsi_' + counter).remove();
			$tr.find('#label_proporsi_' + counter).show();
			
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
</style>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<?php
					$url = site_url('master/remunerasi_jp/edit/'.$data->jenis_pelayanan_id);
					$title = "Edit Remunerasi Jasa Pelayanan";
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
									
								</div>
								
								<div class="span12" style="margin-left: 0;">
									
									<table class="table table-condensed table-striped table-hover table-bordered pull-left" id="table_remunerasi_detail" style="margin-bottom: 0;">
										<thead>
											<tr>
												<th style="text-align: center;">Uraian</th>
												<th style="text-align: center; width: 115px;">Proporsi (%)</th>
												<th style="text-align: center; width: 115px;">Langsung (%)</th>
												<th style="text-align: center; width: 115px;">Balancing (%)</th>
												<th style="text-align: center; width: 115px;">Kebersamaan (%)</th>
												<th style="text-align: center; width: 112px;">Action</th>
											</tr>
										</thead>
										<tbody>
											<?php
												$index = 0;
												foreach ($data->details as $row) {
											?>
											<tr>
												<td>
													<input type="hidden" id="remunerasi_detail_id_<?php echo $index; ?>" name="remunerasi_detail_id[]" value="<?php echo $row->id; ?>"/>
													<input type="hidden" id="uraian_<?php echo $index; ?>" name="uraian[]" value="<?php echo $row->uraian; ?>" />
													<label id="label_uraian_<?php echo $index; ?>" style="display: none;"></label>
													<input type="text" id="disp_uraian_<?php echo $index; ?>" style="width: 98%;" />
												</td>
												<td>
													<input type="hidden" id="proporsi_<?php echo $index; ?>" name="proporsi[]" value="<?php echo $row->proporsi; ?>" />
													<label id="label_proporsi_<?php echo $index; ?>" style="display: none; text-align: right;"></label>
													<input type="text" id="disp_proporsi_<?php echo $index; ?>" data-v-min="0.00" data-v-max="100.00" data-a-sep="." data-a-dec="," maxlength="6" value="0" style="width: 115px; text-align: right;" />
												</td>
												<td>
													<input type="hidden" id="langsung_<?php echo $index; ?>" name="langsung[]" value="<?php echo $row->langsung; ?>" />
													<label id="label_langsung_<?php echo $index; ?>" style="display: none; text-align: right;"></label>
													<input type="text" id="disp_langsung_<?php echo $index; ?>" data-v-min="0.00" data-v-max="100.00" data-a-sep="." data-a-dec="," maxlength="6" value="0" style="width: 115px; text-align: right;" />
												</td>
												<td>
													<input type="hidden" id="balancing_<?php echo $index; ?>" name="balancing[]" value="<?php echo $row->balancing; ?>" />
													<label id="label_balancing_<?php echo $index; ?>" style="display: none; text-align: right;"></label>
													<input type="text" id="disp_balancing_<?php echo $index; ?>" data-v-min="0.00" data-v-max="100.00" data-a-sep="." data-a-dec="," maxlength="6" value="0" style="width: 115px; text-align: right;" />
												</td>
												<td>
													<input type="hidden" id="kebersamaan_<?php echo $index; ?>" name="kebersamaan[]" value="<?php echo $row->kebersamaan; ?>" />
													<label id="label_kebersamaan_<?php echo $index; ?>" style="display: none; text-align: right;"></label>
													<input type="text" id="disp_kebersamaan_<?php echo $index; ?>" data-v-min="0.00" data-v-max="100.00" data-a-sep="." data-a-dec="," maxlength="6" value="0" style="width: 115px; text-align: right;" />
												</td>
												<td style="text-align: center; vertical-align: middle;">
													<button type="button" id="button_1" class="btn btn-primary btn-mini bottom-margin">Simpan</button>
													<button type="button" id="button_2" class="btn btn-primary btn-mini bottom-margin">Batal</button>
												</td>
											</tr>
											<?php
												}
											?>
										</tbody>
									</table>
									<table class="table table-condensed table-striped table-bordered table-hover no-margin" style="border-top: 0;">
										<tbody>
											<tr>
												<td><strong>Jumlah</strong></td>
												<td style="width: 115px;"></td>
												<td style="width: 115px;"></td>
												<td style="width: 115px;"></td>
												<td style="width: 115px;"></td>
												<td style="width: 112px;"></td>
											</tr>
										</tbody>
									</table>
									<table class="table table-condensed table-striped table-bordered table-hover no-margin" style="border-top: 0;">
										<tbody>
											<tr>
												<td colspan="6" class="hidden-phone">
													<button type="button" id="button_tambah" class="btn btn-primary btn-mini bottom-margin">Tambah</button>
												</td>
											</tr>
										</tbody>
									</table>
									<table style="display: none;">
										<tbody>
											<tr id="row_remunerasi_detail_clone">
												<td>
													<input type="hidden" id="clone_remunerasi_detail_id" />
													<input type="hidden" id="clone_penerima_jp_id" />
													<label id="clone_label_uraian" style="display: none;"></label>
													<select id="clone_disp_uraian" size="1" style="width: 100%; display: block;"></select>
												</td>
												<td>
													<input type="hidden" id="clone_proporsi" />
													<label id="clone_label_proporsi" style="display: none; text-align: right;"></label>
													<input type="text" id="clone_disp_proporsi" data-v-max="999.99" data-a-sep="." data-a-dec="," maxlength="6" value="0" style="width: 90%; display: block; text-align: right;" />
												</td>
												<td>
													<input type="hidden" id="clone_langsung" />
													<label id="clone_label_langsung" style="display: none; text-align: right;"></label>
													<input type="text" id="clone_disp_langsung" data-v-max="999.99" data-a-sep="." data-a-dec="," maxlength="6" value="0" style="width: 90%; display: block; text-align: right;" />
												</td>
												<td>
													<input type="hidden" id="clone_balancing" />
													<label id="clone_label_balancing" style="display: none; text-align: right;"></label>
													<input type="text" id="clone_disp_balancing" data-v-max="999.99" data-a-sep="." data-a-dec="," maxlength="6" value="0" style="width: 90%; display: block; text-align: right;" />
												</td>
												<td>
													<input type="hidden" id="clone_kebersamaan" />
													<label id="clone_label_kebersamaan" style="display: none; text-align: right;"></label>
													<input type="text" id="clone_disp_kebersamaan" data-v-max="999.99" data-a-sep="." data-a-dec="," maxlength="6" value="0" style="width: 90%; display: block; text-align: right;" />
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
						$counter = count($data->details);
					?>
					<input type="hidden" id="counter" value="<?php echo $counter; ?>" />
				</form>
			</div>
		</div>
	</div>
</div>