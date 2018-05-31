<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "pesananinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$pesanan_edit = NULL; // Initialize page object first

class cpesanan_edit extends cpesanan {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{CB0737F4-C35F-4485-A00D-4D7E8040366B}";

	// Table name
	var $TableName = 'pesanan';

	// Page object name
	var $PageObjName = 'pesanan_edit';

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

		// Table object (pesanan)
		if (!isset($GLOBALS["pesanan"]) || get_class($GLOBALS["pesanan"]) == "cpesanan") {
			$GLOBALS["pesanan"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pesanan"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pesanan', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (user)
		if (!isset($UserTable)) {
			$UserTable = new cuser();
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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("pesananlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->nama_pemesan->SetVisibility();
		$this->tanggal_order->SetVisibility();
		$this->tanggal_selesai->SetVisibility();
		$this->jenis_barang->SetVisibility();
		$this->jenis_bahan->SetVisibility();
		$this->warna_bahan->SetVisibility();
		$this->harga_barang->SetVisibility();
		$this->jumlah_barang->SetVisibility();
		$this->total_harga->SetVisibility();
		$this->status_pembayaran->SetVisibility();
		$this->status_order->SetVisibility();
		$this->upload_link->SetVisibility();
		$this->discount->SetVisibility();

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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				echo ew_ArrayToJson(array($row));
			} else {
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load key from QueryString
		if (@$_GET["order_id"] <> "") {
			$this->order_id->setQueryStringValue($_GET["order_id"]);
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->order_id->CurrentValue == "") {
			$this->Page_Terminate("pesananlist.php"); // Invalid key, return to list
		}

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("pesananlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "pesananlist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->upload_link->Upload->Index = $objForm->Index;
		$this->upload_link->Upload->UploadFile();
		$this->upload_link->CurrentValue = $this->upload_link->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->nama_pemesan->FldIsDetailKey) {
			$this->nama_pemesan->setFormValue($objForm->GetValue("x_nama_pemesan"));
		}
		if (!$this->tanggal_order->FldIsDetailKey) {
			$this->tanggal_order->setFormValue($objForm->GetValue("x_tanggal_order"));
			$this->tanggal_order->CurrentValue = ew_UnFormatDateTime($this->tanggal_order->CurrentValue, 0);
		}
		if (!$this->tanggal_selesai->FldIsDetailKey) {
			$this->tanggal_selesai->setFormValue($objForm->GetValue("x_tanggal_selesai"));
			$this->tanggal_selesai->CurrentValue = ew_UnFormatDateTime($this->tanggal_selesai->CurrentValue, 0);
		}
		if (!$this->jenis_barang->FldIsDetailKey) {
			$this->jenis_barang->setFormValue($objForm->GetValue("x_jenis_barang"));
		}
		if (!$this->jenis_bahan->FldIsDetailKey) {
			$this->jenis_bahan->setFormValue($objForm->GetValue("x_jenis_bahan"));
		}
		if (!$this->warna_bahan->FldIsDetailKey) {
			$this->warna_bahan->setFormValue($objForm->GetValue("x_warna_bahan"));
		}
		if (!$this->harga_barang->FldIsDetailKey) {
			$this->harga_barang->setFormValue($objForm->GetValue("x_harga_barang"));
		}
		if (!$this->jumlah_barang->FldIsDetailKey) {
			$this->jumlah_barang->setFormValue($objForm->GetValue("x_jumlah_barang"));
		}
		if (!$this->total_harga->FldIsDetailKey) {
			$this->total_harga->setFormValue($objForm->GetValue("x_total_harga"));
		}
		if (!$this->status_pembayaran->FldIsDetailKey) {
			$this->status_pembayaran->setFormValue($objForm->GetValue("x_status_pembayaran"));
		}
		if (!$this->status_order->FldIsDetailKey) {
			$this->status_order->setFormValue($objForm->GetValue("x_status_order"));
		}
		if (!$this->discount->FldIsDetailKey) {
			$this->discount->setFormValue($objForm->GetValue("x_discount"));
		}
		if (!$this->order_id->FldIsDetailKey)
			$this->order_id->setFormValue($objForm->GetValue("x_order_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->order_id->CurrentValue = $this->order_id->FormValue;
		$this->nama_pemesan->CurrentValue = $this->nama_pemesan->FormValue;
		$this->tanggal_order->CurrentValue = $this->tanggal_order->FormValue;
		$this->tanggal_order->CurrentValue = ew_UnFormatDateTime($this->tanggal_order->CurrentValue, 0);
		$this->tanggal_selesai->CurrentValue = $this->tanggal_selesai->FormValue;
		$this->tanggal_selesai->CurrentValue = ew_UnFormatDateTime($this->tanggal_selesai->CurrentValue, 0);
		$this->jenis_barang->CurrentValue = $this->jenis_barang->FormValue;
		$this->jenis_bahan->CurrentValue = $this->jenis_bahan->FormValue;
		$this->warna_bahan->CurrentValue = $this->warna_bahan->FormValue;
		$this->harga_barang->CurrentValue = $this->harga_barang->FormValue;
		$this->jumlah_barang->CurrentValue = $this->jumlah_barang->FormValue;
		$this->total_harga->CurrentValue = $this->total_harga->FormValue;
		$this->status_pembayaran->CurrentValue = $this->status_pembayaran->FormValue;
		$this->status_order->CurrentValue = $this->status_order->FormValue;
		$this->discount->CurrentValue = $this->discount->FormValue;
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
		$this->total_harga->setDbValue($rs->fields('total_harga'));
		$this->status_pembayaran->setDbValue($rs->fields('status_pembayaran'));
		$this->status_order->setDbValue($rs->fields('status_order'));
		$this->upload_link->Upload->DbValue = $rs->fields('upload_link');
		$this->upload_link->CurrentValue = $this->upload_link->Upload->DbValue;
		$this->discount->setDbValue($rs->fields('discount'));
		$this->deskripsi->setDbValue($rs->fields('deskripsi'));
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
		$this->total_harga->DbValue = $row['total_harga'];
		$this->status_pembayaran->DbValue = $row['status_pembayaran'];
		$this->status_order->DbValue = $row['status_order'];
		$this->upload_link->Upload->DbValue = $row['upload_link'];
		$this->discount->DbValue = $row['discount'];
		$this->deskripsi->DbValue = $row['deskripsi'];
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
		// total_harga
		// status_pembayaran
		// status_order
		// upload_link
		// discount
		// deskripsi

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

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
			$this->tanggal_selesai->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tanggal_selesai->CurrentValue, 8));
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
			$this->jumlah_barang->EditValue = ew_HtmlEncode($this->jumlah_barang->CurrentValue);
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

			// Edit refer script
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

			// total_harga
			$this->total_harga->LinkCustomAttributes = "";
			$this->total_harga->HrefValue = "";
			$this->total_harga->TooltipValue = "";

			// status_pembayaran
			$this->status_pembayaran->LinkCustomAttributes = "";
			$this->status_pembayaran->HrefValue = "";

			// status_order
			$this->status_order->LinkCustomAttributes = "";
			$this->status_order->HrefValue = "";

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
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->tanggal_selesai->FldIsDetailKey && !is_null($this->tanggal_selesai->FormValue) && $this->tanggal_selesai->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tanggal_selesai->FldCaption(), $this->tanggal_selesai->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->tanggal_selesai->FormValue)) {
			ew_AddMessage($gsFormError, $this->tanggal_selesai->FldErrMsg());
		}
		if (!$this->jumlah_barang->FldIsDetailKey && !is_null($this->jumlah_barang->FormValue) && $this->jumlah_barang->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jumlah_barang->FldCaption(), $this->jumlah_barang->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->jumlah_barang->FormValue)) {
			ew_AddMessage($gsFormError, $this->jumlah_barang->FldErrMsg());
		}
		if ($this->status_pembayaran->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->status_pembayaran->FldCaption(), $this->status_pembayaran->ReqErrMsg));
		}
		if ($this->status_order->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->status_order->FldCaption(), $this->status_order->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// tanggal_selesai
			$this->tanggal_selesai->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tanggal_selesai->CurrentValue, 0), NULL, $this->tanggal_selesai->ReadOnly);

			// jumlah_barang
			$this->jumlah_barang->SetDbValueDef($rsnew, $this->jumlah_barang->CurrentValue, "", $this->jumlah_barang->ReadOnly);

			// status_pembayaran
			$this->status_pembayaran->SetDbValueDef($rsnew, $this->status_pembayaran->CurrentValue, "", $this->status_pembayaran->ReadOnly);

			// status_order
			$this->status_order->SetDbValueDef($rsnew, $this->status_order->CurrentValue, "", $this->status_order->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("pesananlist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_jenis_barang":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT DISTINCT `jenis_barang` AS `LinkFld`, `jenis_barang` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `masterdata`";
			$sWhereWrk = "";
			$this->jenis_barang->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`jenis_barang` = {filter_value}', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->jenis_barang, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_jenis_bahan":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `jenis_bahan` AS `LinkFld`, `jenis_bahan` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `masterdata`";
			$sWhereWrk = "{filter}";
			$this->jenis_bahan->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`jenis_bahan` = {filter_value}', "t0" => "200", "fn0" => "", "f1" => '`jenis_barang` IN ({filter_value})', "t1" => "200", "fn1" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->jenis_bahan, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($pesanan_edit)) $pesanan_edit = new cpesanan_edit();

// Page init
$pesanan_edit->Page_Init();

// Page main
$pesanan_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pesanan_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fpesananedit = new ew_Form("fpesananedit", "edit");

// Validate form
fpesananedit.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_tanggal_selesai");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pesanan->tanggal_selesai->FldCaption(), $pesanan->tanggal_selesai->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tanggal_selesai");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pesanan->tanggal_selesai->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jumlah_barang");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pesanan->jumlah_barang->FldCaption(), $pesanan->jumlah_barang->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jumlah_barang");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pesanan->jumlah_barang->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_status_pembayaran");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pesanan->status_pembayaran->FldCaption(), $pesanan->status_pembayaran->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status_order");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pesanan->status_order->FldCaption(), $pesanan->status_order->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fpesananedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpesananedit.ValidateRequired = true;
<?php } else { ?>
fpesananedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpesananedit.Lists["x_nama_pemesan"] = {"LinkField":"x_cust_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nama_pelanggan","x_alamat_pelanggan","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"customer"};
fpesananedit.Lists["x_jenis_barang"] = {"LinkField":"x_jenis_barang","Ajax":true,"AutoFill":false,"DisplayFields":["x_jenis_barang","","",""],"ParentFields":[],"ChildFields":["x_jenis_bahan","x_harga_barang"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"masterdata"};
fpesananedit.Lists["x_jenis_bahan"] = {"LinkField":"x_jenis_bahan","Ajax":true,"AutoFill":false,"DisplayFields":["x_jenis_bahan","","",""],"ParentFields":["x_jenis_barang"],"ChildFields":["x_harga_barang"],"FilterFields":["x_jenis_barang"],"Options":[],"Template":"","LinkTable":"masterdata"};
fpesananedit.Lists["x_harga_barang"] = {"LinkField":"x_harga","Ajax":true,"AutoFill":false,"DisplayFields":["x_harga","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"masterdata"};
fpesananedit.Lists["x_status_pembayaran"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpesananedit.Lists["x_status_pembayaran"].Options = <?php echo json_encode($pesanan->status_pembayaran->Options()) ?>;
fpesananedit.Lists["x_status_order"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpesananedit.Lists["x_status_order"].Options = <?php echo json_encode($pesanan->status_order->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$pesanan_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $pesanan_edit->ShowPageHeader(); ?>
<?php
$pesanan_edit->ShowMessage();
?>
<form name="fpesananedit" id="fpesananedit" class="<?php echo $pesanan_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pesanan_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pesanan_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pesanan">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($pesanan_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($pesanan->nama_pemesan->Visible) { // nama_pemesan ?>
	<div id="r_nama_pemesan" class="form-group">
		<label id="elh_pesanan_nama_pemesan" for="x_nama_pemesan" class="col-sm-2 control-label ewLabel"><?php echo $pesanan->nama_pemesan->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pesanan->nama_pemesan->CellAttributes() ?>>
<span id="el_pesanan_nama_pemesan">
<span<?php echo $pesanan->nama_pemesan->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pesanan->nama_pemesan->EditValue ?></p></span>
</span>
<input type="hidden" data-table="pesanan" data-field="x_nama_pemesan" name="x_nama_pemesan" id="x_nama_pemesan" value="<?php echo ew_HtmlEncode($pesanan->nama_pemesan->CurrentValue) ?>">
<?php echo $pesanan->nama_pemesan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pesanan->tanggal_order->Visible) { // tanggal_order ?>
	<div id="r_tanggal_order" class="form-group">
		<label id="elh_pesanan_tanggal_order" for="x_tanggal_order" class="col-sm-2 control-label ewLabel"><?php echo $pesanan->tanggal_order->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pesanan->tanggal_order->CellAttributes() ?>>
<span id="el_pesanan_tanggal_order">
<span<?php echo $pesanan->tanggal_order->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pesanan->tanggal_order->EditValue ?></p></span>
</span>
<input type="hidden" data-table="pesanan" data-field="x_tanggal_order" name="x_tanggal_order" id="x_tanggal_order" value="<?php echo ew_HtmlEncode($pesanan->tanggal_order->CurrentValue) ?>">
<?php echo $pesanan->tanggal_order->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pesanan->tanggal_selesai->Visible) { // tanggal_selesai ?>
	<div id="r_tanggal_selesai" class="form-group">
		<label id="elh_pesanan_tanggal_selesai" for="x_tanggal_selesai" class="col-sm-2 control-label ewLabel"><?php echo $pesanan->tanggal_selesai->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pesanan->tanggal_selesai->CellAttributes() ?>>
<span id="el_pesanan_tanggal_selesai">
<input type="text" data-table="pesanan" data-field="x_tanggal_selesai" name="x_tanggal_selesai" id="x_tanggal_selesai" placeholder="<?php echo ew_HtmlEncode($pesanan->tanggal_selesai->getPlaceHolder()) ?>" value="<?php echo $pesanan->tanggal_selesai->EditValue ?>"<?php echo $pesanan->tanggal_selesai->EditAttributes() ?>>
<?php if (!$pesanan->tanggal_selesai->ReadOnly && !$pesanan->tanggal_selesai->Disabled && !isset($pesanan->tanggal_selesai->EditAttrs["readonly"]) && !isset($pesanan->tanggal_selesai->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fpesananedit", "x_tanggal_selesai", 0);
</script>
<?php } ?>
</span>
<?php echo $pesanan->tanggal_selesai->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pesanan->jenis_barang->Visible) { // jenis_barang ?>
	<div id="r_jenis_barang" class="form-group">
		<label id="elh_pesanan_jenis_barang" for="x_jenis_barang" class="col-sm-2 control-label ewLabel"><?php echo $pesanan->jenis_barang->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pesanan->jenis_barang->CellAttributes() ?>>
<span id="el_pesanan_jenis_barang">
<span<?php echo $pesanan->jenis_barang->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pesanan->jenis_barang->EditValue ?></p></span>
</span>
<input type="hidden" data-table="pesanan" data-field="x_jenis_barang" name="x_jenis_barang" id="x_jenis_barang" value="<?php echo ew_HtmlEncode($pesanan->jenis_barang->CurrentValue) ?>">
<?php echo $pesanan->jenis_barang->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pesanan->jenis_bahan->Visible) { // jenis_bahan ?>
	<div id="r_jenis_bahan" class="form-group">
		<label id="elh_pesanan_jenis_bahan" for="x_jenis_bahan" class="col-sm-2 control-label ewLabel"><?php echo $pesanan->jenis_bahan->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pesanan->jenis_bahan->CellAttributes() ?>>
<span id="el_pesanan_jenis_bahan">
<span<?php echo $pesanan->jenis_bahan->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pesanan->jenis_bahan->EditValue ?></p></span>
</span>
<input type="hidden" data-table="pesanan" data-field="x_jenis_bahan" name="x_jenis_bahan" id="x_jenis_bahan" value="<?php echo ew_HtmlEncode($pesanan->jenis_bahan->CurrentValue) ?>">
<?php echo $pesanan->jenis_bahan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pesanan->warna_bahan->Visible) { // warna_bahan ?>
	<div id="r_warna_bahan" class="form-group">
		<label id="elh_pesanan_warna_bahan" for="x_warna_bahan" class="col-sm-2 control-label ewLabel"><?php echo $pesanan->warna_bahan->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pesanan->warna_bahan->CellAttributes() ?>>
<span id="el_pesanan_warna_bahan">
<span<?php echo $pesanan->warna_bahan->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pesanan->warna_bahan->EditValue ?></p></span>
</span>
<input type="hidden" data-table="pesanan" data-field="x_warna_bahan" name="x_warna_bahan" id="x_warna_bahan" value="<?php echo ew_HtmlEncode($pesanan->warna_bahan->CurrentValue) ?>">
<?php echo $pesanan->warna_bahan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pesanan->harga_barang->Visible) { // harga_barang ?>
	<div id="r_harga_barang" class="form-group">
		<label id="elh_pesanan_harga_barang" for="x_harga_barang" class="col-sm-2 control-label ewLabel"><?php echo $pesanan->harga_barang->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pesanan->harga_barang->CellAttributes() ?>>
<span id="el_pesanan_harga_barang">
<span<?php echo $pesanan->harga_barang->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pesanan->harga_barang->EditValue ?></p></span>
</span>
<input type="hidden" data-table="pesanan" data-field="x_harga_barang" name="x_harga_barang" id="x_harga_barang" value="<?php echo ew_HtmlEncode($pesanan->harga_barang->CurrentValue) ?>">
<?php echo $pesanan->harga_barang->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pesanan->jumlah_barang->Visible) { // jumlah_barang ?>
	<div id="r_jumlah_barang" class="form-group">
		<label id="elh_pesanan_jumlah_barang" for="x_jumlah_barang" class="col-sm-2 control-label ewLabel"><?php echo $pesanan->jumlah_barang->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pesanan->jumlah_barang->CellAttributes() ?>>
<span id="el_pesanan_jumlah_barang">
<input type="text" data-table="pesanan" data-field="x_jumlah_barang" name="x_jumlah_barang" id="x_jumlah_barang" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pesanan->jumlah_barang->getPlaceHolder()) ?>" value="<?php echo $pesanan->jumlah_barang->EditValue ?>"<?php echo $pesanan->jumlah_barang->EditAttributes() ?>>
</span>
<?php echo $pesanan->jumlah_barang->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pesanan->total_harga->Visible) { // total_harga ?>
	<div id="r_total_harga" class="form-group">
		<label id="elh_pesanan_total_harga" for="x_total_harga" class="col-sm-2 control-label ewLabel"><?php echo $pesanan->total_harga->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pesanan->total_harga->CellAttributes() ?>>
<span id="el_pesanan_total_harga">
<span<?php echo $pesanan->total_harga->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pesanan->total_harga->EditValue ?></p></span>
</span>
<input type="hidden" data-table="pesanan" data-field="x_total_harga" name="x_total_harga" id="x_total_harga" value="<?php echo ew_HtmlEncode($pesanan->total_harga->CurrentValue) ?>">
<?php echo $pesanan->total_harga->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pesanan->status_pembayaran->Visible) { // status_pembayaran ?>
	<div id="r_status_pembayaran" class="form-group">
		<label id="elh_pesanan_status_pembayaran" class="col-sm-2 control-label ewLabel"><?php echo $pesanan->status_pembayaran->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pesanan->status_pembayaran->CellAttributes() ?>>
<span id="el_pesanan_status_pembayaran">
<div id="tp_x_status_pembayaran" class="ewTemplate"><input type="radio" data-table="pesanan" data-field="x_status_pembayaran" data-value-separator="<?php echo $pesanan->status_pembayaran->DisplayValueSeparatorAttribute() ?>" name="x_status_pembayaran" id="x_status_pembayaran" value="{value}"<?php echo $pesanan->status_pembayaran->EditAttributes() ?>></div>
<div id="dsl_x_status_pembayaran" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $pesanan->status_pembayaran->RadioButtonListHtml(FALSE, "x_status_pembayaran") ?>
</div></div>
</span>
<?php echo $pesanan->status_pembayaran->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pesanan->status_order->Visible) { // status_order ?>
	<div id="r_status_order" class="form-group">
		<label id="elh_pesanan_status_order" class="col-sm-2 control-label ewLabel"><?php echo $pesanan->status_order->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pesanan->status_order->CellAttributes() ?>>
<span id="el_pesanan_status_order">
<div id="tp_x_status_order" class="ewTemplate"><input type="radio" data-table="pesanan" data-field="x_status_order" data-value-separator="<?php echo $pesanan->status_order->DisplayValueSeparatorAttribute() ?>" name="x_status_order" id="x_status_order" value="{value}"<?php echo $pesanan->status_order->EditAttributes() ?>></div>
<div id="dsl_x_status_order" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $pesanan->status_order->RadioButtonListHtml(FALSE, "x_status_order") ?>
</div></div>
</span>
<?php echo $pesanan->status_order->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pesanan->upload_link->Visible) { // upload_link ?>
	<div id="r_upload_link" class="form-group">
		<label id="elh_pesanan_upload_link" class="col-sm-2 control-label ewLabel"><?php echo $pesanan->upload_link->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pesanan->upload_link->CellAttributes() ?>>
<span id="el_pesanan_upload_link">
<span>
<?php echo ew_GetFileViewTag($pesanan->upload_link, $pesanan->upload_link->EditValue) ?>
</span>
</span>
<input type="hidden" data-table="pesanan" data-field="x_upload_link" name="x_upload_link" id="x_upload_link" value="<?php echo ew_HtmlEncode($pesanan->upload_link->CurrentValue) ?>">
<?php echo $pesanan->upload_link->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pesanan->discount->Visible) { // discount ?>
	<div id="r_discount" class="form-group">
		<label id="elh_pesanan_discount" for="x_discount" class="col-sm-2 control-label ewLabel"><?php echo $pesanan->discount->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pesanan->discount->CellAttributes() ?>>
<span id="el_pesanan_discount">
<span<?php echo $pesanan->discount->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pesanan->discount->EditValue ?></p></span>
</span>
<input type="hidden" data-table="pesanan" data-field="x_discount" name="x_discount" id="x_discount" value="<?php echo ew_HtmlEncode($pesanan->discount->CurrentValue) ?>">
<?php echo $pesanan->discount->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-table="pesanan" data-field="x_order_id" name="x_order_id" id="x_order_id" value="<?php echo ew_HtmlEncode($pesanan->order_id->CurrentValue) ?>">
<?php if (!$pesanan_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $pesanan_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fpesananedit.Init();
</script>
<?php
$pesanan_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pesanan_edit->Page_Terminate();
?>
