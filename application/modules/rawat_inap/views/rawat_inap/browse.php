<script type="text/javascript">
	var oTable;
 
	/* Formating function for row details */
	function fnFormatDetails(nTr) {
		var aData = oTable.fnGetData( nTr );
		var sOut = '<div class="row-fluid">';
		sOut += '	<div class="span10">';
		sOut += '		<div class="stylish-lists">';
		sOut += '			<dl class="dl-horizontal no-margin">';
		sOut += '				<dt class="text-info">No. Medrec</dt>';
		sOut += '				<dd>' + aData[7] + '</dd>';
		sOut += '				<dt class="text-info">Nama</dt>';
		sOut += '				<dd>' + aData[8] + '</dd>';
		sOut += '				<dt class="text-info">Jenis Kelamin</dt>';
		sOut += '				<dd>' + aData[9] + '</dd>';
		sOut += '				<dt class="text-info">Umur</dt>';
		sOut += '				<dd>' + aData[10] + '</dd>';
		sOut += '				<dt class="text-info">Dokter</dt>';
		sOut += '				<dd>' + aData[12] + '</dd>';
		sOut += '			</dl>';
		sOut += '		</div>';
		sOut += '	</div>';
		sOut += '	<div class="span2">';
		sOut += '		<div class="wrapper">';
		sOut += '			<a href="#" class="tindakan-row btn btn-small btn-primary btn-block" data-id="' + aData[5] + '" data-original-title="Tindakan">Tindakan</a>';
		sOut += '			<a href="rawat_inap/pelayanan_ri/index/' + aData[5] + '" class="btn btn-small btn-primary btn-block" data-id="' + aData[5] + '" data-original-title="Tindakan">Rincian</a>';
		sOut += '			<a href="#" class="pindah-bed-row btn btn-small btn-primary btn-block" data-id="' + aData[5] + '" data-original-title="Pindah Bed">Pindah Bed</a>';
		sOut += '			<a href="#" class="pindah-ruangan-row btn btn-small btn-primary btn-block" data-id="' + aData[5] + '" data-original-title="Pindah Ruangan">Pindah Ruangan</a>';
		sOut += '			<a href="#" class="checkout-row btn btn-small btn-success btn-block" data-id="' + aData[5] + '" data-original-title="Checkout">Checkout</a>';
		sOut += '		</div>';
		sOut += '	</div>';
		sOut += '</div>';
		return sOut;
	}
	
	$(document).ready(function () {
		
		function check_session() {
			var session_id = "<?php echo $this->session->userdata('session_id'); ?>";
			if (session_id === null) {
				window.location = "<?php echo site_url(''); ?>";
			}
		}
		
		/*
		function checkSession() {
			var request = false;
			if(window.XMLHttpRequest) { // Mozilla/Safari
				request = new XMLHttpRequest();
			} else if(window.ActiveXObject) { // IE
				request = new ActiveXObject("Microsoft.XMLHTTP");
			}
			request.open('POST', 'SessionCheck.aspx', true);
			request.onreadystatechange = function() {
				if(request.readyState == 4) {
					var session = eval('(' + request.responseText + ')');
					if(session.valid) {
						// DO SOMETHING IF SESSION IS VALID
					} else {
						alert("Your Session has expired");
						window.location = "login.aspx";
					}
				}
			}
			request.send(null);
		}
		*/
		//{"valid":<%=Session["username"] != null ? "true" : "false" %>}

		oTable = $('#bed').dataTable({
			"sPaginationType"	: "full_numbers",
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?php echo site_url('rawat_inap/rawat_inap/load_data?gedung_id='.$gedung->id); ?>",
			"aoColumns"			: [
									  { 'sWidth': '4%', "sClass": "center", "bSortable": false },
									  { 'sWidth': '15%' },
									  { 'sWidth': 'null' },
									  { 'sWidth': '60px' },
									  { 'sWidth': '60px' },
									  { 'sWidth': '0', 'bSearchable': false, 'bVisible': false },
									  { 'sWidth': '0', 'bSearchable': false, 'bVisible': false },
									  { 'sWidth': '90px' },
									  { 'sWidth': '150px' },
									  { 'sWidth': '0', 'bSearchable': false, 'bVisible': false },
									  { 'sWidth': '0', 'bSearchable': false, 'bVisible': false },
									  { 'sWidth': '0', 'bSearchable': false, 'bVisible': false },
									  { 'sWidth': '150px' }
								  ],
			"aaSorting"			: [[1, 'asc']]
		});
		
		$('#bed').on( 'click', ".bed_show_hide", function () {
			var nTr = $(this).parents('tr')[0];
			if ( oTable.fnIsOpen(nTr) )
			{
				this.src = "<?php echo base_url('assets/img/details_open.png'); ?>";
				oTable.fnClose( nTr );
			}
			else
			{
				this.src = "<?php echo base_url('assets/img/details_close.png'); ?>";
				oTable.fnOpen( nTr, fnFormatDetails(nTr), 'details' );
			}
		});
		
		function getISODateTime(d){
			var s = function(a,b){return(1e15+a+"").slice(-b);};

			if (typeof d === 'undefined'){
				d = new Date();
			};

			return d.getFullYear() + '-' +
				s(d.getMonth()+1,2) + '-' +
				s(d.getDate(),2) + ' ' +
				s(d.getHours(),2) + ':' +
				s(d.getMinutes(),2) + ':' +
				s(d.getSeconds(),2);
		}
	   
	   function getISODate(d){
			var s = function(a,b){return(1e15+a+"").slice(-b);};

			if (typeof d === 'undefined'){
				d = new Date();
			};

			return d.getFullYear() + '-' +
				s(d.getMonth()+1,2) + '-' +
				s(d.getDate(),2);
		}
		
		var disp_tanggal_tindakan = $('#disp_tanggal_tindakan').datepicker({
			format: 'dd/mm/yyyy'
		}).
		on('changeDate', function(ev) {
			var date = new Date(ev.date);
			var date_str = getISODateTime(date);
			$('#tanggal_tindakan').val(date_str);
			disp_tanggal_tindakan.hide();
		}).data('datepicker');
		
		$('#bed').on("click", ".tindakan-row", function () {
			check_session();
			var pelayanan_ri_id = $(this).data('id');
			if (pelayanan_ri_id) {
				$("#tindakan_modal").removeData ('modal');
				$.getJSON("<?php echo site_url('rawat_inap/get_data_tindakan'); ?>?pelayanan_ri_id=" + pelayanan_ri_id, function(json) {
					$("#tanggal_tindakan").val(json.data.tanggal);
					var t = json.data.tanggal.split(/[- :]/);
					var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
					$("#disp_tanggal_tindakan").val($.datepicker.formatDate('dd/mm/yy', d));
					$("#tindakan_no_medrec").text(json.data.no_medrec);
					$("#tindakan_nama").text(json.data.nama);
					
					$("#loading_tindakan").show();
					$.getJSON("<?php echo site_url('rawat_inap/get_tindakan'); ?>", function(json_tindakan) {
						var option_tindakan = '';
						option_tindakan += '<option value="0" selected="selected">- Pilih Tindakan -</option>';
						for (var i = 0; i < json_tindakan.tindakan.length; i++) {
							if (json.data.dokter_id === json_tindakan.tindakan[i].id) {
								option_tindakan += '<option value="' + json_tindakan.tindakan[i].id + '" selected="selected">    ' + json_tindakan.tindakan[i].nama + '</option>';
							}
							else {
								option_tindakan += '<option value="' + json_tindakan.tindakan[i].id + '">' + json_tindakan.tindakan[i].nama + '</option>';
							}
						}
						$("#tindakan_id").html(option_tindakan);
						$("#loading_tindakan").hide();
					});
					
					$("#loading_dokter").show();
					$.getJSON("<?php echo site_url('rawat_inap/get_dokter'); ?>", function(json_dokter) {
						var option_dokter = '';
						option_dokter += '<option value="0" selected="selected">- Pilih Dokter -</option>';
						for (var i = 0; i < json_dokter.dokter.length; i++) {
							if (json.data.dokter_id === json_dokter.dokter[i].id) {
								option_dokter += '<option value="' + json_dokter.dokter[i].id + '" selected="selected">' + json_dokter.dokter[i].nama + '</option>';
							}
							else {
								option_dokter += '<option value="' + json_dokter.dokter[i].id + '">' + json_dokter.dokter[i].nama + '</option>';
							}
						}
						$("#dokter_id").html(option_dokter);
						$("#loading_dokter").hide();
					})
					.done(function() {
						//alert( "second success" );
					})
					.fail(function() {
						//alert( "error" );
					})
					.always(function() {
						//alert( "finished" );
					});
					
					$('#keterangan').val(json.data.keterangan);
					
					$('#tindakan_dlg_id').val(json.data.id);
					$('#tindakan_dlg_unit_id').val(json.data.unit_id);
					$('#tindakan_dlg_pelayanan_ri_id').val(json.data.pelayanan_ri_id);
					$('#tindakan_dlg_bed_id').val(json.data.bed_id);
					
					$('#tindakan_modal').modal('show');
				});
			}
			return false;
		});
		
		var tindakanApp = {
			initTindakanModal: function () {
				$("#tindakan_form").validate({
					rules: {
						tanggal: { required: true, minlength: 1 },
						tindakan_id: {required: true, min: 1},
						dokter_id: { required: true, min: 1 }
					},
					messages: {
						tanggal: "Tanggal diperlukan.",
						tindakan_id: "Tindakan diperlukan",
						dokter_id: "Dokter diperlukan"
					},
					highlight: function(element) {
						$(element).closest('.control-group').removeClass('success').addClass('error');
					},
					success: function(element) {
						element
						.text('').addClass('valid')
						.closest('.control-group').removeClass('error').addClass('success');
					},
					submitHandler: function (form) {
						tindakanApp.addTindakan($(form));
						return false;
					}

				});
			},
			addTindakan: function (form) {
				var url = "<?php echo site_url('rawat_inap/rawat_inap/simpan_tindakan'); ?>";
				var postData = form.serialize();
				$.post(url, postData, function(data) {
					if (data.toLowerCase() === "ok") {
						oTable.fnDraw();
						alertify.success("Record Tindakan telah di simpan!");
						return;
					}
				});
				/*
				.done(function() {
					//
				})
				.fail(function() {
					//
				})
				 .always(function() {
					//
				});
				*/
				$('#tindakan_modal').modal('hide');
			}
		};
		
		tindakanApp.initTindakanModal();
		
		var disp_pindah_bed_dlg_tanggal = $('#disp_pindah_bed_dlg_tanggal').datepicker({
			format: 'dd/mm/yyyy'
		}).
		on('changeDate', function(ev) {
			var date = new Date(ev.date);
			var date_str = getISODateTime(date);
			$('#pindah_bed_dlg_tanggal').val(date_str);
			disp_pindah_bed_dlg_tanggal.hide();
		}).data('datepicker');
		
		$('#bed').on("click", ".pindah-bed-row", function () {
			var pelayanan_ri_id = $(this).data('id');
			if (pelayanan_ri_id) {
				$("#pindah_bed_modal").removeData('modal');
				$.getJSON("<?php echo site_url('rawat_inap/get_data_pindah_bed'); ?>?pelayanan_ri_id=" + pelayanan_ri_id, function(json) {
					$("#pindah_bed_dlg_tanggal").val(json.data.tanggal);
					var t = json.data.tanggal.split(/[- :]/);
					var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
					$("#disp_pindah_bed_dlg_tanggal").val($.datepicker.formatDate('dd/mm/yy', d));
					$("#pindah_bed_dlg_no_medrec").text(json.data.no_medrec);
					$("#pindah_bed_dlg_nama").text(json.data.nama);
					$("#pindah_bed_dlg_bed_lama").text(json.data.bed_lama_bed + " Kelas: " + json.data.bed_lama_kelas);
					
					var option_bed = '';
					option_bed += '<option value="0" selected="selected">- Pilih Bed -</option>';
					for (var i = 0; i < json.bed_list.length; i++) {
						if (json.data.bed_baru_id === json.bed_list[i].id) {
							option_bed += '<option value="' + json.bed_list[i].id + '" selected="selected">    ' + json.bed_list[i].nama + '</option>';
						}
						else {
							option_bed += '<option value="' + json.bed_list[i].id + '">' + 'bed: ' + json.bed_list[i].nama + ', Kelas: ' + json.bed_list[i].kelas + '</option>';
						}
					}
					$("#pindah_bed_dlg_bed_baru_id").html(option_bed);
					
					$('#pindah_bed_dlg_pelayanan_ri_id').val(json.data.pelayanan_ri_id);
					$('#pindah_bed_dlg_bed_lama_id').val(json.data.bed_lama_id);
					
					$('#pindah_bed_modal').modal('show');
				});
			}
			return false;
		});
		
		var pindahBedApp = {
			initTindakanModal: function () {
				$("#pindah_bed_form").validate({
					rules: {
						pindah_bed_dlg_tanggal: { required: true, minlength: 1 },
						pindah_bed_dlg_bed_baru_id: {required: true, min: 1},
					},
					messages: {
						pindah_bed_dlg_tanggal: "Tanggal diperlukan.",
						pindah_bed_dlg_bed_baru_id: "Bed baru diperlukan."
					},
					highlight: function(element) {
						$(element).closest('.control-group').removeClass('success').addClass('error');
					},
					success: function(element) {
						element
						.text('').addClass('valid')
						.closest('.control-group').removeClass('error').addClass('success');
					},
					submitHandler: function (form) {
						pindahBedApp.addPindahBed($(form));
						return false;
					}

				});
			},
			addPindahBed: function (form) {
				var url = "<?php echo site_url('rawat_inap/rawat_inap/simpan_pindah_bed'); ?>";
				var postData = form.serialize();
				$.post(url, postData, function(data) {
					if (data.toLowerCase() === "ok") {
						oTable.fnDraw();
						alertify.success("Record Pindah Bed telah di simpan!");
						return;
					}
				});
				$('#pindah_bed_modal').modal('hide');
			}
		};
		
		pindahBedApp.initTindakanModal();
		
		var disp_pindah_ruangan_dlg_tanggal = $('#disp_pindah_ruangan_dlg_tanggal').datepicker({
			format: 'dd/mm/yyyy'
		}).
		on('changeDate', function(ev) {
			var date = new Date(ev.date);
			var date_str = getISODateTime(date);
			$('#pindah_ruangan_dlg_tanggal').val(date_str);
			disp_pindah_ruangan_dlg_tanggal.hide();
		}).data('datepicker');
		
		$('#bed').on("click", ".pindah-ruangan-row", function () {
			var pelayanan_ri_id = $(this).data('id');
			if (pelayanan_ri_id) {
				$.getJSON("<?php echo site_url('rawat_inap/get_data_pindah_ruangan'); ?>?pelayanan_ri_id=" + pelayanan_ri_id, function(json) {
					$("#pindah_ruangan_dlg_tanggal").val(json.data.tanggal);
					var t = json.data.tanggal.split(/[- :]/);
					var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
					$("#disp_pindah_ruangan_dlg_tanggal").val($.datepicker.formatDate('dd/mm/yy', d));
					$("#pindah_ruangan_dlg_no_medrec").text(json.data.no_medrec);
					$("#pindah_ruangan_dlg_nama").text(json.data.nama);
					
					var option_gedung = '';
					var caption = '';
					option_gedung += '<option value="0" selected="selected">- Pilih Ruang -</option>';
					for (var i = 0; i < json.gedung_list.length; i++) {
						if (((json.gedung_list[i].bagian !== "") && (json.gedung_list[i].bagian !== null)) && (json.gedung_list[i].bagian !== json.gedung_list[i].nama)) {
							caption = json.gedung_list[i].nama + " (" + json.gedung_list[i].bagian + ")";
						}
						else {
							caption = json.gedung_list[i].nama;
						}
						option_gedung += '<option value="' + json.gedung_list[i].id + '">' + caption + '</option>';
					}
					$("#pindah_ruangan_dlg_gedung_id").html(option_gedung);
					
					$('#pindah_ruangan_dlg_id').val(json.data.id);
					
					$('#pindah_ruangan_modal').modal('show');
				});
			}
			return false;
		});
		
		var disp_tanggal_keluar = $('#disp_tanggal_keluar').datepicker({
			format: 'dd/mm/yyyy'
		}).
		on('changeDate', function(ev) {
			var date = new Date(ev.date);
			var date_str = getISODateTime(date);
			$('#tanggal_keluar').val(date_str);
			disp_tanggal_keluar.hide();
		}).data('datepicker');
		
		$('#bed').on("click", ".checkout-row", function () {
			$("#checkout_modal").removeData('modal');
			var pelayanan_ri_id = $(this).data('id');
			if (pelayanan_ri_id) {
				$.getJSON("<?php echo site_url('rawat_inap/get_data_checkout'); ?>?pelayanan_ri_id=" + pelayanan_ri_id, function(json) {
					$("#tanggal_keluar").val(json.data.tanggal);
					var t = json.data.tanggal.split(/[- :]/);
					var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
					$("#disp_tanggal_keluar").val($.datepicker.formatDate('dd/mm/yy', d));
					$("#checkout_no_medrec").text(json.data.no_medrec);
					$("#checkout_nama").text(json.data.nama);
					
					var options_keadaan_pasien_keluar = '';
					var keyval;
					options_keadaan_pasien_keluar += '<option value="0" selected="selected">- Pilih Keadaan Pasien Keluar -</option>';
					for (var i = 0; i < json.data.keadaan_pasien_keluar_list.length; i++) {
						keyval = json.data.keadaan_pasien_keluar_list[i].split('|');
						if (keyval[1] === json.data.keadaan_pasien_keluar) {
							options_keadaan_pasien_keluar += '<option value="' + keyval[1] + '" selected="selected">' + keyval[0] + '</option>';
						}
						else {
							options_keadaan_pasien_keluar += '<option value="' + keyval[1] + '">' + keyval[0] + '</option>';
						}
					}
					$("#keadaan_pasien_keluar").html(options_keadaan_pasien_keluar);
					
					var keadaan_pasien_keluar = json.data.keadaan_pasien_keluar;
					if (keadaan_pasien_keluar > 0) {
						$("#loading_cara_pasien_keluar").show();
						$.getJSON("<?php echo site_url('rawat_inap/get_cara_pasien_keluar'); ?>?keadaan_pasien_keluar=" + keadaan_pasien_keluar, function(json1) {
							var option_cara_pasien_keluar = '';
							option_cara_pasien_keluar += '<option value="0" selected="selected">- Pilih Ruangan -</option>';
							for (var i = 0; i < json1.data.cara_pasien_keluar_list.length; i++) {
								keyval = json1.data.cara_pasien_keluar_list[i].split('|');
								if (keyval[1] === json.data.cara_pasien_keluar) {
									option_cara_pasien_keluar += '<option value="' + keyval[1] + '" selected="selected">' + keyval[0] + '</option>';
								}
								else {
									option_cara_pasien_keluar += '<option value="' + keyval[1] + '">' + keyval[0] + '</option>';
								}
							}
							$("#cara_pasien_keluar").html(option_cara_pasien_keluar);
							$("#loading_cara_pasien_keluar").hide();
						});
					}
					else {
						$("#cara_pasien_keluar").html('<option value="0" selected="selected">- Pilih Cara Pasien Keluar -</option>');
					}
					
					$("#id").val(json.data.id);
					$("#bed_id").val(json.data.bed_id);
					
					$('#checkout_modal').modal('show');
				});
			}
			return false;
		});
		
		$("#keadaan_pasien_keluar").change(function() {
			var keadaan_pasien_keluar = $("#keadaan_pasien_keluar").val();
			if (keadaan_pasien_keluar > 0) {
				$("#loading_cara_pasien_keluar").show();
				$.getJSON("<?php echo site_url('rawat_inap/get_cara_pasien_keluar'); ?>?keadaan_pasien_keluar=" + keadaan_pasien_keluar, function(json) {
					var option_cara_pasien_keluar = '';
					var keyval;
					option_cara_pasien_keluar += '<option value="0" selected="selected">- Pilih Cara Pasien Keluar -</option>';
					for (var i = 0; i < json.data.cara_pasien_keluar_list.length; i++) {
						keyval = json.data.cara_pasien_keluar_list[i].split('|');
						option_cara_pasien_keluar += '<option value="' + keyval[1] + '">' + keyval[0] + '</option>';
					}
					$("#cara_pasien_keluar").html(option_cara_pasien_keluar);
					$("#loading_cara_pasien_keluar").hide();
				});
			}
			else {
				$("#cara_pasien_keluar").html('<option value="0" selected="selected">- Pilih Cara Pasien Keluar -</option>');
			}
		});
		
		var checkoutApp = {
			initCheckoutModal: function () {
				$("#checkout_form").validate({
					rules: {
						tanggal: { required: true, minlength: 1 },
						keadaan_pasien_keluar: { required: true, min: 1 },
						cara_pasien_keluar: {required: true, min: 1}
					},
					messages: {
						tanggal: "Tanggal diperlukan.",
						keadaan_pasien_keluar: "Keadaan Pasien Keluar diperlukan",
						cara_pasien_keluar: "Cara Pasien Keluar diperlukan"
					},
					highlight: function(element) {
						$(element).closest('.control-group').removeClass('success').addClass('error');
					},
					success: function(element) {
						element
						.text('').addClass('valid')
						.closest('.control-group').removeClass('error').addClass('success');
					},
					submitHandler: function (form) {
						checkoutApp.addCheckout($(form));
						return false;
					}

				});
			},
			addCheckout: function (form) {
				var url = "<?php echo site_url('rawat_inap/rawat_inap/checkout'); ?>";
				var postData = form.serialize();
				$.post(url, postData, function(data) {
					if (data.toLowerCase() === "ok") {
						oTable.fnDraw();
						alertify.success("Record telah di simpan!");
						return;
					}
				});
				/*
				.done(function() {
					//
				})
				.fail(function() {
					//
				})
				 .always(function() {
					//
				});
				*/
				$('#checkout_modal').modal('hide');
			}
		};
		
		checkoutApp.initCheckoutModal();
		
	});
</script>
<style type="text/css">
	.dashboard-wrapper .left-sidebar {
		margin-right: 0;
	}
	.datepicker {
		z-index: 1151;
	}
	.datepicker {
		background: white;
	}
	.form-horizontal .control-group {
		margin-bottom: 2px;
	}
</style>
<?php
	//echo "Bray...Bray...Bray".$session_id;
?>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			
			<form class="form-horizontal no-margin" id="gedung_form" name="gedung_form" method="post" action="<?php echo site_url('rawat_inap'); ?>">
				<div class="control-group">
					<label class="control-label" for="date_range2" style="text-align: left; width: 140px;">Gedung/Bangsal</label>
					<div class="controls controls-row" style="margin-left: 140px;">
						<?php
							 $value = $gedung->id;
						?>
						<select id="gedung_id" name="gedung_id" onchange="document.gedung_form.submit()">
							<?php
								foreach ($gedung_list as $val) {
									if ($value == $val->id) {
										echo "<option value=\"{$val->id}\" selected=\"selected\">{$val->nama}</option>";
									} else {
										echo "<option value=\"{$val->id}\">{$val->nama}</option>";
									}
								}
							?>
						</select>
					</div>
				</div>
			</form>
			
			<div class="widget">
				<div class="widget-header">
					<?php $title = "Bed"; ?>
					<div class="title"><?php echo $title; ?></div>
					<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
				</div>
				<div class="widget-body">
					<div id="dt_example" class="example_alt_pagination">
						<table class="table table-striped table-condensed table-striped table-hover table-bordered pull-left" id="bed">
							<thead>
								<tr>
									<th></th>
									<th>Nama Ruangan</th>
									<th>Bed</th>
									<th>Kelas</th>
									<th>Status</th>
									<th>Pelayanan RI Id</th>
									<th>Pasien Id</th>
									<th>No. Medrec</th>
									<th>Nama</th>
									<th>Jenis Kelamin</th>
									<th>Umur</th>
									<th>Dokter Id</th>
									<th>Dokter</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="13" class="dataTables_empty">Loading data from server</td>
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
<form class="form-horizontal no-margin" id="tindakan_form" name="tindakan_form" method="post" action="">
	<div class="modal hide fade" id="tindakan_modal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h4 id="title_modal">Tindakan</h4>
		</div>
		<div class="modal-body">
			<div class="row-fluid">
				<div class="span12">

					<div class="control-group">
						<label class="control-label" for="disp_tanggal_tindakan">Tanggal</label>
						<div class="controls controls-row">
							<input type="hidden" id="tanggal_tindakan" name="tanggal_tindakan" value="" />
							<input class="span4" type="text" id="disp_tanggal_tindakan" name="disp_tanggal_tindakan" data-date-format="dd/mm/yyyy" value="" />
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">No. Medrec</label>
						<div class="controls controls-row">
							<label id="tindakan_no_medrec" style="padding-top: 5px; color: #000;"></label>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">Nama</label>
						<div class="controls controls-row">
							<label id="tindakan_nama" style="padding-top: 5px; color: #000;"></label>
						</div>
					</div>
							
					<div class="control-group">
						<label class="control-label" for="tindakan_id">Tindakan</label>
						<div class="controls controls-row">
							<select class="span9" id="tindakan_id" name="tindakan_id"></select>
						</div>
					</div>
					
					<!--div class="control-group">
						<label class="control-label" for="icd_10_id">ICD 10</label>
						<div class="controls controls-row">
							<select class="span9" id="icd_10_id" name="icd_10_id"></select>
						</div>
					</div-->
					
					<div class="control-group">
						<label class="control-label" for="dokter_id">Dokter</label>
						<div class="controls controls-row">
							<select class="span9" id="dokter_id" name="dokter_id"></select>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="keterangan">Keterangan</label>
						<div class="controls controls-row">
							<textarea class="span12" id="keterangan" name="keterangan" placeholder="Keterangan"></textarea>
						</div>
					</div>
					
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-primary">Simpan</button>
			<button type="reset" class="btn" data-dismiss="modal">Batal</button>
		</div>
	</div>
	<div>
		<input type="hidden" id="tindakan_dlg_id" name="tindakan_dlg_id" value="" />
		<input type="hidden" id="tindakan_dlg_unit_id" name="tindakan_dlg_unit_id" value="" />
		<input type="hidden" id="tindakan_dlg_pelayanan_ri_id" name="tindakan_dlg_pelayanan_ri_id" value="" />
		<input type="hidden" id="tindakan_dlg_bed_id" name="tindakan_dlg_bed_id" value="" />
	</div>
</form>
<form class="form-horizontal no-margin" id="pindah_bed_form" name="pindah_bed_form" method="post" action="">
	<div class="modal hide fade" id="pindah_bed_modal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h4 id="pindah_bed_title_modal">Pindah Bed</h4>
		</div>
		<div class="modal-body">
			<div class="row-fluid">
				<div class="span12">

					<div class="control-group">
						<label class="control-label" for="disp_pindah_bed_dlg_tanggal">Tanggal</label>
						<div class="controls controls-row">
							<input type="hidden" id="pindah_bed_dlg_tanggal" name="pindah_bed_dlg_tanggal" value="" />
							<input class="span4" type="text" id="disp_pindah_bed_dlg_tanggal" name="disp_pindah_bed_dlg_tanggal" data-date-format="dd/mm/yyyy" value="" />
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">No. Medrec</label>
						<div class="controls controls-row">
							<label id="pindah_bed_dlg_no_medrec" style="padding-top: 5px; color: #000;"></label>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">Nama</label>
						<div class="controls controls-row">
							<label id="pindah_bed_dlg_nama" style="padding-top: 5px; color: #000;"></label>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">Bed Lama</label>
						<div class="controls controls-row">
							<label id="pindah_bed_dlg_bed_lama" style="padding-top: 5px; color: #000;"></label>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="pindah_bed_dlg_bed_baru_id">Pindah Ke Bed</label>
						<div class="controls controls-row">
							<select class="span9" id="pindah_bed_dlg_bed_baru_id" name="pindah_bed_dlg_bed_baru_id"></select>
							<img id="loading_pindah_bed_dlg_bed" alt="loading" src="<?php echo base_url('assets/img/loading.gif'); ?>" style="margin: 5px; display: none;" />
						</div>
					</div>
					
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-primary">Simpan</button>
			<button type="reset" class="btn" data-dismiss="modal">Batal</button>
		</div>
	</div>
	<div>
		<input type="hidden" id="pindah_bed_dlg_pelayanan_ri_id" name="pindah_bed_dlg_pelayanan_ri_id" value="" />
		<input type="hidden" id="pindah_bed_dlg_bed_lama_id" name="pindah_bed_dlg_bed_lama_id" value="" />
	</div>
</form>
<form class="form-horizontal no-margin" id="pindah_ruangan_form" name="pindah_ruangan_form" method="post" action="">
	<div class="modal hide fade" id="pindah_ruangan_modal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h4 id="pindah_ruangan_title_modal">Pindah Ruangan</h4>
		</div>
		<div class="modal-body">
			<div class="row-fluid">
				<div class="span12">

					<div class="control-group">
						<label class="control-label" for="disp_pindah_ruangan_dlg_tanggal">Tanggal</label>
						<div class="controls controls-row">
							<input type="hidden" id="pindah_ruangan_dlg_tanggal" name="pindah_ruangan_dlg_tanggal" value="" />
							<input class="span4" type="text" id="disp_pindah_ruangan_dlg_tanggal" name="disp_pindah_ruangan_dlg_tanggal" data-date-format="dd/mm/yyyy" value="" />
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">No. Medrec</label>
						<div class="controls controls-row">
							<label id="pindah_ruangan_dlg_no_medrec" style="padding-top: 5px; color: #000;"></label>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">Nama</label>
						<div class="controls controls-row">
							<label id="pindah_ruangan_dlg_nama" style="padding-top: 5px; color: #000;"></label>
						</div>
					</div>
								
					<div class="control-group">
						<label class="control-label" for="pindah_ruangan_dlg_gedung_id">Pindah Ke Ruang</label>
						<div class="controls controls-row">
							<select class="span9" id="pindah_ruangan_dlg_gedung_id" name="pindah_ruangan_dlg_gedung_id"></select>
							<img id="loading_pindah_ruangan_dlg_ruangan" alt="loading" src="<?php echo base_url('assets/img/loading.gif'); ?>" style="margin: 5px; display: none;" />
						</div>
					</div>
					
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-primary">Simpan</button>
			<button type="reset" class="btn" data-dismiss="modal">Batal</button>
		</div>
	</div>
	<div>
		<input type="hidden" id="pindah_ruangan_dlg_id" name="pindah_ruangan_dlg_id" value="" />
	</div>
</form>
<form class="form-horizontal no-margin" id="checkout_form" name="checkout_form" method="post" action="">
	<div class="modal hide fade" id="checkout_modal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h4 id="title_modal">Checkout</h4>
		</div>
		<div class="modal-body">
			<div class="row-fluid">
				<div class="span12">

					<div class="control-group">
						<label class="control-label" for="disp_tanggal_keluar">Tanggal Keluar</label>
						<div class="controls controls-row">
							<input type="hidden" id="tanggal_keluar" name="tanggal_keluar" value="" />
							<input class="span4" type="text" id="disp_tanggal_keluar" name="disp_tanggal_keluar" data-date-format="dd/mm/yyyy" value="" />
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">No. Medrec</label>
						<div class="controls controls-row">
							<label id="checkout_no_medrec" style="padding-top: 5px; color: #000;"></label>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">Nama</label>
						<div class="controls controls-row">
							<label id="checkout_nama" style="padding-top: 5px; color: #000;"></label>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label" for="keadaan_pasien_keluar">Keadaan Pasien Keluar</label>
						<div class="controls controls-row">
							<select class="span9" id="keadaan_pasien_keluar" name="keadaan_pasien_keluar"></select>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label" for="cara_pasien_keluar">Cara Pasien Keluar</label>
						<div class="controls controls-row">
							<select class="span9" id="cara_pasien_keluar" name="cara_pasien_keluar"></select>
							<img id="loading_cara_pasien_keluar" alt="loading" src="<?php echo base_url('assets/img/loading.gif'); ?>" style="margin: 5px; display: none;" />
						</div>
					</div>

				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-primary">Checkout</button>
			<button type="reset" class="btn" data-dismiss="modal">Batal</button>
		</div>
	</div>
	<div>
		<input type="hidden" id="id" name="id" value="" />
		<input type="hidden" id="bed_id" name="bed_id" value="" />
	</div>
</form>