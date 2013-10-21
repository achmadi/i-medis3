<script type="text/javascript" charset="utf-8">
	
	$(document).ready(function() {
		
		var oTable = $('#agama').dataTable( {
			"sPaginationType"	: "full_numbers",
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?php echo site_url('master/agama/load_data'); ?>",
			"aoColumnDefs"		: [
									  { "bSearchable": false, "bVisible": false, "aTargets": [ 1 ] },
									  { "bSortable": false, "aTargets": [ 2 ] }
								  ],
			"aoColumns"			: [
									  { sWidth: 'null' },
									  { sWidth: '127px' },
									  { sWidth: '45px' }
								  ],
			"bStateSave": true
		});
		
		$('#agama').on('click', '.delete-row', function() {
			var id = $(this).data('id');
			alertify.set({
				labels: {
					ok: "OK",
					cancel: "Batal"
				},
				delay: 5000,
				buttonReverse: false,
				buttonFocus: "ok"
			});
			alertify.confirm("Hapus record tersebut?", function (e) {
				if (e) {
					$.ajax({
						type: 'get',
						url: '<?php echo site_url('master/agama/delete'); ?>',
						data: 'id=' + id,
						success: function() {
							oTable.fnDraw();
							alertify.success("Record telah di hapus dari database!");
						},
						error: function() {
							alertify.error("Penghapusan record gagal!");
						}
					});
				}
			});
			return false;
		});
		
		$("#agama").on("click", ".order_up", function(event){
			var id = $(this).data('id');
			var ordering = $(this).data('ordering');
			$.ajax({
				type: 'get',
				url: '<?php echo site_url('master/agama/order_up'); ?>',
				data: 'id=' + id + '&ordering=' + ordering,
				success: function() {
					oTable.fnDraw();
					alertify.success("Ordering berhasil disimpan!");
				},
				error: function() {
					alertify.error("Ordering gagal di simpan.");
				}
			});
			return false;
		});

		$("#agama").on("click", ".order_down", function(event){
			var id = $(this).data('id');
			var ordering = $(this).data('ordering');
			$.ajax({
				type: 'get',
				url: '<?php echo site_url('master/agama/order_down'); ?>',
				data: 'id=' + id + '&ordering=' + ordering,
				success: function() {
					oTable.fnDraw();
					alertify.success("Ordering berhasil disimpan!");
				},
				error: function() {
					alertify.error("Ordering gagal di simpan.");
				}
			});
			return false;
		});
		
		$("#tambah_button").on("click", function () {
			$('#validationSummary ul').text('');
			$('#title_modal').text('Tambah Agama');
			$("#id").val('0');
			$("#nama").val('');
			$('#agama_modal').modal('show');
			return false;
		});
		
		$('#agama').on('click', '.edit-row', function() {
			var id = $(this).data('id');
			$.ajax({
				url:		"<?php echo site_url('master/agama/get_agama'); ?>" + "/" + id,
				dataType:	"json",
				cache:		false
			}).done(function (data) {
				$('#title_modal').text('Edit Agama');
				$("#id").val(data.agama.id);
				$("#nama").val(data.agama.nama);
				$('#agama_modal').modal('show');
			});
			return false;
		});
		
		var agamaApp = {
			initAgamaModal: function () {
				$("#agama_form").validate({
					rules: {
						nama: { required: true, minlength: 1 }
					},
					messages: {
						nama: "Agama diperlukan."
					},
					errorContainer: "#validationSummary",
					errorLabelContainer: "#validationSummary ul",
					wrapper: "li",
					submitHandler: function (form) {
						agamaApp.addAgama($(form));
					}
				});
			},
			addAgama: function (form) {
				var url = "<?php echo site_url('master/agama/simpan'); ?>";
				var postData = form.serialize();
				$.post(url, postData, function (data) {
					if (data.toLowerCase() === "ok") {
						oTable.fnDraw();
						alertify.success("Record telah di simpan!");
						return;
					}
					//alert("Something went wrong. Please retry!");
				});
				$('#agama_modal').modal('hide');
			}
		};
		
		agamaApp.initAgamaModal();
		
	});
</script>
<style type="text/css">
	.dashboard-wrapper .left-sidebar {
		margin-right: 0;
	}
	.form-actions {
		border-top: 0;
		border-bottom: 1px solid #E5E5E5;
		margin: 0;
		padding: 5px 10px 5px;
	}
	
	
    #agama_form .modal.fade {
         left: -25%;
          -webkit-transition: opacity 0.3s linear, left 0.3s ease-out;
             -moz-transition: opacity 0.3s linear, left 0.3s ease-out;
               -o-transition: opacity 0.3s linear, left 0.3s ease-out;
                  transition: opacity 0.3s linear, left 0.3s ease-out;
    }
    #agama_form .modal.fade.in {
        left: 50%;
    }
	
    #agama_form .modal-body {
        max-height: 100px;
    }
	#agama_modal {
		width: 600px;
		margin-left: -300px;
		margin-right: -300px;
	}
	#agama_processing {
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
					<div class="title">Agama</div>
					<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
				</div>
				<div class="form-actions no-margin">
					<a id="tambah_button" href="#" class="btn btn-info bottom-margin pull-right" data-original-title="">Tambah</a>
					<div class="clearfix"></div>
				</div>
				<div class="widget-body">
					
					<?php
						//message, error, notice
						$notification = $this->session->flashdata('notification');
						$notification_type = $notification['type'];
						$notification_title = ucfirst($notification['type']);;
						$notification_message = $notification['message'];
						if ($notification_message) {
					?>
					<div class="alert alert-block alert-<?php echo $notification_type; ?> fade in">
						<button data-dismiss="alert" class="close" type="button">×</button>
						<h4 class="alert-heading"><?php echo $notification_title; ?>!</h4>
						<p><?php echo $notification_message; ?></p>
					</div>
					<?php
						}
					?>
					
					<div id="dt_example" class="example_alt_pagination">
						<table class="table table-striped table-condensed table-striped table-hover table-bordered pull-left" id="agama">
							<thead>
								<tr>
									<th>Nama Agama</th>
									<th>Ordering</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="3" class="dataTables_empty">Loading data from server</td>
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
<form class="form-horizontal no-margin" id="agama_form" name="agama_form" method="post" action="">
	<div class="modal fade" id="agama_modal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h4 id="title_modal">Tambah/Edit Agama</h4>
		</div>
		<div class="modal-body">
			<div class="modal-body">
				<div class="row-fluid">
					<div class="span12">

						<div class="control-group">
							<label class="control-label" for="nama">Nama Agama</label>
							<div class="controls controls-row">
								<input type="hidden" id="id" name="id" value="0" />
								<input class="span12" type="text" id="nama" name="nama" maxlength="30" placeholder="Nama Agama" value="">
							</div>
						</div>

					</div>
				</div>
			</div>
			<div id="validationSummary" class="validation-summary">
				<ul></ul>
			</div>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn" data-dismiss="modal">Batal</a>
			<input type="submit" class="btn btn-primary" value="Simpan" />
		</div>
	</div>
</form>
