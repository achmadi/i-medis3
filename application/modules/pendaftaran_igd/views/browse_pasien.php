<script type="text/javascript">
	
	var oTable;
	
	$(document).ready(function () {
		
		oTable = $('#pasien').dataTable({
			"sPaginationType": "full_numbers",
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?php echo site_url('pendaftaran_igd/pendaftaran_igd/load_data_pasien'); ?>",
			"aoColumns"			: [
									  { sWidth: '90px' },
									  { sWidth: 'null' },
									  { sWidth: '77px' },
									  { sWidth: 'null' },
									  { sWidth: '90px' }
								  ]
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
		
		$('#disp_search_tanggal_lahir').datepicker({
			format: 'dd/mm/yyyy',
			viewMode: 'years'
		}).
		on('changeDate', function(ev) {
			var date = new Date(ev.date);
			var date_str = getISODate(date);
			$('#search_tanggal_lahir').val(date_str);
			
			if (ev.viewMode === 'days')
				disp_tanggal_lahir.hide();
		}).data('datepicker');
		
		$("#search_no_medrec").keyup(function() {
			oTable.fnFilter($("#search_no_medrec").val(), 0 );
		});
		
		$("#search_nama").keyup(function() {
			oTable.fnFilter($("#search_nama").val(), 1 );
		});
		
		$("#search_jenis_kelamin1").click(function() {
			oTable.fnFilter($("#search_jenis_kelamin1").val(), 2 );
		});
		
		$("#search_jenis_kelamin2").click(function() {
			oTable.fnFilter($("#search_jenis_kelamin2").val(), 2 );
		});
		
		$("#search_alamat").keyup(function() {
			oTable.fnFilter($("#search_alamat").val(), 3 );
		});
		
	});
</script>
<style type="text/css">
	.dashboard-wrapper .left-sidebar {
		margin-right: 0;
	}
</style>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<div class="widget-body">
					<div class="row-fluid form-horizontal no-margin">
						
						<div class="span6">

							<div class="control-group">
								<label class="control-label" for="search_no_medrec">No. Medrec</label>
								<div class="controls controls-row">
									<input class="span6" type="text" id="search_no_medrec" name="search_no_medrec" maxlength="20" placeholder="No. Medrec" value="" autocomplte="off" />
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="search_nama">Nama</label>
								<div class="controls controls-row">
									<input class="span12" type="text" id="search_nama" name="search_nama" maxlength="60" placeholder="Nama" value="" autocomplte="off" />
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="search_jenis_kelamin1">Jenis Kelamin</label>
								<div class="controls controls-row">
									<label class="radio inline">
										<input type="radio" id="search_jenis_kelamin1" name="search_jenis_kelamin" value="1" />Laki-laki
									</label>
									<label class="radio inline">
										<input type="radio" id="search_jenis_kelamin2" name="search_jenis_kelamin" value="2" />Perempuan
									</label>
								</div>
							</div>

						</div>
						
						<div class="span6">

							<div class="control-group">
								<label class="control-label" for="search_alamat">Alamat</label>
								<div class="controls controls-row">
									<input class="span12" type="text" id="search_alamat" name="search_alamat" maxlength="255" placeholder="Alamat" value="" autocomplte="off" />
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="disp_search_tanggal_lahir">Tanggal Lahir</label>
								<div class="controls controls-row">
									<input type="hidden" id="search_tanggal_lahir" name="search_tanggal_lahir" value="" />
									<input class="span6" type="text" id="disp_search_tanggal_lahir" name="disp_search_tanggal_lahir" placeholder="" value="" />
								</div>
							</div>

						</div>
						
					</div>
					<hr />
					<div id="dt_example" class="example_alt_pagination">
						<table class="table table-striped table-condensed table-striped table-hover table-bordered pull-left" id="pasien">
							<thead>
								<tr>
									<th>No. Medrec</th>
									<th>Nama</th>
									<th>Jenis Kelamin</th>
									<th>Alamat</th>
									<th>Tanggal Lahir</th>
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