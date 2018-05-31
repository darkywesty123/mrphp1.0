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

$detail_pesanan_add = NULL; // Initialize page object first

class cdetail_pesanan_add extends cdetail_pesanan {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{daeb3313-6eb8-4d86-8407-209c5321b7cc}";

	// Table name
	var $TableName = 'detail_pesanan';

	// Page object name
	var $PageObjName = 'detail_pesanan_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("detail_pesananlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// Create form object
		$objForm = new cFormObj();
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

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

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id_pesanan"] != "") {
				$this->id_pesanan->setQueryStringValue($_GET["id_pesanan"]);
				$this->setKey("id_pesanan", $this->id_pesanan->CurrentValue); // Set up key
			} else {
				$this->setKey("id_pesanan", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["nomor_so"] != "") {
				$this->nomor_so->setQueryStringValue($_GET["nomor_so"]);
				$this->setKey("nomor_so", $this->nomor_so->CurrentValue); // Set up key
			} else {
				$this->setKey("nomor_so", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("detail_pesananlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "detail_pesananlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "detail_pesananview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->id_pesanan->CurrentValue = NULL;
		$this->id_pesanan->OldValue = $this->id_pesanan->CurrentValue;
		$this->nomor_so->CurrentValue = NULL;
		$this->nomor_so->OldValue = $this->nomor_so->CurrentValue;
		$this->nama_barang->CurrentValue = NULL;
		$this->nama_barang->OldValue = $this->nama_barang->CurrentValue;
		$this->detail_barang->CurrentValue = NULL;
		$this->detail_barang->OldValue = $this->detail_barang->CurrentValue;
		$this->jumlah_barang->CurrentValue = NULL;
		$this->jumlah_barang->OldValue = $this->jumlah_barang->CurrentValue;
		$this->harga_barang->CurrentValue = NULL;
		$this->harga_barang->OldValue = $this->harga_barang->CurrentValue;
		$this->gambar->CurrentValue = NULL;
		$this->gambar->OldValue = $this->gambar->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id_pesanan->FldIsDetailKey) {
			$this->id_pesanan->setFormValue($objForm->GetValue("x_id_pesanan"));
		}
		if (!$this->nomor_so->FldIsDetailKey) {
			$this->nomor_so->setFormValue($objForm->GetValue("x_nomor_so"));
		}
		if (!$this->nama_barang->FldIsDetailKey) {
			$this->nama_barang->setFormValue($objForm->GetValue("x_nama_barang"));
		}
		if (!$this->detail_barang->FldIsDetailKey) {
			$this->detail_barang->setFormValue($objForm->GetValue("x_detail_barang"));
		}
		if (!$this->jumlah_barang->FldIsDetailKey) {
			$this->jumlah_barang->setFormValue($objForm->GetValue("x_jumlah_barang"));
		}
		if (!$this->harga_barang->FldIsDetailKey) {
			$this->harga_barang->setFormValue($objForm->GetValue("x_harga_barang"));
		}
		if (!$this->gambar->FldIsDetailKey) {
			$this->gambar->setFormValue($objForm->GetValue("x_gambar"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->id_pesanan->CurrentValue = $this->id_pesanan->FormValue;
		$this->nomor_so->CurrentValue = $this->nomor_so->FormValue;
		$this->nama_barang->CurrentValue = $this->nama_barang->FormValue;
		$this->detail_barang->CurrentValue = $this->detail_barang->FormValue;
		$this->jumlah_barang->CurrentValue = $this->jumlah_barang->FormValue;
		$this->harga_barang->CurrentValue = $this->harga_barang->FormValue;
		$this->gambar->CurrentValue = $this->gambar->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_pesanan")) <> "")
			$this->id_pesanan->CurrentValue = $this->getKey("id_pesanan"); // id_pesanan
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("nomor_so")) <> "")
			$this->nomor_so->CurrentValue = $this->getKey("nomor_so"); // nomor_so
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id_pesanan
			$this->id_pesanan->EditAttrs["class"] = "form-control";
			$this->id_pesanan->EditCustomAttributes = "";
			$this->id_pesanan->EditValue = ew_HtmlEncode($this->id_pesanan->CurrentValue);
			$this->id_pesanan->PlaceHolder = ew_RemoveHtml($this->id_pesanan->FldCaption());

			// nomor_so
			$this->nomor_so->EditAttrs["class"] = "form-control";
			$this->nomor_so->EditCustomAttributes = "";
			$this->nomor_so->EditValue = ew_HtmlEncode($this->nomor_so->CurrentValue);
			$this->nomor_so->PlaceHolder = ew_RemoveHtml($this->nomor_so->FldCaption());

			// nama_barang
			$this->nama_barang->EditAttrs["class"] = "form-control";
			$this->nama_barang->EditCustomAttributes = "";
			$this->nama_barang->EditValue = ew_HtmlEncode($this->nama_barang->CurrentValue);
			$this->nama_barang->PlaceHolder = ew_RemoveHtml($this->nama_barang->FldCaption());

			// detail_barang
			$this->detail_barang->EditAttrs["class"] = "form-control";
			$this->detail_barang->EditCustomAttributes = "";
			$this->detail_barang->EditValue = ew_HtmlEncode($this->detail_barang->CurrentValue);
			$this->detail_barang->PlaceHolder = ew_RemoveHtml($this->detail_barang->FldCaption());

			// jumlah_barang
			$this->jumlah_barang->EditAttrs["class"] = "form-control";
			$this->jumlah_barang->EditCustomAttributes = "";
			$this->jumlah_barang->EditValue = ew_HtmlEncode($this->jumlah_barang->CurrentValue);
			$this->jumlah_barang->PlaceHolder = ew_RemoveHtml($this->jumlah_barang->FldCaption());

			// harga_barang
			$this->harga_barang->EditAttrs["class"] = "form-control";
			$this->harga_barang->EditCustomAttributes = "";
			$this->harga_barang->EditValue = ew_HtmlEncode($this->harga_barang->CurrentValue);
			$this->harga_barang->PlaceHolder = ew_RemoveHtml($this->harga_barang->FldCaption());

			// gambar
			$this->gambar->EditAttrs["class"] = "form-control";
			$this->gambar->EditCustomAttributes = "";
			$this->gambar->EditValue = ew_HtmlEncode($this->gambar->CurrentValue);
			$this->gambar->PlaceHolder = ew_RemoveHtml($this->gambar->FldCaption());

			// Add refer script
			// id_pesanan

			$this->id_pesanan->LinkCustomAttributes = "";
			$this->id_pesanan->HrefValue = "";

			// nomor_so
			$this->nomor_so->LinkCustomAttributes = "";
			$this->nomor_so->HrefValue = "";

			// nama_barang
			$this->nama_barang->LinkCustomAttributes = "";
			$this->nama_barang->HrefValue = "";

			// detail_barang
			$this->detail_barang->LinkCustomAttributes = "";
			$this->detail_barang->HrefValue = "";

			// jumlah_barang
			$this->jumlah_barang->LinkCustomAttributes = "";
			$this->jumlah_barang->HrefValue = "";

			// harga_barang
			$this->harga_barang->LinkCustomAttributes = "";
			$this->harga_barang->HrefValue = "";

			// gambar
			$this->gambar->LinkCustomAttributes = "";
			$this->gambar->HrefValue = "";
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
		if (!$this->id_pesanan->FldIsDetailKey && !is_null($this->id_pesanan->FormValue) && $this->id_pesanan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_pesanan->FldCaption(), $this->id_pesanan->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->id_pesanan->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_pesanan->FldErrMsg());
		}
		if (!$this->nomor_so->FldIsDetailKey && !is_null($this->nomor_so->FormValue) && $this->nomor_so->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nomor_so->FldCaption(), $this->nomor_so->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->nomor_so->FormValue)) {
			ew_AddMessage($gsFormError, $this->nomor_so->FldErrMsg());
		}
		if (!ew_CheckInteger($this->jumlah_barang->FormValue)) {
			ew_AddMessage($gsFormError, $this->jumlah_barang->FldErrMsg());
		}
		if (!ew_CheckInteger($this->harga_barang->FormValue)) {
			ew_AddMessage($gsFormError, $this->harga_barang->FldErrMsg());
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// id_pesanan
		$this->id_pesanan->SetDbValueDef($rsnew, $this->id_pesanan->CurrentValue, 0, FALSE);

		// nomor_so
		$this->nomor_so->SetDbValueDef($rsnew, $this->nomor_so->CurrentValue, 0, FALSE);

		// nama_barang
		$this->nama_barang->SetDbValueDef($rsnew, $this->nama_barang->CurrentValue, NULL, FALSE);

		// detail_barang
		$this->detail_barang->SetDbValueDef($rsnew, $this->detail_barang->CurrentValue, NULL, FALSE);

		// jumlah_barang
		$this->jumlah_barang->SetDbValueDef($rsnew, $this->jumlah_barang->CurrentValue, NULL, FALSE);

		// harga_barang
		$this->harga_barang->SetDbValueDef($rsnew, $this->harga_barang->CurrentValue, NULL, FALSE);

		// gambar
		$this->gambar->SetDbValueDef($rsnew, $this->gambar->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['id_pesanan']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['nomor_so']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check for duplicate key
		if ($bInsertRow && $this->ValidateKey) {
			$sFilter = $this->KeyFilter();
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sKeyErrMsg = str_replace("%f", $sFilter, $Language->Phrase("DupKey"));
				$this->setFailureMessage($sKeyErrMsg);
				$rsChk->Close();
				$bInsertRow = FALSE;
			}
		}
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("detail_pesananlist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($detail_pesanan_add)) $detail_pesanan_add = new cdetail_pesanan_add();

// Page init
$detail_pesanan_add->Page_Init();

// Page main
$detail_pesanan_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$detail_pesanan_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fdetail_pesananadd = new ew_Form("fdetail_pesananadd", "add");

// Validate form
fdetail_pesananadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_id_pesanan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $detail_pesanan->id_pesanan->FldCaption(), $detail_pesanan->id_pesanan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_pesanan");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detail_pesanan->id_pesanan->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nomor_so");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $detail_pesanan->nomor_so->FldCaption(), $detail_pesanan->nomor_so->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nomor_so");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detail_pesanan->nomor_so->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jumlah_barang");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detail_pesanan->jumlah_barang->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_harga_barang");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detail_pesanan->harga_barang->FldErrMsg()) ?>");

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
fdetail_pesananadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdetail_pesananadd.ValidateRequired = true;
<?php } else { ?>
fdetail_pesananadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$detail_pesanan_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $detail_pesanan_add->ShowPageHeader(); ?>
<?php
$detail_pesanan_add->ShowMessage();
?>
<form name="fdetail_pesananadd" id="fdetail_pesananadd" class="<?php echo $detail_pesanan_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($detail_pesanan_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $detail_pesanan_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="detail_pesanan">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($detail_pesanan_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($detail_pesanan->id_pesanan->Visible) { // id_pesanan ?>
	<div id="r_id_pesanan" class="form-group">
		<label id="elh_detail_pesanan_id_pesanan" for="x_id_pesanan" class="col-sm-2 control-label ewLabel"><?php echo $detail_pesanan->id_pesanan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $detail_pesanan->id_pesanan->CellAttributes() ?>>
<span id="el_detail_pesanan_id_pesanan">
<input type="text" data-table="detail_pesanan" data-field="x_id_pesanan" name="x_id_pesanan" id="x_id_pesanan" size="30" placeholder="<?php echo ew_HtmlEncode($detail_pesanan->id_pesanan->getPlaceHolder()) ?>" value="<?php echo $detail_pesanan->id_pesanan->EditValue ?>"<?php echo $detail_pesanan->id_pesanan->EditAttributes() ?>>
</span>
<?php echo $detail_pesanan->id_pesanan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($detail_pesanan->nomor_so->Visible) { // nomor_so ?>
	<div id="r_nomor_so" class="form-group">
		<label id="elh_detail_pesanan_nomor_so" for="x_nomor_so" class="col-sm-2 control-label ewLabel"><?php echo $detail_pesanan->nomor_so->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $detail_pesanan->nomor_so->CellAttributes() ?>>
<span id="el_detail_pesanan_nomor_so">
<input type="text" data-table="detail_pesanan" data-field="x_nomor_so" name="x_nomor_so" id="x_nomor_so" size="30" placeholder="<?php echo ew_HtmlEncode($detail_pesanan->nomor_so->getPlaceHolder()) ?>" value="<?php echo $detail_pesanan->nomor_so->EditValue ?>"<?php echo $detail_pesanan->nomor_so->EditAttributes() ?>>
</span>
<?php echo $detail_pesanan->nomor_so->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($detail_pesanan->nama_barang->Visible) { // nama_barang ?>
	<div id="r_nama_barang" class="form-group">
		<label id="elh_detail_pesanan_nama_barang" for="x_nama_barang" class="col-sm-2 control-label ewLabel"><?php echo $detail_pesanan->nama_barang->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $detail_pesanan->nama_barang->CellAttributes() ?>>
<span id="el_detail_pesanan_nama_barang">
<input type="text" data-table="detail_pesanan" data-field="x_nama_barang" name="x_nama_barang" id="x_nama_barang" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($detail_pesanan->nama_barang->getPlaceHolder()) ?>" value="<?php echo $detail_pesanan->nama_barang->EditValue ?>"<?php echo $detail_pesanan->nama_barang->EditAttributes() ?>>
</span>
<?php echo $detail_pesanan->nama_barang->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($detail_pesanan->detail_barang->Visible) { // detail_barang ?>
	<div id="r_detail_barang" class="form-group">
		<label id="elh_detail_pesanan_detail_barang" for="x_detail_barang" class="col-sm-2 control-label ewLabel"><?php echo $detail_pesanan->detail_barang->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $detail_pesanan->detail_barang->CellAttributes() ?>>
<span id="el_detail_pesanan_detail_barang">
<input type="text" data-table="detail_pesanan" data-field="x_detail_barang" name="x_detail_barang" id="x_detail_barang" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($detail_pesanan->detail_barang->getPlaceHolder()) ?>" value="<?php echo $detail_pesanan->detail_barang->EditValue ?>"<?php echo $detail_pesanan->detail_barang->EditAttributes() ?>>
</span>
<?php echo $detail_pesanan->detail_barang->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($detail_pesanan->jumlah_barang->Visible) { // jumlah_barang ?>
	<div id="r_jumlah_barang" class="form-group">
		<label id="elh_detail_pesanan_jumlah_barang" for="x_jumlah_barang" class="col-sm-2 control-label ewLabel"><?php echo $detail_pesanan->jumlah_barang->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $detail_pesanan->jumlah_barang->CellAttributes() ?>>
<span id="el_detail_pesanan_jumlah_barang">
<input type="text" data-table="detail_pesanan" data-field="x_jumlah_barang" name="x_jumlah_barang" id="x_jumlah_barang" size="30" placeholder="<?php echo ew_HtmlEncode($detail_pesanan->jumlah_barang->getPlaceHolder()) ?>" value="<?php echo $detail_pesanan->jumlah_barang->EditValue ?>"<?php echo $detail_pesanan->jumlah_barang->EditAttributes() ?>>
</span>
<?php echo $detail_pesanan->jumlah_barang->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($detail_pesanan->harga_barang->Visible) { // harga_barang ?>
	<div id="r_harga_barang" class="form-group">
		<label id="elh_detail_pesanan_harga_barang" for="x_harga_barang" class="col-sm-2 control-label ewLabel"><?php echo $detail_pesanan->harga_barang->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $detail_pesanan->harga_barang->CellAttributes() ?>>
<span id="el_detail_pesanan_harga_barang">
<input type="text" data-table="detail_pesanan" data-field="x_harga_barang" name="x_harga_barang" id="x_harga_barang" size="30" placeholder="<?php echo ew_HtmlEncode($detail_pesanan->harga_barang->getPlaceHolder()) ?>" value="<?php echo $detail_pesanan->harga_barang->EditValue ?>"<?php echo $detail_pesanan->harga_barang->EditAttributes() ?>>
</span>
<?php echo $detail_pesanan->harga_barang->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($detail_pesanan->gambar->Visible) { // gambar ?>
	<div id="r_gambar" class="form-group">
		<label id="elh_detail_pesanan_gambar" for="x_gambar" class="col-sm-2 control-label ewLabel"><?php echo $detail_pesanan->gambar->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $detail_pesanan->gambar->CellAttributes() ?>>
<span id="el_detail_pesanan_gambar">
<input type="text" data-table="detail_pesanan" data-field="x_gambar" name="x_gambar" id="x_gambar" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($detail_pesanan->gambar->getPlaceHolder()) ?>" value="<?php echo $detail_pesanan->gambar->EditValue ?>"<?php echo $detail_pesanan->gambar->EditAttributes() ?>>
</span>
<?php echo $detail_pesanan->gambar->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$detail_pesanan_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $detail_pesanan_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fdetail_pesananadd.Init();
</script>
<?php
$detail_pesanan_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$detail_pesanan_add->Page_Terminate();
?>
