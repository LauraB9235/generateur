<?php
namespace Addon\test2;

/**
 * API Rest
 *
 * @author  [laura]  <[name]@Medialibs.com>
 *
 * @since 2021-07-06
 */
require_once \em_misc::getSpecifPath() . 'addons/test1/class/tools/API.class.php';
class Login extends API
{
    /**
     * Execution de la requete demandée
     *
     * @return     null
     */
     public function doRequest()
     {
         //  Traitement ...
         if ($ok) {
             $this->respond(200, null, array("key" => "value"));
         } else {
             $this->respond(401, null, array("key" => "value"));
         }
     }
}
