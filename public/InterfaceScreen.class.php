<?php
    namespace Addon\test2;

    /**
     * InterfaceScreen
     *
     * Affichage de l'état de l'add-on
     *
     * @author Medialibs
     *
     * @date 2016-06-28
     */

    require_once __DIR__ .'/../abstracts/MxToolsAbstract.class.php';
    require_once __DIR__ .'/InterfaceTools.class.php';
    require_once __DIR__ .'/../../test2.class.php';

    class InterfaceScreen extends MxToolsAbstract
    {
        protected $templateFile  = 'interfaceScreen.html';
        protected $speTemplatePath = '/../../manageTpls/';

        function __construct(){}

        /**
         * Affiche l'interface de gestion de l'add-on
         *
         * @return string
         */
         public function start()
         {
             $this->initMx();
             $this->deleteBlock('actionResult');

             if (!empty($_POST)) {
                 foreach($_POST as $key => $value) {
                     if(strpos($key, 'action') === 0) {
                        $method = substr($key,6);
                        $obj    = new InterfaceTools();
                        if(method_exists($obj,$method)) {
                           $this->mxText('actionResult.txt', $obj->$method());
                        }
                     }
                 }
             }

             if (!empty($_GET['cron'])) {
                 $obj = new InterfaceTools();
                 $obj->execCron($_GET['cron']);
             }
             $this->displayCrons();
             $this->initStateInfo();
             echo $this->getStringContent();

             exit;
         }

         /**
          * Récupère le chemin vers le template
          *
          * @return string
          */
          public function getTemplatePath()
          {
              return __DIR__ . $this->speTemplatePath;
          }

         /**
          * Récupération et affichage de la liste des crons
          *
          * @return null
          */
          public function displayCrons()
          {
              $obj = new InterfaceTools();
              $crons = $obj->getAllCrons();
              if (empty($crons)) {
                  $this->deleteBlock('installed.crons');
                  return;
              }
              $addonName = explode('\\', get_class($this))[1];
              foreach($crons as $cron){
                  $this->mxText('installed.crons.cron.text', str_replace(array($addonName . '_addons_cron_','.class.php'), '',$cron));
                  $this->mxAttribut('installed.crons.cron.href', $_SERVER['REQUEST_URI'] . '?cron=' . $cron);
                  $this->loopBlock('installed.crons.cron');
              }
          }

          /**
           * Initialise les informations d'état de l'add-on
           *
           * @return null
           */
           protected function initStateInfo()
           {
               //affichage de l'info
               $data  = $this->actions;
               $addon = test2::getInstance();

               if($addon) {
                  if($addon->isEnabled()) {
                     $this->deleteBlock('state.disabled');
                     $this->deleteBlock('state_action.enableAction');
                  } else {
                     $this->deleteBlock('state.enabled');
                     $this->deleteBlock('state_action.disableAction');
                  }
                  $this->deleteBlock('notInstalled');
               }else{
                  $this->deleteBlock('installed');
               }
           }
    }
