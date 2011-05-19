<?php

/**
 * rutas actions.
 *
 * @package    drubu
 * @subpackage rutas
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class rutasActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    // $this->forward('default', 'module');
    // $this->paradaForm = new paradaForm();
    $q = Doctrine_Query::create()
      ->select('b.id, b.placa')
      ->from('Bus b');
    $this->buses = $q->execute();

    $this->paradaForm = new sfForm();
    $this->paradaForm->setWidgets(array(
      'bus' => new sfWidgetFormDoctrineChoice(array(
        'model' => 'Bus',
        'method' => 'getNumBus',
        'add_empty' => false,
      )),
    ));
  }
}
