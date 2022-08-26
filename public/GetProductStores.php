<?php
namespace Addon\test2;

require_once \em_misc::getSpecifPath() . 'addons/test2/class/tools/MapManager.class.php';
/**
 * Récupération de la liste des points de ventes
 *
 * @author Robson <Robson@medialibs.com>
 *
 * @since 2019-03-12
 */
 class GetProductStores
 {

     private $entryTypes;
     private $searchedCoord;
     private $area;
     /**
      * Retourne le type de l'action
      *
      * @return string
      */
      public function type()
      {
          return 'actionName';
      }

     /**
      * Retourne si l'action est active
      *
      * @return boolean
      */
      public function isEnabled()
      {
          return true;
      }

     /**
      * Fonction automatiquement appelée à l'appel de l'action
      *
      * @param
      *
      * @return string
      */
      public function start()
      {
          if (empty($_POST['action'])) {
              \em_output::echoAndExit('');
          }
          switch ($_POST['action']) {
              case 'getEntryList':
                  $data = array();
                  $area = urldecode($_POST['area']);
                  $address = urldecode($_POST['city']);
                  $_SESSION['entries']['area'] = $area;
                  $_SESSION['entries']['city'] = $address;
                  $searchedCoord = null;
                  $entries = MapManager::search($area, $searchedCoord, $address);
                  // Conversion du rayon en mètre
                  \em_output::echoAndExit(json_encode(array('ids' => array_values($entries),  'area' => $area * 1000, 'coord' => $searchedCoord)));
                  break;
              case 'reset':
                  unset($_SESSION['entries']);
                  unset($_SESSION['salePoints']);
                  unset($_SESSION['positions']);
                  \em_output::echoAndExit(json_encode(array('state' => 'RESETED')));
                  break;
              default:
                  \em_output::echoAndExit('');
                   break;
          }
      }
}
