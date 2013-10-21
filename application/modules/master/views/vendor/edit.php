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
                        $url = site_url('master/vendor/add');
                        $title = "Tambah Vendor";
                    } else {
                        $url = site_url('master/vendor/edit/'.$data->vendor_id);
                        $title = "Edit Vendor";
                    }
                ?>
                <form class="form-horizontal no-margin" id="vendor_form" name="vendor_form" method="post" action="<?php echo $url; ?>">
                    <div class="widget-header">
                        <div class="title"><?php echo $title; ?></div>
                        <span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
                    </div>
                    <div class="widget-body">
                        <div class="container-fluid">
                            <div class="row-fluid">

                                <div class="span6">

                                    <div class="control-group">
                                        <label class="control-label" for="vendor_nama">Nama Vendor</label>
                                        <div class="controls controls-row">
                                            <?php
                                                $value = set_value('vendor_nama', $data->vendor_nama);
                                            ?>
                                            <input class="span12" type="text" id="vendor_nama" name="vendor_nama" maxlength="50" placeholder="Nama Vendor" value="<?php echo $value; ?>">
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="vendor_telp">Nomor Telepon</label>
                                        <div class="controls controls-row">
                                            <?php
                                                $value = set_value('telp', $data->vendor_telp);
                                            ?>
                                            <input class="span6" type="text" id="vendor_telp" name="vendor_telp" maxlength="15" placeholder="Nomor Telepon" value="<?php echo $value; ?>">
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="vendor_alamat">Alamat</label>
                                        <div class="controls controls-row">
                                            <?php
                                                $value = set_value('vendor_alamat', $data->vendor_alamat);
                                            ?>
                                            <textarea placeholder="Alamat" name="vendor_alamat" id="vendor_alamat" class="span12"><?php echo $value; ?></textarea>
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
                                $value = set_value('vendor_id', $data->vendor_id);
                        ?>
                        <input type="hidden" id="vendor_id" name="vendor_id" value="<?php echo $value; ?>" />
                </form>
            </div>
        </div>
    </div>
</div>