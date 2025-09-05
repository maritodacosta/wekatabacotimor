<?php 
/**
 * Laporan_tagihan Page Controller
 * @category  Controller
 */
class Laporan_tagihanController extends BaseController{
	function __construct(){
		parent::__construct();
		$this->tablename = "laporan_tagihan";
	}
	/**
     * List page records
     * @param $fieldname (filter record by a field) 
     * @param $fieldvalue (filter field value)
     * @return BaseView
     */
	function index($fieldname = null , $fieldvalue = null){
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array("pengelolah", 
			"alamat_pengelolah", 
			"jual", 
			"bonus", 
			"bayar", 
			"jual*9-bayar AS minus", 
			"jual*9-setor AS tagihan", 
			"setor");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				laporan_tagihan.id_penjualan LIKE ? OR 
				laporan_tagihan.pengelolah LIKE ? OR 
				laporan_tagihan.alamat_pengelolah LIKE ? OR 
				laporan_tagihan.jual LIKE ? OR 
				laporan_tagihan.bonus LIKE ? OR 
				laporan_tagihan.bayar LIKE ? OR 
				jual*9-bayar LIKE ? OR 
				jual*9-setor LIKE ? OR 
				laporan_tagihan.setor LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "laporan_tagihan/search.php";
		}
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("laporan_tagihan.pengelolah", ORDER_TYPE);
		}
		$db->where("jual*9-setor<>0.00");
		if($fieldname){
			$db->where($fieldname , $fieldvalue); //filter by a single field name
		}
		$tc = $db->withTotalCount();
		$records = $db->get($tablename, $pagination, $fields);
		$records_count = count($records);
		$total_records = intval($tc->totalCount);
		$page_limit = $pagination[1];
		$total_pages = ceil($total_records / $page_limit);
		$data = new stdClass;
		$data->records = $records;
		$data->record_count = $records_count;
		$data->total_records = $total_records;
		$data->total_page = $total_pages;
		if($db->getLastError()){
			$this->set_page_error();
		}
		$page_title = $this->view->page_title = "Laporan Tagihan";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("laporan_tagihan/list.php", $data); //render the full page
	}
}
