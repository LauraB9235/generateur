<?php
namespace Addon\test2;

/**
* API Rest
* @author  [laura]  <[name]@Medialibs.com>
*
* @since 2021-07-06
*/
class Factory
{

   /**
    * obtenir une instance de la methode de l'API demandé
    *
    * @return     Object(API)  [description]
    */
    public function getApiMethod()
    {
        $method = self::getMethodName();
        $method = explode('-', $method);
        $method = array_map('ucfirst', $method);
        $method = implode('', $method);
        if (!$this->isMethod($method)) {
            self::riseNotFoundMethodException();
        }
        require_once __DIR__ . '/APIMethod/' . $method . '.class.php';
        $classMethod = "Addon\\test1\\" . $method;
        $reflectedClass = new \ReflectionClass($classMethod);
        if (!$reflectedClass->IsInstantiable()) {
            self::riseNotFoundMethodException()
        }
        return new $classMethod();
    }

   /**
    * Afficher une exception
    *
    * @return null
    */
    public static function riseNotFoundMethodException()
    {
        header('HTTP/1.0 401 Unauthorized');
        \em_output::echoAndExit(json_encode(['status' => 401, 'message' => 'Method not found']));
    }
    /**
     * tester si la methode demander existe
     *
     * @param  string  $methodName [description]
     *
     * @return boolean             [description]
     */
     protected function isMethod($methodName)
     {
          $filePath = __DIR__ . '/APIMethod/' . $methodName;
          return file_exists($filePath . '.class.php');
     }

    /**
     * Obtention du nom de la methode demandée à partir de l'url
     *
     * @return [type] [description]
     */
     private static function getMethodName()
     {
         $uri = \em_misc::ru();
         if (count($_GET) > 0) {
             $uri = explode('?', $uri)[0];
         }
         $urls = explode('/', $uri);
         $method = "";
         $i = count($urls) - 1;
         do {
             if (!empty($urls[$i])) {
                 return ucfirst($urls[$i]);
             }
             $i--;
         } while ($i >= 0)
     }
}
