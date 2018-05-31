<?php

// Global variable for table object
$inventory = NULL;

//
// Table class for inventory
//
class cinventory extends cTable {
	var $inventory_id;
	var $deskripsi_item;
	var $kuantitas;
	var $satuan_unit;
	var $jenis;
	var $warna;
	var $tanggal_masuk;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'inventory';
		$this->TableName = 'inventory';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`inventory`";
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

		// inventory_id
		$this->inventory_id = new cField('inventory', 'inventory', 'x_inventory_id', 'inventory_id', '`inventory_id`', '`inventory_id`', 3, -1, FALSE, '`inventory_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->inventory_id->Sortable = TRUE; // Allow sort
		$this->inventory_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['inventory_id'] = &$this->inventory_id;

		// deskripsi_item
		$this->deskripsi_item = new cField('inventory', 'inventory', 'x_deskripsi_item', 'deskripsi_item', '`deskripsi_item`', '`deskripsi_item`', 200, -1, FALSE, '`deskripsi_item`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->deskripsi_item->Sortable = TRUE; // Allow sort
		$this->fields['deskripsi_item'] = &$this->deskripsi_item;

		// kuantitas
		$this->kuantitas = new cField('inventory', 'inventory', 'x_kuantitas', 'kuantitas', '`kuantitas`', '`kuantitas`', 3, -1, FALSE, '`kuantitas`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->kuantitas->Sortable = TRUE; // Allow sort
		$this->kuantitas->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['kuantitas'] = &$this->kuantitas;

		// satuan_unit
		$this->satuan_unit = new cField('inventory', 'inventory', 'x_satuan_unit', 'satuan_unit', '`satuan_unit`', '`satuan_unit`', 200, -1, FALSE, '`satuan_unit`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->satuan_unit->Sortable = TRUE; // Allow sort
		$this->satuan_unit->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->satuan_unit->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->satuan_unit->OptionCount = 3;
		$this->fields['satuan_unit'] = &$this->satuan_unit;

		// jenis
		$this->jenis = new cField('inventory', 'inventory', 'x_jenis', 'jenis', '`jenis`', '`jenis`', 200, -1, FALSE, '`jenis`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->jenis->Sortable = TRUE; // Allow sort
		$this->jenis->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->jenis->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['jenis'] = &$this->jenis;

		// warna
		$this->warna = new cField('inventory', 'inventory', 'x_warna', 'warna', '`warna`', '`warna`', 200, -1, FALSE, '`warna`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->warna->Sortable = TRUE; // Allow sort
		$this->fields['warna'] = &$this->warna;

		// tanggal_masuk
		$this->tanggal_masuk = new cField('inventory', 'inventory', 'x_tanggal_masuk', 'tanggal_masuk', '`tanggal_masuk`', ew_CastDateFieldForLike('`tanggal_masuk`', 0, "DB"), 133, 0, FALSE, '`tanggal_masuk`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tanggal_masuk->Sortable = TRUE; // Allow sort
		$this->tanggal_masuk->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['tanggal_masuk'] = &$this->tanggal_masuk;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`inventory`";
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
			$this->inventory_id->setDbValue($conn->Insert_ID());
			$rs['inventory_id'] = $this->inventory_id->DbValue;
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
			if (array_key_exists('inventory_id', $rs))
				ew_AddFilter($where, ew_QuotedName('inventory_id', $this->DBID) . '=' . ew_QuotedValue($rs['inventory_id'], $this->inventory_id->FldDataType, $this->DBID));
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
		return "`inventory_id` = @inventory_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->inventory_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@inventory_id@", ew_AdjustSql($this->inventory_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "inventorylist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "inventorylist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("inventoryview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("inventoryview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "inventoryadd.php?" . $this->UrlParm($parm);
		else
			$url = "inventoryadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("inventoryedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("inventoryadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("inventorydelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "inventory_id:" . ew_VarToJson($this->inventory_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->inventory_id->CurrentValue)) {
			$sUrl .= "inventory_id=" . urlencode($this->inventory_id->CurrentValue);
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
			if ($isPost && isset($_POST["inventory_id"]))
				$arKeys[] = ew_StripSlashes($_POST["inventory_id"]);
			elseif (isset($_GET["inventory_id"]))
				$arKeys[] = ew_StripSlashes($_GET["inventory_id"]);
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
			$this->inventory_id->CurrentValue = $key;
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
		$this->inventory_id->setDbValue($rs->fields('inventory_id'));
		$this->deskripsi_item->setDbValue($rs->fields('deskripsi_item'));
		$this->kuantitas->setDbValue($rs->fields('kuantitas'));
		$this->satuan_unit->setDbValue($rs->fields('satuan_unit'));
		$this->jenis->setDbValue($rs->fields('jenis'));
		$this->warna->setDbValue($rs->fields('warna'));
		$this->tanggal_masuk->setDbValue($rs->fields('tanggal_masuk'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// inventory_id
		// deskripsi_item
		// kuantitas
		// satuan_unit
		// jenis
		// warna
		// tanggal_masuk
		// inventory_id

		$this->inventory_id->ViewValue = $this->inventory_id->CurrentValue;
		$this->inventory_id->ViewCustomAttributes = "";

		// deskripsi_item
		$this->deskripsi_item->ViewValue = $this->deskripsi_item->CurrentValue;
		$this->deskripsi_item->ViewCustomAttributes = "";

		// kuantitas
		$this->kuantitas->ViewValue = $this->kuantitas->CurrentValue;
		$this->kuantitas->ViewCustomAttributes = "";

		// satuan_unit
		if (strval($this->satuan_unit->CurrentValue) <> "") {
			$this->satuan_unit->ViewValue = $this->satuan_unit->OptionCaption($this->satuan_unit->CurrentValue);
		} else {
			$this->satuan_unit->ViewValue = NULL;
		}
		$this->satuan_unit->ViewCustomAttributes = "";

		// jenis
		if (strval($this->jenis->CurrentValue) <> "") {
			$sFilterWrk = "`jenis_bahan`" . ew_SearchString("=", $this->jenis->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT DISTINCT `jenis_bahan`, `jenis_bahan` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `masterdata`";
		$sWhereWrk = "";
		$this->jenis->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->jenis, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->jenis->ViewValue = $this->jenis->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->jenis->ViewValue = $this->jenis->CurrentValue;
			}
		} else {
			$this->jenis->ViewValue = NULL;
		}
		$this->jenis->ViewCustomAttributes = "";

		// warna
		$this->warna->ViewValue = $this->warna->CurrentValue;
		$this->warna->ViewCustomAttributes = "";

		// tanggal_masuk
		$this->tanggal_masuk->ViewValue = $this->tanggal_masuk->CurrentValue;
		$this->tanggal_masuk->ViewValue = ew_FormatDateTime($this->tanggal_masuk->ViewValue, 0);
		$this->tanggal_masuk->ViewCustomAttributes = "";

		// inventory_id
		$this->inventory_id->LinkCustomAttributes = "";
		$this->inventory_id->HrefValue = "";
		$this->inventory_id->TooltipValue = "";

		// deskripsi_item
		$this->deskripsi_item->LinkCustomAttributes = "";
		$this->deskripsi_item->HrefValue = "";
		$this->deskripsi_item->TooltipValue = "";

		// kuantitas
		$this->kuantitas->LinkCustomAttributes = "";
		$this->kuantitas->HrefValue = "";
		$this->kuantitas->TooltipValue = "";

		// satuan_unit
		$this->satuan_unit->LinkCustomAttributes = "";
		$this->satuan_unit->HrefValue = "";
		$this->satuan_unit->TooltipValue = "";

		// jenis
		$this->jenis->LinkCustomAttributes = "";
		$this->jenis->HrefValue = "";
		$this->jenis->TooltipValue = "";

		// warna
		$this->warna->LinkCustomAttributes = "";
		$this->warna->HrefValue = "";
		$this->warna->TooltipValue = "";

		// tanggal_masuk
		$this->tanggal_masuk->LinkCustomAttributes = "";
		$this->tanggal_masuk->HrefValue = "";
		$this->tanggal_masuk->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// inventory_id
		$this->inventory_id->EditAttrs["class"] = "form-control";
		$this->inventory_id->EditCustomAttributes = "";
		$this->inventory_id->EditValue = $this->inventory_id->CurrentValue;
		$this->inventory_id->ViewCustomAttributes = "";

		// deskripsi_item
		$this->deskripsi_item->EditAttrs["class"] = "form-control";
		$this->deskripsi_item->EditCustomAttributes = "";
		$this->deskripsi_item->EditValue = $this->deskripsi_item->CurrentValue;
		$this->deskripsi_item->ViewCustomAttributes = "";

		// kuantitas
		$this->kuantitas->EditAttrs["class"] = "form-control";
		$this->kuantitas->EditCustomAttributes = "";
		$this->kuantitas->EditValue = $this->kuantitas->CurrentValue;
		$this->kuantitas->PlaceHolder = ew_RemoveHtml($this->kuantitas->FldCaption());

		// satuan_unit
		$this->satuan_unit->EditAttrs["class"] = "form-control";
		$this->satuan_unit->EditCustomAttributes = "";
		if (strval($this->satuan_unit->CurrentValue) <> "") {
			$this->satuan_unit->EditValue = $this->satuan_unit->OptionCaption($this->satuan_unit->CurrentValue);
		} else {
			$this->satuan_unit->EditValue = NULL;
		}
		$this->satuan_unit->ViewCustomAttributes = "";

		// jenis
		$this->jenis->EditAttrs["class"] = "form-control";
		$this->jenis->EditCustomAttributes = "";
		if (strval($this->jenis->CurrentValue) <> "") {
			$sFilterWrk = "`jenis_bahan`" . ew_SearchString("=", $this->jenis->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT DISTINCT `jenis_bahan`, `jenis_bahan` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `masterdata`";
		$sWhereWrk = "";
		$this->jenis->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->jenis, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->jenis->EditValue = $this->jenis->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->jenis->EditValue = $this->jenis->CurrentValue;
			}
		} else {
			$this->jenis->EditValue = NULL;
		}
		$this->jenis->ViewCustomAttributes = "";

		// warna
		$this->warna->EditAttrs["class"] = "form-control";
		$this->warna->EditCustomAttributes = "";
		$this->warna->EditValue = $this->warna->CurrentValue;
		$this->warna->ViewCustomAttributes = "";

		// tanggal_masuk
		$this->tanggal_masuk->EditAttrs["class"] = "form-control";
		$this->tanggal_masuk->EditCustomAttributes = "";
		$this->tanggal_masuk->EditValue = ew_FormatDateTime($this->tanggal_masuk->CurrentValue, 8);
		$this->tanggal_masuk->PlaceHolder = ew_RemoveHtml($this->tanggal_masuk->FldCaption());

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
					if ($this->inventory_id->Exportable) $Doc->ExportCaption($this->inventory_id);
					if ($this->deskripsi_item->Exportable) $Doc->ExportCaption($this->deskripsi_item);
					if ($this->kuantitas->Exportable) $Doc->ExportCaption($this->kuantitas);
					if ($this->satuan_unit->Exportable) $Doc->ExportCaption($this->satuan_unit);
					if ($this->jenis->Exportable) $Doc->ExportCaption($this->jenis);
					if ($this->warna->Exportable) $Doc->ExportCaption($this->warna);
					if ($this->tanggal_masuk->Exportable) $Doc->ExportCaption($this->tanggal_masuk);
				} else {
					if ($this->inventory_id->Exportable) $Doc->ExportCaption($this->inventory_id);
					if ($this->deskripsi_item->Exportable) $Doc->ExportCaption($this->deskripsi_item);
					if ($this->kuantitas->Exportable) $Doc->ExportCaption($this->kuantitas);
					if ($this->satuan_unit->Exportable) $Doc->ExportCaption($this->satuan_unit);
					if ($this->jenis->Exportable) $Doc->ExportCaption($this->jenis);
					if ($this->warna->Exportable) $Doc->ExportCaption($this->warna);
					if ($this->tanggal_masuk->Exportable) $Doc->ExportCaption($this->tanggal_masuk);
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
						if ($this->inventory_id->Exportable) $Doc->ExportField($this->inventory_id);
						if ($this->deskripsi_item->Exportable) $Doc->ExportField($this->deskripsi_item);
						if ($this->kuantitas->Exportable) $Doc->ExportField($this->kuantitas);
						if ($this->satuan_unit->Exportable) $Doc->ExportField($this->satuan_unit);
						if ($this->jenis->Exportable) $Doc->ExportField($this->jenis);
						if ($this->warna->Exportable) $Doc->ExportField($this->warna);
						if ($this->tanggal_masuk->Exportable) $Doc->ExportField($this->tanggal_masuk);
					} else {
						if ($this->inventory_id->Exportable) $Doc->ExportField($this->inventory_id);
						if ($this->deskripsi_item->Exportable) $Doc->ExportField($this->deskripsi_item);
						if ($this->kuantitas->Exportable) $Doc->ExportField($this->kuantitas);
						if ($this->satuan_unit->Exportable) $Doc->ExportField($this->satuan_unit);
						if ($this->jenis->Exportable) $Doc->ExportField($this->jenis);
						if ($this->warna->Exportable) $Doc->ExportField($this->warna);
						if ($this->tanggal_masuk->Exportable) $Doc->ExportField($this->tanggal_masuk);
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
