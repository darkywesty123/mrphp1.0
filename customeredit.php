<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "customerinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$customer_edit = NULL; // Initialize page object first

class ccustomer_edit extends ccustomer {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{CB0737F4-C35F-4485-A00D-4D7E8040366B}";

	// Table name
	var $TableName = 'customer';

	// Page object name
	var $PageObjName = 'customer_edit';

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

		// Table object (customer)
		if (!isset($GLOBALS["customer"]) || get_class($GLOBALS["customer"]) == "ccustomer") {
			$GLOBALS["customer"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["customer"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'customer', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("customerlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->cust_id->SetVisibility();
		$this->cust_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->nama_pelanggan->SetVisibility();
		$this->alamat_pelanggan->SetVisibility();
		$this->nomor_telepon_pelanggan->SetVisibility();

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
		global $EW_EXPORT, $customer;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($customer);
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
		if (@$_GET["cust_id"] <> "") {
			$this->cust_id->setQueryStringValue($_GET["cust_id"]);
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->cust_id->CurrentValue == "") {
			$this->Page_Terminate("customerlist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("customerlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "customerlist.php")
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
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->cust_id->FldIsDetailKey)
			$this->cust_id->setFormValue($objForm->GetValue("x_cust_id"));
		if (!$this->nama_pelanggan->FldIsDetailKey) {
			$this->nama_pelanggan->setFormValue($objForm->GetValue("x_nama_pelanggan"));
		}
		if (!$this->alamat_pelanggan->FldIsDetailKey) {
			$this->alamat_pelanggan->setFormValue($objForm->GetValue("x_alamat_pelanggan"));
		}
		if (!$this->nomor_telepon_pelanggan->FldIsDetailKey) {
			$this->nomor_telepon_pelanggan->setFormValue($objForm->GetValue("x_nomor_telepon_pelanggan"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->cust_id->CurrentValue = $this->cust_id->FormValue;
		$this->nama_pelanggan->CurrentValue = $this->nama_pelanggan->FormValue;
		$this->alamat_pelanggan->CurrentValue = $this->alamat_pelanggan->FormValue;
		$this->nomor_telepon_pelanggan->CurrentValue = $this->nomor_telepon_pelanggan->FormValue;
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
		$this->cust_id->setDbValue($rs->fields('cust_id'));
		$this->nama_pelanggan->setDbValue($rs->fields('nama_pelanggan'));
		$this->alamat_pelanggan->setDbValue($rs->fields('alamat pelanggan'));
		$this->nomor_telepon_pelanggan->setDbValue($rs->fields('nomor telepon pelanggan'));
		$this->jumlah_pesanan->setDbValue($rs->fields('jumlah_pesanan'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->cust_id->DbValue = $row['cust_id'];
		$this->nama_pelanggan->DbValue = $row['nama_pelanggan'];
		$this->alamat_pelanggan->DbValue = $row['alamat pelanggan'];
		$this->nomor_telepon_pelanggan->DbValue = $row['nomor telepon pelanggan'];
		$this->jumlah_pesanan->DbValue = $row['jumlah_pesanan'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// cust_id
		// nama_pelanggan
		// alamat pelanggan
		// nomor telepon pelanggan
		// jumlah_pesanan

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// cust_id
		$this->cust_id->ViewValue = $this->cust_id->CurrentValue;
		$this->cust_id->ViewCustomAttributes = "";

		// nama_pelanggan
		$this->nama_pelanggan->ViewValue = $this->nama_pelanggan->CurrentValue;
		$this->nama_pelanggan->ViewCustomAttributes = "";

		// alamat pelanggan
		$this->alamat_pelanggan->ViewValue = $this->alamat_pelanggan->CurrentValue;
		$this->alamat_pelanggan->ViewCustomAttributes = "";

		// nomor telepon pelanggan
		$this->nomor_telepon_pelanggan->ViewValue = $this->nomor_telepon_pelanggan->CurrentValue;
		$this->nomor_telepon_pelanggan->ViewCustomAttributes = "";

		// jumlah_pesanan
		$this->jumlah_pesanan->ViewValue = $this->jumlah_pesanan->CurrentValue;
		$this->jumlah_pesanan->ViewCustomAttributes = "";

			// cust_id
			$this->cust_id->LinkCustomAttributes = "";
			$this->cust_id->HrefValue = "";
			$this->cust_id->TooltipValue = "";

			// nama_pelanggan
			$this->nama_pelanggan->LinkCustomAttributes = "";
			$this->nama_pelanggan->HrefValue = "";
			$this->nama_pelanggan->TooltipValue = "";

			// alamat pelanggan
			$this->alamat_pelanggan->LinkCustomAttributes = "";
			$this->alamat_pelanggan->HrefValue = "";
			$this->alamat_pelanggan->TooltipValue = "";

			// nomor telepon pelanggan
			$this->nomor_telepon_pelanggan->LinkCustomAttributes = "";
			$this->nomor_telepon_pelanggan->HrefValue = "";
			$this->nomor_telepon_pelanggan->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// cust_id
			$this->cust_id->EditAttrs["class"] = "form-control";
			$this->cust_id->EditCustomAttributes = "";
			$this->cust_id->EditValue = $this->cust_id->CurrentValue;
			$this->cust_id->ViewCustomAttributes = "";

			// nama_pelanggan
			$this->nama_pelanggan->EditAttrs["class"] = "form-control";
			$this->nama_pelanggan->EditCustomAttributes = "";
			$this->nama_pelanggan->EditValue = ew_HtmlEncode($this->nama_pelanggan->CurrentValue);
			$this->nama_pelanggan->PlaceHolder = ew_RemoveHtml($this->nama_pelanggan->FldCaption());

			// alamat pelanggan
			$this->alamat_pelanggan->EditAttrs["class"] = "form-control";
			$this->alamat_pelanggan->EditCustomAttributes = "";
			$this->alamat_pelanggan->EditValue = ew_HtmlEncode($this->alamat_pelanggan->CurrentValue);
			$this->alamat_pelanggan->PlaceHolder = ew_RemoveHtml($this->alamat_pelanggan->FldCaption());

			// nomor telepon pelanggan
			$this->nomor_telepon_pelanggan->EditAttrs["class"] = "form-control";
			$this->nomor_telepon_pelanggan->EditCustomAttributes = "";
			$this->nomor_telepon_pelanggan->EditValue = ew_HtmlEncode($this->nomor_telepon_pelanggan->CurrentValue);
			$this->nomor_telepon_pelanggan->PlaceHolder = ew_RemoveHtml($this->nomor_telepon_pelanggan->FldCaption());

			// Edit refer script
			// cust_id

			$this->cust_id->LinkCustomAttributes = "";
			$this->cust_id->HrefValue = "";

			// nama_pelanggan
			$this->nama_pelanggan->LinkCustomAttributes = "";
			$this->nama_pelanggan->HrefValue = "";

			// alamat pelanggan
			$this->alamat_pelanggan->LinkCustomAttributes = "";
			$this->alamat_pelanggan->HrefValue = "";

			// nomor telepon pelanggan
			$this->nomor_telepon_pelanggan->LinkCustomAttributes = "";
			$this->nomor_telepon_pelanggan->HrefValue = "";
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
		if (!$this->nama_pelanggan->FldIsDetailKey && !is_null($this->nama_pelanggan->FormValue) && $this->nama_pelanggan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nama_pelanggan->FldCaption(), $this->nama_pelanggan->ReqErrMsg));
		}
		if (!$this->alamat_pelanggan->FldIsDetailKey && !is_null($this->alamat_pelanggan->FormValue) && $this->alamat_pelanggan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->alamat_pelanggan->FldCaption(), $this->alamat_pelanggan->ReqErrMsg));
		}
		if (!$this->nomor_telepon_pelanggan->FldIsDetailKey && !is_null($this->nomor_telepon_pelanggan->FormValue) && $this->nomor_telepon_pelanggan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nomor_telepon_pelanggan->FldCaption(), $this->nomor_telepon_pelanggan->ReqErrMsg));
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

			// nama_pelanggan
			$this->nama_pelanggan->SetDbValueDef($rsnew, $this->nama_pelanggan->CurrentValue, "", $this->nama_pelanggan->ReadOnly);

			// alamat pelanggan
			$this->alamat_pelanggan->SetDbValueDef($rsnew, $this->alamat_pelanggan->CurrentValue, "", $this->alamat_pelanggan->ReadOnly);

			// nomor telepon pelanggan
			$this->nomor_telepon_pelanggan->SetDbValueDef($rsnew, $this->nomor_telepon_pelanggan->CurrentValue, "", $this->nomor_telepon_pelanggan->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("customerlist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
if (!isset($customer_edit)) $customer_edit = new ccustomer_edit();

// Page init
$customer_edit->Page_Init();

// Page main
$customer_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$customer_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fcustomeredit = new ew_Form("fcustomeredit", "edit");

// Validate form
fcustomeredit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nama_pelanggan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $customer->nama_pelanggan->FldCaption(), $customer->nama_pelanggan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_alamat_pelanggan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $customer->alamat_pelanggan->FldCaption(), $customer->alamat_pelanggan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nomor_telepon_pelanggan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $customer->nomor_telepon_pelanggan->FldCaption(), $customer->nomor_telepon_pelanggan->ReqErrMsg)) ?>");

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
fcustomeredit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcustomeredit.ValidateRequired = true;
<?php } else { ?>
fcustomeredit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$customer_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $customer_edit->ShowPageHeader(); ?>
<?php
$customer_edit->ShowMessage();
?>
<form name="fcustomeredit" id="fcustomeredit" class="<?php echo $customer_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($customer_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $customer_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="customer">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($customer_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($customer->cust_id->Visible) { // cust_id ?>
	<div id="r_cust_id" class="form-group">
		<label id="elh_customer_cust_id" class="col-sm-2 control-label ewLabel"><?php echo $customer->cust_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $customer->cust_id->CellAttributes() ?>>
<span id="el_customer_cust_id">
<span<?php echo $customer->cust_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $customer->cust_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="customer" data-field="x_cust_id" name="x_cust_id" id="x_cust_id" value="<?php echo ew_HtmlEncode($customer->cust_id->CurrentValue) ?>">
<?php echo $customer->cust_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($customer->nama_pelanggan->Visible) { // nama_pelanggan ?>
	<div id="r_nama_pelanggan" class="form-group">
		<label id="elh_customer_nama_pelanggan" for="x_nama_pelanggan" class="col-sm-2 control-label ewLabel"><?php echo $customer->nama_pelanggan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $customer->nama_pelanggan->CellAttributes() ?>>
<span id="el_customer_nama_pelanggan">
<input type="text" data-table="customer" data-field="x_nama_pelanggan" name="x_nama_pelanggan" id="x_nama_pelanggan" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($customer->nama_pelanggan->getPlaceHolder()) ?>" value="<?php echo $customer->nama_pelanggan->EditValue ?>"<?php echo $customer->nama_pelanggan->EditAttributes() ?>>
</span>
<?php echo $customer->nama_pelanggan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($customer->alamat_pelanggan->Visible) { // alamat pelanggan ?>
	<div id="r_alamat_pelanggan" class="form-group">
		<label id="elh_customer_alamat_pelanggan" for="x_alamat_pelanggan" class="col-sm-2 control-label ewLabel"><?php echo $customer->alamat_pelanggan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $customer->alamat_pelanggan->CellAttributes() ?>>
<span id="el_customer_alamat_pelanggan">
<input type="text" data-table="customer" data-field="x_alamat_pelanggan" name="x_alamat_pelanggan" id="x_alamat_pelanggan" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($customer->alamat_pelanggan->getPlaceHolder()) ?>" value="<?php echo $customer->alamat_pelanggan->EditValue ?>"<?php echo $customer->alamat_pelanggan->EditAttributes() ?>>
</span>
<?php echo $customer->alamat_pelanggan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($customer->nomor_telepon_pelanggan->Visible) { // nomor telepon pelanggan ?>
	<div id="r_nomor_telepon_pelanggan" class="form-group">
		<label id="elh_customer_nomor_telepon_pelanggan" for="x_nomor_telepon_pelanggan" class="col-sm-2 control-label ewLabel"><?php echo $customer->nomor_telepon_pelanggan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $customer->nomor_telepon_pelanggan->CellAttributes() ?>>
<span id="el_customer_nomor_telepon_pelanggan">
<input type="text" data-table="customer" data-field="x_nomor_telepon_pelanggan" name="x_nomor_telepon_pelanggan" id="x_nomor_telepon_pelanggan" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($customer->nomor_telepon_pelanggan->getPlaceHolder()) ?>" value="<?php echo $customer->nomor_telepon_pelanggan->EditValue ?>"<?php echo $customer->nomor_telepon_pelanggan->EditAttributes() ?>>
</span>
<?php echo $customer->nomor_telepon_pelanggan->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$customer_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $customer_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fcustomeredit.Init();
</script>
<?php
$customer_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$customer_edit->Page_Terminate();
?>
