<?php
namespace Addon\test2;
require_once __DIR__ . '/Factory.php';

/**
 * Controleur de l'API
 *
 * @author  [laura]  <[name]@Medialibs.com>
 *
 * @since2021-07-06
 */
class Controller
{
     protected $factory;

     /**
      * constructeur
      */
      public function __construct()
      {
          $this->factory = new Factory();
      }

      /**
       * Methode start du controleur de l'API rest
       *
       * @return null [description]
       */
       public function start()
       {
           $APIRest = $this->factory->getAPIMethod();
           if ($APIRest) {
               $APIRest->doRequest();
           }
       }
}
