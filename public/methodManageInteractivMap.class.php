<?php
namespace Addon\test2;

/**
 * MethodManageInteractiveMap
 *
 * @author  [laura]  <[name]@Medialibs.com>
 *
 * @since 2021-07-06
 */
class methodManageInteractivMap
{
    /**
     * Constructeur
     */
     public function __construct()
     {
     }

     /**
      * Retourne la description de la rubrique
      *
      * @return string
      */
      public function getDescription()
      {
           return '';
      }

     /**
      * Gestion de la configuration
      *
      * @return string
      */
      public function start()
      {
          require_once \em_misc::getClassPath() . '/core/Emajine_Specif/Emajine_Specif_PublicationMethod.class.php';

          $form = \Emajine_API::form();
          $form->addElement('text', 'label', 'Titre');

          $obj = new \Emajine_Specif_PublicationMethod($form);
          return $obj->start();
      }
}
