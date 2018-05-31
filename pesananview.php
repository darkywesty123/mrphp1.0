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

$pesanan_view = NULL; // Initialize page object first

class cpesanan_view extends cpesanan {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{CB0737F4-C35F-4485-A00D-4D7E8040366B}";

	// Table name
	var $TableName = 'pesanan';

	// Page object name
	var $PageObjName = 'pesanan_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
		$KeyUrl = "";
		if (@$_GET["order_id"] <> "") {
			$this->RecKey["order_id"] = $_GET["order_id"];
			$KeyUrl .= "&amp;order_id=" . urlencode($this->RecKey["order_id"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

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

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (!$Security->CanView()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("pesananlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
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
		$this->total_harga->SetVisibility();
		$this->status_pembayaran->SetVisibility();
		$this->status_order->SetVisibility();
		$this->upload_link->SetVisibility();
		$this->discount->SetVisibility();
		$this->deskripsi->SetVisibility();

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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $IsModal = FALSE;
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["order_id"] <> "") {
				$this->order_id->setQueryStringValue($_GET["order_id"]);
				$this->RecKey["order_id"] = $this->order_id->QueryStringValue;
			} elseif (@$_POST["order_id"] <> "") {
				$this->order_id->setFormValue($_POST["order_id"]);
				$this->RecKey["order_id"] = $this->order_id->FormValue;
			} else {
				$sReturnUrl = "pesananlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "pesananlist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "pesananlist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("ViewPageAddLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->AddUrl) . "',caption:'" . $addcaption . "'});\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$editcaption = ew_HtmlTitle($Language->Phrase("ViewPageEditLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->EditUrl) . "',caption:'" . $editcaption . "'});\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Copy
		$item = &$option->Add("copy");
		$copycaption = ew_HtmlTitle($Language->Phrase("ViewPageCopyLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->CopyUrl) . "',caption:'" . $copycaption . "'});\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->CanAdd());

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("pesananlist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($pesanan_view)) $pesanan_view = new cpesanan_view();

// Page init
$pesanan_view->Page_Init();

// Page main
$pesanan_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pesanan_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fpesananview = new ew_Form("fpesananview", "view");

// Form_CustomValidate event
fpesananview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpesananview.ValidateRequired = true;
<?php } else { ?>
fpesananview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpesananview.Lists["x_nama_pemesan"] = {"LinkField":"x_cust_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nama_pelanggan","x_alamat_pelanggan","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"customer"};
fpesananview.Lists["x_jenis_barang"] = {"LinkField":"x_jenis_barang","Ajax":true,"AutoFill":false,"DisplayFields":["x_jenis_barang","","",""],"ParentFields":[],"ChildFields":["x_jenis_bahan","x_harga_barang"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"masterdata"};
fpesananview.Lists["x_jenis_bahan"] = {"LinkField":"x_jenis_bahan","Ajax":true,"AutoFill":false,"DisplayFields":["x_jenis_bahan","","",""],"ParentFields":[],"ChildFields":["x_harga_barang"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"masterdata"};
fpesananview.Lists["x_harga_barang"] = {"LinkField":"x_harga","Ajax":true,"AutoFill":false,"DisplayFields":["x_harga","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"masterdata"};
fpesananview.Lists["x_status_pembayaran"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpesananview.Lists["x_status_pembayaran"].Options = <?php echo json_encode($pesanan->status_pembayaran->Options()) ?>;
fpesananview.Lists["x_status_order"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpesananview.Lists["x_status_order"].Options = <?php echo json_encode($pesanan->status_order->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if (!$pesanan_view->IsModal) { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php $pesanan_view->ExportOptions->Render("body") ?>
<?php
	foreach ($pesanan_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if (!$pesanan_view->IsModal) { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php $pesanan_view->ShowPageHeader(); ?>
<?php
$pesanan_view->ShowMessage();
?>
<form name="fpesananview" id="fpesananview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pesanan_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pesanan_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pesanan">
<?php if ($pesanan_view->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($pesanan->order_id->Visible) { // order_id ?>
	<tr id="r_order_id">
		<td><span id="elh_pesanan_order_id"><?php echo $pesanan->order_id->FldCaption() ?></span></td>
		<td data-name="order_id"<?php echo $pesanan->order_id->CellAttributes() ?>>
<span id="el_pesanan_order_id">
<span<?php echo $pesanan->order_id->ViewAttributes() ?>>
<?php echo $pesanan->order_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pesanan->nama_pemesan->Visible) { // nama_pemesan ?>
	<tr id="r_nama_pemesan">
		<td><span id="elh_pesanan_nama_pemesan"><?php echo $pesanan->nama_pemesan->FldCaption() ?></span></td>
		<td data-name="nama_pemesan"<?php echo $pesanan->nama_pemesan->CellAttributes() ?>>
<span id="el_pesanan_nama_pemesan">
<span<?php echo $pesanan->nama_pemesan->ViewAttributes() ?>>
<?php echo $pesanan->nama_pemesan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pesanan->tanggal_order->Visible) { // tanggal_order ?>
	<tr id="r_tanggal_order">
		<td><span id="elh_pesanan_tanggal_order"><?php echo $pesanan->tanggal_order->FldCaption() ?></span></td>
		<td data-name="tanggal_order"<?php echo $pesanan->tanggal_order->CellAttributes() ?>>
<span id="el_pesanan_tanggal_order">
<span<?php echo $pesanan->tanggal_order->ViewAttributes() ?>>
<?php echo $pesanan->tanggal_order->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pesanan->tanggal_selesai->Visible) { // tanggal_selesai ?>
	<tr id="r_tanggal_selesai">
		<td><span id="elh_pesanan_tanggal_selesai"><?php echo $pesanan->tanggal_selesai->FldCaption() ?></span></td>
		<td data-name="tanggal_selesai"<?php echo $pesanan->tanggal_selesai->CellAttributes() ?>>
<span id="el_pesanan_tanggal_selesai">
<span<?php echo $pesanan->tanggal_selesai->ViewAttributes() ?>>
<?php echo $pesanan->tanggal_selesai->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pesanan->jenis_barang->Visible) { // jenis_barang ?>
	<tr id="r_jenis_barang">
		<td><span id="elh_pesanan_jenis_barang"><?php echo $pesanan->jenis_barang->FldCaption() ?></span></td>
		<td data-name="jenis_barang"<?php echo $pesanan->jenis_barang->CellAttributes() ?>>
<span id="el_pesanan_jenis_barang">
<span<?php echo $pesanan->jenis_barang->ViewAttributes() ?>>
<?php echo $pesanan->jenis_barang->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pesanan->jenis_bahan->Visible) { // jenis_bahan ?>
	<tr id="r_jenis_bahan">
		<td><span id="elh_pesanan_jenis_bahan"><?php echo $pesanan->jenis_bahan->FldCaption() ?></span></td>
		<td data-name="jenis_bahan"<?php echo $pesanan->jenis_bahan->CellAttributes() ?>>
<span id="el_pesanan_jenis_bahan">
<span<?php echo $pesanan->jenis_bahan->ViewAttributes() ?>>
<?php echo $pesanan->jenis_bahan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pesanan->warna_bahan->Visible) { // warna_bahan ?>
	<tr id="r_warna_bahan">
		<td><span id="elh_pesanan_warna_bahan"><?php echo $pesanan->warna_bahan->FldCaption() ?></span></td>
		<td data-name="warna_bahan"<?php echo $pesanan->warna_bahan->CellAttributes() ?>>
<span id="el_pesanan_warna_bahan">
<span<?php echo $pesanan->warna_bahan->ViewAttributes() ?>>
<?php echo $pesanan->warna_bahan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pesanan->harga_barang->Visible) { // harga_barang ?>
	<tr id="r_harga_barang">
		<td><span id="elh_pesanan_harga_barang"><?php echo $pesanan->harga_barang->FldCaption() ?></span></td>
		<td data-name="harga_barang"<?php echo $pesanan->harga_barang->CellAttributes() ?>>
<span id="el_pesanan_harga_barang">
<span<?php echo $pesanan->harga_barang->ViewAttributes() ?>>
<?php echo $pesanan->harga_barang->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pesanan->jumlah_barang->Visible) { // jumlah_barang ?>
	<tr id="r_jumlah_barang">
		<td><span id="elh_pesanan_jumlah_barang"><?php echo $pesanan->jumlah_barang->FldCaption() ?></span></td>
		<td data-name="jumlah_barang"<?php echo $pesanan->jumlah_barang->CellAttributes() ?>>
<span id="el_pesanan_jumlah_barang">
<span<?php echo $pesanan->jumlah_barang->ViewAttributes() ?>>
<?php echo $pesanan->jumlah_barang->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pesanan->total_harga->Visible) { // total_harga ?>
	<tr id="r_total_harga">
		<td><span id="elh_pesanan_total_harga"><?php echo $pesanan->total_harga->FldCaption() ?></span></td>
		<td data-name="total_harga"<?php echo $pesanan->total_harga->CellAttributes() ?>>
<span id="el_pesanan_total_harga">
<span<?php echo $pesanan->total_harga->ViewAttributes() ?>>
<?php echo $pesanan->total_harga->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pesanan->status_pembayaran->Visible) { // status_pembayaran ?>
	<tr id="r_status_pembayaran">
		<td><span id="elh_pesanan_status_pembayaran"><?php echo $pesanan->status_pembayaran->FldCaption() ?></span></td>
		<td data-name="status_pembayaran"<?php echo $pesanan->status_pembayaran->CellAttributes() ?>>
<span id="el_pesanan_status_pembayaran">
<span<?php echo $pesanan->status_pembayaran->ViewAttributes() ?>>
<?php echo $pesanan->status_pembayaran->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pesanan->status_order->Visible) { // status_order ?>
	<tr id="r_status_order">
		<td><span id="elh_pesanan_status_order"><?php echo $pesanan->status_order->FldCaption() ?></span></td>
		<td data-name="status_order"<?php echo $pesanan->status_order->CellAttributes() ?>>
<span id="el_pesanan_status_order">
<span<?php echo $pesanan->status_order->ViewAttributes() ?>>
<?php echo $pesanan->status_order->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pesanan->upload_link->Visible) { // upload_link ?>
	<tr id="r_upload_link">
		<td><span id="elh_pesanan_upload_link"><?php echo $pesanan->upload_link->FldCaption() ?></span></td>
		<td data-name="upload_link"<?php echo $pesanan->upload_link->CellAttributes() ?>>
<span id="el_pesanan_upload_link">
<span>
<?php echo ew_GetFileViewTag($pesanan->upload_link, $pesanan->upload_link->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pesanan->discount->Visible) { // discount ?>
	<tr id="r_discount">
		<td><span id="elh_pesanan_discount"><?php echo $pesanan->discount->FldCaption() ?></span></td>
		<td data-name="discount"<?php echo $pesanan->discount->CellAttributes() ?>>
<span id="el_pesanan_discount">
<span<?php echo $pesanan->discount->ViewAttributes() ?>>
<?php echo $pesanan->discount->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pesanan->deskripsi->Visible) { // deskripsi ?>
	<tr id="r_deskripsi">
		<td><span id="elh_pesanan_deskripsi"><?php echo $pesanan->deskripsi->FldCaption() ?></span></td>
		<td data-name="deskripsi"<?php echo $pesanan->deskripsi->CellAttributes() ?>>
<span id="el_pesanan_deskripsi">
<span<?php echo $pesanan->deskripsi->ViewAttributes() ?>>
<?php echo $pesanan->deskripsi->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fpesananview.Init();
</script>
<?php
$pesanan_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pesanan_view->Page_Terminate();
?>
