<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "masterdatainfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$masterdata_edit = NULL; // Initialize page object first

class cmasterdata_edit extends cmasterdata {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{CB0737F4-C35F-4485-A00D-4D7E8040366B}";

	// Table name
	var $TableName = 'masterdata';

	// Page object name
	var $PageObjName = 'masterdata_edit';

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

		// Table object (masterdata)
		if (!isset($GLOBALS["masterdata"]) || get_class($GLOBALS["masterdata"]) == "cmasterdata") {
			$GLOBALS["masterdata"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["masterdata"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'masterdata', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id_barang->SetVisibility();
		$this->id_barang->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->jenis_barang->SetVisibility();
		$this->jenis_bahan->SetVisibility();
		$this->kebutuhan->SetVisibility();
		$this->satuan->SetVisibility();
		$this->harga->SetVisibility();

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
		global $EW_EXPORT, $masterdata;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($masterdata);
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
		if (@$_GET["id_barang"] <> "") {
			$this->id_barang->setQueryStringValue($_GET["id_barang"]);
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id_barang->CurrentValue == "") {
			$this->Page_Terminate("masterdatalist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("masterdatalist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "masterdatalist.php")
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
		if (!$this->id_barang->FldIsDetailKey)
			$this->id_barang->setFormValue($objForm->GetValue("x_id_barang"));
		if (!$this->jenis_barang->FldIsDetailKey) {
			$this->jenis_barang->setFormValue($objForm->GetValue("x_jenis_barang"));
		}
		if (!$this->jenis_bahan->FldIsDetailKey) {
			$this->jenis_bahan->setFormValue($objForm->GetValue("x_jenis_bahan"));
		}
		if (!$this->kebutuhan->FldIsDetailKey) {
			$this->kebutuhan->setFormValue($objForm->GetValue("x_kebutuhan"));
		}
		if (!$this->satuan->FldIsDetailKey) {
			$this->satuan->setFormValue($objForm->GetValue("x_satuan"));
		}
		if (!$this->harga->FldIsDetailKey) {
			$this->harga->setFormValue($objForm->GetValue("x_harga"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id_barang->CurrentValue = $this->id_barang->FormValue;
		$this->jenis_barang->CurrentValue = $this->jenis_barang->FormValue;
		$this->jenis_bahan->CurrentValue = $this->jenis_bahan->FormValue;
		$this->kebutuhan->CurrentValue = $this->kebutuhan->FormValue;
		$this->satuan->CurrentValue = $this->satuan->FormValue;
		$this->harga->CurrentValue = $this->harga->FormValue;
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
		$this->id_barang->setDbValue($rs->fields('id_barang'));
		$this->jenis_barang->setDbValue($rs->fields('jenis_barang'));
		$this->jenis_bahan->setDbValue($rs->fields('jenis_bahan'));
		$this->kebutuhan->setDbValue($rs->fields('kebutuhan'));
		$this->satuan->setDbValue($rs->fields('satuan'));
		$this->harga->setDbValue($rs->fields('harga'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_barang->DbValue = $row['id_barang'];
		$this->jenis_barang->DbValue = $row['jenis_barang'];
		$this->jenis_bahan->DbValue = $row['jenis_bahan'];
		$this->kebutuhan->DbValue = $row['kebutuhan'];
		$this->satuan->DbValue = $row['satuan'];
		$this->harga->DbValue = $row['harga'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_barang
		// jenis_barang
		// jenis_bahan
		// kebutuhan
		// satuan
		// harga

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id_barang
		$this->id_barang->ViewValue = $this->id_barang->CurrentValue;
		$this->id_barang->ViewCustomAttributes = "";

		// jenis_barang
		$this->jenis_barang->ViewValue = $this->jenis_barang->CurrentValue;
		$this->jenis_barang->ViewCustomAttributes = "";

		// jenis_bahan
		$this->jenis_bahan->ViewValue = $this->jenis_bahan->CurrentValue;
		$this->jenis_bahan->ViewCustomAttributes = "";

		// kebutuhan
		$this->kebutuhan->ViewValue = $this->kebutuhan->CurrentValue;
		$this->kebutuhan->ViewCustomAttributes = "";

		// satuan
		$this->satuan->ViewValue = $this->satuan->CurrentValue;
		$this->satuan->ViewCustomAttributes = "";

		// harga
		$this->harga->ViewValue = $this->harga->CurrentValue;
		$this->harga->ViewCustomAttributes = "";

			// id_barang
			$this->id_barang->LinkCustomAttributes = "";
			$this->id_barang->HrefValue = "";
			$this->id_barang->TooltipValue = "";

			// jenis_barang
			$this->jenis_barang->LinkCustomAttributes = "";
			$this->jenis_barang->HrefValue = "";
			$this->jenis_barang->TooltipValue = "";

			// jenis_bahan
			$this->jenis_bahan->LinkCustomAttributes = "";
			$this->jenis_bahan->HrefValue = "";
			$this->jenis_bahan->TooltipValue = "";

			// kebutuhan
			$this->kebutuhan->LinkCustomAttributes = "";
			$this->kebutuhan->HrefValue = "";
			$this->kebutuhan->TooltipValue = "";

			// satuan
			$this->satuan->LinkCustomAttributes = "";
			$this->satuan->HrefValue = "";
			$this->satuan->TooltipValue = "";

			// harga
			$this->harga->LinkCustomAttributes = "";
			$this->harga->HrefValue = "";
			$this->harga->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id_barang
			$this->id_barang->EditAttrs["class"] = "form-control";
			$this->id_barang->EditCustomAttributes = "";
			$this->id_barang->EditValue = $this->id_barang->CurrentValue;
			$this->id_barang->ViewCustomAttributes = "";

			// jenis_barang
			$this->jenis_barang->EditAttrs["class"] = "form-control";
			$this->jenis_barang->EditCustomAttributes = "";
			$this->jenis_barang->EditValue = ew_HtmlEncode($this->jenis_barang->CurrentValue);
			$this->jenis_barang->PlaceHolder = ew_RemoveHtml($this->jenis_barang->FldCaption());

			// jenis_bahan
			$this->jenis_bahan->EditAttrs["class"] = "form-control";
			$this->jenis_bahan->EditCustomAttributes = "";
			$this->jenis_bahan->EditValue = ew_HtmlEncode($this->jenis_bahan->CurrentValue);
			$this->jenis_bahan->PlaceHolder = ew_RemoveHtml($this->jenis_bahan->FldCaption());

			// kebutuhan
			$this->kebutuhan->EditAttrs["class"] = "form-control";
			$this->kebutuhan->EditCustomAttributes = "";
			$this->kebutuhan->EditValue = ew_HtmlEncode($this->kebutuhan->CurrentValue);
			$this->kebutuhan->PlaceHolder = ew_RemoveHtml($this->kebutuhan->FldCaption());

			// satuan
			$this->satuan->EditAttrs["class"] = "form-control";
			$this->satuan->EditCustomAttributes = "";
			$this->satuan->EditValue = ew_HtmlEncode($this->satuan->CurrentValue);
			$this->satuan->PlaceHolder = ew_RemoveHtml($this->satuan->FldCaption());

			// harga
			$this->harga->EditAttrs["class"] = "form-control";
			$this->harga->EditCustomAttributes = "";
			$this->harga->EditValue = ew_HtmlEncode($this->harga->CurrentValue);
			$this->harga->PlaceHolder = ew_RemoveHtml($this->harga->FldCaption());

			// Edit refer script
			// id_barang

			$this->id_barang->LinkCustomAttributes = "";
			$this->id_barang->HrefValue = "";

			// jenis_barang
			$this->jenis_barang->LinkCustomAttributes = "";
			$this->jenis_barang->HrefValue = "";

			// jenis_bahan
			$this->jenis_bahan->LinkCustomAttributes = "";
			$this->jenis_bahan->HrefValue = "";

			// kebutuhan
			$this->kebutuhan->LinkCustomAttributes = "";
			$this->kebutuhan->HrefValue = "";

			// satuan
			$this->satuan->LinkCustomAttributes = "";
			$this->satuan->HrefValue = "";

			// harga
			$this->harga->LinkCustomAttributes = "";
			$this->harga->HrefValue = "";
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
		if (!$this->jenis_barang->FldIsDetailKey && !is_null($this->jenis_barang->FormValue) && $this->jenis_barang->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jenis_barang->FldCaption(), $this->jenis_barang->ReqErrMsg));
		}
		if (!$this->jenis_bahan->FldIsDetailKey && !is_null($this->jenis_bahan->FormValue) && $this->jenis_bahan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jenis_bahan->FldCaption(), $this->jenis_bahan->ReqErrMsg));
		}
		if (!$this->kebutuhan->FldIsDetailKey && !is_null($this->kebutuhan->FormValue) && $this->kebutuhan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->kebutuhan->FldCaption(), $this->kebutuhan->ReqErrMsg));
		}
		if (!$this->satuan->FldIsDetailKey && !is_null($this->satuan->FormValue) && $this->satuan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->satuan->FldCaption(), $this->satuan->ReqErrMsg));
		}
		if (!$this->harga->FldIsDetailKey && !is_null($this->harga->FormValue) && $this->harga->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->harga->FldCaption(), $this->harga->ReqErrMsg));
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

			// jenis_barang
			$this->jenis_barang->SetDbValueDef($rsnew, $this->jenis_barang->CurrentValue, "", $this->jenis_barang->ReadOnly);

			// jenis_bahan
			$this->jenis_bahan->SetDbValueDef($rsnew, $this->jenis_bahan->CurrentValue, "", $this->jenis_bahan->ReadOnly);

			// kebutuhan
			$this->kebutuhan->SetDbValueDef($rsnew, $this->kebutuhan->CurrentValue, "", $this->kebutuhan->ReadOnly);

			// satuan
			$this->satuan->SetDbValueDef($rsnew, $this->satuan->CurrentValue, "", $this->satuan->ReadOnly);

			// harga
			$this->harga->SetDbValueDef($rsnew, $this->harga->CurrentValue, "", $this->harga->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("masterdatalist.php"), "", $this->TableVar, TRUE);
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
if (!isset($masterdata_edit)) $masterdata_edit = new cmasterdata_edit();

// Page init
$masterdata_edit->Page_Init();

// Page main
$masterdata_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$masterdata_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fmasterdataedit = new ew_Form("fmasterdataedit", "edit");

// Validate form
fmasterdataedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_jenis_barang");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $masterdata->jenis_barang->FldCaption(), $masterdata->jenis_barang->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jenis_bahan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $masterdata->jenis_bahan->FldCaption(), $masterdata->jenis_bahan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_kebutuhan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $masterdata->kebutuhan->FldCaption(), $masterdata->kebutuhan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_satuan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $masterdata->satuan->FldCaption(), $masterdata->satuan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_harga");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $masterdata->harga->FldCaption(), $masterdata->harga->ReqErrMsg)) ?>");

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
fmasterdataedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmasterdataedit.ValidateRequired = true;
<?php } else { ?>
fmasterdataedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$masterdata_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $masterdata_edit->ShowPageHeader(); ?>
<?php
$masterdata_edit->ShowMessage();
?>
<form name="fmasterdataedit" id="fmasterdataedit" class="<?php echo $masterdata_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($masterdata_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $masterdata_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="masterdata">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($masterdata_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($masterdata->id_barang->Visible) { // id_barang ?>
	<div id="r_id_barang" class="form-group">
		<label id="elh_masterdata_id_barang" class="col-sm-2 control-label ewLabel"><?php echo $masterdata->id_barang->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $masterdata->id_barang->CellAttributes() ?>>
<span id="el_masterdata_id_barang">
<span<?php echo $masterdata->id_barang->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $masterdata->id_barang->EditValue ?></p></span>
</span>
<input type="hidden" data-table="masterdata" data-field="x_id_barang" name="x_id_barang" id="x_id_barang" value="<?php echo ew_HtmlEncode($masterdata->id_barang->CurrentValue) ?>">
<?php echo $masterdata->id_barang->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($masterdata->jenis_barang->Visible) { // jenis_barang ?>
	<div id="r_jenis_barang" class="form-group">
		<label id="elh_masterdata_jenis_barang" for="x_jenis_barang" class="col-sm-2 control-label ewLabel"><?php echo $masterdata->jenis_barang->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $masterdata->jenis_barang->CellAttributes() ?>>
<span id="el_masterdata_jenis_barang">
<input type="text" data-table="masterdata" data-field="x_jenis_barang" name="x_jenis_barang" id="x_jenis_barang" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($masterdata->jenis_barang->getPlaceHolder()) ?>" value="<?php echo $masterdata->jenis_barang->EditValue ?>"<?php echo $masterdata->jenis_barang->EditAttributes() ?>>
</span>
<?php echo $masterdata->jenis_barang->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($masterdata->jenis_bahan->Visible) { // jenis_bahan ?>
	<div id="r_jenis_bahan" class="form-group">
		<label id="elh_masterdata_jenis_bahan" for="x_jenis_bahan" class="col-sm-2 control-label ewLabel"><?php echo $masterdata->jenis_bahan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $masterdata->jenis_bahan->CellAttributes() ?>>
<span id="el_masterdata_jenis_bahan">
<input type="text" data-table="masterdata" data-field="x_jenis_bahan" name="x_jenis_bahan" id="x_jenis_bahan" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($masterdata->jenis_bahan->getPlaceHolder()) ?>" value="<?php echo $masterdata->jenis_bahan->EditValue ?>"<?php echo $masterdata->jenis_bahan->EditAttributes() ?>>
</span>
<?php echo $masterdata->jenis_bahan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($masterdata->kebutuhan->Visible) { // kebutuhan ?>
	<div id="r_kebutuhan" class="form-group">
		<label id="elh_masterdata_kebutuhan" for="x_kebutuhan" class="col-sm-2 control-label ewLabel"><?php echo $masterdata->kebutuhan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $masterdata->kebutuhan->CellAttributes() ?>>
<span id="el_masterdata_kebutuhan">
<input type="text" data-table="masterdata" data-field="x_kebutuhan" name="x_kebutuhan" id="x_kebutuhan" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($masterdata->kebutuhan->getPlaceHolder()) ?>" value="<?php echo $masterdata->kebutuhan->EditValue ?>"<?php echo $masterdata->kebutuhan->EditAttributes() ?>>
</span>
<?php echo $masterdata->kebutuhan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($masterdata->satuan->Visible) { // satuan ?>
	<div id="r_satuan" class="form-group">
		<label id="elh_masterdata_satuan" for="x_satuan" class="col-sm-2 control-label ewLabel"><?php echo $masterdata->satuan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $masterdata->satuan->CellAttributes() ?>>
<span id="el_masterdata_satuan">
<input type="text" data-table="masterdata" data-field="x_satuan" name="x_satuan" id="x_satuan" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($masterdata->satuan->getPlaceHolder()) ?>" value="<?php echo $masterdata->satuan->EditValue ?>"<?php echo $masterdata->satuan->EditAttributes() ?>>
</span>
<?php echo $masterdata->satuan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($masterdata->harga->Visible) { // harga ?>
	<div id="r_harga" class="form-group">
		<label id="elh_masterdata_harga" for="x_harga" class="col-sm-2 control-label ewLabel"><?php echo $masterdata->harga->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $masterdata->harga->CellAttributes() ?>>
<span id="el_masterdata_harga">
<input type="text" data-table="masterdata" data-field="x_harga" name="x_harga" id="x_harga" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($masterdata->harga->getPlaceHolder()) ?>" value="<?php echo $masterdata->harga->EditValue ?>"<?php echo $masterdata->harga->EditAttributes() ?>>
</span>
<?php echo $masterdata->harga->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$masterdata_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $masterdata_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fmasterdataedit.Init();
</script>
<?php
$masterdata_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$masterdata_edit->Page_Terminate();
?>
