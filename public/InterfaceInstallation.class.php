<?php
   namespace Addon\test2;

   /**
    * InterfaceInstallation
    *
    * Déplacement des fichiers modeles / scripts / images
    *
    * @author Célestin
    *
    * @date 2016-09-20
    */
   class InterfaceInstallation
   {
    CONST ADD_ON_NAME = 'test2';

    protected $addOnRoot;
    protected $siteRoot;

   /**
    * Constructeur
    */
    public function __construct()
    {
      $this->addOnRoot = \Addons_Tools::getPathAddon( self::ADD_ON_NAME ) . '/assets/';
      $this->siteRoot =  \em_misc::getSpecifPath() . '../';

    }

  /**
   * Déplacement des fichiers modeles / scripts / images
   *
   * @return null
   */
   public function start()
   {
     if ($this-> alreadyInstalled()) {
         return;
     }

     //installation des scripts : 
     $this-> installScripts();
     $this-> installModeles();
     $this-> intallImages();
  }

   /**
    * Est ce que les fichiers ont déjà été déplacés ?
    *
    * @return boolean
    */
     protected function alreadyInstalled()
     {
       return  $this->scriptAreOK()
           && $this->modelesAreOK()
           && $this->imagesAreOK();
     }

   /**
    * Les scripts ont-ils été déplacés?
    *
    * @return boolean
    */
     protected function scriptAreOK()
     {
       if(is_dir($this->addOnRoot . 'scripts')) {
          return is_dir($this->siteRoot . 'scripts/addons/' . self::ADD_ON_NAME);
       } else {
          return true;
       }
     }

   /**
    * Les modèles ont-ils été déplacés?
    *
    * @return boolean
    */
     protected function modelesAreOK()
     {
       if (is_dir($this->addOnRoot . 'modeles')){
           // récupération du modèle par défaut
           $dir = $this->getModeleDirToTest();
           return is_dir(
              $this->siteRoot . 'modeles/' . \em_misc::getDefaultLang() . \em_misc::getSkin()['root']['Skin']['config']['name'] . $dir 
           );
       }else{
         return true;
       }
     }
    /**
     * Les images ont-elles été déplacées?
     *
     * @return boolean
     */
      protected function imagesAreOK()
      {
          if(is_dir($this->addOnRoot . 'images')) {
             return is_dir($this->siteRoot . 'images/addons/' . self::ADD_ON_NAME);
          } else {
             return true;
          }
     }

    /**
     * Retourne le chemin du premier répertoire au bout d'arbo
     *
     * @return string
     */
      protected function getModeleDirToTest()
      {
          $i = 100;
          $file = $this->addOnRoot . 'modeles';
          do{
            $previous  = $file;
            $file =  $this->getFirstDir($file);
            $i--;
          } while($file && $i > 0);

          return  str_replace($this->addOnRoot,'', $previous);
      }

      /**
       * Retourne le premier repertoire d'un répertoire
       *
       * @Param string $dir répertoire dans lequel effectuer la recherche
       *
       * @return string
       */
        protected function getFirstDir($dir)
        {
            $files = glob($dir . '/*');

            foreach ($files as $file){
              if(is_dir($file)) {
                 return $file;
              }
            }
            return false;
        }

      /**
       * Copie des scripts
       * 
       * @return null
       */
        protected function installScripts()
        {
            $this->copyTree($this->addOnRoot . 'scripts', $this->siteRoot . 'scripts/addons/' . self:: ADD_ON_NAME);
        }

     /**
      * On copie les templates pour tous les modèles de toutes les langues
      *
      * @return null
      */
       protected function installModeles()
       {
          //pour chaque langue
          foreach(glob($this->siteRoot . 'modeles/*') as $lang) {
              if(is_dir($lang)){
                 foreach(glob($lang . '/*') as $modele){
                    if(is_dir($modele)) {
                        $this->copyTree($this->addOnRoot . 'modeles', $modele);
                    }
                 }
             }
          }
       }

       /**
       * Copie des images
       *
       * @return null
       */
       protected function installImage()
       {
           $this->copyTree($this->addOnRoot . 'images', $this->siteRoot . 'images/addons/' . self::ADD_ON_NAME);
       }


      /**
       * Fonction de copie d'une arborescence à une autre
       *
       * @param string $source chemin vers l'arborescence source
       * @param string $dest chemin vers l'arborescence destination
       *
       * @return boolean | null
       */
      protected function copyTree($source,$dest)
      {
         if (!file_exists($source)) {
             return;
         }

          if( is_link($source)) {
           return symlink(readlink($source), $dest);
          }

          if( is_file($source)) {
           return copy ($source,$dest);
          }

          if(!is_dir($dest)) {
           $this->makeDir($dest);          }

          $dir  = dir ($source);
          while (false !== $entry = $dir->read()) {
              if( $entry ==  '.' || $entry ==  '..') {
               continue;
              }
              $this->copyTree("$source/$entry", "$dest/$entry");
          }

          $dir->close();
          return true;
       }

      /**
       * création d'un répertoire
       *
       * @param string $dirToCreate répertoire à créer
       *
       *  return null
       */
       protected function makeDir( $dirToCreate )
       {
           if(!is_dir($dirToCreate)) {
              $dirs = explode( '/',substr($dirToCreate, 1));
              $path = '';
              foreach($dirs as $dir) {
                  $path .=  '/' .  $dir;
                  if(!is_dir($path)) {
                     mkdir($path);
                  }
              }
           }
       }

    }
