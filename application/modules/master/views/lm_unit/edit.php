<style type="text/css">
    .dashboard-wrapper {
        margin-bottom: 10px;
    }
    .dashboard-wrapper .left-sidebar {
        margin-right: 0;
    }
    .dashboard-wrapper .left-sidebar .widget {
        margin-bottom: 0;
    }
    .dashboard-wrapper .left-sidebar .widget .widget-header {
        padding: 5px;
    }
    .dashboard-wrapper .left-sidebar .widget .widget-body {
        border-bottom: 0;
    }
    .form-actions {
        margin-bottom: 0;
        margin-top: 0;
        padding: 5px;
    }
    .form-horizontal .control-group {
        margin-bottom: 4px;
    }

    input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"] {
        font-size: 12px;
    }
    label, input, button, select, textarea {
        font-size: 12px;
    }
    input[type="text"], 
    input[type="password"], 
    input[type="datetime"], 
    input[type="datetime-local"], 
    input[type="date"], 
    input[type="month"], 
    input[type="time"], 
    input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"] {
        padding: 2px 4px;
    }
    hr {
        margin: 1px 0 5px;
    }
</style>
<div class="left-sidebar">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget">
                <?php
                    if ($is_new) {
                        $url = site_url('master/lm_unit/add');
                        $title = "Tambah Satuan Besar";
                    } else {
                        $url = site_url('master/lm_unit/edit/'.$data->satuan_besar_ref_id);
                        $title = "Edit Satuan Besar";
                    }
                ?>
                <form class="form-horizontal no-margin" id="lm_unit_form" name="lm_unit_form" method="post" action="<?php echo $url; ?>">
                    <div class="widget-header">
                        <div class="title"><?php echo $title; ?></div>
                        <span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
                    </div>
                    <div class="widget-body">
                        <div class="container-fluid">
                            <div class="row-fluid">

                                <div class="span6">

                                    <div class="control-group">
                                        <label class="control-label" for="satuan_besar_ref_kode">Kode</label>
                                        <div class="controls controls-row">
                                            <?php
                                                $value = set_value('satuan_besar_ref_kode', $data->satuan_besar_ref_kode);
                                            ?>
                                            <input class="span12" type="text" id="satuan_besar_ref_kode" name="satuan_besar_ref_kode" maxlength="50" placeholder="Kode Satuan" value="<?php echo $value; ?>">
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="satuan_besar_ref_nama">Nama Satuan</label>
                                        <div class="controls controls-row">
                                            <?php
                                                $value = set_value('satuan_besar_ref_nama', $data->satuan_besar_ref_nama);
                                            ?>
                                            <input class="span6" type="text" id="satuan_besar_ref_nama" name="satuan_besar_ref_nama" maxlength="15" placeholder="Nama Satuan" value="<?php echo $value; ?>">
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="form-actions" style="text-align: right;">
                                <button class="btn btn-info" type="submit" name="simpan" value="Simpan">Simpan</button>
                                <button class="btn" type="submit" name="batal" value="Batal">Batal</button>
                            </div>
                        </div>
                    </div>
                        <?php
                                $value = set_value('satuan_besar_ref_id', $data->satuan_besar_ref_id);
                        ?>
                        <input type="hidden" id="satuan_besar_ref_id" name="satuan_besar_ref_id" value="<?php echo $value; ?>" />
                </form>
            </div>
        </div>
    </div>
</div>