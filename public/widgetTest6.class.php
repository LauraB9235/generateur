<?php
namespace Addon\test2;
/**
 * Gestion du widget "Test5"
 *
 * @author  [Laura]  <[name]@Medialibs.com>
 *
 */
 class widgetTest6
 {

     // Variable contenant un contenu prévu pour s'afficher dans la zone de contenu
     private $contentZone = '';
     private $title  = '';

    /**
     * Constructeur
     */
     function __construct($parent, $title)
     {
         $this->title = $title;
     }

    /**
     * Gestion et affichage du widget
     *
     * @return string
     */
     public function start()
     {
         // Your code here
     }
}
