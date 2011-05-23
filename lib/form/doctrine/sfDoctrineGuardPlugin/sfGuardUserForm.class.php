<?php

/**
 * sfGuardUser form.
 *
 * @package    drubu
 * @subpackage form
 * @author     Paola Andrea Ospina Gonzalez
 * @version    SVN: $Id: sfDoctrinePluginFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardUserForm extends PluginsfGuardUserForm
{
  public function configure()
  {
    unset($this['created_at']);
    unset($this['updated_at']);
  }
}
