<?php
namespace Addon\test2 ;
require_once \em_misc::getSpecifPath() . 'addons/test2/class/tools/crud/class.php';

/**
 * 
 *
 * @author Medialibs
 *
 * @since 2021-07-06
 */
 class hook_2C extends \Emajine_Hooks
 {
    /**
     * Intervention sur les écrans en 2 colonnes (écrans de configuration)
     *
     * @param string $className Identifiant de l'écran
     * @param string $label Libellé de la zone
     * @param array $items Items proposés dans la colonne de gauche
     * @param string $defaultItem Item par défaut
     *
     * @return null
     */
     public function getContenersZoneParams($className, &$label, &$items, &$defaultItem)
     {
         if (in_array($className, array('Emajine_Configuration_Notifications'))) {
             $newElementLabel ='';
             if (!is_array($items[$newElementLabel])) {
                 $items[$newElementLabel] = array();
             }
             $items[$newElementLabel]['myCallbackName']['label'] ='';
        }
     }

     /**
      * Action executé au clic sur le menu
      *
      * @return     string  contenu 
      */
     public function actionMyCallbackName()
     {
         $customForm = new();
         return $customForm->start();
     }
 }
