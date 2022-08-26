<?php

namespace Addon\;

require_once __DIR__ . '/../test1.class.php';
/**
 * Classe de gestion de la notification spécifique de l'add-on test1
 *
 * 
 *
 * @author  [Laura]  <[name]@Medialibs.com>
 *
 * @since2021-07-06
 */
class  extends \Mail_Notification_Abstract 
{
    //Template
    const PATH = 'specifs/addons/test1/notification';
    //A créer
    const TEMPLATE ='notifications_template.html';

    //Panneau de configuration
    const GROUP = '';
    const ORDER ='';
    const NAME ='';
    const DESCRIPTION ='';
    const TYPE = 'html';
    const IS_SENT_TO_ADMIN =;

    //Notification
    const MAIL_SUBJECT = '{firstname} {fastname}!';

   //Tags
   protected $customTagsLabel = array(
      'title' => 'Titre du client',
      'firstname' => 'Prénom du client',
      'lastname' => 'Nom du client',
   );

   protected $customTagsHtml = array(
      'title' => '<mx:text id="title" />',
      'firstname' => '<mx:text id="firstname" />',
      'lastname' => '<mx:text id="firstname" />'
   );


   // Les fausses données pour l’envoi de test
   protected $fakeData = array(
      'firstnamemember' => 'Bob',
      'lastnamemember' => 'Round',
   );

   protected $subjectFakeData = array(
      'firstname' => 'John',
      'lastname' => 'Rand',
   );

   /**
    * Retourne l'etat d'activation dans le webo facto du module gérant la notification
    *
    * @return bool
    */
    public function isEnabled()
    {
        $addon =test1::getInstance();

        if ($addon) {
             if ($addon->isEnabled()) {
                 return true;
             }
        }
        return false;
    }

   /**
    * Envoi la notification
    *
    * @Param integer $userId
    * @Param array $datas The datas
    *
    *@return boolean
    */
    public function send($userId,$datas)
    {
        if(empty($userId)) {
           return false;
    }

    $person = self::getPerson($userId);

    $messageData = array(
       'data'   => $datas,
       'person' => $person
    );

    $subjectData = array(
       '{firstname}' => $datas["modificateur"]['prenom'],
       '{lastname}' => $datas["modificateur"]['nom']
    );
    if ($this->sendMx($messageData, $subjectData, array($person))) {
        return true;
    }
    return false;
  }

  /**
   * Remplissage du template avec les données fournies
   *
   * @param array $data
   * @param string $type
   * @param string $mxPrefix
   *
   * @return modeliXe
   */
   public function fillMx(array $data, $type = 'html_', $mxPrefix = '')
   {
       $mx = $this->getMxTemplate();
       \em_mx::text($mx, 'firstnamemember', $data['data']['prenom']);
       \em_mx::text($mx, 'lastnamemember',  $data['data']['nom']);
       return $mx;
   }

 }
