<?php

// Global variable for table object
$pesanan = NULL;

//
// Table class for pesanan
//
class cpesanan extends cTable {
	var $order_id;
	var $nama_pemesan;
	var $tanggal_order;
	var $tanggal_selesai;
	var $jenis_barang;
	var $jenis_bahan;
	var $warna_bahan;
	var $harga_barang;
	var $jumlah_barang;
	var $total_harga;
	var $status_pembayaran;
	var $status_order;
	var $upload_link;
	var $discount;
	var $deskripsi;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'pesanan';
		$this->TableName = 'pesanan';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`pesanan`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// order_id
		$this->order_id = new cField('pesanan', 'pesanan', 'x_order_id', 'order_id', '`order_id`', '`order_id`', 3, -1, FALSE, '`order_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->order_id->Sortable = TRUE; // Allow sort
		$this->order_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['order_id'] = &$this->order_id;

		// nama_pemesan
		$this->nama_pemesan = new cField('pesanan', 'pesanan', 'x_nama_pemesan', 'nama_pemesan', '`nama_pemesan`', '`nama_pemesan`', 3, -1, FALSE, '`nama_pemesan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->nama_pemesan->Sortable = TRUE; // Allow sort
		$this->nama_pemesan->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->nama_pemesan->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['nama_pemesan'] = &$this->nama_pemesan;

		// tanggal_order
		$this->tanggal_order = new cField('pesanan', 'pesanan', 'x_tanggal_order', 'tanggal_order', '`tanggal_order`', ew_CastDateFieldForLike('`tanggal_order`', 0, "DB"), 133, 0, FALSE, '`tanggal_order`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tanggal_order->Sortable = TRUE; // Allow sort
		$this->tanggal_order->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['tanggal_order'] = &$this->tanggal_order;

		// tanggal_selesai
		$this->tanggal_selesai = new cField('pesanan', 'pesanan', 'x_tanggal_selesai', 'tanggal_selesai', '`tanggal_selesai`', ew_CastDateFieldForLike('`tanggal_selesai`', 0, "DB"), 133, 0, FALSE, '`tanggal_selesai`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tanggal_selesai->Sortable = TRUE; // Allow sort
		$this->tanggal_selesai->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['tanggal_selesai'] = &$this->tanggal_selesai;

		// jenis_barang
		$this->jenis_barang = new cField('pesanan', 'pesanan', 'x_jenis_barang', 'jenis_barang', '`jenis_barang`', '`jenis_barang`', 200, -1, FALSE, '`jenis_barang`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->jenis_barang->Sortable = TRUE; // Allow sort
		$this->jenis_barang->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->jenis_barang->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->jenis_barang->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['jenis_barang'] = &$this->jenis_barang;

		// jenis_bahan
		$this->jenis_bahan = new cField('pesanan', 'pesanan', 'x_jenis_bahan', 'jenis_bahan', '`jenis_bahan`', '`jenis_bahan`', 200, -1, FALSE, '`jenis_bahan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->jenis_bahan->Sortable = TRUE; // Allow sort
		$this->jenis_bahan->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->jenis_bahan->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['jenis_bahan'] = &$this->jenis_bahan;

		// warna_bahan
		$this->warna_bahan = new cField('pesanan', 'pesanan', 'x_warna_bahan', 'warna_bahan', '`warna_bahan`', '`warna_bahan`', 200, -1, FALSE, '`warna_bahan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->warna_bahan->Sortable = TRUE; // Allow sort
		$this->fields['warna_bahan'] = &$this->warna_bahan;

		// harga_barang
		$this->harga_barang = new cField('pesanan', 'pesanan', 'x_harga_barang', 'harga_barang', '`harga_barang`', '`harga_barang`', 200, -1, FALSE, '`harga_barang`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->harga_barang->Sortable = TRUE; // Allow sort
		$this->harga_barang->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->harga_barang->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->harga_barang->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['harga_barang'] = &$this->harga_barang;

		// jumlah_barang
		$this->jumlah_barang = new cField('pesanan', 'pesanan', 'x_jumlah_barang', 'jumlah_barang', '`jumlah_barang`', '`jumlah_barang`', 200, -1, FALSE, '`jumlah_barang`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jumlah_barang->Sortable = TRUE; // Allow sort
		$this->jumlah_barang->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['jumlah_barang'] = &$this->jumlah_barang;

		// total_harga
		$this->total_harga = new cField('pesanan', 'pesanan', 'x_total_harga', 'total_harga', '`total_harga`', '`total_harga`', 200, -1, FALSE, '`total_harga`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->total_harga->Sortable = TRUE; // Allow sort
		$this->total_harga->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['total_harga'] = &$this->total_harga;

		// status_pembayaran
		$this->status_pembayaran = new cField('pesanan', 'pesanan', 'x_status_pembayaran', 'status_pembayaran', '`status_pembayaran`', '`status_pembayaran`', 200, -1, FALSE, '`status_pembayaran`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->status_pembayaran->Sortable = TRUE; // Allow sort
		$this->status_pembayaran->OptionCount = 2;
		$this->fields['status_pembayaran'] = &$this->status_pembayaran;

		// status_order
		$this->status_order = new cField('pesanan', 'pesanan', 'x_status_order', 'status_order', '`status_order`', '`status_order`', 200, -1, FALSE, '`status_order`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->status_order->Sortable = TRUE; // Allow sort
		$this->status_order->OptionCount = 4;
		$this->fields['status_order'] = &$this->status_order;

		// upload_link
		$this->upload_link = new cField('pesanan', 'pesanan', 'x_upload_link', 'upload_link', '`upload_link`', '`upload_link`', 200, -1, TRUE, '`upload_link`', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->upload_link->Sortable = TRUE; // Allow sort
		$this->upload_link->UploadAllowedFileExt = "jpg,jpeg,png";
		$this->upload_link->UploadMaxFileSize = 2000000;
		$this->upload_link->UploadMultiple = TRUE;
		$this->upload_link->Upload->UploadMultiple = TRUE;
		$this->upload_link->UploadMaxFileCount = 5;
		$this->fields['upload_link'] = &$this->upload_link;

		// discount
		$this->discount = new cField('pesanan', 'pesanan', 'x_discount', 'discount', '`discount`', '`discount`', 200, -1, FALSE, '`discount`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->discount->Sortable = TRUE; // Allow sort
		$this->fields['discount'] = &$this->discount;

		// deskripsi
		$this->deskripsi = new cField('pesanan', 'pesanan', 'x_deskripsi', 'deskripsi', '`deskripsi`', '`deskripsi`', 200, -1, FALSE, '`deskripsi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->deskripsi->Sortable = TRUE; // Allow sort
		$this->fields['deskripsi'] = &$this->deskripsi;
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`pesanan`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {

			// Get insert id if necessary
			$this->order_id->setDbValue($conn->Insert_ID());
			$rs['order_id'] = $this->order_id->DbValue;
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('order_id', $rs))
				ew_AddFilter($where, ew_QuotedName('order_id', $this->DBID) . '=' . ew_QuotedValue($rs['order_id'], $this->order_id->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`order_id` = @order_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->order_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@order_id@", ew_AdjustSql($this->order_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "pesananlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "pesananlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("pesananview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("pesananview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "pesananadd.php?" . $this->UrlParm($parm);
		else
			$url = "pesananadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("pesananedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("pesananadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("pesanandelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "order_id:" . ew_VarToJson($this->order_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->order_id->CurrentValue)) {
			$sUrl .= "order_id=" . urlencode($this->order_id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["order_id"]))
				$arKeys[] = ew_StripSlashes($_POST["order_id"]);
			elseif (isset($_GET["order_id"]))
				$arKeys[] = ew_StripSlashes($_GET["order_id"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->order_id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->order_id->setDbValue($rs->fields('order_id'));
		$this->nama_pemesan->setDbValue($rs->fields('nama_pemesan'));
		$this->tanggal_order->setDbValue($rs->fields('tanggal_order'));
		$this->tanggal_selesai->setDbValue($rs->fields('tanggal_selesai'));
		$this->jenis_barang->setDbValue($rs->fields('jenis_barang'));
		$this->jenis_bahan->setDbValue($rs->fields('jenis_bahan'));
		$this->warna_bahan->setDbValue($rs->fields('warna_bahan'));
		$this->harga_barang->setDbValue($rs->fields('harga_barang'));
		$this->jumlah_barang->setDbValue($rs->fields('jumlah_barang'));
		$this->total_harga->setDbValue($rs->fields('total_harga'));
		$this->status_pembayaran->setDbValue($rs->fields('status_pembayaran'));
		$this->status_order->setDbValue($rs->fields('status_order'));
		$this->upload_link->Upload->DbValue = $rs->fields('upload_link');
		$this->discount->setDbValue($rs->fields('discount'));
		$this->deskripsi->setDbValue($rs->fields('deskripsi'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// order_id
		// nama_pemesan
		// tanggal_order
		// tanggal_selesai
		// jenis_barang
		// jenis_bahan
		// warna_bahan
		// harga_barang
		// jumlah_barang
		// total_harga
		// status_pembayaran
		// status_order
		// upload_link
		// discount
		// deskripsi
		// order_id

		$this->order_id->ViewValue = $this->order_id->CurrentValue;
		$this->order_id->ViewCustomAttributes = "";

		// nama_pemesan
		if (strval($this->nama_pemesan->CurrentValue) <> "") {
			$sFilterWrk = "`cust_id`" . ew_SearchString("=", $this->nama_pemesan->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `cust_id`, `nama_pelanggan` AS `DispFld`, `alamat pelanggan` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `customer`";
		$sWhereWrk = "";
		$this->nama_pemesan->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->nama_pemesan, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `cust_id`";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->nama_pemesan->ViewValue = $this->nama_pemesan->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->nama_pemesan->ViewValue = $this->nama_pemesan->CurrentValue;
			}
		} else {
			$this->nama_pemesan->ViewValue = NULL;
		}
		$this->nama_pemesan->ViewCustomAttributes = "";

		// tanggal_order
		$this->tanggal_order->ViewValue = $this->tanggal_order->CurrentValue;
		$this->tanggal_order->ViewValue = ew_FormatDateTime($this->tanggal_order->ViewValue, 0);
		$this->tanggal_order->ViewCustomAttributes = "";

		// tanggal_selesai
		$this->tanggal_selesai->ViewValue = $this->tanggal_selesai->CurrentValue;
		$this->tanggal_selesai->ViewValue = ew_FormatDateTime($this->tanggal_selesai->ViewValue, 0);
		$this->tanggal_selesai->ViewCustomAttributes = "";

		// jenis_barang
		if (strval($this->jenis_barang->CurrentValue) <> "") {
			$sFilterWrk = "`jenis_barang`" . ew_SearchString("=", $this->jenis_barang->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT DISTINCT `jenis_barang`, `jenis_barang` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `masterdata`";
		$sWhereWrk = "";
		$this->jenis_barang->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->jenis_barang, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->jenis_barang->ViewValue = $this->jenis_barang->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->jenis_barang->ViewValue = $this->jenis_barang->CurrentValue;
			}
		} else {
			$this->jenis_barang->ViewValue = NULL;
		}
		$this->jenis_barang->ViewCustomAttributes = "";

		// jenis_bahan
		if (strval($this->jenis_bahan->CurrentValue) <> "") {
			$sFilterWrk = "`jenis_bahan`" . ew_SearchString("=", $this->jenis_bahan->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `jenis_bahan`, `jenis_bahan` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `masterdata`";
		$sWhereWrk = "";
		$this->jenis_bahan->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->jenis_bahan, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->jenis_bahan->ViewValue = $this->jenis_bahan->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->jenis_bahan->ViewValue = $this->jenis_bahan->CurrentValue;
			}
		} else {
			$this->jenis_bahan->ViewValue = NULL;
		}
		$this->jenis_bahan->ViewCustomAttributes = "";

		// warna_bahan
		$this->warna_bahan->ViewValue = $this->warna_bahan->CurrentValue;
		$this->warna_bahan->ViewCustomAttributes = "";

		// harga_barang
		if (strval($this->harga_barang->CurrentValue) <> "") {
			$sFilterWrk = "`harga`" . ew_SearchString("=", $this->harga_barang->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `harga`, `harga` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `masterdata`";
		$sWhereWrk = "";
		$this->harga_barang->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->harga_barang, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->harga_barang->ViewValue = $this->harga_barang->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->harga_barang->ViewValue = $this->harga_barang->CurrentValue;
			}
		} else {
			$this->harga_barang->ViewValue = NULL;
		}
		$this->harga_barang->ViewValue = ew_FormatCurrency($this->harga_barang->ViewValue, 2, -2, -2, -2);
		$this->harga_barang->ViewCustomAttributes = "";

		// jumlah_barang
		$this->jumlah_barang->ViewValue = $this->jumlah_barang->CurrentValue;
		$this->jumlah_barang->ViewCustomAttributes = "";

		// total_harga
		$this->total_harga->ViewValue = $this->total_harga->CurrentValue;
		$this->total_harga->ViewValue = ew_FormatCurrency($this->total_harga->ViewValue, 2, -2, -2, -2);
		$this->total_harga->ViewCustomAttributes = "";

		// status_pembayaran
		if (strval($this->status_pembayaran->CurrentValue) <> "") {
			$this->status_pembayaran->ViewValue = $this->status_pembayaran->OptionCaption($this->status_pembayaran->CurrentValue);
		} else {
			$this->status_pembayaran->ViewValue = NULL;
		}
		$this->status_pembayaran->ViewCustomAttributes = "";

		// status_order
		if (strval($this->status_order->CurrentValue) <> "") {
			$this->status_order->ViewValue = $this->status_order->OptionCaption($this->status_order->CurrentValue);
		} else {
			$this->status_order->ViewValue = NULL;
		}
		$this->status_order->ViewCustomAttributes = "";

		// upload_link
		if (!ew_Empty($this->upload_link->Upload->DbValue)) {
			$this->upload_link->ImageAlt = $this->upload_link->FldAlt();
			$this->upload_link->ViewValue = $this->upload_link->Upload->DbValue;
		} else {
			$this->upload_link->ViewValue = "";
		}
		$this->upload_link->ViewCustomAttributes = "";

		// discount
		$this->discount->ViewValue = $this->discount->CurrentValue;
		$this->discount->ViewCustomAttributes = "";

		// deskripsi
		$this->deskripsi->ViewValue = $this->deskripsi->CurrentValue;
		$this->deskripsi->ViewCustomAttributes = "";

		// order_id
		$this->order_id->LinkCustomAttributes = "";
		$this->order_id->HrefValue = "";
		$this->order_id->TooltipValue = "";

		// nama_pemesan
		$this->nama_pemesan->LinkCustomAttributes = "";
		$this->nama_pemesan->HrefValue = "";
		$this->nama_pemesan->TooltipValue = "";

		// tanggal_order
		$this->tanggal_order->LinkCustomAttributes = "";
		$this->tanggal_order->HrefValue = "";
		$this->tanggal_order->TooltipValue = "";

		// tanggal_selesai
		$this->tanggal_selesai->LinkCustomAttributes = "";
		$this->tanggal_selesai->HrefValue = "";
		$this->tanggal_selesai->TooltipValue = "";

		// jenis_barang
		$this->jenis_barang->LinkCustomAttributes = "";
		$this->jenis_barang->HrefValue = "";
		$this->jenis_barang->TooltipValue = "";

		// jenis_bahan
		$this->jenis_bahan->LinkCustomAttributes = "";
		$this->jenis_bahan->HrefValue = "";
		$this->jenis_bahan->TooltipValue = "";

		// warna_bahan
		$this->warna_bahan->LinkCustomAttributes = "";
		$this->warna_bahan->HrefValue = "";
		$this->warna_bahan->TooltipValue = "";

		// harga_barang
		$this->harga_barang->LinkCustomAttributes = "";
		$this->harga_barang->HrefValue = "";
		$this->harga_barang->TooltipValue = "";

		// jumlah_barang
		$this->jumlah_barang->LinkCustomAttributes = "";
		$this->jumlah_barang->HrefValue = "";
		$this->jumlah_barang->TooltipValue = "";

		// total_harga
		$this->total_harga->LinkCustomAttributes = "";
		$this->total_harga->HrefValue = "";
		$this->total_harga->TooltipValue = "";

		// status_pembayaran
		$this->status_pembayaran->LinkCustomAttributes = "";
		$this->status_pembayaran->HrefValue = "";
		$this->status_pembayaran->TooltipValue = "";

		// status_order
		$this->status_order->LinkCustomAttributes = "";
		$this->status_order->HrefValue = "";
		$this->status_order->TooltipValue = "";

		// upload_link
		$this->upload_link->LinkCustomAttributes = "";
		if (!ew_Empty($this->upload_link->Upload->DbValue)) {
			$this->upload_link->HrefValue = "%u"; // Add prefix/suffix
			$this->upload_link->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->upload_link->HrefValue = ew_ConvertFullUrl($this->upload_link->HrefValue);
		} else {
			$this->upload_link->HrefValue = "";
		}
		$this->upload_link->HrefValue2 = $this->upload_link->UploadPath . $this->upload_link->Upload->DbValue;
		$this->upload_link->TooltipValue = "";
		if ($this->upload_link->UseColorbox) {
			if (ew_Empty($this->upload_link->TooltipValue))
				$this->upload_link->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
			$this->upload_link->LinkAttrs["data-rel"] = "pesanan_x_upload_link";
			ew_AppendClass($this->upload_link->LinkAttrs["class"], "ewLightbox");
		}

		// discount
		$this->discount->LinkCustomAttributes = "";
		$this->discount->HrefValue = "";
		$this->discount->TooltipValue = "";

		// deskripsi
		$this->deskripsi->LinkCustomAttributes = "";
		$this->deskripsi->HrefValue = "";
		$this->deskripsi->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// order_id
		$this->order_id->EditAttrs["class"] = "form-control";
		$this->order_id->EditCustomAttributes = "";
		$this->order_id->EditValue = $this->order_id->CurrentValue;
		$this->order_id->ViewCustomAttributes = "";

		// nama_pemesan
		$this->nama_pemesan->EditAttrs["class"] = "form-control";
		$this->nama_pemesan->EditCustomAttributes = "";
		if (strval($this->nama_pemesan->CurrentValue) <> "") {
			$sFilterWrk = "`cust_id`" . ew_SearchString("=", $this->nama_pemesan->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `cust_id`, `nama_pelanggan` AS `DispFld`, `alamat pelanggan` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `customer`";
		$sWhereWrk = "";
		$this->nama_pemesan->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->nama_pemesan, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `cust_id`";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->nama_pemesan->EditValue = $this->nama_pemesan->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->nama_pemesan->EditValue = $this->nama_pemesan->CurrentValue;
			}
		} else {
			$this->nama_pemesan->EditValue = NULL;
		}
		$this->nama_pemesan->ViewCustomAttributes = "";

		// tanggal_order
		$this->tanggal_order->EditAttrs["class"] = "form-control";
		$this->tanggal_order->EditCustomAttributes = "";
		$this->tanggal_order->EditValue = $this->tanggal_order->CurrentValue;
		$this->tanggal_order->EditValue = ew_FormatDateTime($this->tanggal_order->EditValue, 0);
		$this->tanggal_order->ViewCustomAttributes = "";

		// tanggal_selesai
		$this->tanggal_selesai->EditAttrs["class"] = "form-control";
		$this->tanggal_selesai->EditCustomAttributes = "";
		$this->tanggal_selesai->EditValue = ew_FormatDateTime($this->tanggal_selesai->CurrentValue, 8);
		$this->tanggal_selesai->PlaceHolder = ew_RemoveHtml($this->tanggal_selesai->FldCaption());

		// jenis_barang
		$this->jenis_barang->EditAttrs["class"] = "form-control";
		$this->jenis_barang->EditCustomAttributes = "";
		if (strval($this->jenis_barang->CurrentValue) <> "") {
			$sFilterWrk = "`jenis_barang`" . ew_SearchString("=", $this->jenis_barang->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT DISTINCT `jenis_barang`, `jenis_barang` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `masterdata`";
		$sWhereWrk = "";
		$this->jenis_barang->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->jenis_barang, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->jenis_barang->EditValue = $this->jenis_barang->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->jenis_barang->EditValue = $this->jenis_barang->CurrentValue;
			}
		} else {
			$this->jenis_barang->EditValue = NULL;
		}
		$this->jenis_barang->ViewCustomAttributes = "";

		// jenis_bahan
		$this->jenis_bahan->EditAttrs["class"] = "form-control";
		$this->jenis_bahan->EditCustomAttributes = "";
		if (strval($this->jenis_bahan->CurrentValue) <> "") {
			$sFilterWrk = "`jenis_bahan`" . ew_SearchString("=", $this->jenis_bahan->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `jenis_bahan`, `jenis_bahan` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `masterdata`";
		$sWhereWrk = "";
		$this->jenis_bahan->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->jenis_bahan, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->jenis_bahan->EditValue = $this->jenis_bahan->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->jenis_bahan->EditValue = $this->jenis_bahan->CurrentValue;
			}
		} else {
			$this->jenis_bahan->EditValue = NULL;
		}
		$this->jenis_bahan->ViewCustomAttributes = "";

		// warna_bahan
		$this->warna_bahan->EditAttrs["class"] = "form-control";
		$this->warna_bahan->EditCustomAttributes = "";
		$this->warna_bahan->EditValue = $this->warna_bahan->CurrentValue;
		$this->warna_bahan->ViewCustomAttributes = "";

		// harga_barang
		$this->harga_barang->EditAttrs["class"] = "form-control";
		$this->harga_barang->EditCustomAttributes = "";
		if (strval($this->harga_barang->CurrentValue) <> "") {
			$sFilterWrk = "`harga`" . ew_SearchString("=", $this->harga_barang->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `harga`, `harga` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `masterdata`";
		$sWhereWrk = "";
		$this->harga_barang->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->harga_barang, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->harga_barang->EditValue = $this->harga_barang->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->harga_barang->EditValue = $this->harga_barang->CurrentValue;
			}
		} else {
			$this->harga_barang->EditValue = NULL;
		}
		$this->harga_barang->EditValue = ew_FormatCurrency($this->harga_barang->EditValue, 2, -2, -2, -2);
		$this->harga_barang->ViewCustomAttributes = "";

		// jumlah_barang
		$this->jumlah_barang->EditAttrs["class"] = "form-control";
		$this->jumlah_barang->EditCustomAttributes = "";
		$this->jumlah_barang->EditValue = $this->jumlah_barang->CurrentValue;
		$this->jumlah_barang->PlaceHolder = ew_RemoveHtml($this->jumlah_barang->FldCaption());

		// total_harga
		$this->total_harga->EditAttrs["class"] = "form-control";
		$this->total_harga->EditCustomAttributes = "";
		$this->total_harga->EditValue = $this->total_harga->CurrentValue;
		$this->total_harga->EditValue = ew_FormatCurrency($this->total_harga->EditValue, 2, -2, -2, -2);
		$this->total_harga->ViewCustomAttributes = "";

		// status_pembayaran
		$this->status_pembayaran->EditCustomAttributes = "";
		$this->status_pembayaran->EditValue = $this->status_pembayaran->Options(FALSE);

		// status_order
		$this->status_order->EditCustomAttributes = "";
		$this->status_order->EditValue = $this->status_order->Options(FALSE);

		// upload_link
		$this->upload_link->EditAttrs["class"] = "form-control";
		$this->upload_link->EditCustomAttributes = "";
		if (!ew_Empty($this->upload_link->Upload->DbValue)) {
			$this->upload_link->ImageAlt = $this->upload_link->FldAlt();
			$this->upload_link->EditValue = $this->upload_link->Upload->DbValue;
		} else {
			$this->upload_link->EditValue = "";
		}
		$this->upload_link->ViewCustomAttributes = "";

		// discount
		$this->discount->EditAttrs["class"] = "form-control";
		$this->discount->EditCustomAttributes = "";
		$this->discount->EditValue = $this->discount->CurrentValue;
		$this->discount->ViewCustomAttributes = "";

		// deskripsi
		$this->deskripsi->EditAttrs["class"] = "form-control";
		$this->deskripsi->EditCustomAttributes = "";
		$this->deskripsi->EditValue = $this->deskripsi->CurrentValue;
		$this->deskripsi->PlaceHolder = ew_RemoveHtml($this->deskripsi->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->order_id->Exportable) $Doc->ExportCaption($this->order_id);
					if ($this->nama_pemesan->Exportable) $Doc->ExportCaption($this->nama_pemesan);
					if ($this->tanggal_order->Exportable) $Doc->ExportCaption($this->tanggal_order);
					if ($this->tanggal_selesai->Exportable) $Doc->ExportCaption($this->tanggal_selesai);
					if ($this->jenis_barang->Exportable) $Doc->ExportCaption($this->jenis_barang);
					if ($this->jenis_bahan->Exportable) $Doc->ExportCaption($this->jenis_bahan);
					if ($this->warna_bahan->Exportable) $Doc->ExportCaption($this->warna_bahan);
					if ($this->harga_barang->Exportable) $Doc->ExportCaption($this->harga_barang);
					if ($this->jumlah_barang->Exportable) $Doc->ExportCaption($this->jumlah_barang);
					if ($this->total_harga->Exportable) $Doc->ExportCaption($this->total_harga);
					if ($this->status_pembayaran->Exportable) $Doc->ExportCaption($this->status_pembayaran);
					if ($this->status_order->Exportable) $Doc->ExportCaption($this->status_order);
					if ($this->upload_link->Exportable) $Doc->ExportCaption($this->upload_link);
					if ($this->discount->Exportable) $Doc->ExportCaption($this->discount);
					if ($this->deskripsi->Exportable) $Doc->ExportCaption($this->deskripsi);
				} else {
					if ($this->order_id->Exportable) $Doc->ExportCaption($this->order_id);
					if ($this->nama_pemesan->Exportable) $Doc->ExportCaption($this->nama_pemesan);
					if ($this->tanggal_order->Exportable) $Doc->ExportCaption($this->tanggal_order);
					if ($this->tanggal_selesai->Exportable) $Doc->ExportCaption($this->tanggal_selesai);
					if ($this->jenis_barang->Exportable) $Doc->ExportCaption($this->jenis_barang);
					if ($this->jenis_bahan->Exportable) $Doc->ExportCaption($this->jenis_bahan);
					if ($this->warna_bahan->Exportable) $Doc->ExportCaption($this->warna_bahan);
					if ($this->harga_barang->Exportable) $Doc->ExportCaption($this->harga_barang);
					if ($this->jumlah_barang->Exportable) $Doc->ExportCaption($this->jumlah_barang);
					if ($this->total_harga->Exportable) $Doc->ExportCaption($this->total_harga);
					if ($this->status_pembayaran->Exportable) $Doc->ExportCaption($this->status_pembayaran);
					if ($this->status_order->Exportable) $Doc->ExportCaption($this->status_order);
					if ($this->upload_link->Exportable) $Doc->ExportCaption($this->upload_link);
					if ($this->discount->Exportable) $Doc->ExportCaption($this->discount);
					if ($this->deskripsi->Exportable) $Doc->ExportCaption($this->deskripsi);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->order_id->Exportable) $Doc->ExportField($this->order_id);
						if ($this->nama_pemesan->Exportable) $Doc->ExportField($this->nama_pemesan);
						if ($this->tanggal_order->Exportable) $Doc->ExportField($this->tanggal_order);
						if ($this->tanggal_selesai->Exportable) $Doc->ExportField($this->tanggal_selesai);
						if ($this->jenis_barang->Exportable) $Doc->ExportField($this->jenis_barang);
						if ($this->jenis_bahan->Exportable) $Doc->ExportField($this->jenis_bahan);
						if ($this->warna_bahan->Exportable) $Doc->ExportField($this->warna_bahan);
						if ($this->harga_barang->Exportable) $Doc->ExportField($this->harga_barang);
						if ($this->jumlah_barang->Exportable) $Doc->ExportField($this->jumlah_barang);
						if ($this->total_harga->Exportable) $Doc->ExportField($this->total_harga);
						if ($this->status_pembayaran->Exportable) $Doc->ExportField($this->status_pembayaran);
						if ($this->status_order->Exportable) $Doc->ExportField($this->status_order);
						if ($this->upload_link->Exportable) $Doc->ExportField($this->upload_link);
						if ($this->discount->Exportable) $Doc->ExportField($this->discount);
						if ($this->deskripsi->Exportable) $Doc->ExportField($this->deskripsi);
					} else {
						if ($this->order_id->Exportable) $Doc->ExportField($this->order_id);
						if ($this->nama_pemesan->Exportable) $Doc->ExportField($this->nama_pemesan);
						if ($this->tanggal_order->Exportable) $Doc->ExportField($this->tanggal_order);
						if ($this->tanggal_selesai->Exportable) $Doc->ExportField($this->tanggal_selesai);
						if ($this->jenis_barang->Exportable) $Doc->ExportField($this->jenis_barang);
						if ($this->jenis_bahan->Exportable) $Doc->ExportField($this->jenis_bahan);
						if ($this->warna_bahan->Exportable) $Doc->ExportField($this->warna_bahan);
						if ($this->harga_barang->Exportable) $Doc->ExportField($this->harga_barang);
						if ($this->jumlah_barang->Exportable) $Doc->ExportField($this->jumlah_barang);
						if ($this->total_harga->Exportable) $Doc->ExportField($this->total_harga);
						if ($this->status_pembayaran->Exportable) $Doc->ExportField($this->status_pembayaran);
						if ($this->status_order->Exportable) $Doc->ExportField($this->status_order);
						if ($this->upload_link->Exportable) $Doc->ExportField($this->upload_link);
						if ($this->discount->Exportable) $Doc->ExportField($this->discount);
						if ($this->deskripsi->Exportable) $Doc->ExportField($this->deskripsi);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		global $conn;
		$discount=0;
		$sqlnew = "SELECT `jumlah_pesanan` FROM customer";
		$sqlnew .= " WHERE `cust_id` = ";
		$sqlnew .= $rsnew["nama_pemesan"];
		$res = ew_ExecuteRow($sqlnew);
		$result = $res['jumlah_pesanan'];
		if ($rsnew["jumlah_barang"] >= 1000) {
			$discount=0.05;
		}else if ($rsnew["jumlah_barang"] >= 100 && $rsnew["jumlah_barang"] < 1000 ) {
			$discount=0.02;
		}else{
			$discount=0;
		}
		$sql = "UPDATE customer SET `jumlah_pesanan` = `jumlah_pesanan`+1";
		$sql .= " WHERE `cust_id` = ";
		$sql .= $rsnew["nama_pemesan"];
		$conn->Execute($sql); 
		if	($result >= 100){
			$discount+=0.1;
		}else if ($result >=50 && $result <100){
			$discount+=0.05;
		}else if ($result >=20 && $result <50){
			$discount+=0.02;
		}else{
			$discount+=0;
		}
		$rsnew["discount"] = $discount;
		$rsnew["total_harga"] = ($rsnew["harga_barang"] * $rsnew["jumlah_barang"])-($rsnew["harga_barang"] * $rsnew["jumlah_barang"] * $discount);
		$sqlbahan = "SELECT `kebutuhan`,`satuan` FROM masterdata";
		$sqlbahan .= " WHERE `jenis_barang` = '";
		$sqlbahan .= $rsnew["jenis_barang"];
		$sqlbahan .= "' AND `jenis_bahan` = '";
		$sqlbahan .= $rsnew["jenis_bahan"];
		$sqlbahan .= "'";
		$res2 = ew_ExecuteRow($sqlbahan);
		$resultbahan = $res2['kebutuhan'];
		$sqlsisa = "SELECT `inventory_id`,`kuantitas` FROM inventory";
		$sqlsisa .= " WHERE `jenis` = '";
		$sqlsisa .= $rsnew["jenis_bahan"];
		$sqlsisa .= "' AND `warna` = '";
		$sqlsisa .= $rsnew["warna_bahan"];
		$sqlsisa .= "'";
		$res3 = ew_ExecuteRow($sqlsisa);
		$resultsisa = $res3['kuantitas'];
		$jumlahkebutuhan = $rsnew["jumlah_barang"] * $resultbahan;
		if($jumlahkebutuhan > $resultsisa){
			$sisa_kebutuhan = $jumlahkebutuhan- $resultsisa;
			$sisa_inv = 0;
		}else{
			$sisa_kebutuhan = 0;
			$sisa_inv = $resultsisa-$jumlahkebutuhan;
		}
		$sqlinv = "UPDATE inventory SET `kuantitas` = ";
		$sqlinv .= $sisa_inv;
		$sqlinv .= " WHERE `inventory_id` = ";
		$sqlinv .= $res3['inventory_id'];
		$conn->Execute($sqlinv);
		$rsnew['deskripsi'] = "Di Inventory ada ".$resultsisa." ".$res2['satuan'].".<br />Kebutuhan sebesar ";
		$rsnew['deskripsi'] .= $jumlahkebutuhan." ".$res2['satuan'].".<br />Ada ";
		$rsnew['deskripsi'] .= $sisa_kebutuhan." ".$res2['satuan']. " yang harus dibeli.<br />Sisa Inventory setelah digunakan menjadi ";
		$rsnew['deskripsi'] .= $sisa_inv." ".$res2['satuan'];

		//$rsnew['deskripsi'] = "Inventory hilang".$sisa_kebutuhan.$sqlinv;
		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		global $conn;
		$discount=0;
		$sqlnew = "SELECT `jumlah_pesanan` FROM customer";
		$sqlnew .= " WHERE `cust_id` = ";
		$sqlnew .= $rsold["nama_pemesan"];
		$res = ew_ExecuteRow($sqlnew);
		$result = $res['jumlah_pesanan'];
		if ($rsnew["jumlah_barang"] >= 1000) {
			$discount=0.05;
		}else if ($rsnew["jumlah_barang"] >= 100 && $rsnew["jumlah_barang"] < 1000 ) {
			$discount=0.02;
		}else{
			$discount=0;
		}
		if	($result >= 100){
			$discount+=0.1;
		}else if ($result >=50 && $result <100){
			$discount+=0.05;
		}else if ($result >=20 && $result <50){
			$discount+=0.02;
		}else{
			$discount+=0;
		}
		$rsnew["discount"] = $discount;
		$rsnew["total_harga"] = ($rsold["harga_barang"] * $rsnew["jumlah_barang"])-($rsold["harga_barang"] * $rsnew["jumlah_barang"] * $discount);
		$sqlbahan = "SELECT `kebutuhan`,`satuan` FROM masterdata";
		$sqlbahan .= " WHERE `jenis_barang` = '";
		$sqlbahan .= $rsold["jenis_barang"];
		$sqlbahan .= "' AND `jenis_bahan` = '";
		$sqlbahan .= $rsold["jenis_bahan"];
		$sqlbahan .= "'";
		$res2 = ew_ExecuteRow($sqlbahan);
		$resultbahan = $res2['kebutuhan'];
		$sqlsisa = "SELECT `inventory_id`,`kuantitas` FROM inventory";
		$sqlsisa .= " WHERE `jenis` = '";
		$sqlsisa .= $rsold["jenis_bahan"];
		$sqlsisa .= "' AND `warna` = '";
		$sqlsisa .= $rsold["warna_bahan"];
		$sqlsisa .= "'";
		$res3 = ew_ExecuteRow($sqlsisa);
		$resultsisa = $res3['kuantitas'];
		if($rsnew["jumlah_barang"] >= $rsold["jumlah_barang"]){
			$tambahan = $rsnew["jumlah_barang"] - $rsold["jumlah_barang"];
		}else{
			$tambahan = $rsold["jumlah_barang"] - $rsnew["jumlah_barang"];
		}
		$totalasli = $rsnew["jumlah_barang"];
		$jumlahasli = $totalasli * $resultbahan;
		$jumlahkebutuhan = $tambahan * $resultbahan;
		if($jumlahkebutuhan > $resultsisa){
			$sisa_kebutuhan = $jumlahkebutuhan - $resultsisa;
			$sisa_inv = 0;
		}else{
			$sisa_kebutuhan = 0;
			$sisa_inv = $resultsisa-$jumlahkebutuhan;
		}
		$sqlinv = "UPDATE inventory SET `kuantitas` = ";
		$sqlinv .= $sisa_inv;
		$sqlinv .= " WHERE `inventory_id` = ";
		$sqlinv .= $res3['inventory_id'];
		$conn->Execute($sqlinv);
		$rsnew['deskripsi'] = "Di Inventory ada ".$resultsisa." ".$res2['satuan'].".<br />Kebutuhan sebesar ";
		$rsnew['deskripsi'] .= $jumlahasli." ".$res2['satuan'].".<br />Ada Penambahan Kebutuhan sebesar ";
		$rsnew['deskripsi'] .= $jumlahkebutuhan." ".$res2['satuan'].".<br />Ada ";
		$rsnew['deskripsi'] .= $sisa_kebutuhan." ".$res2['satuan']. " yang harus dibeli.<br />Sisa Inventory setelah digunakan menjadi ";
		$rsnew['deskripsi'] .= $sisa_inv." ".$res2['satuan'];

		//$rsnew['deskripsi'] = "Inventory hilang".$sisa_kebutuhan.$sqlinv;
		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
