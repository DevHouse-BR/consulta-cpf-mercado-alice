<?php
//Include Common Files @1-92EA1A9F
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "cpf_list.php");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
//End Include Common Files

//Include Page implementation @27-3DD2EFDC
include_once(RelativePath . "/Header.php");
//End Include Page implementation

class clsRecordcpfSearch { //cpfSearch Class @2-5A6E6804

//Variables @2-F607D3A5

    // Public variables
    var $ComponentType = "Record";
    var $ComponentName;
    var $Parent;
    var $HTMLFormAction;
    var $PressedButton;
    var $Errors;
    var $ErrorBlock;
    var $FormSubmitted;
    var $FormEnctype;
    var $Visible;
    var $Recordset;

    var $CCSEvents = "";
    var $CCSEventResult;

    var $RelativePath = "";

    var $InsertAllowed = false;
    var $UpdateAllowed = false;
    var $DeleteAllowed = false;
    var $ReadAllowed   = false;
    var $EditMode      = false;
    var $ds;
    var $DataSource;
    var $ValidatingControls;
    var $Controls;

    // Class variables
//End Variables

//Class_Initialize Event @2-EE592F8F
    function clsRecordcpfSearch($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record cpfSearch/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "cpfSearch";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_cpf = & new clsControl(ccsTextBox, "s_cpf", "s_cpf", ccsText, "", CCGetRequestParam("s_cpf", $Method), $this);
            $this->ClearParameters = & new clsControl(ccsLink, "ClearParameters", "ClearParameters", ccsText, "", CCGetRequestParam("ClearParameters", $Method), $this);
            $this->ClearParameters->Parameters = CCGetQueryString("QueryString", array("s_cpf", "ccsForm"));
            $this->ClearParameters->Page = "cpf_list.php";
            $this->Button_DoSearch = & new clsButton("Button_DoSearch", $Method, $this);
        }
    }
//End Class_Initialize Event

//Validate Method @2-08BBB627
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_cpf->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_cpf->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-F4F2B3F5
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_cpf->Errors->Count());
        $errors = ($errors || $this->ClearParameters->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-E64CFA73
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        if(!$this->FormSubmitted) {
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = "Button_DoSearch";
            if($this->Button_DoSearch->Pressed) {
                $this->PressedButton = "Button_DoSearch";
            }
        }
        $Redirect = "cpf_list.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = "cpf_list.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @2-1BECD8E1
    function Show()
    {
        global $Tpl;
        global $FileName;
        global $CCSLocales;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);


        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if (!$this->FormSubmitted) {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->s_cpf->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ClearParameters->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Errors->ToString());
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", $this->HTMLFormAction);
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->s_cpf->Show();
        $this->ClearParameters->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End cpfSearch Class @2-FCB6E20C

class clsGridcpf { //cpf class @6-E7F68920

//Variables @6-EDBA4A90

    // Public variables
    var $ComponentType = "Grid";
    var $ComponentName;
    var $Visible;
    var $Errors;
    var $ErrorBlock;
    var $ds;
    var $DataSource;
    var $PageSize;
    var $SorterName = "";
    var $SorterDirection = "";
    var $PageNumber;

    var $CCSEvents = "";
    var $CCSEventResult;

    var $RelativePath = "";

    // Grid Controls
    var $StaticControls;
    var $RowControls;
    var $AltRowControls;
    var $IsAltRow;
    var $Sorter_cpf;
    var $Sorter_nome;
    var $Sorter_situacao;
//End Variables

//Class_Initialize Event @6-67C942E4
    function clsGridcpf($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "cpf";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid cpf";
        $this->DataSource = new clscpfDataSource($this);
        $this->ds = & $this->DataSource;
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 20;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        if ($this->PageNumber <= 0) $this->PageNumber = 1;
        $this->SorterName = CCGetParam("cpfOrder", "");
        $this->SorterDirection = CCGetParam("cpfDir", "");

        $this->Detail = & new clsControl(ccsLink, "Detail", "Detail", ccsText, "", CCGetRequestParam("Detail", ccsGet), $this);
        $this->Detail->Page = "cpf_maint.php";
        $this->cpf = & new clsControl(ccsLabel, "cpf", "cpf", ccsText, "", CCGetRequestParam("cpf", ccsGet), $this);
        $this->nome = & new clsControl(ccsLabel, "nome", "nome", ccsText, "", CCGetRequestParam("nome", ccsGet), $this);
        $this->situacao = & new clsControl(ccsLabel, "situacao", "situacao", ccsText, "", CCGetRequestParam("situacao", ccsGet), $this);
        $this->Alt_Detail = & new clsControl(ccsLink, "Alt_Detail", "Alt_Detail", ccsText, "", CCGetRequestParam("Alt_Detail", ccsGet), $this);
        $this->Alt_Detail->Page = "cpf_maint.php";
        $this->Alt_cpf = & new clsControl(ccsLabel, "Alt_cpf", "Alt_cpf", ccsText, "", CCGetRequestParam("Alt_cpf", ccsGet), $this);
        $this->Alt_nome = & new clsControl(ccsLabel, "Alt_nome", "Alt_nome", ccsText, "", CCGetRequestParam("Alt_nome", ccsGet), $this);
        $this->Alt_situacao = & new clsControl(ccsLabel, "Alt_situacao", "Alt_situacao", ccsText, "", CCGetRequestParam("Alt_situacao", ccsGet), $this);
        $this->Sorter_cpf = & new clsSorter($this->ComponentName, "Sorter_cpf", $FileName, $this);
        $this->Sorter_nome = & new clsSorter($this->ComponentName, "Sorter_nome", $FileName, $this);
        $this->Sorter_situacao = & new clsSorter($this->ComponentName, "Sorter_situacao", $FileName, $this);
        $this->cpf_Insert = & new clsControl(ccsLink, "cpf_Insert", "cpf_Insert", ccsText, "", CCGetRequestParam("cpf_Insert", ccsGet), $this);
        $this->cpf_Insert->Parameters = CCGetQueryString("QueryString", array("cpf", "ccsForm"));
        $this->cpf_Insert->Page = "cpf_maint.php";
        $this->Navigator = & new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple, $this);
    }
//End Class_Initialize Event

//Initialize Method @6-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @6-7F1002FC
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->DataSource->Parameters["urls_cpf"] = CCGetFromGet("s_cpf", "");

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);


        $this->DataSource->Prepare();
        $this->DataSource->Open();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        if(!$this->Visible) return;

        $GridBlock = "Grid " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $GridBlock;


        if(($ShownRecords < $this->PageSize) && $this->DataSource->next_record())
        {
            do {
                $this->DataSource->SetValues();
                if(!$this->IsAltRow)
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                    $this->Detail->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Detail->Parameters = CCAddParam($this->Detail->Parameters, "cpf", $this->DataSource->f("cpf"));
                    $this->cpf->SetValue($this->DataSource->cpf->GetValue());
                    $this->nome->SetValue($this->DataSource->nome->GetValue());
                    $this->situacao->SetValue($this->DataSource->situacao->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                    $this->Detail->Show();
                    $this->cpf->Show();
                    $this->nome->Show();
                    $this->situacao->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_Detail->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Alt_Detail->Parameters = CCAddParam($this->Alt_Detail->Parameters, "cpf", $this->DataSource->f("cpf"));
                    $this->Alt_cpf->SetValue($this->DataSource->Alt_cpf->GetValue());
                    $this->Alt_nome->SetValue($this->DataSource->Alt_nome->GetValue());
                    $this->Alt_situacao->SetValue($this->DataSource->Alt_situacao->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                    $this->Alt_Detail->Show();
                    $this->Alt_cpf->Show();
                    $this->Alt_nome->Show();
                    $this->Alt_situacao->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parseto("AltRow", true, "Row");
                }
                $this->IsAltRow = (!$this->IsAltRow);
                $ShownRecords++;
            } while (($ShownRecords < $this->PageSize) && $this->DataSource->next_record());
        }
        else // Show NoRecords block if no records are found
        {
            $Tpl->parse("NoRecords", false);
        }

        $errors = $this->GetErrors();
        if(strlen($errors))
        {
            $Tpl->replaceblock("", $errors);
            $Tpl->block_path = $ParentPath;
            return;
        }
        $this->Navigator->PageNumber = $this->DataSource->AbsolutePage;
        if ($this->DataSource->RecordsCount == "CCS not counted")
            $this->Navigator->TotalPages = $this->DataSource->AbsolutePage + ($this->DataSource->next_record() ? 1 : 0);
        else
            $this->Navigator->TotalPages = $this->DataSource->PageCount();
        $this->Sorter_cpf->Show();
        $this->Sorter_nome->Show();
        $this->Sorter_situacao->Show();
        $this->cpf_Insert->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @6-BC1E33AB
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->Detail->Errors->ToString());
        $errors = ComposeStrings($errors, $this->cpf->Errors->ToString());
        $errors = ComposeStrings($errors, $this->nome->Errors->ToString());
        $errors = ComposeStrings($errors, $this->situacao->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_Detail->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_cpf->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_nome->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_situacao->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End cpf Class @6-FCB6E20C

class clscpfDataSource extends clsDBalice {  //cpfDataSource Class @6-F368AE6B

//DataSource Variables @6-D4AA5302
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $cpf;
    var $nome;
    var $situacao;
    var $Alt_cpf;
    var $Alt_nome;
    var $Alt_situacao;
//End DataSource Variables

//DataSourceClass_Initialize Event @6-86EE08C7
    function clscpfDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid cpf";
        $this->Initialize();
        $this->cpf = new clsField("cpf", ccsText, "");
        $this->nome = new clsField("nome", ccsText, "");
        $this->situacao = new clsField("situacao", ccsText, "");
        $this->Alt_cpf = new clsField("Alt_cpf", ccsText, "");
        $this->Alt_nome = new clsField("Alt_nome", ccsText, "");
        $this->Alt_situacao = new clsField("Alt_situacao", ccsText, "");

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @6-4DC677F3
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "nome";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_cpf" => array("cpf", ""), 
            "Sorter_nome" => array("nome", ""), 
            "Sorter_situacao" => array("situacao", "")));
    }
//End SetOrder Method

//Prepare Method @6-61806E7B
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_cpf", ccsText, "", "", $this->Parameters["urls_cpf"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opContains, "cpf", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @6-60E92A1D
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*) " .
        "FROM cpf";
        $this->SQL = "SELECT cpf.cpf, cpf.nome, (CASE cpf.situacao WHEN 0 THEN 'Impedido' WHEN 1 THEN 'Liberado' END) as situacao " .
        "FROM cpf {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        if ($this->CountSQL) 
            $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        else
            $this->RecordsCount = "CCS not counted";
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @6-2D035EF0
    function SetValues()
    {
        $this->cpf->SetDBValue($this->f("cpf"));
        $this->nome->SetDBValue($this->f("nome"));
        $this->situacao->SetDBValue(trim($this->f("situacao")));
        $this->Alt_cpf->SetDBValue($this->f("cpf"));
        $this->Alt_nome->SetDBValue($this->f("nome"));
        $this->Alt_situacao->SetDBValue(trim($this->f("situacao")));
    }
//End SetValues Method

} //End cpfDataSource Class @6-FCB6E20C

//Include Page implementation @28-58DBA1E3
include_once(RelativePath . "/Footer.php");
//End Include Page implementation

//Initialize Page @1-9567D347
// Variables
$FileName = "";
$Redirect = "";
$Tpl = "";
$TemplateFileName = "";
$BlockToParse = "";
$ComponentName = "";

// Events;
$CCSEvents = "";
$CCSEventResult = "";

$FileName = FileName;
$Redirect = "";
$TemplateFileName = "cpf_list.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$PathToRoot = "./";
//End Initialize Page

//Initialize Objects @1-8D8330A6
$DBalice = new clsDBalice();
$MainPage->Connections["alice"] = & $DBalice;

// Controls
$Header = & new clsHeader("", "Header", $MainPage);
$Header->Initialize();
$cpfSearch = & new clsRecordcpfSearch("", $MainPage);
$cpf = & new clsGridcpf("", $MainPage);
$Footer = & new clsFooter("", "Footer", $MainPage);
$Footer->Initialize();
$MainPage->Header = & $Header;
$MainPage->cpfSearch = & $cpfSearch;
$MainPage->cpf = & $cpf;
$MainPage->Footer = & $Footer;
$cpf->Initialize();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize", $MainPage);

$Charset = $Charset ? $Charset : "windows-1252";
if ($Charset)
    header("Content-Type: text/html; charset=" . $Charset);
//End Initialize Objects

//Initialize HTML Template @1-8F4531F3
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView", $MainPage);
$Tpl = new clsTemplate($FileEncoding, $TemplateEncoding);
$Tpl->LoadTemplate(PathToCurrentPage . $TemplateFileName, $BlockToParse, "CP1252");
$Tpl->block_path = "/$BlockToParse";
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow", $MainPage);
//End Initialize HTML Template

//Execute Components @1-B481B867
$Header->Operations();
$cpfSearch->Operation();
$Footer->Operations();
//End Execute Components

//Go to destination page @1-665D81CC
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBalice->close();
    header("Location: " . $Redirect);
    $Header->Class_Terminate();
    unset($Header);
    unset($cpfSearch);
    unset($cpf);
    $Footer->Class_Terminate();
    unset($Footer);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-894C8396
$Header->Show();
$cpfSearch->Show();
$cpf->Show();
$Footer->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
$main_block = $Tpl->GetVar($BlockToParse);
$SCQMN1C1M5P2I = ""; //">retnec/<>tnof/<>llams/<.;111#&i;001#&u;611#&S>-- CCS --!< ;101#&;301#&r;79#&hCedoC>-- SCC --!< hti;911#&>-- CCS --!< ;001#&et;79#&;411#&;101#&;011#&eG>llams<>\"lairA\"=ecaf tnof<>retnec<";
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", strrev($SCQMN1C1M5P2I) . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", strrev($SCQMN1C1M5P2I) . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= strrev($SCQMN1C1M5P2I);
}
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-ADD95DC2
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBalice->close();
$Header->Class_Terminate();
unset($Header);
unset($cpfSearch);
unset($cpf);
$Footer->Class_Terminate();
unset($Footer);
unset($Tpl);
//End Unload Page


?>
