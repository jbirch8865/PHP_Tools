<?php
namespace bootstrap;

class Alert
{
    public function Display_Warninig($strong_text_to_display, $text_to_display)
    {
      echo '<div class="alert alert-danger alert-dismissible" style = "margin-bottom:0">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>'.$strong_text_to_display.'</strong>'.$text_to_display.'
    </div>';
    }
}

class navbar
{
  function __construct()
  {
    echo '  <!-- A vertical navbar -->
      <nav class="navbar navbar-dark bg-dark">
  
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
      echo '<table id = "'.$id.'" class="table table-striped table-bordered table-sm table-hover" style = "margin-top:25px;">';
  }

  function Close_Table()
  {
    echo '</table>';
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
  /**
   * data_context is to store complex json data inside the tr html element in a data_context attribut
   * This will then be able to be used when using a context menu to pass information onto a php script
   * in order to update the database table the html table was derived from
   * @param int $num_of_cols how many columns the table is
   * @param array $values an array of values in order for column 1, 2 ,3 etc
   * @param array this is the array that will be converted to json for passing into other apps
   */
  function __construct(int $num_of_cols,array $values,array $data_context = array())
  {
    $this->data_context = json_encode($data_context);
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
      $this->Add_Data($values[$i]);
      $i = $i + 1;
    }
    echo '</tr>';
  }

  private function Add_Data($data)
  {
    echo '<td>';
    echo $data;
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
  function __construct($id = "context_menu",$tbody_id = "mt")
  {
    $jscontext_menu = new \bootstrap_js\Context_Menu($tbody_id,$id);    
    echo '<div>
        <ul  id="'.$id.'" class="dropdown-menu list-group" role="menu">';
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
      if($data_context['checked']){echo '<img src="images/checkmark.jpg" style="width:20px;margin-right:25px;">';}
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
?>