<?php
namespace Addon\test2;

require_once \em_misc::getSpecifPath() . 'addons/test2/class/tools/libs/cruds/Monitoring_CRUD.class.php';

/**
 * monitoring
 *
 * Ecran de contrôle des tâches plannifiées
 *
 * @author Robson <robson@medialibs.com>
 *
 * @since      2020-03-16
 */
class menuMonitoring
{

/**
 * Constructeur
 */
 public function __construct() {}

/**
 * Gestion de la section
 *
 * Par exemple, si la méthode start retourne "Hello", la section affichera "Hello"
 *
 * @return string
 */
 public function start()
 {
     $crudObject = new Monitoring_CRUD();
     $output     = $crudObject->start();

     return $output;
  }
}
