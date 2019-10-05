<?php
namespace bootstrap_js;
/**
 * @param string $tbody_id this is a unique id given to the bootstrap tbody element that this context menu will be active inside
 * @param string $context_menu this is the id for the context menu 
 * WARNING THIS CLASS DEPENDS ON A JAVASCRIPT FUNCTION CALLED Show_Element_If_True(element to hide/show,true = show[css_display=block] false = hidden[css_display=none])
 */
class Context_Menu
{
  function __construct(string $tbody_id,string $context_menu)
  {
    global $html_checkmark;
    echo "<script>
    $('#".$tbody_id." tr').on('contextmenu', function (e) {
            tbl_name = '".$tbody_id."';
            console.log('user right clicked a table row');
            cm = document.querySelector('#".$context_menu."');
            e.preventDefault();
            var children = cm.children;
            var custom = document.createEvent('HTMLEvents');
            custom.initEvent('customEvent', true, true);
            for (var i = 0; i < children.length; i++) {
              var tableChild = children[i];
              if(tableChild.classList.contains('divider'))
              {
                continue;
              }
              var html = tableChild.innerHTML;
              html = html.replace('".$html_checkmark."','');
              tableChild.innerHTML = html;
              json = JSON.parse(tableChild.dataset.context);
              json.added_context = JSON.parse(this.dataset.context);
              tableChild.dataset.context = JSON.stringify(json);
              tableChild.dataset.unique_id = json.unique_id;
              custom.data = JSON.stringify(json);
              dispatchEvent(custom);
            }
            Show_Element_If_True(cm,true);
            console.log(tbl_name);
            if(String(tbl_name).startsWith('modal'))
            {
              cm.style.top = e.originalEvent.layerY+'px';
              cm.style.left = e.originalEvent.layerX+'px';
            }else
            {
              cm.style.top = e.originalEvent.layerY+'px';
              cm.style.left = e.originalEvent.layerX+'px';
            }
        });
        window.addEventListener('click', () => {
            cm = document.querySelector('#".$context_menu."');  
            Show_Element_If_True(cm,false);
        });
    </script>";
  }
}

?>