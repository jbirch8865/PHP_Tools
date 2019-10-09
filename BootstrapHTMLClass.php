<?php
namespace bootstrap;

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
        ForEach($this->alerts as $key => $alert)
        {
            echo $this->alerts[$key]->Display_Alert();
            $should_i_hault = $this->alerts[$key];
            $this->Remove_Alert($key);
            $should_i_hault->Terminate_Execution_On_Hault();
        }
    }
}

class navbar
{
  function __construct()
  {
    echo '  <!-- A vertical navbar -->
      <nav class="navbar navbar-dark bg-dark" style = "width:200px;margin:auto;float:left;display:inline-block;">
  
      <!-- Links -->
      <ul class="navbar-nav">';
  }
  function Add_Link($href,$text_to_display)
  {
    echo '<li class="nav-item">
      <a class="nav-link" href="'.$href.'">'.$text_to_display.'</a>
      </li>';
  }
  function Close_Navbar()
  {
    echo '</ul>
      </nav>';
  }
}

class table 
{
  function __construct($id = "")
  {
      echo '<div class = "container-fluid">';
      echo '<div class = "table-responsive">';
      echo '<table id = "'.$id.'" class="table p-4 table-dark table-lg table-hover">';
  }

  function Close_Table()
  {
    echo '</table>';
    echo '</div>';
    echo '</div>';
  }
}

class Table_Header
{
  function __construct()
  {
    echo '<thead>
    <tr>';
  }
  function Add_Header($text,$scope="col")
  {
    echo '<th scope="'.$scope.'">'.$text.'</th>';
  }
  function Close_Header()
  {
    echo '</tr>
      </thead>';
  }
}

class Table_Body
{
  function __construct($id = "")
  {
    echo '<tbody id = "'.$id.'">';
  }

  function Close_Body()
  {
    echo '</tbody>';
  }
}

class Table_Row
{
  private $num_of_cols;
  private $data_context;
  private $three_dots_context;
  /**
   * data_context is to store complex json data inside the tr html element in a data_context attribut
   * This will then be able to be used when using a context menu to pass information onto a php script
   * in order to update the database table the html table was derived from
   * @param int $num_of_cols how many columns the table is
   * @param array $values an array of values in order for column 1, 2 ,3 etc
   * @param array this is the array that will be converted to json for passing into other apps
   */
  function __construct(int $num_of_cols,array $values,array $data_context = array(),array $three_dots_context = array())
  {
    $this->data_context = json_encode($data_context);
    $this->three_dots_context = $three_dots_context;
    if(count($values) <> $num_of_cols)
    {
      throw new \Exception("data given doesn't match number of columns");
    }
    $this->num_of_cols = $num_of_cols;
    $this->Add_Row($values);
  }
  
  private function Add_Row($values)
  {
    echo '<tr data-context = \''.$this->data_context.'\'>';
    $i = 0;
    while($i < $this->num_of_cols)
    {
      if($i == $this->num_of_cols - 1 && !empty($this->three_dots_context))
      {
        $this->Add_Data($values[$i],true);
      }else
      {
        $this->Add_Data($values[$i],false);
      }
      $i = $i + 1;
    }
    echo '</tr>';
  }
  private function Add_Data($data,$context_menu)
  {
    if($context_menu)
    {
      echo '<td nowrap>';
      echo $data;
        $three_dots = new \bootstrap\drop_down_menu();
      ForEach($this->three_dots_context as $text_to_display => $context_option)
      {
        $three_dots->Add_Action($text_to_display,$context_option);
      }
      $three_dots->Close_Context_Menu();
    }else
    {
      echo '<td>';
      echo $data;      
    }
    echo '</td>';
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
  /**
   * @param string $tbody_id this is a unique id given to the bootstrap tbody element that this context menu will be active inside
   * @param string $context_menu this is the id for the context menu 
   * WARNING THIS CLASS DEPENDS ON A JAVASCRIPT FUNCTION CALLED Show_Element_If_True(element to hide/show,true = show[css_display=block] false = hidden[css_display=none])
   */
  function __construct($id = "drop_down_menu")
  {
    echo '<div class="dropdown show d-inline-block">
        <a  id="'.$id.'" class="btn" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        ';
    global $white_html_three_dots_jpg;
    echo $white_html_three_dots_jpg;
    echo '</a>
        <div class="dropdown-menu" aria-labelledby="'.$id.'">';
  }       
  /**
   * @param string $text_to_display plain text to display to user
   * @param array an array of complex data you want to store as json in the data_context attribute
   */
  function Add_Action(string $text_to_display,array $data_context)
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
      echo '<a'.$a_string.'>';
    }else
    {
      echo '<a href = "#" class="dropdown-item">';
    }
    if(isset($data_context['checked']))
    {
      if($data_context['checked'])
      {
        global $html_green_checkmark;
        echo $html_green_checkmark;
      }
    }
    echo $text_to_display.'</a>';
  }

  function Add_Divider()
  {
    echo '<li class="divider list-group-item"></li>';
    
  }

  function Close_Context_Menu()
  {
    echo '</div></div>';
  }
}
?>