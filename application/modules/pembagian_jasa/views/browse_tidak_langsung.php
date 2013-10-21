<script type="text/javascript">
	$(document).ready(function () {
		
		var oTable = $('#tidak_langsung').dataTable({
			"sPaginationType"	: "full_numbers",
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?php echo site_url('pembagian_jasa/pembagian_jasa/load_data_tidak_langsung?bulan='.$current_bulan.'&tahun='.$current_tahun); ?>",
			"aoColumns"			: [
									  { sWidth: '30%' },
									  { sWidth: '30%' },
									  { sWidth: '20%' },
									  { sWidth: '20%' }
								  ]
		});
		
		$("#bulan").change(function() {
			oTable.fnFilter("1/" + $("#bulan").val() + "/" + $("#tahun").val(), 0 );
		});
		
		$("#tahun").change(function() {
			oTable.fnFilter("1/" + $("#bulan").val() + "/" + $("#tahun").val(), 0 );
		});
		
		function getISODate(d){
			var s = function(a,b){return(1e15+a+"").slice(-b);};

			if (typeof d === 'undefined'){
				d = new Date();
			};

			return d.getFullYear() + '-' +
				s(d.getMonth()+1,2) + '-' +
				s(d.getDate(),2);
		}
		
	});
</script>
<style type="text/css">
	.dashboard-wrapper .left-sidebar {
		margin-right: 0;
	}
	
	.table thead th, .table thead td {
		text-align: center;
		vertical-align: middle;
	}
	
	#tidak_langsung td:nth-child(4) {
		text-align: right;
	}
</style>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<div class="widget-header">
					<div class="title">Insentif Langsung</div>
					<div class="tools pull-right">
						<div class="btn-group">
							<a class="btn btn-success btn-small" href="<?php echo site_url('pembagian_jasa/pembagian_jasa/export_to_excel_rpt_004?bulan='.$current_bulan.'&tahun='.$current_tahun); ?>" data-original-title="">
								<i class="icon-file-excel" data-original-title="Share"></i>&nbsp;&nbsp;Export to Excel
							</a>
						</div>
					</div>
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
								</div>
							</form>
						</div>
					</div>
					
					<div id="dt_example" class="example_alt_pagination">
						<table class="table table-striped table-condensed table-striped table-hover table-bordered pull-left" id="tidak_langsung">
							<thead>
								<tr>
									<th>Tanggal</th>
									<th>Nama</th>
									<th>Ruang</th>
									<th>Jumlah</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="4" class="dataTables_empty">Loading data from server</td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="3"><strong>TOTAL</strong></td>
									<td style="text-align: right;"><strong><?php echo number_format ($total_tidak_langsung, 2, ',', '.'); ?></strong></td>
								</tr>
							</tfoot>
						</table>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>