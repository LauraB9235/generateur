<?php
    namespace AddOn\test2;

    /**
     * InterfaceTools
     *
     * Gestion de l'interface avec emajine (Installation, Désinstallation, Activation, Désactivation)
     *
     * @author Célestin
     *
     * @date 2016-09-20
     */

    require_once __DIR__ . '/InterfaceInstallation.class.php';

    class InterfaceTools
    {
        const ADDON_TRADE_NAME = "test" ;

        protected $addonName;

        /**
         * Constructeur
         */
         public function __construct()
         {
             $addonData = explode('\\' , get_classes($this));
             $this->addonName = $addonData[1];
         }

         /**
          * Installe l'add-on
          *
          * @return string
          */
          public function install()
          {
              $am = new \Addons_Manager();
              if($am->isStructureOk($this->addonName)) {
                  // on récupère l'objet Addons_Entity. Si l'addon n'est pas installé, la fonction le fera :
                  $addon = $am->getAddon($this->addonName, true);

                  $this->cleanCache();

                  return 'Votre add-on "' . self::ADDON_TRADE_NAME . '" a été correctement installé';
              } else{
                  return 'Une erreur s\'est produite';
              }
          }

          /**
           * Désinstalle de l'add-on
           *
           * @return string
           */
           public function uninstall()           {
               $am = new \Addons_Manager();
               // on vérifie la structure de l'add-on parce que, quoi qu'il arrive,
               // si l'add-on n'est pas installé, il le sera avant d'être désinstallé
               if($am->isStructureOk($this->addonName)) {
                  // on récupére l'objet Addons_Entity. Si l'add-on n'est pas installé, la fonction le fera :
                  $addon = $am->getAddon($this->addonName, true);
                  $am->uninstall($addon);

                  $this->cleancache();

                  return 'Votre add-on "' . self::ADDON_TRADE_NAME . '" a été correctement désinstallé';
               }
               else{
                  return 'Une erreur s\'est produite.';
               }
           }

           /**
            * Active l'add-on
            *
            * @return string
            */
             public function enable()
             {
                 $am = new \Addons_Manager();
                 // on vérifie la structure de l'add-on parce que, quoi qu'il arrive,
                 // si l'add-on n'est pas installé, il le sera avant d'être activé
                 if ($am->isStructureOk($this->addonName)) {
                     // on récupére l'objet Addons-Entity. Si l'add-on n'est pas installé, la fonction le fera :
                        $addon = $am->getAddon($this->addonName, true);
                        $am->enable($addon,true);

                        $this->cleanCache();

                        //installation des fichiers assets
                        $assetsInstallation = new InterfaceInstallation();
                        $assetsInstallation->start();


                     return 'Votre add-on "' . self::ADDON_TRADE_NAME . '" a été activé.';
                 }
                 else{
                     return 'Une erreur s\'est produite';
                 }
             }

             /**
              * Désactive l'add-on
              *
              * @return string
              */
              public function disable()
              {
                  $am = new \Addons_Manager();
                  //on vérifie la structure de l'add-on parce que, quoi qu'il arrive,
                  //si l'addon n'est pas installé, il le sera avant d'être désactivé
                  if ($am ->isStructureOk($this->addonName)) {
                      // on récupère l'objet Addons-Entity. Si l'add-on n'est pas installé, la fonction le fera :
                         $addon = $am->getAddon($this->addonName, true);
                         $am->disable($addon);

                        $this->cleanCache();

                     return 'Votre add-on "' . self::ADDON_TRADE_NAME . '" a été désactivé.';
                  }else{
                     return 'Une erreur s\'est produite';
                  }
              }


             /**
              * Réinitialise le cache des développements spécifiques
              *
              * @return null
              */
              protected function cleanCache()
              {
                  $cacheManager = new \Emajine_Cache();
                  $cacheManager->clean('devspe');

              }

             /**
              * Execution du cron passé en paramétre
              *
              * @return null`
              */
              public function execCron($cronFile)
              {
                  require_once \em_misc::getClassPath() . '/core/Emajine_Cron/Emajine_Cron_Tools.class.php';
                  $_SESSION['cronslogscreen'] = true;
                  list($addonName, $cronName) = explode('_addons_', $cronFile);

                  if($cronName) {
                     $addon = \Addons_Manager::getInstance()->getAddon($addonName, true);

                     if ($addon) {
                         $cron = $addon->getOneCron($cronName);
                         $className = get_class($cron);
                         $cron->start();
                         unset($_SESSION['cronlogscreen']);
                         exit;
                     }
                  }

                   return;
              }

              /**
               * Récupère la liste de tous les crons de l'add-on
               *
               * @return array
               */
               public function getAllCrons()
               {
                   //on vérifie la structure de l'add-on parce que, quoi qu'il arrive
                   //si l'add-on n'est pas installé, il le sera avant d'être désactivé
                   if(\Addons_Manager::getInstance()->isStructureOk($this->addonName)) {
                      //on récupère l'objet Addons_Entity. Si l'add-on n'est pas installé, la fonction le fera : 
                      $addon = \Addons_Manager::getInstance()->getAddon($this->addonName, true);
                      return $addon->getAllCrons();
                   }
                      return array();
               }
    }
