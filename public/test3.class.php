<?php
 namespace Addon\test3;

/**
 * test3
 *
 * Master de la classe
 *
 * @author Medialibs
 *
 * @date 2021-06-24
 */
class test3 extends \Addons_Entity
{
   //singleton
  protected static $instance;

 /**
  * Récupération du singleton
  *
  * Permet d'instancier le singleton depuis les classes standards :
  *
  * @return \AddOn\ test3\ test3 
  */
  static public function getInstance()
  {
   if (!self::instance) {
     self::$instance = \Addons_Manager::getInstance()->getAddon(end(explode('\\', get_class($this))), true);
   }
   return self::$instance;
 }
 
 /**
  * Permet de lancer des actions spécifiques après installation de l'add_on
  *
  * @return null
  */
   public function onInstallation()
  {

   }

  /**
   * Permet de lancer des actions spécifiques après désinstallation de l'add_on
   *
   * @return null
   */
   public function onUninstallation()
  {

    }

  /**
   *
   * Permet de lancer des actions spécifiques après la désactivation de l'add-on
   *
   * @return null
   */
   public function onDisable()
  {

   }

  /**
   * Permet de lancer des actions spécifiques avant l'export de l'add-on
   *
   * @return null
   */
   public function onExport()
   {

   }

  /**
   * Vérifie l'activation de l'add-on 
   *
   * @return boolean true si l'add-on est actif
   */
   public function isEnabled()
   {
   return $this->status() == \Addons_Entity::STATUS_ACTIVE;
   }

  /**
   * Retourne le chemin de destination des images
   *
   * @return string
   */
   public function imagePath()
   {
   return '/images/addons/test3/';
   }
}
