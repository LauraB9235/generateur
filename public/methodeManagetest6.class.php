<?php
namespace Addon\test2;

/**
 * Configuration du type de page"test5"
 *
 *
 *
 * @author  [Laura]  <[name]@Medialibs.com> 
 *
 * @since 2021-06-26
 */
class test6
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

         $form = new \Emajine_Form();
         $form->addElement(\Emajine_Form::elementFactory('text', 'Titre', 'label'));
         $obj = new \Emajine_Specif_PublicationMethod($form);
         return $obj->start();
     }
}
