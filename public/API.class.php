<?php
namespace Addon\test2;
require_once \em_misc::getSpecifPath() . 'addons/test2/class/tools/HTTPResponse.class.php';
require_once \em_misc::getSpecifPath() . 'addons/test2/class/tools/ELog.php';

/**
 * Class API
 *
 * @author  [laura]  <[name]@Medialibs.com>
 *
 * @since2021-07-06
 */
 class API
 {
     protected $response;
     protected $method = 'POST';
     const HTTP_STATUS_401 = 401;
     const HTTP_STATUS_403 = 403;

  /**
   * methode repondre
   *
   * @param  int $statut              [description]
   * @param  int $code                [description]
   * @param  array $data              [description]
   * @param  string $supplementMessage [description]
   *
   * @return null                    [description]
   */
   public function respond($statut, $code, array $data = null, $supplementMessage = null)
   {
       if (!is_null($data)) {
           $this->response = $data;
       } else {
           $this->response = array("response" => (new HTTPResponse())->response($statut, $code, $supplementMessage));
       }
       header('Content-Type: application/json');
       ELog::create('responseLogs', \em_misc::getSpecifPath() . 'logs/api/', 'response_' . date('Y-m-d'), false, true);
       ELog::get('responseLogs')->file('######################');
       ELog::get('responseLogs')->file('## ' . date('d/m/Y H:i:s') . ' ##');
       ELog::get('responseLogs')->file(json_encode($this->response));

       \em_output::echoAndExit(json_encode($this->response));
  }

  /**
   * Obtention de l'id d'un utilisateur avec l'access token
   *
   * @return string
   */
   protected function idUser($accessToken)
   {
       if (!is_null($accessToken)) {
           $query = 'SELECT '
               . 'id_acteur '
               . 'FROM acteur '
               . 'WHERE MD5(CONCAT(login, passwd, date_crea, "' . $_SESSION[self::getClientIP()]['TOKEN_DATE'] . '")) = "' . $accessToken . '"';
           return \em_db::one($query);
       }

  }
   /**
    * tester si l'access token correspond à un user
    *
    * @return boolean [description]
    */
    protected function isUserAccessToken()
    {
         if ($this->method == 'POST') {
            $accessToken = $_POST['access_token'];
         } else {
            $accessToken = $_GET['access_token'];
         }
         return !is_null($this->idUser($accessToken));
    }

  /**
   * Tester si il y a un champ non existant
   *
   * @param      array  $datas      The datas
   * @param      array  $allFields  All fields
   *
   *  @return null
   */
   public function checkIfexist($datas, $allFields)
   {
       foreach ($datas as $key => $value) {
           if (!in_array($key, $allFields)) {
               $errorCode = '1000' . ($key + 1);
               $this->respond(
                         200,
                        null,
                   [
                       'errorCode' => $errorCode,
                       'fieldName' => $key,
                       'message' => 'Ce champ n\'est pas autorisé',
                   ]
               );
           }
       }
  }

  /**
   * Récuperer l'adresse IP du client
   *
   * @return [type] [description]
   */
   public static function getClientIP()
   {
       $ipaddress = 'UNKNOWN';
       $keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
       foreach ($keys as $k) {
           if (isset($_SERVER[$k]) && !empty($_SERVER[$k]) && filter_var($_SERVER[$k], FILTER_VALIDATE_IP)) {
               $ipaddress = $_SERVER[$k];
               break;
           }
       }
       return $ipaddress;
   }
 }
