<script type="text/javascript">
	
	var oTable_rj;
	var oTable_rd;
	
	$(document).ready(function () {
		
		oTable_rj = $('#pasien_rawat_jalan').dataTable({
			"sPaginationType": "full_numbers",
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?php echo site_url('tp2ri/pendaftaran/load_data_pasien_rj'); ?>",
			"aoColumns"			: [
									  { sWidth: '120px' },
									  { sWidth: '90px' },
									  { sWidth: '90px' },
									  { sWidth: 'null' },
									  { sWidth: 'null' }
								  ]
		});
		
		oTable_rd = $('#pasien_rawat_darurat').dataTable({
			"sPaginationType": "full_numbers",
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?php echo site_url('tp2ri/pendaftaran/load_data_pasien_rd'); ?>",
			"aoColumns"			: [
									  { sWidth: '120px' },
									  { sWidth: '90px' },
									  { sWidth: '90px' },
									  { sWidth: 'null' },
									  { sWidth: 'null' }
								  ]
		});
		
		function getISODate3(d){
			var s = function(a,b){return(1e15+a+"").slice(-b);};

			if (typeof d === 'undefined'){
				d = new Date();
			};

			return d.getFullYear() + '-' +
				s(d.getMonth()+1,2) + '-' +
				s(d.getDate(),2);
		}
		
		$('#disp_search_tanggal_lahir').datepicker({
			format: 'dd/mm/yyyy',
			viewMode: 'years'
		}).
		on('changeDate', function(ev) {
			var date = new Date(ev.date);
			var date_str = getISODate3(date);
			$('#search_tanggal_lahir').val(date_str);
			
			if (ev.viewMode === 'days') {
				oTable_rj.fnFilter($("#search_tanggal_lahir").val(), 4 );
				$('#disp_search_tanggal_lahir').datepicker('hide');
			}
		}).data('datepicker');
		
		$("#search_no_medrec").keyup(function() {
			oTable_rj.fnFilter($("#search_no_medrec").val(), 0 );
		});
		
		$("#search_nama").keyup(function() {
			oTable_rj.fnFilter($("#search_nama").val(), 1 );
		});
		
		$("#search_jenis_kelamin1").click(function() {
			oTable_rj.fnFilter($("#search_jenis_kelamin1").val(), 2 );
		});
		
		$("#search_jenis_kelamin2").click(function() {
			oTable_rj.fnFilter($("#search_jenis_kelamin2").val(), 2 );
		});
		
		$("#search_alamat").keyup(function() {
			oTable_rj.fnFilter($("#search_alamat").val(), 3 );
		});
		
	});
</script>
<style type="text/css">
	.dashboard-wrapper .left-sidebar {
		margin-right: 0;
	}
	.datepicker {
		z-index: 1151;
	}
</style>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<div class="widget-body">
					<ul class="nav nav-tabs no-margin myTabBeauty">
						<li class="<?php echo $instalasi == 1 ? 'active' : ''; ?>">
							<a data-toggle="tab" href="#rawat_jalan">Rawat Jalan</a>
						</li>
						<li class="<?php echo $instalasi == 2 ? 'active' : ''; ?>">
							<a data-toggle="tab" href="#rawat_darurat">Rawat Darurat</a>
						</li>
					</ul>
					<div class="tab-content" id="myTabContent">
						<div id="rawat_jalan" class="tab-pane fade <?php echo $instalasi == 1 ? 'active in' : ''; ?>">

							<div class="row-fluid form-horizontal no-margin">
								<div class="span6">
									<div class="control-group">
										<label class="control-label" for="search_no_medrec_rj">No. Medrec</label>
										<div class="controls controls-row">
											<input class="span6" type="text" id="search_no_medrec_rj" name="search_no_medrec_rj" maxlength="20" placeholder="No. Medrec" value="" autocomplte="off" />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="search_nama_rj">Nama</label>
										<div class="controls controls-row">
											<input class="span12" type="text" id="search_nama_rj" name="search_nama_rj" maxlength="60" placeholder="Nama" value="" autocomplte="off" />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="search_jenis_kelamin1_rj">Jenis Kelamin</label>
										<div class="controls controls-row">
											<label class="radio inline">
												<input type="radio" id="search_jenis_kelamin1_rj" name="search_jenis_kelamin_rj" value="1" />Laki-laki
											</label>
											<label class="radio inline">
												<input type="radio" id="search_jenis_kelamin2_rj" name="search_jenis_kelamin_rj" value="2" />Perempuan
											</label>
										</div>
									</div>
								</div>
								<div class="span6">
									<div class="control-group">
										<label class="control-label" for="search_alamat_rj">Alamat</label>
										<div class="controls controls-row">
											<input class="span12" type="text" id="search_alamat_rj" name="search_alamat_rj" maxlength="255" placeholder="Alamat" value="" autocomplte="off" />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="disp_search_tanggal_lahir_rj">Tanggal Lahir</label>
										<div class="controls controls-row">
											<input type="hidden" id="search_tanggal_lahir_rj" name="search_tanggal_lahir_rj" value="" />
											<input class="span6" type="text" id="disp_search_tanggal_lahir_rj" name="disp_search_tanggal_lahir_rj" placeholder="" value="" />
										</div>
									</div>
								</div>
							</div>
							<hr />
							<div id="dt_example" class="example_alt_pagination">
								<table class="table table-striped table-condensed table-striped table-hover table-bordered pull-left" id="pasien_rawat_jalan">
									<thead>
										<tr>
											<th>Tanggal</th>
											<th>No. Register</th>
											<th>No. Medrec</th>
											<th>Nama</th>
											<th>Alamat</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td colspan="5" class="dataTables_empty">Loading data from server</td>
										</tr>
									</tbody>
								</table>
								<div class="clearfix"></div>
							</div>
							
						</div>
						<div id="rawat_darurat" class="tab-pane fade <?php echo $instalasi == 2 ? 'active in' : ''; ?>">
							
							<div class="row-fluid form-horizontal no-margin">
								<div class="span6">
									<div class="control-group">
										<label class="control-label" for="search_no_medrec_rd">No. Medrec</label>
										<div class="controls controls-row">
											<input class="span6" type="text" id="search_no_medrec_rd" name="search_no_medrec_rd" maxlength="20" placeholder="No. Medrec" value="" autocomplte="off" />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="search_nama_rd">Nama</label>
										<div class="controls controls-row">
											<input class="span12" type="text" id="search_nama_rd" name="search_nama_rd" maxlength="60" placeholder="Nama" value="" autocomplte="off" />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="search_jenis_kelamin1_rd">Jenis Kelamin</label>
										<div class="controls controls-row">
											<label class="radio inline">
												<input type="radio" id="search_jenis_kelamin1_rd" name="search_jenis_kelamin_rd" value="1" />Laki-laki
											</label>
											<label class="radio inline">
												<input type="radio" id="search_jenis_kelamin2_rd" name="search_jenis_kelamin_rd" value="2" />Perempuan
											</label>
										</div>
									</div>
								</div>
								<div class="span6">
									<div class="control-group">
										<label class="control-label" for="search_alamat_rd">Alamat</label>
										<div class="controls controls-row">
											<input class="span12" type="text" id="search_alamat_rd" name="search_alamat_rd" maxlength="255" placeholder="Alamat" value="" autocomplte="off" />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="disp_search_tanggal_lahir_rd">Tanggal Lahir</label>
										<div class="controls controls-row">
											<input type="hidden" id="search_tanggal_lahir_rd" name="search_tanggal_lahir_rd" value="" />
											<input class="span6" type="text" id="disp_search_tanggal_lahir_rd" name="disp_search_tanggal_lahir_rd" placeholder="" value="" />
										</div>
									</div>
								</div>
							</div>
							<hr />
							<div id="dt_example" class="example_alt_pagination">
								<table class="table table-striped table-condensed table-striped table-hover table-bordered pull-left" id="pasien_rawat_darurat">
									<thead>
										<tr>
											<th>Tanggal</th>
											<th>No. Register</th>
											<th>No. Medrec</th>
											<th>Nama</th>
											<th>Alamat</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td colspan="5" class="dataTables_empty">Loading data from server</td>
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
	</div>
</div>