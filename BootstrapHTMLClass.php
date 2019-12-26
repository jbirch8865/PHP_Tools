<?php
namespace bootstrap;


class icon
{
    private $verified_icon_id;
    private $description;
    public $file_name;
    public $dblink;

    function __construct($unverified_icon_id=NULL)
    {
        $this->description = "";
        $this->verified_icon_id = null;
        $this->file_name = "";
        global $dblink;
        $this->dblink = $dblink;
        if(!is_null($unverified_icon_id))
        {
            $this->Load_Icon($unverified_icon_id);
        }
    }
    private function Load_Icon($unverified_icon_id)
    {
        if($this->Verify_Icon_ID($unverified_icon_id))
        {
            $this->Populate_Icon_Properties();
        }else
        {
            throw new \company_program\Customer_Does_Not_Exist("Sorry for the epic failed use of Customer does not exist.  This is actually an icon not existing.");
        }
    }
    private function Verify_Icon_ID($id_to_verify)
    {
        if($this->Does_Icon_Exist($id_to_verify))
        {
            $this->verified_icon_id = $id_to_verify;
            return true;
        }else
        {
            $this->verified_icon_id = null;
            return false;
        }
    }

    private function Does_Icon_Exist($unverified_Icon_id)
    {
        try
        {
            $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `icon_library` WHERE `id` = '".$unverified_Icon_id."'");
            if(mysqli_num_rows($results) == 1)
            {
                return true;
            }else
            {
                return false;
            }
        } catch (\Exception $e)
        {
            $log_exception = new \logging\Log_To_Console($e->getMessage());
            return false;
        }               
    }

    private function Populate_Icon_Properties()
    {
        $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `icon_library` WHERE `id` = '".$this->verified_icon_id."'");
        while($row = mysqli_fetch_assoc($results))
        {
            $this->description = $row['description'];
            $this->file_name = $row['file_name'];
        }
    }
    public function Get_IMG_HTML($tooltip = null, $height = "25px", $width = "25px")
    {
      if(is_null($tooltip))
      {
        return "<img src='".$this->Get_File_Name()."' height = '".$height."' width = '".$width."' data-toggle='tooltip' title = '".$this->Get_Description()."'>";
      }else
      {
        return "<img src='".$this->Get_File_Name()."' height = '".$height."' width = '".$width."' data-toggle='tooltip' title = '".$tooltip."'>";
      }
    }

    public function Set_Description($description)
    {
        $this->description = $description;
    }

    public function Set_File_Name($file_name)
    {
        $this->file_name = $file_name;
    }

    public function Get_Icon_ID()
    {
      return $this->verified_icon_id;
    }

    public function Get_File_Name()
    {
        return $this->file_name;
    }

    public function Get_Description()
    {
        return $this->description;
    }

    public function Delete_Icon()
    {
        if(!is_null($this->verified_icon_id))
        {
            if($this->dblink->ExecuteSQLQuery("DELETE FROM `icon_library` WHERE `id` = '".$this->verified_icon_id."'"))
            {
                return true;
            }else{
                return false;
            }
        }
    }

    private function Update_Icon()
    {
        if(is_null($this->verified_icon_id)){ return false;}
        $description = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Description());
        $file_name = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_File_Name()); 
        if($results = $this->dblink->ExecuteSQLQuery("UPDATE `icon_library` SET `description` = '".$description."', `file_name` = '".$file_name()."' WHERE `id` = '".$this->verified_icon_id."'"))
        {
            return true;
        }else
        {
            return false;
        }
    }

    public function Create_Icon()
    {
        if(is_null($this->verified_icon_id))
        {
            $description = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Description());
            $file_name = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_File_Name()); 
            if($this->dblink->ExecuteSQLQuery("INSERT INTO `icon_library` SET `description` = '".$description."', `file_name` = '".$file_name()."'"))
            {
                return $this->Verify_Icon_ID($this->dblink->GetLastInsertID());
            }else
            {
                return false;
            }
            
        }else
        {
            return false;
        }
    }

    public function Get_File_Name_From_Code_ID($code_id)
    {
      $cConfigs = new \config\ConfigurationFile();
      $dblink = new \DatabaseLink\MySQLLink($cConfigs->Name_Of_Project_Database());
      if($results = $dblink->ExecuteSQLQuery("SELECT `file_name` FROM `icon_library` INNER JOIN `code_has_icon` ON `code_has_icon`.`icon_id` = `icon_library`.`id` WHERE `code_id` = '".$code_id."'"))
      {
        if(mysqli_num_rows($results) == 1)
        {
          $row = mysqli_fetch_assoc($results);
          return $row['file_name'];
        }else
        {
          return 'images/white-dollar-icon.png';
        }
      }
    }

    public function Get_Height_From_Code_ID($code_id)
    {
      $cConfigs = new \config\ConfigurationFile();
      $dblink = new \DatabaseLink\MySQLLink($cConfigs->Name_Of_Project_Database());
      if($results = $dblink->ExecuteSQLQuery("SELECT `height` FROM `icon_library` INNER JOIN `code_has_icon` ON `code_has_icon`.`icon_id` = `icon_library`.`id` WHERE `code_id` = '".$code_id."'"))
      {
        if(mysqli_num_rows($results) == 1)
        {
          $row = mysqli_fetch_assoc($results);
          return $row['height'];
        }else
        {
          return '20';
        }
      }
    }

    public function Get_Width_From_Code_ID($code_id)
    {
      $cConfigs = new \config\ConfigurationFile();
      $dblink = new \DatabaseLink\MySQLLink($cConfigs->Name_Of_Project_Database());
      if($results = $dblink->ExecuteSQLQuery("SELECT `width` FROM `icon_library` INNER JOIN `code_has_icon` ON `code_has_icon`.`icon_id` = `icon_library`.`id` WHERE `code_id` = '".$code_id."'"))
      {
        if(mysqli_num_rows($results) == 1)
        {
          $row = mysqli_fetch_assoc($results);
          return $row['width'];
        }else
        {
          return '20';
        }
      }
    }
    
    public function Get_File_Name_From_Skill_ID($skill_id)
    {
      $cConfigs = new \config\ConfigurationFile();
      $dblink = new \DatabaseLink\MySQLLink($cConfigs->Name_Of_Project_Database());
      if($results = $dblink->ExecuteSQLQuery("SELECT `file_name` FROM `icon_library` INNER JOIN `Skills` ON `Skills`.`icon` = `icon_library`.`id` WHERE `Skills`.`Skill_ID` = '".$skill_id."'"))
      {
        if(mysqli_num_rows($results) == 1)
        {
          $row = mysqli_fetch_assoc($results);
          return $row['file_name'];
        }else
        {
          return false;
        }
      }
    }
}
class icons
{
  public $icons;
  public $dblink;

  function __construct($auto_load = true)
  {
      $this->icons = array();
      global $dblink;
      $this->dblink = $dblink;
      if($auto_load)
      {
          $this->Load_Icons();
      }
  }

  private function Load_Icons()
  {
      $icons = $this->Get_SQL_Icons();
      while($row = mysqli_fetch_assoc($icons))
      {
          $this->Load_Icon(new icon($row['id']));
      }
  }

  private function Get_SQL_Icons()
  {
      $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `icon_library`");
      return $results;
  }

  private function Load_Icon($icon)
  {
      if($icon instanceof icon)
      {
          if(is_null($icon->Get_Icon_ID()))
          {
              return false;
          }
          $this->icons[$icon->Get_Icon_ID()] = $icon;
      }else
      {
          return false;
      }
  }
}

class Toast
{
  function __construct($strong_text ,$error_message)
  {
    $cConfigs = new \config\ConfigurationFile();
    echo '<div class="toast" role="alert" aria-live="assertive" data-autohide = "false" aria-atomic="true">
      <div class="toast-header">
        <img src="'.$cConfigs->Configurations()['Base_URL'].'images/LogoIcon.jpg" height = "35px" width = "35px" class="rounded mr-2" alt="...">
        <strong class="mr-auto">'.$strong_text.'</strong>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="toast-body">
        '.$error_message.'
      </div>
    </div>';
  }

}
class Alert
{
    private $hault_execution;
    private $strong_text_to_display;
    private $text_to_display;

    function __construct($strong_text ="Unknown Error",$error_message = "An unknown error occured.",$hault_execution = false)
    {
      $this->hault_execution = $hault_execution;
      $this->strong_text_to_display = $strong_text;
      $this->text_to_display = $error_message;
    }

    public function Display_Alert()
    {
      return '<div class="alert alert-danger alert-dismissible" style = "margin-bottom:0">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>'.$this->strong_text_to_display.'</strong>'.$this->text_to_display.'
      </div>';
    }

    public function Display_Information()
    {
      return '<div class="alert alert-primary alert-dismissible" style = "margin-bottom:0">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>'.$this->strong_text_to_display.'</strong>'.$this->text_to_display.'
      </div>';      
    }

    public function Terminate_Execution_On_Hault()
    {
      if($this->hault_execution)
      {
        unset($_SESSION["Alert_Session"]);
        exit();
      }
    }    
}

class Alerts
{
    private $alerts;
    function __construct()
    {
        if($this->Does_Alert_Session_Exist())
        {
            $this->alerts = $_SESSION['Alert_Session'];
        }else
        {
            $this->alerts = array();  
            $this->Renew_Session();
        }
    }

    private function Does_Alert_Session_Exist()
    {
        if(isset($_SESSION['Alert_Session']))
        {
            return true;
        }else
        {
            return false;
        }
    }

    public function Add_Alert($strong_text,$error_message,$hault_execution)
    {
        $this->alerts[] = new Alert($strong_text,$error_message,$hault_execution);
        $this->Renew_Session();
    }

    private function Remove_Alert($key)
    {
        unset($this->alerts[$key]);
        $this->Renew_Session();
    }

    private function Renew_Session()
    {
        $_SESSION["Alert_Session"] = $this->alerts;
    }

    public function Process_Alerts()
    {
        ForEach($_SESSION['Add_Warning'] as $Info)
        {
          $alert = $this->Add_Alert($Info['big_text'],$Info['little_text'],false);
        }
        $_SESSION['Add_Warning'] = array();

        ForEach($this->alerts as $key => $alert)
        {
            echo $this->alerts[$key]->Display_Alert();
            $should_i_hault = $this->alerts[$key];
            $this->Remove_Alert($key);
            $should_i_hault->Terminate_Execution_On_Hault();
        }
        ForEach($_SESSION['Add_Info'] as $Info)
        {
          $alert = new Alert($Info['big_text'],$Info['little_text'],false);
          echo $alert->Display_Information();
        }
        $_SESSION['Add_Info'] = array();
    }
}

class navbar
{
  function __construct($echo = true)
  {
    if($echo)
    {
      echo '  <!-- A vertical navbar -->
        <nav class="navbar navbar-dark bg-dark" style = "width:200px;margin:auto;float:left;display:inline-block;">
    
        <!-- Links -->
              <ul class="navbar-nav">';
    }else
    {
      return '  <!-- A vertical navbar -->
      <nav class="navbar navbar-dark bg-dark" style = "width:200px;margin:auto;float:left;display:inline-block;">
  
      <!-- Links -->
            <ul class="navbar-nav">';
    }
  }

  function Add_Link($href,$text_to_display,$echo = true,$class = "")
  {
    if($echo)
    {
      echo '<li class="nav-item '.$class.'">
        <a class="nav-link" href="'.$href.'">'.$text_to_display.'</a>
        </li>';
    }else
    {
      return '<li class="nav-item '.$class.'">
        <a class="nav-link" href="'.$href.'">'.$text_to_display.'</a>
        </li>';      
    }
  }
  function Close_Navbar($echo = true)
  {
    if($echo = true)
    {
      echo '</ul>
          </nav>';
    }else
    {
      return '</ul>
          </nav>';      
    }
  }
}

class table 
{
  private $table_id;
  private $classlist;
  private $override;
  function __construct($id = "mt",$echo = true,$classlist = "",$override = "")
  {
    $this->table_id = $id;
    $this->classlist = $classlist;
    $this->override = $override;
    if($echo)
    {
      echo '<div class = "container-fluid">';
      echo '<div class = "table-responsive">';
      if($this->override != "")
      {
        echo '<table id = "'.$this->table_id.'" class="'.$this->override.'">';
      }else
      {
        echo '<table id = "'.$this->table_id.'" class="table p-4 table-dark table-lg table-hover '.$this->classlist.'">';
      }
    }
  }

  function Start_Table()
  {
    if($this->override != "")
    {
      return '<div class = "container-fluid">
      <div class = "table-responsive">
      <table id = "'.$this->table_id.'" class="'.$this->override.'">';         
      }else
    {
      return '<div class = "container-fluid">
      <div class = "table-responsive">
      <table id = "'.$this->table_id.'" class="table p-4 table-dark table-lg table-hover '.$this->classlist.'">';         
      }
  }

  function Close_Table($echo = true)
  {
    if($echo)
    {
      echo '</table>';
      echo '</div>';
      echo '</div>';
    }else
    {
      return '</table>
      </div>
      </div>';      
    }
  }
}

class Table_Header
{
  private $classlist;
  function __construct($echo = true,$classlist="")
  {
    $this->classlist = $classlist;
    if($echo)
    {
      echo '<thead>
      <tr class = "'.$this->classlist.'">';
    }
  }

  function Start_Header()
  {
    return '<thead>
    <tr class = "'.$this->classlist.'">';
  }
  function Add_Header($text,$scope="col", $echo = true)
  {
    if($echo)
    {
      echo '<th scope="'.$scope.'">'.$text.'</th>';
    }else
    {
      return '<th scope="'.$scope.'">'.$text.'</th>';
    }
  }
  function Close_Header($echo = true)
  {
    if($echo)
    {
      echo '</tr>
        </thead>';
    }else
    {
      return '</tr>
        </thead>';      
    }
  }
}

class Table_Body
{
  private $body_id;
  function __construct($id = "",$echo = true)
  {
    $this->body_id = $id;
    if($echo)
    {
      echo '<tbody id = "'.$this->body_id.'">';
    }
  }

  function Start_Body()
  {
    return '<tbody id = "'.$this->body_id.'">';
  }

  function Close_Body($echo = true)
  {
    if($echo)
    {
      echo '</tbody>';
    }else
    {
      return '</tbody>';
    }
  }
}

class Table_Row
{
  private $num_of_cols;
  private $tr_data_context;
  private $td_data_context;
  private $three_dots_context;
  private $current_string;
  private $classlist;
  private $echo;
  /**
   * data_context is to store complex json data inside the tr html element in a data_context attribut
   * This will then be able to be used when using a context menu to pass information onto a php script
   * in order to update the database table the html table was derived from
   * @param int $num_of_cols how many columns the table is
   * @param array $values an array of values in order for column 1, 2 ,3 etc
   * @param array this is the array that will be converted to json for passing into other apps
   */
  function __construct(int $num_of_cols,array $values,array $tr_data_context = array(),array $three_dots_context = array(),$echo = true,$tooltip = "",$td_data_context = array(),$classlist = "")
  {
    $this->classlist = $classlist;
    $this->echo = $echo;
    $this->current_string = "";
    $this->tr_data_context = json_encode($tr_data_context);
    $this->td_data_context = $td_data_context;
    $this->three_dots_context = $three_dots_context;
    if(count($values) <> $num_of_cols)
    {
      throw new \Exception("data given doesn't match number of columns");
    }
    $this->num_of_cols = $num_of_cols;
    $this->Add_Row($values,$tooltip);
  }
  
  public function Return_String()
  {
    return $this->current_string;
  }

  private function Add_Row($values,$tooltip)
  {
    if($this->echo)
    {
      echo '<tr class = "'.$this->classlist.'" data-context = \''.$this->tr_data_context.'\' data-toggle="tooltip" title="'.$tooltip.'" data-trigger="click">';
    }else
    {
      $this->current_string = $this->current_string.'<tr class = "'.$this->classlist.'" data-toggle="tooltip" title="'.$tooltip.'" data-trigger="click" data-context = \''.$this->tr_data_context.'\'>';
    }
    $am_i_last_column_yet = 0;
    ForEach($values as $key => $data)
    {
      if(substr($key,0,7) == 'colspan')
      {
        $colspan = "colspan=\"".substr($key,7,strlen($key))."\"";
      }else
      {
        $colspan = "";
      }
      if($am_i_last_column_yet == $this->num_of_cols - 1 && !empty($this->three_dots_context))
      {
        $last_column = $am_i_last_column_yet;
        $this->Add_Data($data,$last_column - 1,true,$colspan);
      }else
      {
        $i_am_not_last_column_yet = $am_i_last_column_yet;
        $this->Add_Data($data,$i_am_not_last_column_yet - 1,false,$colspan);
      }
      $am_i_last_column_yet = $am_i_last_column_yet + 1;
    }
    if($this->echo)
    {
      echo '</tr>';
    }else
    {
      $this->current_string = $this->current_string.'</tr>';
    }
  }
  private function Add_Data($data,$column_number,$context_menu,$colspan)
  {
    if($context_menu)
    {
      if(!empty($this->td_data_context[$column_number]))
      {
        $td = '<td '.$colspan.' data-context = \''.$this->td_data_context[$column_number].'\' nowrap>';
      }else
      {
        $td = '<td '.$colspan.' nowrap>';
      }
      if($this->echo)
      {
        echo $td;
        echo $data;
      }else
      {
        $this->current_string = $this->current_string.$td.$data;
      }

      $three_dots = new \bootstrap\drop_down_menu('drop_down_menu',$this->echo);
      ForEach($this->three_dots_context as $text_to_display => $context_option)
      {
        $three_dots->Add_Action($text_to_display,$context_option,$this->echo);
      }
      $three_dots->Close_Context_Menu();
      if(!$this->echo)
      {
        $this->current_string = $this->current_string.$three_dots->Return_String();
      }
    }else
    {
      if(!empty($this->td_data_context[$column_number]))
      {
        $td = '<td '.$colspan.' data-context = \''.$this->td_data_context[$column_number].'\'>';
      }else
      {
        $td = '<td '.$colspan.'>';
      }
      if($this->echo)
      {
        echo $td;
        echo $data;      
      }else
      {
        $this->current_string = $this->current_string.$td.$data;
      }
    }
    if($this->echo)
    {
      echo '</td>';
    }else
    {
      $this->current_string = $this->current_string.'</td>';
    }
  }
}

class context_menu
{
  /**
   * @param string $tbody_id this is a unique id given to the bootstrap tbody element that this context menu will be active inside
   * @param string $context_menu this is the id for the context menu 
   * WARNING THIS CLASS DEPENDS ON A JAVASCRIPT FUNCTION CALLED Show_Element_If_True(element to hide/show,true = show[css_display=block] false = hidden[css_display=none])
   */
  function __construct($id = "context_menu",$tbody_id = "modalmt")
  {
    $jscontext_menu = new \bootstrap_js\Context_Menu($tbody_id,$id);    
    echo '<div>
        <ul  id="'.$id.'" style = "position: absolute;" class="dropdown-menu list-group" role="menu">';
  }
  /**
   * @param string $text_to_display plain text to display to user
   * @param array an array of complex data you want to store as json in the data_context attribute
   */
  function Add_Action(string $text_to_display,array $data_context,$Red_Color = false)
  {
    echo '<li tabindex="-1" data-context = \''.json_encode($data_context).'\'>';
    if($Red_Color)
    {
      echo '<a href = "#" class="list-group-item list-group-item-action list-group-item-danger">';
    }else
    {
      echo '<a href = "#" class="list-group-item list-group-item-action">';
    }
    if(isset($data_context['checked']))
    {
      if($data_context['checked'])
      {
        global $html_green_checkmark;
        echo $html_green_checkmark;
      }
    }
    echo $text_to_display.'</a></li>';
  }

  function Add_Divider()
  {
    echo '<li class="divider list-group-item"></li>';
    
  }

  function Close_Context_Menu()
  {
    echo '</ul></div>';
  }
}

class drop_down_menu
{
  private $string_to_return;
  /**
   * @param string $tbody_id this is a unique id given to the bootstrap tbody element that this context menu will be active inside
   * @param string $context_menu this is the id for the context menu 
   * WARNING THIS CLASS DEPENDS ON A JAVASCRIPT FUNCTION CALLED Show_Element_If_True(element to hide/show,true = show[css_display=block] false = hidden[css_display=none])
   */
  function __construct($id = "drop_down_menu",$echo = true)
  {
    global $white_html_three_dots_jpg;
    if($echo)
    {
      echo '<div class="dropdown show d-inline-block three_dots_menu" style = "float:right;">
          <a data-context="three_dots_context" id="'.$id.'" class="btn" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
      echo $white_html_three_dots_jpg;
      echo '</a>
          <div class="dropdown-menu" aria-labelledby="'.$id.'">';
    }else
    {
      $this->string_to_return = '<div class="dropdown show d-inline-block" style = "float:right;" >
          <a  data-context="three_dots_context" id="'.$id.'" class="btn" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          '.$white_html_three_dots_jpg.'
          </a>
          <div class="dropdown-menu" aria-labelledby="'.$id.'">';
    }
  }

  public function Return_String()
  {
    return $this->string_to_return;
  }
  /**
   * @param string $text_to_display plain text to display to user
   * @param array an array of complex data you want to store as json in the data_context attribute
   */
  function Add_Action(string $text_to_display,array $data_context,$echo = true)
  {
    $a_string = "";
    if(!empty($data_context))
    {
      if(isset($data_context['href']))
      {
        $a_string = ' href = "'.$data_context['href'].'"';
      }
      $a_string = $a_string.' class = "dropdown-item ';
      if(isset($data_context['class']))
      {
        
        ForEach($data_context['class'] as $class)
        {
          $a_string = $a_string.$class." ";
        }
      }
      $a_string = substr($a_string,0,strlen($a_string) - 1);      
      $a_string = $a_string.'"';
      if($echo)
      {
        echo '<a'.$a_string.'>';
      }else
      {
        $this->string_to_return = $this->string_to_return.'<a'.$a_string.'>';
      }
    }else
    {
      if($echo)
      {
        echo '<a href = "#" class="dropdown-item">';
      }else
      {
        $this->string_to_return = $this->string_to_return.'<a href = "#" class="dropdown-item">';
      }
    }
    if(isset($data_context['checked']))
    {
      if($data_context['checked'])
      {
        global $html_green_checkmark;
        if($echo)
        {
          echo $html_green_checkmark;
        }else
        {
          $this->string_to_return = $this->string_to_return.$html_green_checkmark;
        }
      }
    }
    if($echo)
    {
      echo $text_to_display.'</a>';
    }else
    {
      $this->string_to_return = $this->string_to_return.$text_to_display.'</a>';
      return $this->string_to_return;
    }
  }

  function Add_Divider($echo = true)
  {
    if($echo)
    {
      echo '<li class="divider list-group-item"></li>';
    }else
    {
      return '<li class="divider list-group-item"></li>';
    }
  }

  function Close_Context_Menu($echo = true)
  {
    if($echo)
    {
      echo '</div></div>';
    }else
    {
      return '</div></div>';
    }
  }
}
?>