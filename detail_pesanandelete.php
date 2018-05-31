<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "detail_pesananinfo.php" ?>
<?php include_once "admininfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$detail_pesanan_delete = NULL; // Initialize page object first

class cdetail_pesanan_delete extends cdetail_pesanan {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{daeb3313-6eb8-4d86-8407-209c5321b7cc}";

	// Table name
	var $TableName = 'detail_pesanan';

	// Page object name
	var $PageObjName = 'detail_pesanan_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (detail_pesanan)
		if (!isset($GLOBALS["detail_pesanan"]) || get_class($GLOBALS["detail_pesanan"]) == "cdetail_pesanan") {
			$GLOBALS["detail_pesanan"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["detail_pesanan"];
		}

		// Table object (admin)
		if (!isset($GLOBALS['admin'])) $GLOBALS['admin'] = new cadmin();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'detail_pesanan', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (admin)
		if (!isset($UserTable)) {
			$UserTable = new cadmin();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("detail_pesananlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id_pesanan->SetVisibility();
		$this->nomor_so->SetVisibility();
		$this->nama_barang->SetVisibility();
		$this->detail_barang->SetVisibility();
		$this->jumlah_barang->SetVisibility();
		$this->harga_barang->SetVisibility();
		$this->gambar->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $detail_pesanan;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($detail_pesanan);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("detail_pesananlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in detail_pesanan class, detail_pesananinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("detail_pesananlist.php"); // Return to list
			}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->id_pesanan->setDbValue($rs->fields('id_pesanan'));
		$this->nomor_so->setDbValue($rs->fields('nomor_so'));
		$this->nama_barang->setDbValue($rs->fields('nama_barang'));
		$this->detail_barang->setDbValue($rs->fields('detail_barang'));
		$this->jumlah_barang->setDbValue($rs->fields('jumlah_barang'));
		$this->harga_barang->setDbValue($rs->fields('harga_barang'));
		$this->gambar->setDbValue($rs->fields('gambar'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_pesanan->DbValue = $row['id_pesanan'];
		$this->nomor_so->DbValue = $row['nomor_so'];
		$this->nama_barang->DbValue = $row['nama_barang'];
		$this->detail_barang->DbValue = $row['detail_barang'];
		$this->jumlah_barang->DbValue = $row['jumlah_barang'];
		$this->harga_barang->DbValue = $row['harga_barang'];
		$this->gambar->DbValue = $row['gambar'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_pesanan
		// nomor_so
		// nama_barang
		// detail_barang
		// jumlah_barang
		// harga_barang
		// gambar

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id_pesanan
		$this->id_pesanan->ViewValue = $this->id_pesanan->CurrentValue;
		$this->id_pesanan->ViewCustomAttributes = "";

		// nomor_so
		$this->nomor_so->ViewValue = $this->nomor_so->CurrentValue;
		$this->nomor_so->ViewCustomAttributes = "";

		// nama_barang
		$this->nama_barang->ViewValue = $this->nama_barang->CurrentValue;
		$this->nama_barang->ViewCustomAttributes = "";

		// detail_barang
		$this->detail_barang->ViewValue = $this->detail_barang->CurrentValue;
		$this->detail_barang->ViewCustomAttributes = "";

		// jumlah_barang
		$this->jumlah_barang->ViewValue = $this->jumlah_barang->CurrentValue;
		$this->jumlah_barang->ViewCustomAttributes = "";

		// harga_barang
		$this->harga_barang->ViewValue = $this->harga_barang->CurrentValue;
		$this->harga_barang->ViewCustomAttributes = "";

		// gambar
		$this->gambar->ViewValue = $this->gambar->CurrentValue;
		$this->gambar->ViewCustomAttributes = "";

			// id_pesanan
			$this->id_pesanan->LinkCustomAttributes = "";
			$this->id_pesanan->HrefValue = "";
			$this->id_pesanan->TooltipValue = "";

			// nomor_so
			$this->nomor_so->LinkCustomAttributes = "";
			$this->nomor_so->HrefValue = "";
			$this->nomor_so->TooltipValue = "";

			// nama_barang
			$this->nama_barang->LinkCustomAttributes = "";
			$this->nama_barang->HrefValue = "";
			$this->nama_barang->TooltipValue = "";

			// detail_barang
			$this->detail_barang->LinkCustomAttributes = "";
			$this->detail_barang->HrefValue = "";
			$this->detail_barang->TooltipValue = "";

			// jumlah_barang
			$this->jumlah_barang->LinkCustomAttributes = "";
			$this->jumlah_barang->HrefValue = "";
			$this->jumlah_barang->TooltipValue = "";

			// harga_barang
			$this->harga_barang->LinkCustomAttributes = "";
			$this->harga_barang->HrefValue = "";
			$this->harga_barang->TooltipValue = "";

			// gambar
			$this->gambar->LinkCustomAttributes = "";
			$this->gambar->HrefValue = "";
			$this->gambar->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id_pesanan'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['nomor_so'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("detail_pesananlist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($detail_pesanan_delete)) $detail_pesanan_delete = new cdetail_pesanan_delete();

// Page init
$detail_pesanan_delete->Page_Init();

// Page main
$detail_pesanan_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$detail_pesanan_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fdetail_pesanandelete = new ew_Form("fdetail_pesanandelete", "delete");

// Form_CustomValidate event
fdetail_pesanandelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdetail_pesanandelete.ValidateRequired = true;
<?php } else { ?>
fdetail_pesanandelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $detail_pesanan_delete->ShowPageHeader(); ?>
<?php
$detail_pesanan_delete->ShowMessage();
?>
<form name="fdetail_pesanandelete" id="fdetail_pesanandelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($detail_pesanan_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $detail_pesanan_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="detail_pesanan">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($detail_pesanan_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $detail_pesanan->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($detail_pesanan->id_pesanan->Visible) { // id_pesanan ?>
		<th><span id="elh_detail_pesanan_id_pesanan" class="detail_pesanan_id_pesanan"><?php echo $detail_pesanan->id_pesanan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($detail_pesanan->nomor_so->Visible) { // nomor_so ?>
		<th><span id="elh_detail_pesanan_nomor_so" class="detail_pesanan_nomor_so"><?php echo $detail_pesanan->nomor_so->FldCaption() ?></span></th>
<?php } ?>
<?php if ($detail_pesanan->nama_barang->Visible) { // nama_barang ?>
		<th><span id="elh_detail_pesanan_nama_barang" class="detail_pesanan_nama_barang"><?php echo $detail_pesanan->nama_barang->FldCaption() ?></span></th>
<?php } ?>
<?php if ($detail_pesanan->detail_barang->Visible) { // detail_barang ?>
		<th><span id="elh_detail_pesanan_detail_barang" class="detail_pesanan_detail_barang"><?php echo $detail_pesanan->detail_barang->FldCaption() ?></span></th>
<?php } ?>
<?php if ($detail_pesanan->jumlah_barang->Visible) { // jumlah_barang ?>
		<th><span id="elh_detail_pesanan_jumlah_barang" class="detail_pesanan_jumlah_barang"><?php echo $detail_pesanan->jumlah_barang->FldCaption() ?></span></th>
<?php } ?>
<?php if ($detail_pesanan->harga_barang->Visible) { // harga_barang ?>
		<th><span id="elh_detail_pesanan_harga_barang" class="detail_pesanan_harga_barang"><?php echo $detail_pesanan->harga_barang->FldCaption() ?></span></th>
<?php } ?>
<?php if ($detail_pesanan->gambar->Visible) { // gambar ?>
		<th><span id="elh_detail_pesanan_gambar" class="detail_pesanan_gambar"><?php echo $detail_pesanan->gambar->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$detail_pesanan_delete->RecCnt = 0;
$i = 0;
while (!$detail_pesanan_delete->Recordset->EOF) {
	$detail_pesanan_delete->RecCnt++;
	$detail_pesanan_delete->RowCnt++;

	// Set row properties
	$detail_pesanan->ResetAttrs();
	$detail_pesanan->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$detail_pesanan_delete->LoadRowValues($detail_pesanan_delete->Recordset);

	// Render row
	$detail_pesanan_delete->RenderRow();
?>
	<tr<?php echo $detail_pesanan->RowAttributes() ?>>
<?php if ($detail_pesanan->id_pesanan->Visible) { // id_pesanan ?>
		<td<?php echo $detail_pesanan->id_pesanan->CellAttributes() ?>>
<span id="el<?php echo $detail_pesanan_delete->RowCnt ?>_detail_pesanan_id_pesanan" class="detail_pesanan_id_pesanan">
<span<?php echo $detail_pesanan->id_pesanan->ViewAttributes() ?>>
<?php echo $detail_pesanan->id_pesanan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($detail_pesanan->nomor_so->Visible) { // nomor_so ?>
		<td<?php echo $detail_pesanan->nomor_so->CellAttributes() ?>>
<span id="el<?php echo $detail_pesanan_delete->RowCnt ?>_detail_pesanan_nomor_so" class="detail_pesanan_nomor_so">
<span<?php echo $detail_pesanan->nomor_so->ViewAttributes() ?>>
<?php echo $detail_pesanan->nomor_so->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($detail_pesanan->nama_barang->Visible) { // nama_barang ?>
		<td<?php echo $detail_pesanan->nama_barang->CellAttributes() ?>>
<span id="el<?php echo $detail_pesanan_delete->RowCnt ?>_detail_pesanan_nama_barang" class="detail_pesanan_nama_barang">
<span<?php echo $detail_pesanan->nama_barang->ViewAttributes() ?>>
<?php echo $detail_pesanan->nama_barang->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($detail_pesanan->detail_barang->Visible) { // detail_barang ?>
		<td<?php echo $detail_pesanan->detail_barang->CellAttributes() ?>>
<span id="el<?php echo $detail_pesanan_delete->RowCnt ?>_detail_pesanan_detail_barang" class="detail_pesanan_detail_barang">
<span<?php echo $detail_pesanan->detail_barang->ViewAttributes() ?>>
<?php echo $detail_pesanan->detail_barang->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($detail_pesanan->jumlah_barang->Visible) { // jumlah_barang ?>
		<td<?php echo $detail_pesanan->jumlah_barang->CellAttributes() ?>>
<span id="el<?php echo $detail_pesanan_delete->RowCnt ?>_detail_pesanan_jumlah_barang" class="detail_pesanan_jumlah_barang">
<span<?php echo $detail_pesanan->jumlah_barang->ViewAttributes() ?>>
<?php echo $detail_pesanan->jumlah_barang->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($detail_pesanan->harga_barang->Visible) { // harga_barang ?>
		<td<?php echo $detail_pesanan->harga_barang->CellAttributes() ?>>
<span id="el<?php echo $detail_pesanan_delete->RowCnt ?>_detail_pesanan_harga_barang" class="detail_pesanan_harga_barang">
<span<?php echo $detail_pesanan->harga_barang->ViewAttributes() ?>>
<?php echo $detail_pesanan->harga_barang->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($detail_pesanan->gambar->Visible) { // gambar ?>
		<td<?php echo $detail_pesanan->gambar->CellAttributes() ?>>
<span id="el<?php echo $detail_pesanan_delete->RowCnt ?>_detail_pesanan_gambar" class="detail_pesanan_gambar">
<span<?php echo $detail_pesanan->gambar->ViewAttributes() ?>>
<?php echo $detail_pesanan->gambar->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$detail_pesanan_delete->Recordset->MoveNext();
}
$detail_pesanan_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $detail_pesanan_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fdetail_pesanandelete.Init();
</script>
<?php
$detail_pesanan_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$detail_pesanan_delete->Page_Terminate();
?>
