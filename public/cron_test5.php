<?php
namespace Addons\test2;

require_once \em_misc::getSpecifPath() . 'addons/test2/class/tools/libs/MonitoringLog.class.php';


/**
 * Tache repetitive test4 .
 * 
 * @author  [Laura]  <[name]@Medialibs.com>
 *
 *@since2021-06-24
 */
class cron_test5

{
    //Fréquence d'exécution de la tâche
    private $period = ['min' => [0], 'hour' => [1]];
    const LOG_PATH = 'logs/test6/';

    //
    private $monitoringLog;

    /**
     * Le cron est actif?
     *
     * -Pendant le développement il est préférable de lancer le cron manuellement, initialisez la valeur sur false
     * -En production, initialiser la variable à true pour que la tâche soit automatiquement executée
     */
     private $enabled = false;

    /**
     * Constructeur
     */
     public function __construct() {}

    /**
     * Retourne la fréquence d'exécution de la tâche
     *
     *@return array
     */
     public function getPeriod()
     {
        return $this->period;
     }

    /**
     * Retourne l'état d'activation de la tâche
     *
     * @return bool
     */
     public function isEnabled()
     {
        return $this->enabled;
     }

    /**
     * Traitement à effectuer
     *
     * @return null
     */
     public function start()
     {
        $this->monitoringLog = new MonitoringLog();
        // Exemple des données à afficher
        $this->monitoringLog->setService('Test cron');
        // Exemple type de tâche executé par le cron
        $this->monitoringLog->setType('import');
        $this->monitoringLog->beforeProcess();
        // Traitement ....
        $messageLogSummary = $this->process();
        // Les données qui seront écrites dans le fichier
        $this->monitoringLog->writeLog(
        true,
        $messageLogSummary
        );
      }

     /**
      * Process
      *
      * @return string
      */
      public function process()
      {
          // Traitement ....
          return 'Message à afficher sur le CRUD monitoring';
       }

     /**
      * Gestion d'un rapport sur l'exécution de la tâche
      *  - si elle est lancée manuellement alors le message sera affiché à l'écran
      *  - si ele est exécutée automatiquement alrors le message sera envoyé par email
      *
      * @param mixed données à afficher ou à envoyer dans le mail
      * @param string adresse email du destinaire
      *
      * @return null
      */
      private function log($datas, $email = 'prestation@medialibs.com')
      {
          //\cron_tools::dump($datas, $email);
      }
    }
