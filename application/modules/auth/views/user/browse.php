<script type="text/javascript">
	$(document).ready(function () {
		
		var oTable = $('#user').dataTable({
			"sPaginationType"	: "full_numbers",
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?php echo site_url('auth/user/load_data'); ?>",
			"aoColumnDefs"		: [
									  { "bSortable": false, "aTargets": [ 2 ] }
								  ],
			"aoColumns"			: [
									  { sWidth: '10%' },
									  { sWidth: '20%' },
									  { sWidth: '20%' },
									  { sWidth: '10%' },
									  { sWidth: '20%' },
									  { sWidth: '10%' }
								  ],
			"bStateSave": true
		});
		
		$("#tambah_button").on("click", function () {
			$.getJSON("<?php echo site_url('auth/user/get_data'); ?>?user_id=0", function(json) {
				$("#title_modal").text("Tambah User");
				
				$("#name").val(json.data.name);
				$("#username").val(json.data.username);
				$("#email").val(json.data.email);
				$("#password").val('');
				$("#konfirmasi_password").val('');
				
				var option_role = '';
				option_role += '<option value="0" selected="selected">- Pilih Role -</option>';
				for (var i = 0; i < json.role_list.length; i++) {
					if (json.data.role === (i + 1)) {
						option_role += '<option value="' + (i + 1) + '" selected="selected">' + json.role_list[i] + '</option>';
					}
					else {
						option_role += '<option value="' + (i + 1) + '">' + json.role_list[i] + '</option>';
					}
				}
				$("#role").html(option_role);
				
				switch (parseInt(json.data.role)) {
					case 1:
						$("#unit_kerja").hide();
						$("#ruang").hide();
						break;
					case 2:
						$("#unit_kerja").hide();
						$("#ruang").hide();
						break;
					case 3:
						$("#unit_kerja").show();
						$("#ruang").show();
						break;
					default:
						$("#unit_kerja").hide();
						$("#ruang").hide();
				}
				
				var option_unit = '';
				option_unit += '<option value="0" selected="selected">- Pilih Unit -</option>';
				for (var i = 0; i < json.unit_list.length; i++) {
					if (json.data.unit_kerja_id === (i + 1)) {
						option_unit += '<option value="' + (i + 1) + '" selected="selected">' + json.unit_list[i] + '</option>';
					}
					else {
						option_unit += '<option value="' + (i + 1) + '">' + json.unit_list[i] + '</option>';
					}
				}
				$("#unit_kerja_id").html(option_unit);
				
				checkUnit(json.data.unit_kerja_id);
				
				var option_ruang = '';
				option_ruang += '<option value="0" selected="selected">- Pilih Ruang -</option>';
				for (var i = 0; i < json.gedung_list.length; i++) {
					if (json.data.gedung_id === json.gedung_list[i].id) {
						option_ruang += '<option value="' + json.gedung_list[i].id + '" selected="selected">' + json.gedung_list[i].nama + '</option>';
					}
					else {
						option_ruang += '<option value="' + json.gedung_list[i].id + '">' + json.gedung_list[i].nama + '</option>';
					}
				}
				$("#gedung_id").html(option_ruang);
				
				$("#id").val(json.data.id);
				
				$('#user_modal').modal('show');
			});
			return false;
		});
		
		$('#user').on('click', '.edit-row', function() {
			var user_id = $(this).data('id');
			$.getJSON("<?php echo site_url('auth/user/get_data'); ?>?user_id=" + user_id, function(json) {
				$("#title_modal").text("Edit User");
				
				$("#name").val(json.data.name);
				$("#username").val(json.data.username);
				$("#email").val(json.data.email);
				$("#password").val('');
				$("#konfirmasi_password").val('');
				
				var option_role = '';
				option_role += '<option value="0" selected="selected">- Pilih Role -</option>';
				for (var i = 0; i < json.role_list.length; i++) {
					if (json.data.role === (i + 1)) {
						option_role += '<option value="' + (i + 1) + '" selected="selected">' + json.role_list[i] + '</option>';
					}
					else {
						option_role += '<option value="' + (i + 1) + '">' + json.role_list[i] + '</option>';
					}
				}
				$("#role").html(option_role);
				
				switch (parseInt(json.data.role)) {
					case 1:
						$("#unit_kerja").hide();
						$("#ruang").hide();
						break;
					case 2:
						$("#unit_kerja").hide();
						$("#ruang").hide();
						break;
					case 3:
						$("#unit_kerja").show();
						$("#ruang").show();
						break;
					default:
				}
				
				$("#id").val(json.data.id);
				
				$('#user_modal').modal('show');
			});
			return false;
		});
		
		$('#user').on('click', '.delete-row', function() {
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
						url: '<?php echo site_url('auth/user/delete'); ?>',
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
		
		function checkRole(role) {
			switch (parseInt(role)) {
				case 1:
					$("#unit_kerja").hide();
					$("#ruang").hide();
					break;
				case 2:
					$("#unit_kerja").hide();
					$("#ruang").hide();
					break;
				case 3:
					$("#unit_kerja").show();
					var unit = $("#unit_kerja_id").val();
					checkUnit(unit);
					break;
				default:
					$("#unit_kerja").hide();
					$("#ruang").hide();
			}
		}
		
		$("#role").change(function() {
			var role = $("#role").val();
			checkRole(role);
		});
		
		function checkUnit(unit) {
			if (parseInt(unit) === 3) {
				$("#ruang").show();
			}
			else {
				$("#ruang").hide();
			}
		}
		
		$("#unit_kerja_id").change(function() {
			var unit = $("#unit_kerja_id").val();
			checkUnit(unit);
		});
		
		var userApp = {
			initUserModal: function () {
				$("#user_form").validate({
					rules: {
						name: { required: true, minlength: 1 },
						username: { required: true, minlength: 1 },
						email: { required: true, minlength: 1 },
						password: { required: true, minlength: 1 },
						konfirmasi_password: {
							equalTo: "#password",
							required: true, 
							minlength: 1 
						},
						role: { required: true, min: 1 }
					},
					messages: {
						nama: "Nama User diperlukan.",
						username: "Username diperlukan.",
						email: "Email diperlukan.",
						password: "Password diperlukan.",
						konfirmasi_password: "Konfirmasi Password tidak sama dengan password",
						role: "Role diperlukan"
					},
					errorContainer: "#validationSummary",
					errorLabelContainer: "#validationSummary ul",
					wrapper: "li",
					submitHandler: function (form) {
						userApp.addUser($(form));
					}
				});
			},
			addUser: function (form) {
				var url = "<?php echo site_url('auth/user/simpan'); ?>";
				var postData = form.serialize();
				$.post(url, postData, function (data) {
					if (data.toLowerCase() === "ok") {
						oTable.fnDraw();
						alertify.success("Record telah di simpan!");
						return;
					}
					//alert("Something went wrong. Please retry!");
				});
				$('#user_modal').modal('hide');
			}
		};
		
		userApp.initUserModal();
		
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
	.form-horizontal .control-group {
		margin-bottom: 2px;
	}
	#user_processing {
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
					<div class="title">User</div>
					<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
				</div>
				<div class="form-actions no-margin">
					<a id="tambah_button" href="#" class="btn btn-info bottom-margin pull-right" data-original-title="">Tambah</a>
					<div class="clearfix"></div>
				</div>
				<div class="widget-body">
					<div id="dt_example" class="example_alt_pagination">
						<table class="table table-striped table-condensed table-striped table-hover table-bordered pull-left" id="user">
							<thead>
								<tr>
									<th>Nama</th>
									<th>Username</th>
									<th>Email</th>
									<th>Role</th>
									<th>Terdaftar</th>
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
<form class="form-horizontal no-margin" id="user_form" name="user_form" method="post" action="">
	<div class="modal fade" id="user_modal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h4 id="title_modal">Tambah/Edit User</h4>
		</div>
		<div class="modal-body">
			<div class="modal-body">
				<div class="row-fluid">
					<div class="span12">

						<div class="control-group">
							<label class="control-label" for="name">Nama</label>
							<div class="controls controls-row">
								<input class="span12" type="text" id="name" name="name" maxlength="100" placeholder="Nama User" value="" autocomplete="off" />
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="username">Username</label>
							<div class="controls controls-row">
								<input class="span12" type="text" id="username" name="username" maxlength="255" placeholder="Username" value="" autocomplete="off" />
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="email">Email</label>
							<div class="controls controls-row">
								<input class="span12" type="text" id="email" name="email" maxlength="255" placeholder="Email" value="" autocomplete="off" />
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="password">Password</label>
							<div class="controls controls-row">
								<input class="span12" type="password" id="password" name="password" maxlength="255" placeholder="" value="" autocomplete="off" />
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="konfirmasi_password">Konfirmasi Password</label>
							<div class="controls controls-row">
								<input class="span12" type="password" id="konfirmasi_password" name="konfirmasi_password" maxlength="255" placeholder="" value="" autocomplete="off" />
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="role">Role</label>
							<div class="controls controls-row">
								<select id="role" name="role" class="span12">
								</select>
							</div>
						</div>
						
						<div id="unit_kerja" class="control-group">
							<label class="control-label" for="unit_kerja_id">Unit Kerja</label>
							<div class="controls controls-row">
								<select id="unit_kerja_id" name="unit_kerja_id" class="span12">
								</select>
							</div>
						</div>
						
						<div id="ruang" class="control-group">
							<label class="control-label" for="gedung_id">Ruang</label>
							<div class="controls controls-row">
								<select id="gedung_id" name="gedung_id" class="span12">
								</select>
							</div>
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
		<input type="hidden" id="id" name="id" value="" />
	</div>
</form>