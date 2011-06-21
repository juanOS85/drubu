<?php

require_once dirname(__FILE__).'/../lib/busesGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/busesGeneratorHelper.class.php';

/**
 * buses actions.
 *
 * @package    drubu
 * @subpackage buses
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class busesActions extends autoBusesActions
{
   /**
    * Executes getBuses action
    *
    * @param sfRequest $request A request object
    */
  public function executeActivos() {
    $q = Doctrine_Query::create()
      ->select('b.id, b.num_bus')
      ->from('Bus b')
      ->where('b.esta_activo = ?', true);
    $buses = $q->execute();

    $busesJSON = json_encode($buses->toArray());

    return $this->renderText($busesJSON);
  }
}
