<?php
namespace Addon\test2;
require_once \em_misc::getClassPath() . '/core/Emajine_API/Emajine_2C.class.php';
/**
 * briochin
 * Gestion du menu specifique Briochin
 *
 *
 * @author Herizo de Medialibs <zo@medialibs.com>
 *
 * @since 2019-06-13 14:02 
 */
 class menuNew extends \Emajine_2C
 {

     /**
      * Les différentes zones
      *
      * @var array
      */
      public $_contenersZoneItems = array(
          'SubMenu' => array(
              'first'  => array('label' => 'First')
          )
      );

      /**
       * La zone à afficher par défaut
       *
       * @var string
       */
       public $_contenersZoneDefaultItem = 'my2Ccontent';

       /**
        * Retourne la description du 2C
        * Cette méthode est prévue pour être overloadé dans les classes enfants
        *
        * @return string
        */
        protected function getContentDescription()
        {
             return 'Ma description';
        }

       /**
        * First Item
        *
        * @return string
        */
        public function _first()
        {
             return $this->_getContentForm('generateForm', 'onSave', 'getDefaultValue');
        }

       /**
        * Récupérer le template à afficher
        *
        * @return string
        */
        public function generateForm($form)
        {
            $form->addElement('fieldset', 'Title of screen');
            $form->addElement(
               'text',
               'myField',
               'Mon champ',
                array(),
                true,
               '<div class='description'>Description ici</div>'
            );
            // Ajouter un champ date
            $form->addElement(
                'date',
                'date',
                'Mon champ',
                 array(),
                 true,
                '<div class='description'>Description ici</div>'
            );
            // Ajouter un radio
            $form->addElement(
                'radM',
                'nom_champ',
                'libelle_champ',
                array('values' => array(1 => 'Oui',0 =>'Non'), 'useNumericKey' => true), true
            );
            // Ajouter un checkbox
            $form->addElement(
                'mChe',

                'mon_checkbox',
                'libelle_champ',
                array('values' => array(1 => 'Oui',0 =>'Non'), 'checked' => array(), 'useNumericKey' => true), true
            );
            // Ajout d'un champ de type ressource
            $form->addElement(
                'iRes',
                'visual',
                'Visuel',
                array('ressource' => 'media'), true
            );
            // Ajout d'un champ de type ressource
            $form->addElement(
                'mRes',
                'visuals',
                'Visuels',
                 array(
                'value'     => $valeurParDefaut,
                'ressource' => 'media', //article, media, news, form, map, link, poll, …
                'linklabel' => 'linklabel',
                'js'        => $js
            ),
            true
            );
            // Ajouter une description
            $form->addElement('description', 'Indiquez ici votre nom et votre prénom');
            // Ajouter un champ de type select
            $form->addElement('select', 'civility', 'Civilité', array('values' => array('M' => 'Monsieur', 'Mme' => 'Madamme')), true);            // Ajouter un champ de type textarea
            $form->addElement('area', 'address', 'Adresse', array('rows' => 4));

            // Ajouter un champ de type file
            $form->addElement(
                'file',
                'rapportFile',
                'Importer un fichier',
                 array('accept' => 'csv'),
                 true,
                '<span>Veuillez sélectionner un fichier au format CSV (encodage UTF-8)<br />Les données doivent être séparées par un ","</span>'
            );
            // Ajouter un champ de selection d'utilisateur
            $form->addElement(
                "seDb" , "nom_du_champ" ,"Titre du champ" ,
                 array(
                    "dbtable"     => "acteur" ,
                    "dbid"        => "id_acteur" ,
                    "dblabel"     => "CONCAT(nom , ' ' , prenom)" ,
                    "selected"    => $id_de_l_utilisateur_selectionne ,
                    "dbcondition" => " login IS NOT NULL " ,
                    'behaviour'  => 'layer',
                 )
            );
            // Ajouter un champ date
            $form->addElement('capT',
                 'Captcha',
                 'Mon captcha',
                 array(
                    'captchatype' => 'recaptcha', //operation,question',
                    'style'       => 'clas_css',
                    'maxlength'   => $maxLegth,
                    'size'        => $size,
                    'value'       => $value
                 ),
                 true,
                '<div class='description'>Description ici</div>'
            );
            // Ajouter un champ de type hidden
            $form->addElement('hidden', 'champ_hidden', 'valeur');

            // Ajouter un menu déroulant avec lecture en base de données
            $form->addElement('seDb', 'myDbSelect', 'Mon selection', array(
                    'dbtable'     => 'addons' , // : nom de la table (accepte des jointures)
                    'dbid'        => 'id' , // : champ "identifiant"
                    'dblabel'     => 'name' , // : champ "label"
                    'dbcondition' => '1', // : condition MySQL à appliquer
                    'dbdistinct'  => true , // : si true, ajoute un ‘DISTINCT’ sur le label
                    // 'selected'    => array(1) , // : tableau contenant les valeurs sélectionnées par défaut
                    // 'size'         => 20 , // : nombre d’options affichées
                    'multiple'    => false , // : est ce un champ multiple ?
                    'required'    => false , // : si true, le select ne proposera pas de valeur vide
                    // 'js'           => $dbJs , // : javascript pour l'attribut onchange
                    'behaviour'   => 'layer' , // : si "layer", la sélection se fera par l’intermédiaire d’un layer
                    // 'dborder'      => 'id DESC' // : partie “ORDER BY” de la requête
                )
            );
       }

     /**
      * Enregistrement des données à la validation du formulaire
      *
      * @return null
      */
      public function onSave()
      {

      }

      /**
       * Récupération des données par défaut en base
       *
       * @return array
       */
       public function getDefaultValue()
       {

       }
 }
