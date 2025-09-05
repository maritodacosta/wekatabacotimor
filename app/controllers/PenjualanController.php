<?php 
/**
 * Penjualan Page Controller
 * @category  Controller
 */
class PenjualanController extends BaseController{
	function __construct(){
		parent::__construct();
		$this->tablename = "penjualan";
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
		$fields = array("id_penjualan", 
			"tanggal", 
			"nama_pelanggang", 
			"kode_pelanggang", 
			"hp_pelanggang", 
			"jual", 
			"bonus", 
			"bayar");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				penjualan.id_penjualan LIKE ? OR 
				penjualan.pengelolah LIKE ? OR 
				penjualan.alamat_pengelolah LIKE ? OR 
				penjualan.tanggal LIKE ? OR 
				penjualan.nama_pelanggang LIKE ? OR 
				penjualan.kode_pelanggang LIKE ? OR 
				penjualan.hp_pelanggang LIKE ? OR 
				penjualan.jual LIKE ? OR 
				penjualan.bonus LIKE ? OR 
				penjualan.bayar LIKE ? OR 
				penjualan.setor LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "penjualan/search.php";
		}
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("penjualan.id_penjualan", ORDER_TYPE);
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
				$record['tanggal'] = format_date($record['tanggal'],'d/m/Y');
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
		$page_title = $this->view->page_title = "Penjualan";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("penjualan/list.php", $data); //render the full page
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
			$fields = $this->fields = array("pengelolah","alamat_pengelolah","tanggal","nama_pelanggang","kode_pelanggang","hp_pelanggang","jual","bonus","bayar","setor");
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'pengelolah' => 'required',
				'alamat_pengelolah' => 'required',
				'tanggal' => 'required',
				'nama_pelanggang' => 'required',
				'kode_pelanggang' => 'required',
				'hp_pelanggang' => 'required',
				'jual' => 'required|numeric',
				'bonus' => 'numeric',
			);
			$this->sanitize_array = array(
				'pengelolah' => 'sanitize_string',
				'alamat_pengelolah' => 'sanitize_string',
				'tanggal' => 'sanitize_string',
				'nama_pelanggang' => 'sanitize_string',
				'kode_pelanggang' => 'sanitize_string',
				'hp_pelanggang' => 'sanitize_string',
				'jual' => 'sanitize_string',
				'bonus' => 'sanitize_string',
				'bayar' => 'sanitize_string',
				'setor' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
				if($rec_id){
					$this->set_flash_msg("Data sudah tersimpan", "success");
					return	$this->redirect("penjualan");
				}
				else{
					$this->set_page_error();
				}
			}
		}
		$page_title = $this->view->page_title = "Add New Penjualan";
		$this->render_view("penjualan/add.php");
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
		$fields = $this->fields = array("id_penjualan","pengelolah","alamat_pengelolah","tanggal","nama_pelanggang","kode_pelanggang","hp_pelanggang","jual","bonus","bayar","setor");
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'pengelolah' => 'required',
				'alamat_pengelolah' => 'required',
				'tanggal' => 'required',
				'nama_pelanggang' => 'required',
				'kode_pelanggang' => 'required',
				'hp_pelanggang' => 'required',
				'jual' => 'required|numeric',
				'bonus' => 'numeric',
			);
			$this->sanitize_array = array(
				'pengelolah' => 'sanitize_string',
				'alamat_pengelolah' => 'sanitize_string',
				'tanggal' => 'sanitize_string',
				'nama_pelanggang' => 'sanitize_string',
				'kode_pelanggang' => 'sanitize_string',
				'hp_pelanggang' => 'sanitize_string',
				'jual' => 'sanitize_string',
				'bonus' => 'sanitize_string',
				'bayar' => 'sanitize_string',
				'setor' => 'sanitize_string',
			);
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$db->where("penjualan.id_penjualan", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if($bool && $numRows){
					$this->set_flash_msg("Data sudah terupdate", "success");
					return $this->redirect("penjualan");
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
						return	$this->redirect("penjualan");
					}
				}
			}
		}
		$db->where("penjualan.id_penjualan", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = "Edit  Penjualan";
		if(!$data){
			$this->set_page_error();
		}
		return $this->render_view("penjualan/edit.php", $data);
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
		$db->where("penjualan.id_penjualan", $arr_rec_id, "in");
		$bool = $db->delete($tablename);
		if($bool){
			$this->set_flash_msg("Data sudah terhapus", "success");
		}
		elseif($db->getLastError()){
			$page_error = $db->getLastError();
			$this->set_flash_msg($page_error, "danger");
		}
		return	$this->redirect("penjualan");
	}
}
