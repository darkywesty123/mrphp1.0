<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "inventoryinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$inventory_edit = NULL; // Initialize page object first

class cinventory_edit extends cinventory {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{CB0737F4-C35F-4485-A00D-4D7E8040366B}";

	// Table name
	var $TableName = 'inventory';

	// Page object name
	var $PageObjName = 'inventory_edit';

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

		// Table object (inventory)
		if (!isset($GLOBALS["inventory"]) || get_class($GLOBALS["inventory"]) == "cinventory") {
			$GLOBALS["inventory"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["inventory"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'inventory', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("inventorylist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->deskripsi_item->SetVisibility();
		$this->kuantitas->SetVisibility();
		$this->satuan_unit->SetVisibility();
		$this->jenis->SetVisibility();
		$this->warna->SetVisibility();
		$this->tanggal_masuk->SetVisibility();

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
		global $EW_EXPORT, $inventory;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($inventory);
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
		if (@$_GET["inventory_id"] <> "") {
			$this->inventory_id->setQueryStringValue($_GET["inventory_id"]);
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->inventory_id->CurrentValue == "") {
			$this->Page_Terminate("inventorylist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("inventorylist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "inventorylist.php")
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
		if (!$this->deskripsi_item->FldIsDetailKey) {
			$this->deskripsi_item->setFormValue($objForm->GetValue("x_deskripsi_item"));
		}
		if (!$this->kuantitas->FldIsDetailKey) {
			$this->kuantitas->setFormValue($objForm->GetValue("x_kuantitas"));
		}
		if (!$this->satuan_unit->FldIsDetailKey) {
			$this->satuan_unit->setFormValue($objForm->GetValue("x_satuan_unit"));
		}
		if (!$this->jenis->FldIsDetailKey) {
			$this->jenis->setFormValue($objForm->GetValue("x_jenis"));
		}
		if (!$this->warna->FldIsDetailKey) {
			$this->warna->setFormValue($objForm->GetValue("x_warna"));
		}
		if (!$this->tanggal_masuk->FldIsDetailKey) {
			$this->tanggal_masuk->setFormValue($objForm->GetValue("x_tanggal_masuk"));
			$this->tanggal_masuk->CurrentValue = ew_UnFormatDateTime($this->tanggal_masuk->CurrentValue, 0);
		}
		if (!$this->inventory_id->FldIsDetailKey)
			$this->inventory_id->setFormValue($objForm->GetValue("x_inventory_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->inventory_id->CurrentValue = $this->inventory_id->FormValue;
		$this->deskripsi_item->CurrentValue = $this->deskripsi_item->FormValue;
		$this->kuantitas->CurrentValue = $this->kuantitas->FormValue;
		$this->satuan_unit->CurrentValue = $this->satuan_unit->FormValue;
		$this->jenis->CurrentValue = $this->jenis->FormValue;
		$this->warna->CurrentValue = $this->warna->FormValue;
		$this->tanggal_masuk->CurrentValue = $this->tanggal_masuk->FormValue;
		$this->tanggal_masuk->CurrentValue = ew_UnFormatDateTime($this->tanggal_masuk->CurrentValue, 0);
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
		$this->inventory_id->setDbValue($rs->fields('inventory_id'));
		$this->deskripsi_item->setDbValue($rs->fields('deskripsi_item'));
		$this->kuantitas->setDbValue($rs->fields('kuantitas'));
		$this->satuan_unit->setDbValue($rs->fields('satuan_unit'));
		$this->jenis->setDbValue($rs->fields('jenis'));
		$this->warna->setDbValue($rs->fields('warna'));
		$this->tanggal_masuk->setDbValue($rs->fields('tanggal_masuk'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->inventory_id->DbValue = $row['inventory_id'];
		$this->deskripsi_item->DbValue = $row['deskripsi_item'];
		$this->kuantitas->DbValue = $row['kuantitas'];
		$this->satuan_unit->DbValue = $row['satuan_unit'];
		$this->jenis->DbValue = $row['jenis'];
		$this->warna->DbValue = $row['warna'];
		$this->tanggal_masuk->DbValue = $row['tanggal_masuk'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// inventory_id
		// deskripsi_item
		// kuantitas
		// satuan_unit
		// jenis
		// warna
		// tanggal_masuk

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// deskripsi_item
			$this->deskripsi_item->EditAttrs["class"] = "form-control";
			$this->deskripsi_item->EditCustomAttributes = "";
			$this->deskripsi_item->EditValue = $this->deskripsi_item->CurrentValue;
			$this->deskripsi_item->ViewCustomAttributes = "";

			// kuantitas
			$this->kuantitas->EditAttrs["class"] = "form-control";
			$this->kuantitas->EditCustomAttributes = "";
			$this->kuantitas->EditValue = ew_HtmlEncode($this->kuantitas->CurrentValue);
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
			$this->tanggal_masuk->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tanggal_masuk->CurrentValue, 8));
			$this->tanggal_masuk->PlaceHolder = ew_RemoveHtml($this->tanggal_masuk->FldCaption());

			// Edit refer script
			// deskripsi_item

			$this->deskripsi_item->LinkCustomAttributes = "";
			$this->deskripsi_item->HrefValue = "";
			$this->deskripsi_item->TooltipValue = "";

			// kuantitas
			$this->kuantitas->LinkCustomAttributes = "";
			$this->kuantitas->HrefValue = "";

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
		if (!$this->kuantitas->FldIsDetailKey && !is_null($this->kuantitas->FormValue) && $this->kuantitas->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->kuantitas->FldCaption(), $this->kuantitas->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->kuantitas->FormValue)) {
			ew_AddMessage($gsFormError, $this->kuantitas->FldErrMsg());
		}
		if (!$this->tanggal_masuk->FldIsDetailKey && !is_null($this->tanggal_masuk->FormValue) && $this->tanggal_masuk->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tanggal_masuk->FldCaption(), $this->tanggal_masuk->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->tanggal_masuk->FormValue)) {
			ew_AddMessage($gsFormError, $this->tanggal_masuk->FldErrMsg());
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

			// kuantitas
			$this->kuantitas->SetDbValueDef($rsnew, $this->kuantitas->CurrentValue, 0, $this->kuantitas->ReadOnly);

			// tanggal_masuk
			$this->tanggal_masuk->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tanggal_masuk->CurrentValue, 0), ew_CurrentDate(), $this->tanggal_masuk->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("inventorylist.php"), "", $this->TableVar, TRUE);
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
if (!isset($inventory_edit)) $inventory_edit = new cinventory_edit();

// Page init
$inventory_edit->Page_Init();

// Page main
$inventory_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$inventory_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = finventoryedit = new ew_Form("finventoryedit", "edit");

// Validate form
finventoryedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_kuantitas");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory->kuantitas->FldCaption(), $inventory->kuantitas->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_kuantitas");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($inventory->kuantitas->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tanggal_masuk");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory->tanggal_masuk->FldCaption(), $inventory->tanggal_masuk->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tanggal_masuk");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($inventory->tanggal_masuk->FldErrMsg()) ?>");

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
finventoryedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
finventoryedit.ValidateRequired = true;
<?php } else { ?>
finventoryedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
finventoryedit.Lists["x_satuan_unit"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finventoryedit.Lists["x_satuan_unit"].Options = <?php echo json_encode($inventory->satuan_unit->Options()) ?>;
finventoryedit.Lists["x_jenis"] = {"LinkField":"x_jenis_bahan","Ajax":true,"AutoFill":false,"DisplayFields":["x_jenis_bahan","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"masterdata"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$inventory_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $inventory_edit->ShowPageHeader(); ?>
<?php
$inventory_edit->ShowMessage();
?>
<form name="finventoryedit" id="finventoryedit" class="<?php echo $inventory_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($inventory_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $inventory_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="inventory">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($inventory_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($inventory->deskripsi_item->Visible) { // deskripsi_item ?>
	<div id="r_deskripsi_item" class="form-group">
		<label id="elh_inventory_deskripsi_item" for="x_deskripsi_item" class="col-sm-2 control-label ewLabel"><?php echo $inventory->deskripsi_item->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $inventory->deskripsi_item->CellAttributes() ?>>
<span id="el_inventory_deskripsi_item">
<span<?php echo $inventory->deskripsi_item->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory->deskripsi_item->EditValue ?></p></span>
</span>
<input type="hidden" data-table="inventory" data-field="x_deskripsi_item" name="x_deskripsi_item" id="x_deskripsi_item" value="<?php echo ew_HtmlEncode($inventory->deskripsi_item->CurrentValue) ?>">
<?php echo $inventory->deskripsi_item->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->kuantitas->Visible) { // kuantitas ?>
	<div id="r_kuantitas" class="form-group">
		<label id="elh_inventory_kuantitas" for="x_kuantitas" class="col-sm-2 control-label ewLabel"><?php echo $inventory->kuantitas->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $inventory->kuantitas->CellAttributes() ?>>
<span id="el_inventory_kuantitas">
<input type="text" data-table="inventory" data-field="x_kuantitas" name="x_kuantitas" id="x_kuantitas" size="30" placeholder="<?php echo ew_HtmlEncode($inventory->kuantitas->getPlaceHolder()) ?>" value="<?php echo $inventory->kuantitas->EditValue ?>"<?php echo $inventory->kuantitas->EditAttributes() ?>>
</span>
<?php echo $inventory->kuantitas->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->satuan_unit->Visible) { // satuan_unit ?>
	<div id="r_satuan_unit" class="form-group">
		<label id="elh_inventory_satuan_unit" for="x_satuan_unit" class="col-sm-2 control-label ewLabel"><?php echo $inventory->satuan_unit->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $inventory->satuan_unit->CellAttributes() ?>>
<span id="el_inventory_satuan_unit">
<span<?php echo $inventory->satuan_unit->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory->satuan_unit->EditValue ?></p></span>
</span>
<input type="hidden" data-table="inventory" data-field="x_satuan_unit" name="x_satuan_unit" id="x_satuan_unit" value="<?php echo ew_HtmlEncode($inventory->satuan_unit->CurrentValue) ?>">
<?php echo $inventory->satuan_unit->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->jenis->Visible) { // jenis ?>
	<div id="r_jenis" class="form-group">
		<label id="elh_inventory_jenis" for="x_jenis" class="col-sm-2 control-label ewLabel"><?php echo $inventory->jenis->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $inventory->jenis->CellAttributes() ?>>
<span id="el_inventory_jenis">
<span<?php echo $inventory->jenis->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory->jenis->EditValue ?></p></span>
</span>
<input type="hidden" data-table="inventory" data-field="x_jenis" name="x_jenis" id="x_jenis" value="<?php echo ew_HtmlEncode($inventory->jenis->CurrentValue) ?>">
<?php echo $inventory->jenis->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->warna->Visible) { // warna ?>
	<div id="r_warna" class="form-group">
		<label id="elh_inventory_warna" for="x_warna" class="col-sm-2 control-label ewLabel"><?php echo $inventory->warna->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $inventory->warna->CellAttributes() ?>>
<span id="el_inventory_warna">
<span<?php echo $inventory->warna->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory->warna->EditValue ?></p></span>
</span>
<input type="hidden" data-table="inventory" data-field="x_warna" name="x_warna" id="x_warna" value="<?php echo ew_HtmlEncode($inventory->warna->CurrentValue) ?>">
<?php echo $inventory->warna->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->tanggal_masuk->Visible) { // tanggal_masuk ?>
	<div id="r_tanggal_masuk" class="form-group">
		<label id="elh_inventory_tanggal_masuk" for="x_tanggal_masuk" class="col-sm-2 control-label ewLabel"><?php echo $inventory->tanggal_masuk->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $inventory->tanggal_masuk->CellAttributes() ?>>
<span id="el_inventory_tanggal_masuk">
<input type="text" data-table="inventory" data-field="x_tanggal_masuk" name="x_tanggal_masuk" id="x_tanggal_masuk" placeholder="<?php echo ew_HtmlEncode($inventory->tanggal_masuk->getPlaceHolder()) ?>" value="<?php echo $inventory->tanggal_masuk->EditValue ?>"<?php echo $inventory->tanggal_masuk->EditAttributes() ?>>
<?php if (!$inventory->tanggal_masuk->ReadOnly && !$inventory->tanggal_masuk->Disabled && !isset($inventory->tanggal_masuk->EditAttrs["readonly"]) && !isset($inventory->tanggal_masuk->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("finventoryedit", "x_tanggal_masuk", 0);
</script>
<?php } ?>
</span>
<?php echo $inventory->tanggal_masuk->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-table="inventory" data-field="x_inventory_id" name="x_inventory_id" id="x_inventory_id" value="<?php echo ew_HtmlEncode($inventory->inventory_id->CurrentValue) ?>">
<?php if (!$inventory_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $inventory_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
finventoryedit.Init();
</script>
<?php
$inventory_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$inventory_edit->Page_Terminate();
?>
