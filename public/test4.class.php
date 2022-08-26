<?php
namespace Addon\test2;

/**
 * Gestion de la balise MX "test3"
 *
 * @author  [Laura]  <[name]@Medialibs.com>
 *
 * @since 2021-06-24
 */
 class mxTest4
 {
     /**
      * Constructeur
      */
      public function __construct()
      {}
      /**
       * Initialisation de l'emplacement du dossier specifs
       *
       * @param string $path Emplacement des développements
       *
       * @return null
       */
       public function setDevPath($path)
       {
           $this->devPath = $path;
       }
       /**
        * Récupération de l'emplacement du dossier specifs
        *
        * @return string
        */
        public function getDevPath()
        {
            return $this->devPath;
        }
        /**
         * Initialisation des attribus de la balise spécifique
         *
         * @param string $attributName Nom de l'attribut
         * @param string $attributValue Valeur de l'attribut
         *
         * @return null
         */
         public function setTagParams($attributName, $attributValue)
         {
              $varname = '_tagParam' . ucFirst($attributName);
              $this->$varname = $attributValue;
         }
         /**
          * Récupération de la valeur définie pour un attribut de la balise
          *
          * @param string $attributName Nom de l'attribut
          *
          * @return string
          */
          public function getTagParam($attributName)
          {
              $varname = '_tagParam' . ucFirst($attributName);
              if (isset($this->$varname)) {
                  return $this->$varname;
              }
              return '';
          }
          /**
           * Gestion de la balise. La méthode retourne le contenu de celle-ci.
           *
           * Si start retourne 'test' alors la balise prendra comme valeur 'test'
           *
           * @return string
           */
           public function start()
           {
               return 'Hello world';
           }
 }
