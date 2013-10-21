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
	.form-actions {
		margin-bottom: 0;
		margin-top: 0;
		padding: 5px;
	}
	.form-horizontal .control-group {
		margin-bottom: 4px;
	}
</style>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget" style="margin-bottom: 10px;">
				<div class="widget-header">
					<div class="title">Filter</div>
					<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
				</div>
				<div class="widget-body">
					<div id="dt_example" class="example_alt_pagination">
						<form class="form-horizontal no-margin" method="post" action="">
							<div class="control-group">
								<label class="control-label" for="report_range1">Tanggal Kunjungan</label>
								<div class="controls">
									<div class="input-append">
										<input type="hidden" id="tanggal_dari" name="tanggal_dari" value="" />
										<input type="hidden" id="tanggal_sampai" name="tanggal_sampai" value="" />
										<input id="report_range1" class="report_range" type="text" placeholder="01/29/2013 - 01/31/2013" name="report_range1">
										<span class="add-on btn report_range">
											<i class="icon-calendar"></i>
										</span>
									</div>
								</div>
							</div>
							<div class="form-actions no-margin">
								<button id="cari" class="btn btn-info pull-left" value="Simpan" name="simpan" type="submit">Cari</button>
								<div class="clearfix"> </div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<div class="widget-header">
					<div class="title"></div>
					<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
				</div>
				<div class="row-fluid" style="border-top: 0; border-bottom: 1px solid #E5E5E5;">
					<div class="span12">
						<div class="form-actions">
							<a rel="nofollow" onclick="window.open(this.href,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no'); return false;" title="Print" class="btn btn-info pull-left" href="" style="margin-right: 2px;">Print</a>
							<a id="excel" class="btn btn-info pull-left" href="">Excel</a>
						</div>
					</div>
				</div>
				<div class="widget-body">
					<div id="dt_example" class="example_alt_pagination">
						<table class="table table-striped table-condensed table-striped table-hover table-bordered pull-left" id="rpt_001">
							<thead>
								<tr>
									<th>Klinik</th>
									<th>Laki-laki</th>
									<th>Perempuan</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="4" class="dataTables_empty">Loading data from server</td>
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