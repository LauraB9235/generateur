<?php
namespace Addon\test1;

require_once \em_misc::getSpecifPath() . 'addons/test1/class/tools/Manager.class.php';

/**
 * Gestion du type de page "test3"
 *
 *
 *
 * @author  [Laura]  <[name]@Medialibs.com> 
 *
 * @since 2021-06-20
*/
class methodPublictest3

{
     private $beginList;
     private $title;
     const UPDATE_STRING = 'update';
     const SAVE_STRING   = 'add';
     const NB_PAR_PAGE   = 50;
     /**
      * Constructeur
      *
      * Vous aurez autant d'argument à l'appel de cette méthode que de champs défini lors de la configuration.
      * Ainsi, par exemple, si vous définissez un nom et un nombre d'élément à afficher, vous pourrez récupérer
      * ces éléments via la définition suivante :
      * public function __construct($title = false, $nbElements = 10)
      */
      public function __construct($title = false)
      {
          $this->title = $title;
      }

     /**
      * Gestion et affichage du contenu
      *
      * @return string
      */
     public function start()
     {
         // Récupere le template contenant le CRUD , veuillez copier le fichier crudList.html dans cette répertoire(répertoire à créer)
         $mx = \em_mx::initMx(\em_misc::getPublicTemplatePath('specifs/addons/test1/publication/crudList.html'));

         // Traitement ...
         $this->initListMx($mx);

         // Traitement...........

        // Affichage du formulaire
        $this->initFormMx($mx, $idActor);
        return \em_mx::write($mx);
     }

     /**
      * Initialise la liste $mx
      * @param Modelixe $mx
      *
      * @return string
      */
      public static function initListMx($mx)
      {
          // Template pour les recherches
          $mxSearch = \em_mx::initMx(\em_misc::getPublicTemplatePath('specifs/addons/test1/publication/search.html'));
          $this->initFormSearchMx($mxSearch);

         // Traitement ....

         \em_mx::text($mx, 'searchBloc.search', \em_mx::write($mxSearch));

         // Affichage de la pagination à calculer les nombres des données à afficher et les nombres des pages
         $this->initPagination($mx, $nbTotalOfData, $page);

         $this->beginList = ($page - 1) * self::NB_PAR_PAGE;
         // Initialiser le CRUD à afficher
         $datas = Manager::getData($isListMember, self::NB_PAR_PAGE, $this->beginList);
         // Remplir le template (exemple)
         foreach ($datas as $value) {
            \em_mx::text($mx, 'members.member.name', $value['nom']);
            \em_mx::text($mx, 'members.member.prenom', $value['prenom']);
            \em_mx::text($mx, 'members.member.login', $value['login']);
            \em_mx::text($mx, 'members.member.mail', $value['mail']);
            \em_mx::text($mx, 'members.member.date_crea', $value['dateCrea']);
         }
         // Traitement
      }

      /**
       * Initialise le formulaire $mx
       *
       * @param Modelixe $mx
       * @param int $idActor
       *
       * @return null
       */
       private function initFormMx($mx, $idActor)
       {
            $nameVal     = "";
            $prenomVal   = "";
            $loginVal    = "";
            $mailVal     = "";
            $submitValue = self::SAVE_STRING;

             if (0 != $idActor) {
                 $actor       = $this->getUserById($idActor);
                 $nameVal     = stripslashes($actor['nom']);
                 $prenomVal   = stripslashes($actor['prenom']);
                 $mailVal     = $actor['mail'];
                 $submitValue = self::UPDATE_STRING;
             }

             $form = \Emajine_API::form();
             $form->addElement('fieldset', 'Membre');
             $form->addElement('text', 'nom', 'Nom', ['value' => $nameVal], true);
             $form->addElement('text', 'prenom', 'Prenom', ['value' => $prenomVal], true);
             $form->addElement('text', 'login', 'Login', ['value' => $loginVal], true);
             $form->addElement('text', 'mail', 'Mail', ['value' => $mailVal], true);
             $form->addElement('submit', $submitValue, 'Enregistrer');

             if ($form->validate()) {
                 // Traitement, insertion ou sauvegarde après validation du formulaire
             }
      }

     /**
      * Initialiser la pagination pour la gestion des membres
      *
      * @param Modelixe  $mx
      * @param int         $nbTotalOfData
      * @param int         $page
      *
      * @return null
      */
      private function initPagination($mx, $nbTotalOfData, $page)
      {
          if (0 == $nbTotalOfData) {
              \em_mx::delete($mx, 'pager');
          }
          require_once \em_misc::getClassPath() . '/core/Emajine_API/Emajine_Pager.class.php';
          $pager = new \em_pager();
          $pager->setMxPrefix('pager');
          $pager->setNbItems($nbTotalOfData);
          $pager->setNbItemsPerPage(self::NB_PAR_PAGE);
          $pager->setCurrentPage($page);
          $pager->setUrlMask('page-{page}');
          $pager->generate($mx);
      }

      /**
       * Initialise le formulaire de recherche
       *
       * @param Modelixe $mx
       * @param boolean $searchKey
       *
       * @return null
       */
       private function initFormSearchMx($mx, $searchKey = false)
       {
            if (!empty($searchKey)) {
                \em_mx::attr($mx, 'search.searchKey', $searchKey);
            }
            \em_mx::attr($mx, 'search.idsearch', 'input-filter');
            \em_mx::attr($mx, 'search.searchType', 'search');
            \em_mx::attr($mx, 'search.searchName', 'search');
            \em_mx::attr($mx, 'search.onchange', 'search()');
            \em_mx::attr($mx, 'search.placeholder', 'search');
            \em_mx::attr($mx, 'search.dataTable', 'order-table');
            \em_mx::attr($mx, 'search.size', '15');
            \em_misc::loadScript('/scripts/filter.js');
      }
}
