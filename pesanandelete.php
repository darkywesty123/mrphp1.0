<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "pesananinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$pesanan_delete = NULL; // Initialize page object first

class cpesanan_delete extends cpesanan {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{CB0737F4-C35F-4485-A00D-4D7E8040366B}";

	// Table name
	var $TableName = 'pesanan';

	// Page object name
	var $PageObjName = 'pesanan_delete';

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
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (pesanan)
		if (!isset($GLOBALS["pesanan"]) || get_class($GLOBALS["pesanan"]) == "cpesanan") {
			$GLOBALS["pesanan"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pesanan"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pesanan', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->order_id->SetVisibility();
		$this->order_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->nama_pemesan->SetVisibility();
		$this->tanggal_order->SetVisibility();
		$this->tanggal_selesai->SetVisibility();
		$this->jenis_barang->SetVisibility();
		$this->jenis_bahan->SetVisibility();
		$this->warna_bahan->SetVisibility();
		$this->harga_barang->SetVisibility();
		$this->jumlah_barang->SetVisibility();
		$this->discount->SetVisibility();
		$this->total_harga->SetVisibility();
		$this->status_pembayaran->SetVisibility();
		$this->status_order->SetVisibility();
		$this->upload_link->SetVisibility();

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
		global $EW_EXPORT, $pesanan;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($pesanan);
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
			$this->Page_Terminate("pesananlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in pesanan class, pesananinfo.php

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
				$this->Page_Terminate("pesananlist.php"); // Return to list
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
		$this->order_id->setDbValue($rs->fields('order_id'));
		$this->nama_pemesan->setDbValue($rs->fields('nama_pemesan'));
		$this->tanggal_order->setDbValue($rs->fields('tanggal_order'));
		$this->tanggal_selesai->setDbValue($rs->fields('tanggal_selesai'));
		$this->jenis_barang->setDbValue($rs->fields('jenis_barang'));
		$this->jenis_bahan->setDbValue($rs->fields('jenis_bahan'));
		$this->warna_bahan->setDbValue($rs->fields('warna_bahan'));
		$this->harga_barang->setDbValue($rs->fields('harga_barang'));
		$this->jumlah_barang->setDbValue($rs->fields('jumlah_barang'));
		$this->discount->setDbValue($rs->fields('discount'));
		$this->total_harga->setDbValue($rs->fields('total_harga'));
		$this->status_pembayaran->setDbValue($rs->fields('status_pembayaran'));
		$this->status_order->setDbValue($rs->fields('status_order'));
		$this->upload_link->setDbValue($rs->fields('upload_link'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->order_id->DbValue = $row['order_id'];
		$this->nama_pemesan->DbValue = $row['nama_pemesan'];
		$this->tanggal_order->DbValue = $row['tanggal_order'];
		$this->tanggal_selesai->DbValue = $row['tanggal_selesai'];
		$this->jenis_barang->DbValue = $row['jenis_barang'];
		$this->jenis_bahan->DbValue = $row['jenis_bahan'];
		$this->warna_bahan->DbValue = $row['warna_bahan'];
		$this->harga_barang->DbValue = $row['harga_barang'];
		$this->jumlah_barang->DbValue = $row['jumlah_barang'];
		$this->discount->DbValue = $row['discount'];
		$this->total_harga->DbValue = $row['total_harga'];
		$this->status_pembayaran->DbValue = $row['status_pembayaran'];
		$this->status_order->DbValue = $row['status_order'];
		$this->upload_link->DbValue = $row['upload_link'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// order_id
		// nama_pemesan
		// tanggal_order
		// tanggal_selesai
		// jenis_barang
		// jenis_bahan
		// warna_bahan
		// harga_barang
		// jumlah_barang
		// discount
		// total_harga
		// status_pembayaran
		// status_order
		// upload_link

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// order_id
		$this->order_id->ViewValue = $this->order_id->CurrentValue;
		$this->order_id->ViewCustomAttributes = "";

		// nama_pemesan
		$this->nama_pemesan->ViewValue = $this->nama_pemesan->CurrentValue;
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
		$this->jenis_barang->ViewValue = $this->jenis_barang->CurrentValue;
		$this->jenis_barang->ViewCustomAttributes = "";

		// jenis_bahan
		$this->jenis_bahan->ViewValue = $this->jenis_bahan->CurrentValue;
		$this->jenis_bahan->ViewCustomAttributes = "";

		// warna_bahan
		$this->warna_bahan->ViewValue = $this->warna_bahan->CurrentValue;
		$this->warna_bahan->ViewCustomAttributes = "";

		// harga_barang
		$this->harga_barang->ViewValue = $this->harga_barang->CurrentValue;
		$this->harga_barang->ViewCustomAttributes = "";

		// jumlah_barang
		$this->jumlah_barang->ViewValue = $this->jumlah_barang->CurrentValue;
		$this->jumlah_barang->ViewCustomAttributes = "";

		// discount
		$this->discount->ViewValue = $this->discount->CurrentValue;
		$this->discount->ViewCustomAttributes = "";

		// total_harga
		$this->total_harga->ViewValue = $this->total_harga->CurrentValue;
		$this->total_harga->ViewCustomAttributes = "";

		// status_pembayaran
		$this->status_pembayaran->ViewValue = $this->status_pembayaran->CurrentValue;
		$this->status_pembayaran->ViewCustomAttributes = "";

		// status_order
		$this->status_order->ViewValue = $this->status_order->CurrentValue;
		$this->status_order->ViewCustomAttributes = "";

		// upload_link
		$this->upload_link->ViewValue = $this->upload_link->CurrentValue;
		$this->upload_link->ViewCustomAttributes = "";

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

			// discount
			$this->discount->LinkCustomAttributes = "";
			$this->discount->HrefValue = "";
			$this->discount->TooltipValue = "";

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
			$this->upload_link->HrefValue = "";
			$this->upload_link->TooltipValue = "";
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
				$sThisKey .= $row['order_id'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("pesananlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($pesanan_delete)) $pesanan_delete = new cpesanan_delete();

// Page init
$pesanan_delete->Page_Init();

// Page main
$pesanan_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pesanan_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fpesanandelete = new ew_Form("fpesanandelete", "delete");

// Form_CustomValidate event
fpesanandelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpesanandelete.ValidateRequired = true;
<?php } else { ?>
fpesanandelete.ValidateRequired = false; 
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
<?php $pesanan_delete->ShowPageHeader(); ?>
<?php
$pesanan_delete->ShowMessage();
?>
<form name="fpesanandelete" id="fpesanandelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pesanan_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pesanan_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pesanan">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($pesanan_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $pesanan->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($pesanan->order_id->Visible) { // order_id ?>
		<th><span id="elh_pesanan_order_id" class="pesanan_order_id"><?php echo $pesanan->order_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pesanan->nama_pemesan->Visible) { // nama_pemesan ?>
		<th><span id="elh_pesanan_nama_pemesan" class="pesanan_nama_pemesan"><?php echo $pesanan->nama_pemesan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pesanan->tanggal_order->Visible) { // tanggal_order ?>
		<th><span id="elh_pesanan_tanggal_order" class="pesanan_tanggal_order"><?php echo $pesanan->tanggal_order->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pesanan->tanggal_selesai->Visible) { // tanggal_selesai ?>
		<th><span id="elh_pesanan_tanggal_selesai" class="pesanan_tanggal_selesai"><?php echo $pesanan->tanggal_selesai->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pesanan->jenis_barang->Visible) { // jenis_barang ?>
		<th><span id="elh_pesanan_jenis_barang" class="pesanan_jenis_barang"><?php echo $pesanan->jenis_barang->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pesanan->jenis_bahan->Visible) { // jenis_bahan ?>
		<th><span id="elh_pesanan_jenis_bahan" class="pesanan_jenis_bahan"><?php echo $pesanan->jenis_bahan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pesanan->warna_bahan->Visible) { // warna_bahan ?>
		<th><span id="elh_pesanan_warna_bahan" class="pesanan_warna_bahan"><?php echo $pesanan->warna_bahan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pesanan->harga_barang->Visible) { // harga_barang ?>
		<th><span id="elh_pesanan_harga_barang" class="pesanan_harga_barang"><?php echo $pesanan->harga_barang->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pesanan->jumlah_barang->Visible) { // jumlah_barang ?>
		<th><span id="elh_pesanan_jumlah_barang" class="pesanan_jumlah_barang"><?php echo $pesanan->jumlah_barang->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pesanan->discount->Visible) { // discount ?>
		<th><span id="elh_pesanan_discount" class="pesanan_discount"><?php echo $pesanan->discount->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pesanan->total_harga->Visible) { // total_harga ?>
		<th><span id="elh_pesanan_total_harga" class="pesanan_total_harga"><?php echo $pesanan->total_harga->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pesanan->status_pembayaran->Visible) { // status_pembayaran ?>
		<th><span id="elh_pesanan_status_pembayaran" class="pesanan_status_pembayaran"><?php echo $pesanan->status_pembayaran->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pesanan->status_order->Visible) { // status_order ?>
		<th><span id="elh_pesanan_status_order" class="pesanan_status_order"><?php echo $pesanan->status_order->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pesanan->upload_link->Visible) { // upload_link ?>
		<th><span id="elh_pesanan_upload_link" class="pesanan_upload_link"><?php echo $pesanan->upload_link->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$pesanan_delete->RecCnt = 0;
$i = 0;
while (!$pesanan_delete->Recordset->EOF) {
	$pesanan_delete->RecCnt++;
	$pesanan_delete->RowCnt++;

	// Set row properties
	$pesanan->ResetAttrs();
	$pesanan->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$pesanan_delete->LoadRowValues($pesanan_delete->Recordset);

	// Render row
	$pesanan_delete->RenderRow();
?>
	<tr<?php echo $pesanan->RowAttributes() ?>>
<?php if ($pesanan->order_id->Visible) { // order_id ?>
		<td<?php echo $pesanan->order_id->CellAttributes() ?>>
<span id="el<?php echo $pesanan_delete->RowCnt ?>_pesanan_order_id" class="pesanan_order_id">
<span<?php echo $pesanan->order_id->ViewAttributes() ?>>
<?php echo $pesanan->order_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pesanan->nama_pemesan->Visible) { // nama_pemesan ?>
		<td<?php echo $pesanan->nama_pemesan->CellAttributes() ?>>
<span id="el<?php echo $pesanan_delete->RowCnt ?>_pesanan_nama_pemesan" class="pesanan_nama_pemesan">
<span<?php echo $pesanan->nama_pemesan->ViewAttributes() ?>>
<?php echo $pesanan->nama_pemesan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pesanan->tanggal_order->Visible) { // tanggal_order ?>
		<td<?php echo $pesanan->tanggal_order->CellAttributes() ?>>
<span id="el<?php echo $pesanan_delete->RowCnt ?>_pesanan_tanggal_order" class="pesanan_tanggal_order">
<span<?php echo $pesanan->tanggal_order->ViewAttributes() ?>>
<?php echo $pesanan->tanggal_order->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pesanan->tanggal_selesai->Visible) { // tanggal_selesai ?>
		<td<?php echo $pesanan->tanggal_selesai->CellAttributes() ?>>
<span id="el<?php echo $pesanan_delete->RowCnt ?>_pesanan_tanggal_selesai" class="pesanan_tanggal_selesai">
<span<?php echo $pesanan->tanggal_selesai->ViewAttributes() ?>>
<?php echo $pesanan->tanggal_selesai->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pesanan->jenis_barang->Visible) { // jenis_barang ?>
		<td<?php echo $pesanan->jenis_barang->CellAttributes() ?>>
<span id="el<?php echo $pesanan_delete->RowCnt ?>_pesanan_jenis_barang" class="pesanan_jenis_barang">
<span<?php echo $pesanan->jenis_barang->ViewAttributes() ?>>
<?php echo $pesanan->jenis_barang->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pesanan->jenis_bahan->Visible) { // jenis_bahan ?>
		<td<?php echo $pesanan->jenis_bahan->CellAttributes() ?>>
<span id="el<?php echo $pesanan_delete->RowCnt ?>_pesanan_jenis_bahan" class="pesanan_jenis_bahan">
<span<?php echo $pesanan->jenis_bahan->ViewAttributes() ?>>
<?php echo $pesanan->jenis_bahan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pesanan->warna_bahan->Visible) { // warna_bahan ?>
		<td<?php echo $pesanan->warna_bahan->CellAttributes() ?>>
<span id="el<?php echo $pesanan_delete->RowCnt ?>_pesanan_warna_bahan" class="pesanan_warna_bahan">
<span<?php echo $pesanan->warna_bahan->ViewAttributes() ?>>
<?php echo $pesanan->warna_bahan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pesanan->harga_barang->Visible) { // harga_barang ?>
		<td<?php echo $pesanan->harga_barang->CellAttributes() ?>>
<span id="el<?php echo $pesanan_delete->RowCnt ?>_pesanan_harga_barang" class="pesanan_harga_barang">
<span<?php echo $pesanan->harga_barang->ViewAttributes() ?>>
<?php echo $pesanan->harga_barang->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pesanan->jumlah_barang->Visible) { // jumlah_barang ?>
		<td<?php echo $pesanan->jumlah_barang->CellAttributes() ?>>
<span id="el<?php echo $pesanan_delete->RowCnt ?>_pesanan_jumlah_barang" class="pesanan_jumlah_barang">
<span<?php echo $pesanan->jumlah_barang->ViewAttributes() ?>>
<?php echo $pesanan->jumlah_barang->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pesanan->discount->Visible) { // discount ?>
		<td<?php echo $pesanan->discount->CellAttributes() ?>>
<span id="el<?php echo $pesanan_delete->RowCnt ?>_pesanan_discount" class="pesanan_discount">
<span<?php echo $pesanan->discount->ViewAttributes() ?>>
<?php echo $pesanan->discount->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pesanan->total_harga->Visible) { // total_harga ?>
		<td<?php echo $pesanan->total_harga->CellAttributes() ?>>
<span id="el<?php echo $pesanan_delete->RowCnt ?>_pesanan_total_harga" class="pesanan_total_harga">
<span<?php echo $pesanan->total_harga->ViewAttributes() ?>>
<?php echo $pesanan->total_harga->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pesanan->status_pembayaran->Visible) { // status_pembayaran ?>
		<td<?php echo $pesanan->status_pembayaran->CellAttributes() ?>>
<span id="el<?php echo $pesanan_delete->RowCnt ?>_pesanan_status_pembayaran" class="pesanan_status_pembayaran">
<span<?php echo $pesanan->status_pembayaran->ViewAttributes() ?>>
<?php echo $pesanan->status_pembayaran->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pesanan->status_order->Visible) { // status_order ?>
		<td<?php echo $pesanan->status_order->CellAttributes() ?>>
<span id="el<?php echo $pesanan_delete->RowCnt ?>_pesanan_status_order" class="pesanan_status_order">
<span<?php echo $pesanan->status_order->ViewAttributes() ?>>
<?php echo $pesanan->status_order->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pesanan->upload_link->Visible) { // upload_link ?>
		<td<?php echo $pesanan->upload_link->CellAttributes() ?>>
<span id="el<?php echo $pesanan_delete->RowCnt ?>_pesanan_upload_link" class="pesanan_upload_link">
<span<?php echo $pesanan->upload_link->ViewAttributes() ?>>
<?php echo $pesanan->upload_link->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$pesanan_delete->Recordset->MoveNext();
}
$pesanan_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $pesanan_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fpesanandelete.Init();
</script>
<?php
$pesanan_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pesanan_delete->Page_Terminate();
?>
