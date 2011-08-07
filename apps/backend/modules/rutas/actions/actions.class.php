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

    $ruta = new Ruta();
    $ruta->setNombreRuta($infoRuta->nombre);
    $ruta->setDuracionRuta($infoRuta->duracion);
    $ruta->setNumTotalParadas($infoRuta->totalParadas);
    $ruta->setConductor($infoRuta->conductor);
    $ruta->setDescripcion($infoRuta->descripcion);
    
    if ($infoRuta->bus != -1) {
      $ruta->setBusId($infoRuta->bus);
    }

    try {
      $ruta->save();
    } catch (Exception $e) {
      return $this->renderText('{"success": false, "error": "Error al guardar en la base de datos ' . $e . '"}');
    }

    return $this->renderText('{"success": true, "rutaId": ' . $ruta->getId() . '}');
  }

  /**
   * Executes guardarParadas action
   *
   * @param sfRequest $request A request object
   */
  public function executeGuardarParadas(sfWebRequest $request) {
    $infoParadas = json_decode($request->getPostParameter('paradas'));
    $ruta = json_decode($request->getPostParameter('ruta'));

    foreach ($infoParadas as $infoParada) {
      $parada = new Parada();
      $parada->setLongitud($infoParada->longitud);
      $parada->setLatitud($infoParada->latitud);
      $parada->setNumero($infoParada->numero);
      $parada->setDireccion($infoParada->direccion);
      $parada->setbarrio($infoParada->barrio);
      $parada->setHora($infoParada->hora);
      $parada->setRutaId($ruta);

      try {
        $parada->save();
      } catch (Exception $e) {
        return $this->renderText('{"success": false, "error": "Error al guardar en la base de datos ' . $e . '"}');
      }
    }

    return $this->renderText('{"success": true}');
  }

  /**
   * Executes editar action
   *
   * @param sfRequest $request A request object
   */
  public function executeEditar() {
    $q = Doctrine_Query::create()
      ->select('r.id, r.nombre_ruta')
      ->from('Ruta r');
    $rutas = $q->execute();

    $rutasJSON = json_encode($rutas->toArray());

    return $this->renderText($rutasJSON);
  }
}
