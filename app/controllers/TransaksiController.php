<?php 
/**
 * Transaksi Page Controller
 * @category  Controller
 */
class TransaksiController extends BaseController{
	function __construct(){
		parent::__construct();
		$this->tablename = "transaksi";
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
		$fields = array("id_transaksi", 
			"tanggal_transaksi", 
			"keterangan_transaksi", 
			"jual_transaksi", 
			"bonus_transaksi", 
			"bayar_transaksi", 
			"jual_transaksi*9-bayar_transaksi AS minus");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				transaksi.id_transaksi LIKE ? OR 
				transaksi.alamat_transaksi LIKE ? OR 
				transaksi.pengelolah_transaksi LIKE ? OR 
				transaksi.tanggal_transaksi LIKE ? OR 
				transaksi.keterangan_transaksi LIKE ? OR 
				transaksi.jual_transaksi LIKE ? OR 
				transaksi.bonus_transaksi LIKE ? OR 
				transaksi.bayar_transaksi LIKE ? OR 
				transaksi.setor_tagihan LIKE ? OR 
				jual_transaksi*9-bayar_transaksi LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "transaksi/search.php";
		}
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("transaksi.id_transaksi", ORDER_TYPE);
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
				$record['tanggal_transaksi'] = format_date($record['tanggal_transaksi'],'d-m-Y');
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
		$page_title = $this->view->page_title = "Transaksi";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("transaksi/list.php", $data); //render the full page
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
			$fields = $this->fields = array("alamat_transaksi","pengelolah_transaksi","tanggal_transaksi","keterangan_transaksi","jual_transaksi","bonus_transaksi","bayar_transaksi","setor_tagihan");
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'alamat_transaksi' => 'required',
				'pengelolah_transaksi' => 'required',
				'tanggal_transaksi' => 'required',
				'keterangan_transaksi' => 'required',
				'jual_transaksi' => 'required|numeric',
				'bonus_transaksi' => 'numeric',
			);
			$this->sanitize_array = array(
				'alamat_transaksi' => 'sanitize_string',
				'pengelolah_transaksi' => 'sanitize_string',
				'tanggal_transaksi' => 'sanitize_string',
				'keterangan_transaksi' => 'sanitize_string',
				'jual_transaksi' => 'sanitize_string',
				'bonus_transaksi' => 'sanitize_string',
				'bayar_transaksi' => 'sanitize_string',
				'setor_tagihan' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
				if($rec_id){
					$this->set_flash_msg("Data sudah tersimpan", "success");
					return	$this->redirect("transaksi");
				}
				else{
					$this->set_page_error();
				}
			}
		}
		$page_title = $this->view->page_title = "Add New Transaksi";
		$this->render_view("transaksi/add.php");
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
		$fields = $this->fields = array("id_transaksi","alamat_transaksi","pengelolah_transaksi","tanggal_transaksi","keterangan_transaksi","jual_transaksi","bonus_transaksi","bayar_transaksi","setor_tagihan");
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'alamat_transaksi' => 'required',
				'pengelolah_transaksi' => 'required',
				'tanggal_transaksi' => 'required',
				'keterangan_transaksi' => 'required',
				'jual_transaksi' => 'required|numeric',
				'bonus_transaksi' => 'numeric',
			);
			$this->sanitize_array = array(
				'alamat_transaksi' => 'sanitize_string',
				'pengelolah_transaksi' => 'sanitize_string',
				'tanggal_transaksi' => 'sanitize_string',
				'keterangan_transaksi' => 'sanitize_string',
				'jual_transaksi' => 'sanitize_string',
				'bonus_transaksi' => 'sanitize_string',
				'bayar_transaksi' => 'sanitize_string',
				'setor_tagihan' => 'sanitize_string',
			);
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$db->where("transaksi.id_transaksi", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if($bool && $numRows){
					$this->set_flash_msg("Data sudah terupdate", "success");
					return $this->redirect("transaksi");
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
						return	$this->redirect("transaksi");
					}
				}
			}
		}
		$db->where("transaksi.id_transaksi", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = "Edit  Transaksi";
		if(!$data){
			$this->set_page_error();
		}
		return $this->render_view("transaksi/edit.php", $data);
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
		$db->where("transaksi.id_transaksi", $arr_rec_id, "in");
		$bool = $db->delete($tablename);
		if($bool){
			$this->set_flash_msg("Data sudah terhapus", "success");
		}
		elseif($db->getLastError()){
			$page_error = $db->getLastError();
			$this->set_flash_msg($page_error, "danger");
		}
		return	$this->redirect("transaksi");
	}
	/**
     * List page records
     * @param $fieldname (filter record by a field) 
     * @param $fieldvalue (filter field value)
     * @return BaseView
     */
	function list_minus($fieldname = null , $fieldvalue = null){
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array("id_transaksi", 
			"tanggal_transaksi", 
			"keterangan_transaksi", 
			"jual_transaksi", 
			"bonus_transaksi", 
			"bayar_transaksi", 
			"setor_tagihan", 
			"jual_transaksi*9-bayar_transaksi AS minus");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				transaksi.id_transaksi LIKE ? OR 
				transaksi.alamat_transaksi LIKE ? OR 
				transaksi.pengelolah_transaksi LIKE ? OR 
				transaksi.tanggal_transaksi LIKE ? OR 
				transaksi.keterangan_transaksi LIKE ? OR 
				transaksi.jual_transaksi LIKE ? OR 
				transaksi.bonus_transaksi LIKE ? OR 
				transaksi.bayar_transaksi LIKE ? OR 
				transaksi.setor_tagihan LIKE ? OR 
				jual_transaksi*9-bayar_transaksi LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "transaksi/search.php";
		}
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("transaksi.id_transaksi", ORDER_TYPE);
		}
		$db->where("jual_transaksi*9-bayar_transaksi <> 0.00");
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
		$page_title = $this->view->page_title = "Transaksi";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("transaksi/list_minus.php", $data); //render the full page
	}
}
