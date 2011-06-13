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
  public function executeIndex(sfWebRequest $request) {
  }

  /**
   * Executes guardar action
   *
   * @param sfRequest $request A request object
   */
  public function executeGuardar(sfWebRequest $request) {
    $infoRuta = json_decode($request->getPostParameter('ruta'));
    
    print_r($infoRuta);

    $ruta = new Ruta();
    $ruta->setNombreRuta($infoRuta[0]);

    try {
      $ruta->save();
    } catch (Exception $e) {
      return $this->renderText('{"success": false, "error": "Error al guardar en la base de datos"}');
    }

    return $this->renderText('{"success": true}');
  }
}
