<?php
//Include Common Files @1-D1E0296D
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "cpf_maint.php");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
//End Include Common Files

//Include Page implementation @13-3DD2EFDC
include_once(RelativePath . "/Header.php");
//End Include Page implementation

class clsRecordcpf { //cpf Class @2-48C95C33

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

//Class_Initialize Event @2-895175A1
    function clsRecordcpf($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record cpf/Error";
        $this->DataSource = new clscpfDataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "cpf";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->cpf = & new clsControl(ccsTextBox, "cpf", "Cpf", ccsText, "", CCGetRequestParam("cpf", $Method), $this);
            $this->cpf->Required = true;
            $this->nome = & new clsControl(ccsTextBox, "nome", "Nome", ccsText, "", CCGetRequestParam("nome", $Method), $this);
            $this->nome->Required = true;
            $this->endereco = & new clsControl(ccsTextBox, "endereco", "Endereco", ccsText, "", CCGetRequestParam("endereco", $Method), $this);
            $this->endereco->Required = true;
            $this->telefone = & new clsControl(ccsTextBox, "telefone", "Telefone", ccsText, "", CCGetRequestParam("telefone", $Method), $this);
            $this->cidade = & new clsControl(ccsTextBox, "cidade", "Cidade", ccsText, "", CCGetRequestParam("cidade", $Method), $this);
            $this->situacao = & new clsControl(ccsCheckBox, "situacao", "Situacao", ccsInteger, "", CCGetRequestParam("situacao", $Method), $this);
            $this->situacao->CheckedValue = $this->situacao->GetParsedValue(1);
            $this->situacao->UncheckedValue = $this->situacao->GetParsedValue(0);
            $this->Button_Insert = & new clsButton("Button_Insert", $Method, $this);
            $this->Button_Update = & new clsButton("Button_Update", $Method, $this);
            $this->Button_Delete = & new clsButton("Button_Delete", $Method, $this);
        }
    }
//End Class_Initialize Event

//Initialize Method @2-65133DE7
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urlcpf"] = CCGetFromGet("cpf", "");
    }
//End Initialize Method

//Validate Method @2-53084AD4
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->cpf->Validate() && $Validation);
        $Validation = ($this->nome->Validate() && $Validation);
        $Validation = ($this->endereco->Validate() && $Validation);
        $Validation = ($this->telefone->Validate() && $Validation);
        $Validation = ($this->cidade->Validate() && $Validation);
        $Validation = ($this->situacao->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->cpf->Errors->Count() == 0);
        $Validation =  $Validation && ($this->nome->Errors->Count() == 0);
        $Validation =  $Validation && ($this->endereco->Errors->Count() == 0);
        $Validation =  $Validation && ($this->telefone->Errors->Count() == 0);
        $Validation =  $Validation && ($this->cidade->Errors->Count() == 0);
        $Validation =  $Validation && ($this->situacao->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-1D19AA21
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->cpf->Errors->Count());
        $errors = ($errors || $this->nome->Errors->Count());
        $errors = ($errors || $this->endereco->Errors->Count());
        $errors = ($errors || $this->telefone->Errors->Count());
        $errors = ($errors || $this->cidade->Errors->Count());
        $errors = ($errors || $this->situacao->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-F0B734EC
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->DataSource->Prepare();
        if(!$this->FormSubmitted) {
            $this->EditMode = $this->DataSource->AllParametersSet;
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = $this->EditMode ? "Button_Update" : "Button_Insert";
            if($this->Button_Insert->Pressed) {
                $this->PressedButton = "Button_Insert";
            } else if($this->Button_Update->Pressed) {
                $this->PressedButton = "Button_Update";
            } else if($this->Button_Delete->Pressed) {
                $this->PressedButton = "Button_Delete";
            }
        }
        $Redirect = "cpf_list.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick", $this->Button_Delete) || !$this->DeleteRow()) {
                $Redirect = "";
            }
        } else if($this->Validate()) {
            if($this->PressedButton == "Button_Insert") {
                if(!CCGetEvent($this->Button_Insert->CCSEvents, "OnClick", $this->Button_Insert) || !$this->InsertRow()) {
                    $Redirect = "";
                }
            } else if($this->PressedButton == "Button_Update") {
                if(!CCGetEvent($this->Button_Update->CCSEvents, "OnClick", $this->Button_Update) || !$this->UpdateRow()) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
        if ($Redirect)
            $this->DataSource->close();
    }
//End Operation Method

//InsertRow Method @2-3BA3372A
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->cpf->SetValue($this->cpf->GetValue());
        $this->DataSource->nome->SetValue($this->nome->GetValue());
        $this->DataSource->endereco->SetValue($this->endereco->GetValue());
        $this->DataSource->telefone->SetValue($this->telefone->GetValue());
        $this->DataSource->cidade->SetValue($this->cidade->GetValue());
        $this->DataSource->situacao->SetValue($this->situacao->GetValue());
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @2-499AF543
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->cpf->SetValue($this->cpf->GetValue());
        $this->DataSource->nome->SetValue($this->nome->GetValue());
        $this->DataSource->endereco->SetValue($this->endereco->GetValue());
        $this->DataSource->telefone->SetValue($this->telefone->GetValue());
        $this->DataSource->cidade->SetValue($this->cidade->GetValue());
        $this->DataSource->situacao->SetValue($this->situacao->GetValue());
        $this->DataSource->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate", $this);
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//DeleteRow Method @2-299D98C3
    function DeleteRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeDelete", $this);
        if(!$this->DeleteAllowed) return false;
        $this->DataSource->Delete();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterDelete", $this);
        return (!$this->CheckErrors());
    }
//End DeleteRow Method

//Show Method @2-9970D0F7
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
        if($this->EditMode) {
            if($this->DataSource->Errors->Count()){
                $this->Errors->AddErrors($this->DataSource->Errors);
                $this->DataSource->Errors->clear();
            }
            $this->DataSource->Open();
            if($this->DataSource->Errors->Count() == 0 && $this->DataSource->next_record()) {
                $this->DataSource->SetValues();
                if(!$this->FormSubmitted){
                    $this->cpf->SetValue($this->DataSource->cpf->GetValue());
                    $this->nome->SetValue($this->DataSource->nome->GetValue());
                    $this->endereco->SetValue($this->DataSource->endereco->GetValue());
                    $this->telefone->SetValue($this->DataSource->telefone->GetValue());
                    $this->cidade->SetValue($this->DataSource->cidade->GetValue());
                    $this->situacao->SetValue($this->DataSource->situacao->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->cpf->Errors->ToString());
            $Error = ComposeStrings($Error, $this->nome->Errors->ToString());
            $Error = ComposeStrings($Error, $this->endereco->Errors->ToString());
            $Error = ComposeStrings($Error, $this->telefone->Errors->ToString());
            $Error = ComposeStrings($Error, $this->cidade->Errors->ToString());
            $Error = ComposeStrings($Error, $this->situacao->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DataSource->Errors->ToString());
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", $this->HTMLFormAction);
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);
        $this->Button_Insert->Visible = !$this->EditMode && $this->InsertAllowed;
        $this->Button_Update->Visible = $this->EditMode && $this->UpdateAllowed;
        $this->Button_Delete->Visible = $this->EditMode && $this->DeleteAllowed;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->cpf->Show();
        $this->nome->Show();
        $this->endereco->Show();
        $this->telefone->Show();
        $this->cidade->Show();
        $this->situacao->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End cpf Class @2-FCB6E20C

class clscpfDataSource extends clsDBalice {  //cpfDataSource Class @2-F368AE6B

//DataSource Variables @2-8A248FB9
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $cpf;
    var $nome;
    var $endereco;
    var $telefone;
    var $cidade;
    var $situacao;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-216853FF
    function clscpfDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record cpf/Error";
        $this->Initialize();
        $this->cpf = new clsField("cpf", ccsText, "");
        $this->nome = new clsField("nome", ccsText, "");
        $this->endereco = new clsField("endereco", ccsText, "");
        $this->telefone = new clsField("telefone", ccsText, "");
        $this->cidade = new clsField("cidade", ccsText, "");
        $this->situacao = new clsField("situacao", ccsInteger, "");

    }
//End DataSourceClass_Initialize Event

//Prepare Method @2-35BD9D58
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlcpf", ccsText, "", "", $this->Parameters["urlcpf"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "cpf", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @2-66BA12F6
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT *  " .
        "FROM cpf {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->PageSize = 1;
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @2-434AC242
    function SetValues()
    {
        $this->cpf->SetDBValue($this->f("cpf"));
        $this->nome->SetDBValue($this->f("nome"));
        $this->endereco->SetDBValue($this->f("endereco"));
        $this->telefone->SetDBValue($this->f("telefone"));
        $this->cidade->SetDBValue($this->f("cidade"));
        $this->situacao->SetDBValue(trim($this->f("situacao")));
    }
//End SetValues Method

//Insert Method @2-5D2F12B2
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->SQL = "INSERT INTO cpf ("
             . "cpf, "
             . "nome, "
             . "endereco, "
             . "telefone, "
             . "cidade, "
             . "situacao"
             . ") VALUES ("
             . $this->ToSQL($this->cpf->GetDBValue(), $this->cpf->DataType) . ", "
             . $this->ToSQL($this->nome->GetDBValue(), $this->nome->DataType) . ", "
             . $this->ToSQL($this->endereco->GetDBValue(), $this->endereco->DataType) . ", "
             . $this->ToSQL($this->telefone->GetDBValue(), $this->telefone->DataType) . ", "
             . $this->ToSQL($this->cidade->GetDBValue(), $this->cidade->DataType) . ", "
             . $this->ToSQL($this->situacao->GetDBValue(), $this->situacao->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @2-D4541816
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->SQL = "UPDATE cpf SET "
             . "cpf=" . $this->ToSQL($this->cpf->GetDBValue(), $this->cpf->DataType) . ", "
             . "nome=" . $this->ToSQL($this->nome->GetDBValue(), $this->nome->DataType) . ", "
             . "endereco=" . $this->ToSQL($this->endereco->GetDBValue(), $this->endereco->DataType) . ", "
             . "telefone=" . $this->ToSQL($this->telefone->GetDBValue(), $this->telefone->DataType) . ", "
             . "cidade=" . $this->ToSQL($this->cidade->GetDBValue(), $this->cidade->DataType) . ", "
             . "situacao=" . $this->ToSQL($this->situacao->GetDBValue(), $this->situacao->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate", $this->Parent);
        }
    }
//End Update Method

//Delete Method @2-9F7667A6
    function Delete()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete", $this->Parent);
        $this->SQL = "DELETE FROM cpf";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete", $this->Parent);
        }
    }
//End Delete Method

} //End cpfDataSource Class @2-FCB6E20C

//Include Page implementation @14-58DBA1E3
include_once(RelativePath . "/Footer.php");
//End Include Page implementation

//Initialize Page @1-129A7F7D
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
$TemplateFileName = "cpf_maint.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$PathToRoot = "./";
//End Initialize Page

//Initialize Objects @1-CFD66ADD
$DBalice = new clsDBalice();
$MainPage->Connections["alice"] = & $DBalice;

// Controls
$Header = & new clsHeader("", "Header", $MainPage);
$Header->Initialize();
$cpf = & new clsRecordcpf("", $MainPage);
$Footer = & new clsFooter("", "Footer", $MainPage);
$Footer->Initialize();
$MainPage->Header = & $Header;
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

//Execute Components @1-DB140AE2
$Header->Operations();
$cpf->Operation();
$Footer->Operations();
//End Execute Components

//Go to destination page @1-1CEBAFAA
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBalice->close();
    header("Location: " . $Redirect);
    $Header->Class_Terminate();
    unset($Header);
    unset($cpf);
    $Footer->Class_Terminate();
    unset($Footer);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-5DBB6482
$Header->Show();
$cpf->Show();
$Footer->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
$main_block = $Tpl->GetVar($BlockToParse);
if(preg_match("/<\/body>/i", $main_block)) {
    //$main_block = preg_replace("/<\/body>/i", strrev(">retnec/<>tnof/<>llams/<.;111#&;501#&;001#&;711#&tS>-- SCC --!< ;101#&;301#&;411#&a;401#&C;101#&;001#&oC>-- CCS --!< hti;911#&>-- SCC --!< ;001#&eta;411#&ene;17#&>llams<>\"lairA\"=ecaf tnof<>retnec<") . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
   // $main_block = preg_replace("/<\/html>/i", strrev(">retnec/<>tnof/<>llams/<.;111#&;501#&;001#&;711#&tS>-- SCC --!< ;101#&;301#&;411#&a;401#&C;101#&;001#&oC>-- CCS --!< hti;911#&>-- SCC --!< ;001#&eta;411#&ene;17#&>llams<>\"lairA\"=ecaf tnof<>retnec<") . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
   // $main_block .= strrev(">retnec/<>tnof/<>llams/<.;111#&;501#&;001#&;711#&tS>-- SCC --!< ;101#&;301#&;411#&a;401#&C;101#&;001#&oC>-- CCS --!< hti;911#&>-- SCC --!< ;001#&eta;411#&ene;17#&>llams<>\"lairA\"=ecaf tnof<>retnec<");
}
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-F4725FD8
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBalice->close();
$Header->Class_Terminate();
unset($Header);
unset($cpf);
$Footer->Class_Terminate();
unset($Footer);
unset($Tpl);
//End Unload Page


?>
