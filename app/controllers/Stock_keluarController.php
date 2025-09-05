<?php 
/**
 * Stock_keluar Page Controller
 * @category  Controller
 */
class Stock_keluarController extends BaseController{
	function __construct(){
		parent::__construct();
		$this->tablename = "stock_keluar";
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
		$fields = array("id_stock_keluar", 
			"tanggal", 
			"keterangan", 
			"jual", 
			"bonus", 
			"bayar");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				stock_keluar.id_stock_keluar LIKE ? OR 
				stock_keluar.tanggal LIKE ? OR 
				stock_keluar.keterangan LIKE ? OR 
				stock_keluar.jual LIKE ? OR 
				stock_keluar.bonus LIKE ? OR 
				stock_keluar.bayar LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "stock_keluar/search.php";
		}
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("stock_keluar.id_stock_keluar", ORDER_TYPE);
		}
		if($fieldname){
			$db->where($fieldname , $fieldvalue); //filter by a single field name
		}
		$tc = $db->withTotalCount();
		$records = $db->get($tablename, $pagination, $fields);
		$records_count = count($records);
		$total_records = intval($tc->totalCount);
		$page_limit = $pagination[1];
		$total_pages = ceil($total_records / $page_limit);
		if(	!empty($records)){
			foreach($records as &$record){
				$record['tanggal'] = format_date($record['tanggal'],'d-m-Y');
			}
		}
		$data = new stdClass;
		$data->records = $records;
		$data->record_count = $records_count;
		$data->total_records = $total_records;
		$data->total_page = $total_pages;
		if($db->getLastError()){
			$this->set_page_error();
		}
		$page_title = $this->view->page_title = "Stock Keluar";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("stock_keluar/list.php", $data); //render the full page
	}
	/**
     * Insert new record to the database table
	 * @param $formdata array() from $_POST
     * @return BaseView
     */
	function add($formdata = null){
		if($formdata){
			$db = $this->GetModel();
			$tablename = $this->tablename;
			$request = $this->request;
			//fillable fields
			$fields = $this->fields = array("tanggal","keterangan","jual","bonus","bayar");
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'tanggal' => 'required',
				'keterangan' => 'required',
				'jual' => 'required|numeric',
				'bonus' => 'required|numeric',
				'bayar' => 'required',
			);
			$this->sanitize_array = array(
				'tanggal' => 'sanitize_string',
				'keterangan' => 'sanitize_string',
				'jual' => 'sanitize_string',
				'bonus' => 'sanitize_string',
				'bayar' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
				if($rec_id){
					$this->set_flash_msg("Data sudah tersimpan", "success");
					return	$this->redirect("stock_keluar");
				}
				else{
					$this->set_page_error();
				}
			}
		}
		$page_title = $this->view->page_title = "Add New Stock Keluar";
		$this->render_view("stock_keluar/add.php");
	}
	/**
     * Update table record with formdata
	 * @param $rec_id (select record by table primary key)
	 * @param $formdata array() from $_POST
     * @return array
     */
	function edit($rec_id = null, $formdata = null){
		$request = $this->request;
		$db = $this->GetModel();
		$this->rec_id = $rec_id;
		$tablename = $this->tablename;
		 //editable fields
		$fields = $this->fields = array("id_stock_keluar","tanggal","keterangan","jual","bonus","bayar");
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'tanggal' => 'required',
				'keterangan' => 'required',
				'jual' => 'required|numeric',
				'bonus' => 'required|numeric',
				'bayar' => 'required',
			);
			$this->sanitize_array = array(
				'tanggal' => 'sanitize_string',
				'keterangan' => 'sanitize_string',
				'jual' => 'sanitize_string',
				'bonus' => 'sanitize_string',
				'bayar' => 'sanitize_string',
			);
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$db->where("stock_keluar.id_stock_keluar", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if($bool && $numRows){
					$this->set_flash_msg("Data sudah terupdate", "success");
					return $this->redirect("stock_keluar");
				}
				else{
					if($db->getLastError()){
						$this->set_page_error();
					}
					elseif(!$numRows){
						//not an error, but no record was updated
						$page_error = "No record updated";
						$this->set_page_error($page_error);
						$this->set_flash_msg($page_error, "warning");
						return	$this->redirect("stock_keluar");
					}
				}
			}
		}
		$db->where("stock_keluar.id_stock_keluar", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = "Edit  Stock Keluar";
		if(!$data){
			$this->set_page_error();
		}
		return $this->render_view("stock_keluar/edit.php", $data);
	}
	/**
     * Delete record from the database
	 * Support multi delete by separating record id by comma.
     * @return BaseView
     */
	function delete($rec_id = null){
		Csrf::cross_check();
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$this->rec_id = $rec_id;
		//form multiple delete, split record id separated by comma into array
		$arr_rec_id = array_map('trim', explode(",", $rec_id));
		$db->where("stock_keluar.id_stock_keluar", $arr_rec_id, "in");
		$bool = $db->delete($tablename);
		if($bool){
			$this->set_flash_msg("Data sudah terhapus", "success");
		}
		elseif($db->getLastError()){
			$page_error = $db->getLastError();
			$this->set_flash_msg($page_error, "danger");
		}
		return	$this->redirect("stock_keluar");
	}
}
