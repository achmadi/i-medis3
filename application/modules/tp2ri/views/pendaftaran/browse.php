<script type="text/javascript">
	$(document).ready(function () {
		
		var oTable = $('#pendaftaran').dataTable({
			"sPaginationType"	: "full_numbers",
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?php echo site_url('tp2ri/pendaftaran/load_data?browse='.$browse.'&tanggal='.$tanggal_current); ?>",
			"sScrollX"			: "100%",
			"sScrollXInner"		: "1970px",
			"bScrollCollapse"	: true,
			"fnInitComplete"	: function () {
				new FixedColumns(oTable, {
					"iLeftColumns": 1,
					"iLeftWidth": 150,
					"iRightColumns": 1,
					"iRightWidth": 150,
				});
			},
			"aoColumns"			: [
									  { sWidth: '138px' },
									  { sWidth: '79px' },
									  { sWidth: '77px' },
									  { sWidth: '250px' },
									  { sWidth: '88px' },
									  { sWidth: '250px' },
									  { sWidth: '88px' },
									  { sWidth: '80px' },
									  { sWidth: '150px' },
									  { sWidth: '150px' },
									  { sWidth: '150px' },
									  { sWidth: '150px' },
									  { sWidth: '150px' },
									  { sWidth: null } //122
								  ]
		});
		
		$("#prev-date").click(function() {
			var disabled = $(this).attr('disabled');
			if (typeof disabled === 'undefined') {
				$("#direction").val(1);
				document.pendaftaran_form.submit();
			}
		});
		
		$("#next-date").click(function() {
			var disabled = $(this).attr('disabled');
			if (typeof disabled === 'undefined') {
				$("#direction").val(2);
				document.pendaftaran_form.submit();
			}
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
	#pendaftaran_processing {
		position:absolute;
		top: 50%;
		left: 50%;
		width:20em;
		height:2em;
		margin-top: -10em; /*set to a negative number 1/2 of your height*/
		margin-left: -10em; /*set to a negative number 1/2 of your width*/
		border: 1px solid #ccc;
		background-color: #f3f3f3;
		text-align: center;
		padding-top: 0.5em;
		padding-bottom: 0.5em;
	}
</style>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<div class="widget-header">
					<div class="title"><?php echo $title; ?></div>
					<div class="tools pull-right">
						<div class="btn-group">
							<?php
								if (strtotime($tanggal_current) > strtotime($tanggal_awal)) {
									$disabled_prev = false;
								}
								else {
									$disabled_prev = true;
								}
							?>
							<a id="prev-date" class="btn btn-small <?php echo $disabled_prev ? "" : "btn-info"; ?>" data-original-title="" <?php echo $disabled_prev ? "disabled=\"disabled\"" : ""; ?>>
								<i class="icon-chevron-left <?php echo $disabled_prev ? "" : "icon-white"; ?>" data-original-title="Prev"></i> Tanggal Sebelumnya
							</a>
							<?php
								if (strtotime($tanggal_current) < strtotime($tanggal_akhir)) {
									$disabled_next = false;
								}
								else {
									$disabled_next = true;
								}
							?>
							<a id="next-date" class="btn btn-small <?php echo $disabled_next ? "" : "btn-info"; ?>" data-original-title="" <?php echo $disabled_next ? "disabled=\"disabled\"" : ""; ?>>
								Tanggal Berikutnya <i class="icon-chevron-right <?php echo $disabled_next ? "" : "icon-white"; ?>" data-original-title="Next"></i>
							</a>
						</div>
					</div>
				</div>
				<div class="widget-body">
					<form class="form-horizontal no-margin" id="pendaftaran_form" name="pendaftaran_form" method="post" action="<?php echo site_url('tp2ri/pendaftaran/browse/'.$browse); ?>">
						<div id="dt_example" class="example_alt_pagination">
							<table class="table table-striped table-condensed table-striped table-hover table-bordered pull-left" id="pendaftaran">
								<thead>
									<tr>
										<th>Tanggal/Jam</th>
										<th>No. Register</th>
										<th>No. Medrec</th>
										<th>Nama</th>
										<th>Jenis Kelamin</th>
										<th>Alamat</th>
										<th>Tanggal Lahir</th>
										<th>Agama</th>
										<th>Pendidikan</th>
										<th>Pekerjaan</th>
										<th>Cara Pembayaran</th>
										<th>Dokter</th>
										<th>Bed</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="14" class="dataTables_empty">Loading data from server</td>
									</tr>
								</tbody>
							</table>
							<div class="clearfix"></div>
						</div>
						<div>
							<input type="hidden" id="tanggal_awal" name="tanggal_awal" value="<?php echo $tanggal_awal; ?>" />
							<input type="hidden" id="tanggal_akhir" name="tanggal_akhir" value="<?php echo $tanggal_akhir; ?>" />
							<input type="hidden" id="tanggal_current" name="tanggal_current" value="<?php echo $tanggal_current; ?>" />
							<input type="hidden" id="direction" name="direction" value="0" />
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>