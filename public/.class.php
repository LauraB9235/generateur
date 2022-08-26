<?php
namespace Addon\test2;
require_once \em_misc::getClassPath() . '/core/Emajine_API/Emajine_CRUD.class.php';

/**
 * CRUD gestion des sélections du moment
 *
 * @author Zo de Medialibs <robson@medialibs.com>
 *
 * @since 2021-07-06
 */
 class  extends \Emajine_CRUD

{
     private $products;

    /**
     * Constructeur du CRUD
     *
     * @return null
     */
     public function __construct()
     {
         $this->products = array('tire' => i18n('Pneus'), 'vo' => i18n('Véhicules d\'occasion'));
         $this->initCrud();
         return parent::__construct();
     }

   /**
    * Génère le formulaire d'ajout ou de modification d'une plaque
    *
    * @param emajine_form   $form   Un objet formulaire
    * @param string     $mode   Le type de formulaire. 2 valeurs possibles : add ou edit
    * @return null
    */
    public function _getFormDatas($form, $mode)
    {
        $form->addElement(
           'text',
           'offer_name',
           'Nom de l\'offre',
            array(), true
        );
        $form->addElement(
           'radM',
           'offer_type',
           'Type d\'offre',
            array(
               'values'  => $this->products,
               'checked' => 'tire',
            ),
            true
        );
        $form->addElement(
           'iRes',
           'visual',
           'Visuel de l\'offre',
            array('ressource' => 'media'), true
        );
        $form->addElement(
           'text',
           'offer_link',
           'Lien',
            array(), true
        );
        $form->addElement(
           'select',
           'plates',
           'Plaques concernées par l\'offre',
            array('multiple' => true, 'values' => \em_db::assoc('SELECT id_plate, plate_name FROM specifs_plates ORDER BY plate_name ASC')), true
        );

            $this->hookGetFormDatas($form, $mode);
            $this->_getFormDatasActions($form, $mode);
    }

    /**
     * Initialisation du crud
     *
     * @return null
     */
     private function initCrud()
     {
         $this->setListTitle('Title');
         $this->setListDescription('Description');
         $this->setDBTable('specifs_selections_offers');
         $this->setDBFields('offer_id, offer_name, offer_type, visual, plates ');
         $this->setDBId('offer_id');
         $this->setDBLabel('offer_name');
         $this->setListDefaultSort('offer_name', 'ASC');
         $this->setListMap(array('offer_name' => 'Nom de l\'offre', 'offer_type' => "Type d\'offre", 'visual' => "Visuel de l\'offre", 'plates' => "Plaques concernées par l\'offre"));
         $this->setListNewElementLinkLabel('Ajouter une offre');
         $this->setFieldsCallBack('plates', array($this, 'formatPlates'));
         $this->setFieldsCallBack('offer_type', array($this, 'formatType'));
         $this->setListSearchCrit(array('Nom de l\'offre' => 'offer_name'));
     }

      /**
       * Redéfinir les éléments à afficher
       *
       * @return null
       */
       public function _getDatasToDisplay()
       {
           $query = 'SELECT offer_name, offer_type, plates, visual '
           . 'FROM ' . $this->_dbtable . ' '
           . 'WHERE ' . $this->_dbid . '=' . intval($_GET['id']);
           $datas               = \em_db::row($query);
           list(, $mediaId)     = explode('://', $datas['visual']);
           $datas['plates']     = $this->formatPlates($datas['plates'], intval($_GET['id']));
           $datas['offer_type'] = $this->formatType($datas['offer_type'], intval($_GET['id']));
           $datas['visual']     = "<img src= '" . getFileUrl($mediaId) . "' alt='" . getFileUrl($mediaId) . "'/>";
           return $datas;
       }

       /**
        * format Plates
        * @param  string $value
        * @param  int    $elementId
        *
        * @return string
        */
        public function formatPlates($value, $elementId)
        {
             $query = 'SELECT plate_name '
             . 'FROM specifs_plates '
             . 'WHERE id_plate IN (' . implode(',', array_filter(explode('!', $value))) . ') '
             . 'ORDER BY plate_name';
             $plates = implode(', ', \em_db::ids($query));
             return $plates;
        }

       /**
        * format types
        * @param  string $value
        * @param  int    $elementId
        *
        * @return string
        */
        public function formatType($value, $elementId)
        {
             return $this->products[$value];
        }
 }