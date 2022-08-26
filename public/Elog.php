<?php
namespace Addon\test2;

/**
 * Gestion des logs
 *
 * @author  [laura]  <[name]@Medialibs.com>
 *
 * @since2021-07-06
 */
 class ELog
 {
     public static $userVd = array();

     private $rep; // répertoire du fichier de logs
     private $file; // fichier de logs
     private $mustDisplay; // affiche les logs sur la page
     private $sum; // résumé du processus
     public $is_active; // le processus de log est actif
     private $extension;

     private static $active = true; // Les logs sont actifs
     private static $elogs = array(); // tableau de toutes les entités ELog
     private static $currentELog; // dernier ELog utilisé

     /**
      *  Permet de créer une entité
      *
      * @param $id Identifiant de l'entité, utile pour la récupération
      * @param String $log_path Répertoir des logs
      * @param String $file_name FileName
      * @param boolean $display Si true, affiche tous les logs sur la page (y compris les file)
      * @param boolean $active Si true, les logs seront actifs
      */
      public static function create($id, $log_path, $file_name, $display, $active, $extension = '.txt')
      {
          $elog = new ELog();
          self::makeDir($log_path);
          $elog->extension = $extension;
          $elog->rep = $log_path;
          $elog->file = $file_name . $elog->extension;
          $elog->mustDisplay = $display;
          $elog->is_active = $active;

          self::$currentELog = $elog;
          self::$elogs[$id] = $elog;
      }

      public static function makeDir($log_path)
      {
          if (!is_dir($log_path)) {
              $dirs = explode('/', substr($log_path, 1));
              $path = '';
              foreach ($dirs as $dir) {
                  $path .= '/' . $dir;
                  if (!is_dir($path)) {
                      mkdir($path);
                  }
              }
          }
      }

      /**
       * Permet de récupérer l'entité ELog définis par $id lors du ELog::create(...)
       * Si $id n'est pas définis, on renvoie le dernier ELog utilisé.
       *
       * @param $id
       */
       public static function get($id = null)
       {
           if ($id && self::$elogs[$id]) {
               self::$currentELog = self::$elogs[$id];
               return self::$elogs[$id];
           }
           return self::$currentELog;
       }

       /**
        * Affiche $message dans le fichier $file_name.
        * Si $file_name n'est pas renseigné, le message est ajouté à $this->file
        *
        * @param String $message Message de logs
        * @param int $niveau Niveau d'indentation
        * @param String $file_name Fichier où est ajouté $message
        */
        public function file($message, $niveau = 0, $file_name = null)
        {
            if ($this->is_active && self::$active) {
                if ($file_name) {
                    $file_name = (strpos($filename, ) !== false) ? $file_name : $file_name . $this->extension;
                }
                $currentFile = ($file_name) ? $this->rep . '/' . $file_name : $this->rep . '/' . $this->file;

                if (!file_exists($currentFile)) {
                    $file = fopen($currentFile, "w+");
                    fclose($file);
                }

                $this->display($this->getSpace($niveau) . $message);
                error_log($this->getSpace($niveau) . $message . "\r\n", 3, $currentFile);
            }
        }

      /**
       * Affiche le message sur a page
       *
       * @param String $message Message à afficher
       * @param int $niveau Niveau d'indentation
       */
       public function display($message, $niveau = 0)
       {
           if ($this->is_active && self::$active && $this->mustDisplay) {
               echo $this->getSpace($niveau) . $message . '<br/>';
           }
       }

       /**
        * Ajoute une ligne au résumé
        *
        * @param String $message Message ajouté
        * @param int $niveau Niveau d'indentation
        */
        public function sum($message, $niveau = 0)
        {
            if ($this->is_active && self::$active) {
                $this->sum .= $this->getSpace($niveau) . $message . "\r\n";
            }
        }

        /**
         * Génère l'indentation
         * @param int $niveau Niveau d'indentation
         * @return Elog
         */
         public function getSpace($niveau)
         {
             $str = "";
             for ($i = 0; $i < $niveau; $i++) {
                  $str .= "\t";
             }
             return $str;
         }

         /**
          * Génère le résumé
          */
          public function displaySum()
          {
              if ($this->is_active && self::$active) {
                  $b = $this->mustDisplay;
                  $this->mustDisplay = false;
                  $this->file("--------------------------------------------------------\r\n--------				RESUME					--------\r\n--------------------------------------------------------");
                  $this->file($this->sum);
                  $this->file("--------------------------------------------------------\r\n");
                  $this->file("\r\n");
                  $this->file("\r\n");
                  $this->file("\r\n");
                  $this->mustDisplay = $b;
                  if ($this->mustDisplay) {
                      $this->display("--------------------------------------------------------");
                      $this->display("--------				RESUME					--------");
                      $this->display("--------------------------------------------------------");
                      $this->display(str_replace('\t', "&nbsp;&nbsp;&nbsp;&nbsp;", str_replace("\r\n", "<br/>", $this->sum)));
                      $this->display("--------------------------------------------------------");
                      $this->display("");
                      $this->display("");
                  }
              }
          }

         /**
          * Ajoute le résultat d'un var_dump dans $this->file
          * @param * $var Variable à var_dumper
          */
          public function fileVD($var)
          {
              if ($this->is_active && self::$active) {
                  ob_start();
                  var_dump($var);
                  $strVar = ob_get_contents();
                  ob_end_clean();
                  $this->file($strVar);
              }
          }

          /**
           * Supprime le fichier de logs
           * @param String $file nom du fichier dans le répertoire $this->rep
           */
           public function reset()
           {
               if ($this->is_active && self::$active) {
                   $currentFile = ($file_name) ? $this->rep . '/' . $file_name . $this->extension : $this->rep . '/' . $this->file;
                   if (file_exists($currentFile)) {
                       unlink($currentFile);
                   }
               }
          }

          /**
           * Active le ELog
           *
           * @param boolean $state Etat d'activation
           */
           public function activate($state = true)
           {
                $this->is_active = $state;
           }

          /**
           * Active tous les processus de Logs
           *
           * @param boolean $state Etat d'activation
           */
           public static function enable($state = true)
           {
               self::$active = $state;
           }

          /**
           * Equivalent à ELog::get()->display
           */
           public static function _display($string = null, $niveau = null)
           {
               ELog::get()->display($string, $niveau);
           }

          /**
           * Equivalent à ELog::get()->file
           */
           public static function _file($string = null, $niveau = null)
           {
               ELog::get()->file($string, $niveau)
           }

           /**
            * Equivalent à ELog::get()->fileVD
            */
            public static function _fileVD($string = null, $niveau = null)
            {
                ELog::get()->fileVD($string, $niveau);
            }

           /**
            * Equivalent à ELog::get()->reset
            */
            public static function _reset()
            {
                ELog::get()->reset();
            }

            public static function vd($data, $continue = 1)
            {
                require_once em_misc::getClassPath() . '/core/Emajine_Debug.class.php';
                if (in_array(em_misc::getUserId(), self::$userVd)) {
                    Emajine_Debug::forceVd();
                    vd($data, $continue);
                }
            }

            public static function addUserIdForVD($vd)
            {
                self::$userVd[] = $vd;
            }

            /**
             * Supprime les fichiers de logs plus vieux que $jours
             * avec la condition dans le nom
             *
             */
             public static function _deleteOldLogs($jours, $condition)
             {
                 ELog::get()->deleteOldLogs($jours, $condition);
             }

             public function deleteOldLogs($jours, $condition)
             {
                 if ($condition) {
                     $files = glob($this->rep . '/' . $condition);
                 } else {
                     $files = glob($this->rep . '/*');
                 }

                 foreach ($files as $file) {
                     if (is_file($file) && time() - filemtime($file) > 86400 * $jours) {
                         unlink($file);
                     }
                 }
             }

            /**
             * Supprime les fichiers de logs plus vieux que $jours
             * avec la condition dans le nom
             *
             */
             public static function _deleteTooMuchLogs($nb, $condition = '/*', $includeDir = false)
             {
                 ELog::get()->deleteTooMuchLogs($nb, $condition);
             }

             public function deleteTooMuchLogs($nb, $condition = '/*', $includeDir = false)
             {
                 if ($condition) {
                     $files = glob($this->rep . "/" . $condition);
                 } else {
                     $files = glob($this->rep . "/*");
                 }

                 usort($files, array($this, 'sortByTime'));

                 for ($i = $nb; $i < count($files); $i++) {
                      if ($includeDir || is_file($files[$i])) {
                          unlink($files[$i]);
                      }
                 }
             }

             public function sortByTime($a, $b)
             {
                 return filemtime($a) - filemtime($b);
             }
 }
