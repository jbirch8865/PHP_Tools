<?php
namespace bootstrap;

class Alert
{
    public function Display_Warninig($strong_text_to_display, $text_to_display)
    {
      echo '<div class="alert alert-danger alert-dismissible">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>'.$strong_text_to_display.'</strong>'.$text_to_display.'
    </div>';
    }
}


?>