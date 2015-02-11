<?php
/**
 * Date: 23-Jan-15
 * Time: 4:23 PM
 */

 
class TemplateEngineImpl implements \core\testing\TemplateEngineInterface
{
  public function event1() {
?>
    <code>Running event1 through the <i>\core\testing\TemplateEngineInterface</i> provided by <i>Module_Routing_Engine</i>.</code> <br />
<?php
  }
}

?>