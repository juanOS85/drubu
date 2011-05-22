<?php

/**
 * ruta form.
 *
 * @package    drubu
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class rutaForm extends BaserutaForm
{
  public function configure()
  {
    $this->setWidget('bus_id', new sfWidgetFormDoctrineChoice(array('model'  => 'Bus', 'method' => 'getNumBus')));
    unset($this['created_at']);
    unset($this['updated_at']);
  }
}
