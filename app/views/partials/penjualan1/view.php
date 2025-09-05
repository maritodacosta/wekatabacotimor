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
                    <h4 class="record-title">View  Penjualan1</h4>
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
                        $rec_id = (!empty($data['id_penjualan']) ? urlencode($data['id_penjualan']) : null);
                        $counter++;
                        ?>
                        <div id="page-report-body" class="">
                            <table class="table table-hover table-borderless table-striped">
                                <!-- Table Body Start -->
                                <tbody class="page-data" id="page-data-<?php echo $page_element_id; ?>">
                                    <tr  class="td-id_penjualan">
                                        <th class="title"> Id Penjualan: </th>
                                        <td class="value"> <?php echo $data['id_penjualan']; ?></td>
                                    </tr>
                                    <tr  class="td-pengelolah">
                                        <th class="title"> Pengelolah: </th>
                                        <td class="value"> <?php echo $data['pengelolah']; ?></td>
                                    </tr>
                                    <tr  class="td-alamat_pengelolah">
                                        <th class="title"> Alamat Pengelolah: </th>
                                        <td class="value"> <?php echo $data['alamat_pengelolah']; ?></td>
                                    </tr>
                                    <tr  class="td-tanggal">
                                        <th class="title"> Tanggal: </th>
                                        <td class="value"> <?php echo $data['tanggal']; ?></td>
                                    </tr>
                                    <tr  class="td-nama_pelanggang">
                                        <th class="title"> Nama Pelanggang: </th>
                                        <td class="value"> <?php echo $data['nama_pelanggang']; ?></td>
                                    </tr>
                                    <tr  class="td-kode_pelanggang">
                                        <th class="title"> Kode Pelanggang: </th>
                                        <td class="value"> <?php echo $data['kode_pelanggang']; ?></td>
                                    </tr>
                                    <tr  class="td-hp_pelanggang">
                                        <th class="title"> Hp Pelanggang: </th>
                                        <td class="value"> <?php echo $data['hp_pelanggang']; ?></td>
                                    </tr>
                                    <tr  class="td-jual">
                                        <th class="title"> Jual: </th>
                                        <td class="value"> <?php echo $data['jual']; ?></td>
                                    </tr>
                                    <tr  class="td-bonus">
                                        <th class="title"> Bonus: </th>
                                        <td class="value"> <?php echo $data['bonus']; ?></td>
                                    </tr>
                                    <tr  class="td-bayar">
                                        <th class="title"> Bayar: </th>
                                        <td class="value"> <?php echo $data['bayar']; ?></td>
                                    </tr>
                                    <tr  class="td-setor">
                                        <th class="title"> Setor: </th>
                                        <td class="value"> <?php echo $data['setor']; ?></td>
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
                                    <a class="btn btn-sm btn-info"  href="<?php print_link("penjualan1/edit/$rec_id"); ?>">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <a class="btn btn-sm btn-danger record-delete-btn mx-1"  href="<?php print_link("penjualan1/delete/$rec_id/?csrf_token=$csrf_token&redirect=$current_page"); ?>" data-prompt-msg="Yakin untuk menghapus?" data-display-style="modal">
                                        <i class="fa fa-times"></i> Delete
                                    </a>
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
