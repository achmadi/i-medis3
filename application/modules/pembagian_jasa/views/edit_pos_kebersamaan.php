<script type="text/javascript">
	
    $(document).ready(function() {
		
		var oTable = $('#insentif_tak_langsung').dataTable({
			"sPaginationType": "full_numbers",
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?php echo site_url('pembagian_jasa/pos_kebersamaan/load_data?bulan='.$current_bulan.'&tahun='.$current_tahun); ?>",
			"aoColumns"			: [
									  { sWidth: '10%' },
									  { sWidth: '10%' },
									  { sWidth: '10%' },
									  { sWidth: '10%' },
									  { sWidth: '10%' },
									  { sWidth: '10%' },
									  { sWidth: '10%' },
									  { sWidth: '10%' },
									  { sWidth: '10%' }
								  ],
			"aoColumnDefs"		: [
									{ "bSearchable": true, "bVisible": false, "aTargets": [ 0 ] }
								  ]
		});
		
		$("#bulan").change(function() {
			oTable.fnFilter("1/" + $("#bulan").val() + "/" + $("#tahun").val(), 0 );
		});
		
		$("#tahun").change(function() {
			oTable.fnFilter("1/" + $("#bulan").val() + "/" + $("#tahun").val(), 0 );
		});
		
		$("#hitung_kebersamaan").click(function() {
			$("#bulan").attr('disabled', true);
			$("#tahun").attr('disabled', true);
			$("#hitung_kebersamaan").attr('disabled', true);
			var bulan = $("#bulan").val();
			var tahun = $("#tahun").val();
			$.ajax({
				type: "GET",
				url: "<?php echo site_url('pembagian_jasa/pos_kebersamaan/hitung_kebersamaan') ?>",
				data: "bulan=" + bulan + "&tahun=" + tahun, 
				cache: false,
				beforeSend: function() {
					$('#loading_process').show();
					$('#loading_process').css("display", "inline");
				},
				success: function() {
					$('#loading_process').hide();
				},
				complete: function($response, $status) {
					if ($status === "success" || $status === "notmodified") {
						$("#bulan").attr('disabled', false);
						$("#tahun").attr('disabled', false);
						$("#hitung_kebersamaan").attr('disabled', false);
						oTable.fnDraw();
					}
				}
			});
		});
		
    });
</script>
<style type="text/css">
	.dashboard-wrapper .left-sidebar {
		margin-right: 0;
	}
	.fg-toolbar {
		height: 35px;
	}
	table {
		max-width: none;
	}
	.table {
		margin-bottom: 0;
	}
	.table th {
		text-align: center;
	}
	.dataTables_scrollBody {
		margin-bottom: 5px;
	}
	.table thead th, .table thead td {
		text-align: center;
		vertical-align: middle;
	}
	.dashboard-wrapper .left-sidebar .widget .widget-body #dt_example .dataTables_scroll {
		clear: both;
	}
</style>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<div class="widget-header">
					<div class="title">
						Pos Kebersamaan
					</div>
					<span class="tools">
						<a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a>
					</span>
				</div>
				<div class="widget-body">
					
					<div class="navbar">
						<div class="navbar-inner">
							<form class="navbar-form">
								<a class="brand" href="#" data-original-title=""> Bulan </a>
								<div class="input-append">
									<?php
										$abulans = array(
											1 => "Januari",
											2 => "Februari",
											3 => "Maret",
											4 => "April",
											5 => "Mei",
											6 => "Juni",
											7 => "Juli",
											8 => "Agustus",
											9 => "September",
											10 => "Oktober",
											11 => "November",
											12 => "Desember"
										);
									?>
									<select id="bulan" name="bulan" class="span2" style="margin-top: 0; margin-right: 2px;">
										<?php
											foreach ($abulans as $key => $value) {
												if ($current_bulan == $key) {
													echo "<option value=\"{$key}\" selected=\"selected\">{$value}</option>";
												} else {
													echo "<option value=\"{$key}\">{$value}</option>";
												}
											}
										?>
									</select>
									<select id="tahun" name="tahun" size="1" class="span2" style="margin-top: 0; margin-right: 4px;">
										<?php
											if ($tahun_list) {
												foreach ($tahun_list as $thn) {
													if ($current_tahun == $key) {
														echo "<option value=\"{$thn->tahun}\" selected=\"selected\">{$thn->tahun}</option>";
													} else {
														echo "<option value=\"{$thn->tahun}\">{$thn->tahun}</option>";
													}
												}
											}
											else {
												echo "<option value=\"{$current_tahun}\" selected=\"selected\">{$current_tahun}</option>";
											}
										?>
									</select>
									<button id="hitung_kebersamaan" class="btn btn-info" type="button">Hitung Kebersamaan</button>
									<img id="loading_process" alt="loading" src="<?php echo base_url('assets/img/loading.gif'); ?>" style="position: absolute; margin: 5px; display: none;" />
								</div>
								<div class="progress progress-info" style="display: none;">
									<div class="bar" style="width: 100%"> </div>
								</div>
							</form>
						</div>
					</div>
					
					<div id="dt_example" class="example_alt_pagination">
						<table class="table table-condensed table-striped table-hover table-bordered pull-left" id="insentif_tak_langsung">
							<thead>
								<tr>
									<th>Tanggal</th>
									<th>No. Rekening</th>
									<th>Nama</th>
									<th>Ruang</th>
									<th>NIP</th>
									<th>NPWP</th>
									<th>Gol</th>
									<th>Indeks</th>
									<th>Insentif</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="9" class="dataTables_empty">Loading data from server</td>
								</tr>
							</tbody>
						</table>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>