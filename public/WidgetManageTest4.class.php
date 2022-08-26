<?php
namespace Addon\test2;
/**
 * Gestion du widget "Test3"
 *
 * @author  [Laura]  <[name]@Medialibs.com>
 *
 * @since2021-06-24
 */
 class widgetManageTest4
 {

    /**
     * Constructeur
     */
     public function __construct()
     {
     }

    /**
     * Gestion de la configuration
     *
     * @return null
     */
     public function start($form, $id)
     {
         $form->addElement('text', 'title', 'Titre');
     }
 }
