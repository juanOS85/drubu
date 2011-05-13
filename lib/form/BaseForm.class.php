<?php

/**
 * Base project form.
 * 
 * @package    drubu
 * @subpackage form
 * @author     Your name here 
 * @version    SVN: $Id: BaseForm.class.php 20147 2009-07-13 11:46:57Z FabianLange $
 */
class BaseForm extends sfFormSymfony
{
  //sobreescritoura del método sfForm::addCSRFProtection para validar el error.
  public function addCSRFProtection($secret = null)
  {
    parent::addCSRFProtection($secret);

    if (isset($this->validatorSchema[self::$CSRFFieldName])) //addCSRFProtection doesn't always add a validator
    {
      $this->validatorSchema[self::$CSRFFieldName] = new myValidatorCSRFToken(array(
        'token' => $this->validatorSchema[self::$CSRFFieldName]->getOption('token')
      ));
    }
  }
}
