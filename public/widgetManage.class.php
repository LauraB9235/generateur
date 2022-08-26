<?php
namespace Addon\test2;
/**
 * Gestion du widget ""
 *
 * @author  [laura]  <[name]@Medialibs.com>
 *
 * @since2021-07-06
 */
 class widgetManage
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
