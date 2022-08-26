<?php
namespace Addon\test2;

/**
 * Gestion du type de page ""
 *
 *
 *
 * @author  [laura]  <[name]@Medialibs.com> 
 *
 * @since2021-07-06
 */
 class methoPublictest2

 {

     // Titre de la méthode de publication
     private $title = '';

 /**
  * Constructeur
  *
  * Vous aurez autant d'argument à l'appel de cette méthode que de champs défini lors de la configuration.
  * Ainsi, par exemple, si vous définissez un nom et un nombre d'élément à afficher, vous pourrez récupérer
  * ces éléments via la définition suivante :
  * public function __construct($title = false, $nbElements = 10)
  */  public function __construct($title = false)
  {
       $this->title = $title;
  }

 /**
  * Gestion et affichage du contenu
  *
  * @return string
  */
  public function start()
  {      return $this->title;
  }
}
