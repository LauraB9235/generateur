<?php
namespace Addon\test2 ;
require_once \em_misc::getClassPath() . '/core/Emajine_API/Emajine_CRUD.class.php';
/**
 *
 * Master de la classe
 *
 * @author Medialibs
 *
 * @since 2021-06-26
 */
 class test8 extends \Emajine_CRUD
 {

     /**
      * Constructeur du CRUD
      *
      * @return null
      */
      public function __construct()
      {
           $this->initCrud();
           return parent::__construct();
      }

      /**
       * Génère le formulaire d'ajout ou de modification d'une {nom de l'entité}
       *
       * @param emajine_form   $form   Un objet formulaire
       * @param string     $mode   Le type de formulaire. 2 valeurs possibles : add ou edit
       * @return null
       */
       public function _getFormDatas($form, $mode)
       {
           // Ajouter votre code de création de formulaire ici
           $this->hookGetFormDatas($form, $mode);
           $this->_getFormDatasActions($form,$mode);
       }

      /**
       * Initialisation du crud
       *
       * @return null
       */
       private function initCrud()
       {
         $this->setListTitle('Titre');
         $this->setListDescription('Description');
         // Ajouter nom table
         // $this->setDBTable('specifs_table');
         // Ajouter les champ à séléctionner
         // $this->setDBFields('');
         // Ajouter la clé primaire
         // $this->setDBId('id');
         // Ajouter la colonne à mettre comme label
         // $this->setDBLabel('label');
         // Ajouter le tri
         // $this->setListDefaultSort('my_column', 'ASC');
         // Ajouter les colonnes à afficher
         // $this->setListMap(array('col1' => 'Libele colonne 1', 'col2' => 'Libele colonne 2'));
         // Ajouter le libellé pour le bouton ajouter
         // $this->setListNewElementLinkLabel('Ajouter une ...');
         // Ajouter une recherche
         // $this->setListSearchCrit(array('Libellé' => 'label', 'Etat' => 'status'));
       }

      /**
       * Verifier si on peut supprimer une {nom de l'entité} lors de l'affichage du message de confirmation
       *
       * @param string $message
       * @param array $param
       *
       * @return string
       */
       public function _delete_confirmation($message = null, array $param = array())
       {
           // à utiliser si on veut modifier le comportement de la confirmation de suppression
           // return parent::_delete_confirmation($message, $param);
       }

       /**
        * Suppression d'une {nom de l'entité}'
        *
        *@return null
        */
        public function _delete()
        {
        }

       /**
        * Enlever les actions Afficher détails des lignes
        *
        * @param object $list
        *
        * @return ?
        */
        public function _getListActions($list)
        {
            // if ($this->_rights['modifier']) {
            //     $list->setEditAction($this->_layerIDPrefix);
            // }
            // if ($this->_rights['supprimer']) {
            //     $list->setDeleteAction($this->_layerIDPrefix);
            // }
            // $this->_getAdditionnalListActions($list);
        }

       /**
        * Définition des actions de masse
        *
        * @return null
        */
        public function _getMassivesActions($list)
        {
            // fonction à vide permettant de ne pas afficher les actions de masse
        }

       /**
        * Redefinir la fonction getLayerTabs pour que l'onglet détail ne s'affiche plus lors d'une edition        *`
        * @param object $layer
        * @return null
        */
        public function _getLayerTabs($layer)
        {
            // $get = array();
            // $get['ch'] = $_GET['ch'];
            // $get['id'] = $_GET['id'];
            // if ($this->_rights['modifier']) {
            //     $get['action'] = 'edit';
            //     $js = \em_js::layer($this->getLayerId($_GET['id']),
            //                         getRewriteUrl(false, $get), i18n('Edit'));
            //     $label = i18n('_edit_');
            //     $img = \em_misc::getManageImg('actions/edit_small.png', $label);
            //     $layer->addTab('_edit_', $img, $js, 'edit');
            // }
            // $this->_getLayerAdditionnalTabs($layer);
            // $this->hookSetLayerTabsAfterDefault($layer);
        }


        /**
         * Redefinir la fonction actionAfterAdd pour que le detail ne soit plus affiché suite à l'ajout
         *
         * @return null
         */
         public function _actionAfterAdd()
         {
             // $out =  'updateList("' . $this->_prepareURL(array('action'=>'list')) . '", {contents:true});';'
             // $out .= \em_js::closeLayer($this->getLayerId());
             // \em_output::echoAndExit(\em_js::getJsTag($out));
         }

         /**
          * Met à jour l'ordre des {nom de l'entité}s après l'enregistrement
          *
          */
          public function _actionAfterEdit()
          {
              // Votre code ici
              // Appel de la méthode parente
              parent::_actionAfterEdit();
          }


 }
