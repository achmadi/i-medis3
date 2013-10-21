<script type="text/javascript">
	$(document).ready(function () {
		$('#pembagian_jasa').dataTable({
			"sPaginationType": "full_numbers",
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?php echo site_url('pembagian_jasa/pembagian_jasa/load_data'); ?>",
			"aoColumnDefs"		: [
									  { "bSortable": false, "aTargets": [ 5 ] }
								  ],
			"aoColumns"			: [
									  { sWidth: '138px' },
									  { sWidth: '79px' },
									  { sWidth: '77px' },
									  { sWidth: 'null' },
									  { sWidth: '88px' },
									  { sWidth: '101px' }
								  ]
		});
		
		$('#pembagian_jasa').on('click', '.uraian-button', function(e) {
			e.preventDefault();
			/*
			$tr = $(this).parent().parent();
			var counter = getCounter($tr);
			
			var rows = $("#table_penerima_jp_detail_" + counter + " tbody tr");
			var data = new Array();
			rows.each(function(index) {
				data[index] = new Array();
				data[index]['id'] = $('#penerima_jp_detail_id_' + counter + '_' + index).val();
				data[index]['tanggal'] = $('#penerima_jp_detail_tanggal_' + counter + '_' + index).val();
				data[index]['nama'] = $('#penerima_jp_detail_nama_' + counter + '_' + index).val();
				data[index]['pegawai_id'] = $('#penerima_jp_detail_pegawai_id_' + counter + '_' + index).val();
				data[index]['tarif_langsung'] = $('#penerima_jp_detail_tarif_langsung_' + counter + '_' + index).val();
				data[index]['persen_proporsi'] = $('#penerima_jp_detail_persen_proporsi_' + counter + '_' + index).val();
				data[index]['jumlah_proporsi'] = $('#penerima_jp_detail_jumlah_proporsi_' + counter + '_' + index).val();
				data[index]['persen_langsung'] = $('#penerima_jp_detail_persen_langsung_' + counter + '_' + index).val();
				data[index]['jumlah_langsung'] = $('#penerima_jp_detail_jumlah_langsung_' + counter + '_' + index).val();
				data[index]['persen_balancing'] = $('#penerima_jp_detail_persen_balancing_' + counter + '_' + index).val();
				data[index]['jumlah_balancing'] = $('#penerima_jp_detail_jumlah_balancing_' + counter + '_' + index).val();
				data[index]['persen_kebersamaan'] = $('#penerima_jp_detail_persen_kebersamaan_' + counter + '_' + index).val();
				data[index]['jumlah_kebersamaan'] = $('#penerima_jp_detail_jumlah_kebersamaan_' + counter + '_' + index).val();
			});
			var data_serial = SerializePHP(data);
			var url = $(this).data('url') + '?counter=' + counter + '&data=' + escape(data_serial);
			*/
		    var id = $(this).data('id');
			var url = $(this).attr('href') + '?pembagian_jasa_id=' + id;
			$("#pembagian_jasa_modal").removeData ('modal');
			$('#pembagian_jasa_modal')
				.modal({
					remote: url,
					show: true
				});
		});
		
	});
</script>
<style type="text/css">
	.dashboard-wrapper .left-sidebar {
		margin-right: 0;
	}
	
	/* begin pasien_modal */
    .dashboard-wrapper #pembagian_jasa_modal .modal.fade {
         left: -25%;
          -webkit-transition: opacity 0.3s linear, left 0.3s ease-out;
             -moz-transition: opacity 0.3s linear, left 0.3s ease-out;
               -o-transition: opacity 0.3s linear, left 0.3s ease-out;
                  transition: opacity 0.3s linear, left 0.3s ease-out;
    }
    .dashboard-wrapper #pembagian_jasa_modal .modal.fade.in {
        left: 50%;
    }
	.dashboard-wrapper #pembagian_jasa_modal .modal-body {
        max-height: 400px;
    }
	#pembagian_jasa_modal {
		width: 900px;
		margin-left: -450px;
		margin-right: -450px;
	}
	/* end pasien_modal */
</style>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<div class="widget-header">
					<div class="title">Daftar Pembagian Jasa</div>
					<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
				</div>
				<div class="widget-body">
					<div id="dt_example" class="example_alt_pagination">
						<table class="table table-striped table-condensed table-striped table-hover table-bordered pull-left" id="pembagian_jasa">
							<thead>
								<tr>
									<th>Tanggal/Jam</th>
									<th>No. Medrec</th>
									<th>Nama</th>
									<th>Alamat</th>
									<th>Jumlah</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="6" class="dataTables_empty">Loading data from server</td>
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
<div class="modal fade" id="pembagian_jasa_modal" data-remote="">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3>Pembagian Jasa</h3>
	</div>
	<div class="modal-body">
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	</div>
</div>