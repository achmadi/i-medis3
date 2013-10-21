<script type="text/javascript">
    $(document).ready(function () {
        $('#harga_obat').dataTable({
            "sPaginationType"           : "full_numbers",
            "bProcessing"		: true,
            "bServerSide"		: true,
            "sAjaxSource"		: "<?php echo site_url('master/harga_obat/load_data'); ?>",
            "aoColumnDefs"		: [
                                            { "bSortable": false, "aTargets": [ 2 ] }
                                            ],
            "aoColumns"			: [
                                            { sWidth: '50%' },
                                            { sWidth: '40%' },
                                            { sWidth: '10%' }
                                            ]
        });
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
</style>
<div class="left-sidebar">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget">
                <div class="widget-header">
                    <div class="title">Harga Obat</div>
                    <span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
                </div>
                <div class="form-actions no-margin">
                    <a id="tambah" class="btn btn-info bottom-margin pull-right" href="<?php echo site_url('master/harga_obat/add'); ?>" data-original-title="">Tambah</a>
                    <div class="clearfix"></div>
                </div>
                <div class="widget-body">
                    <div id="dt_example" class="example_alt_pagination">
                        <table class="table table-striped table-condensed table-striped table-hover table-bordered pull-left" id="harga_obat">
                            <thead>
                                <tr>
                                    <th>Nama Obat</th>
                                    <th>Harga Pokok</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="8" class="dataTables_empty">Loading data from server</td>
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
