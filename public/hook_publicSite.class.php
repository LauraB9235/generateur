<?php
namespace Addon\test2;
require_once __DIR__ . '/../../tools/Controller.php';

/**
 * Gestion des logs
 *
 * @author  [laura]  <[name]@Medialibs.com>
 *
 * @since2021-07-06
 */
 class hook_PublicSite extends \Emajine_Hooks
 {

     const API_ROOT = 'api';
     /**
      * Intervention lors de l'initialisation du site public
      *
      * @return null
      */
      public function onInit()
      {
           $url = explode('/', \em_misc::ru());
           if ($url[1] != self::API_ROOT) {
               return;
           }
           $apiController = new Controller();
           $apiController->start();
      }
 }
