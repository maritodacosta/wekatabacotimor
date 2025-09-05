<?php
$comp_model = new SharedController;
$page_element_id = "view-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
//Page Data Information from Controller
$data = $this->view_data;
//$rec_id = $data['__tableprimarykey'];
$page_id = $this->route->page_id; //Page id from url
$view_title = $this->view_title;
$show_header = $this->show_header;
$show_edit_btn = $this->show_edit_btn;
$show_delete_btn = $this->show_delete_btn;
$show_export_btn = $this->show_export_btn;
?>
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="view"  data-display-type="table" data-page-url="<?php print_link($current_page); ?>">
    <?php
    if( $show_header == true ){
    ?>
    <div  class="bg-light p-3 mb-3">
        <div class="container">
            <div class="row ">
                <div class="col ">
                    <h4 class="record-title">View  Tagihan</h4>
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
                <div class="col-md-12 comp-grid">
                    <?php $this :: display_page_errors(); ?>
                    <div  class="card animated fadeIn page-content">
                        <?php
                        $counter = 0;
                        if(!empty($data)){
                        $rec_id = (!empty($data['']) ? urlencode($data['']) : null);
                        $counter++;
                        ?>
                        <div id="page-report-body" class="">
                            <table class="table table-hover table-borderless table-striped">
                                <!-- Table Body Start -->
                                <tbody class="page-data" id="page-data-<?php echo $page_element_id; ?>">
                                    <tr  class="td-id_transaksi">
                                        <th class="title"> Id Transaksi: </th>
                                        <td class="value"> <?php echo $data['id_transaksi']; ?></td>
                                    </tr>
                                    <tr  class="td-alamat_transaksi">
                                        <th class="title"> Alamat Transaksi: </th>
                                        <td class="value"> <?php echo $data['alamat_transaksi']; ?></td>
                                    </tr>
                                    <tr  class="td-pengelolah_transaksi">
                                        <th class="title"> Pengelolah Transaksi: </th>
                                        <td class="value"> <?php echo $data['pengelolah_transaksi']; ?></td>
                                    </tr>
                                    <tr  class="td-tanggal_transaksi">
                                        <th class="title"> Tanggal Transaksi: </th>
                                        <td class="value"> <?php echo $data['tanggal_transaksi']; ?></td>
                                    </tr>
                                    <tr  class="td-keterangan_transaksi">
                                        <th class="title"> Keterangan Transaksi: </th>
                                        <td class="value"> <?php echo $data['keterangan_transaksi']; ?></td>
                                    </tr>
                                    <tr  class="td-jual_transaksi">
                                        <th class="title"> Jual Transaksi: </th>
                                        <td class="value"> <?php echo $data['jual_transaksi']; ?></td>
                                    </tr>
                                    <tr  class="td-bonus_transaksi">
                                        <th class="title"> Bonus Transaksi: </th>
                                        <td class="value"> <?php echo $data['bonus_transaksi']; ?></td>
                                    </tr>
                                    <tr  class="td-bayar_transaksi">
                                        <th class="title"> Bayar Transaksi: </th>
                                        <td class="value"> <?php echo $data['bayar_transaksi']; ?></td>
                                    </tr>
                                    <tr  class="td-setor_tagihan">
                                        <th class="title"> Setor Tagihan: </th>
                                        <td class="value"> <?php echo $data['setor_tagihan']; ?></td>
                                    </tr>
                                </tbody>
                                <!-- Table Body End -->
                            </table>
                        </div>
                        <div class="p-3 d-flex">
                            <div class="dropup export-btn-holder mx-1">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-save"></i> Export
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <?php $export_print_link = $this->set_current_page_link(array('format' => 'print')); ?>
                                    <a class="dropdown-item export-link-btn" data-format="print" href="<?php print_link($export_print_link); ?>" target="_blank">
                                        <img src="<?php print_link('assets/images/print.png') ?>" class="mr-2" /> PRINT
                                        </a>
                                        <?php $export_pdf_link = $this->set_current_page_link(array('format' => 'pdf')); ?>
                                        <a class="dropdown-item export-link-btn" data-format="pdf" href="<?php print_link($export_pdf_link); ?>" target="_blank">
                                            <img src="<?php print_link('assets/images/pdf.png') ?>" class="mr-2" /> PDF
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                else{
                                ?>
                                <!-- Empty Record Message -->
                                <div class="text-muted p-3">
                                    <i class="fa fa-ban"></i> No Record Found
                                </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
