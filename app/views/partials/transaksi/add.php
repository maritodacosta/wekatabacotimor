<?php
$comp_model = new SharedController;
$page_element_id = "add-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
$show_header = $this->show_header;
$view_title = $this->view_title;
$redirect_to = $this->redirect_to;
?>
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="add"  data-display-type="" data-page-url="<?php print_link($current_page); ?>">
    <?php
    if( $show_header == true ){
    ?>
    <div  class="bg-light p-3 mb-3">
        <div class="container">
            <div class="row ">
                <div class="col ">
                    <h4 class="record-title">Add New Transaksi</h4>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
    <div  class="">
        <div class="container">
            <div class="row ">
                <div class="col-md-7 comp-grid">
                    <?php $this :: display_page_errors(); ?>
                    <div  class="bg-light p-3 animated fadeIn page-content">
                        <form id="transaksi-add-form" role="form" novalidate enctype="multipart/form-data" class="form page-form form-horizontal needs-validation" action="<?php print_link("transaksi/add?csrf_token=$csrf_token") ?>" method="post">
                            <div>
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="alamat_transaksi">Alamat Transaksi <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input id="ctrl-alamat_transaksi"  value="<?php  echo $this->set_field_value('alamat_transaksi',""); ?>" type="text" placeholder="Enter Alamat Transaksi"  required="" name="alamat_transaksi"  class="form-control " />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label" for="pengelolah_transaksi">Pengelolah Transaksi <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="">
                                                    <input id="ctrl-pengelolah_transaksi"  value="<?php  echo $this->set_field_value('pengelolah_transaksi',""); ?>" type="text" placeholder="Enter Pengelolah Transaksi"  required="" name="pengelolah_transaksi"  class="form-control " />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="control-label" for="tanggal_transaksi">Tanggal Transaksi <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <input id="ctrl-tanggal_transaksi" class="form-control datepicker  datepicker"  required="" value="<?php  echo $this->set_field_value('tanggal_transaksi',date_now()); ?>" type="datetime" name="tanggal_transaksi" placeholder="Enter Tanggal Transaksi" data-enable-time="false" data-min-date="" data-max-date="" data-date-format="Y-m-d" data-alt-format="d-m-Y" data-inline="false" data-no-calendar="false" data-mode="single" />
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label class="control-label" for="keterangan_transaksi">Keterangan Transaksi <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="">
                                                            <input id="ctrl-keterangan_transaksi"  value="<?php  echo $this->set_field_value('keterangan_transaksi',""); ?>" type="text" placeholder="Enter Keterangan Transaksi"  required="" name="keterangan_transaksi"  class="form-control " />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group ">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <label class="control-label" for="jual_transaksi">Jual Slop <span class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <div class="">
                                                                <input id="ctrl-jual_transaksi"  value="<?php  echo $this->set_field_value('jual_transaksi',""); ?>" type="number" placeholder="Enter Jual Slop" step="1"  required="" name="jual_transaksi"  class="form-control " />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group ">
                                                        <div class="row">
                                                            <div class="col-sm-4">
                                                                <label class="control-label" for="bonus_transaksi">Bonus Slop </label>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="">
                                                                    <input id="ctrl-bonus_transaksi"  value="<?php  echo $this->set_field_value('bonus_transaksi',""); ?>" type="number" placeholder="Enter Bonus Slop" step="1"  name="bonus_transaksi"  class="form-control " />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group ">
                                                            <div class="row">
                                                                <div class="col-sm-4">
                                                                    <label class="control-label" for="bayar_transaksi">Bayar Transaksi </label>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <div class="">
                                                                        <input id="ctrl-bayar_transaksi"  value="<?php  echo $this->set_field_value('bayar_transaksi',""); ?>" type="text" placeholder="Enter Bayar Transaksi"  name="bayar_transaksi"  class="form-control " />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group ">
                                                                <div class="row">
                                                                    <div class="col-sm-4">
                                                                        <label class="control-label" for="setor_tagihan">Setor Tagihan </label>
                                                                    </div>
                                                                    <div class="col-sm-8">
                                                                        <div class="">
                                                                            <input id="ctrl-setor_tagihan"  value="<?php  echo $this->set_field_value('setor_tagihan',""); ?>" type="text" placeholder="Enter Setor Tagihan"  name="setor_tagihan"  class="form-control " />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-submit-btn-holder text-center mt-3">
                                                                <div class="form-ajax-status"></div>
                                                                <button class="btn btn-primary" type="submit">
                                                                    Simpan
                                                                    <i class="fa fa-send"></i>
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
