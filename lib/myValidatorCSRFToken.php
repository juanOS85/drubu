<?php

/**
* Configuración de los errores globales y validadores
*tomado de http://discover-symfony.blogspot.com/2011/03/how-to-change-csrf-attack-detected-in.html
* @package    drubu
* @author     
* @version    SVN: $Id: myValidatorCSRFToken.php 20147 2011-05-02 
*/


class myValidatorCSRFToken extends sfValidatorCSRFToken
{
  
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    $this->addMessage('csrf_attack', 'Su sesi&oacute;n ha expirado. Por favor regrese a la p&aacute;gina de inicio y trate de nuevo.');
  }


  protected function doClean($value)
  {
    try {
      return parent::doClean($value);
    } catch (sfValidatorError $e) {
      throw new sfValidatorErrorSchema($this, array($e));
    }
  }
}

?>