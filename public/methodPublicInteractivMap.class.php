<?php
namespace Addon\test2;
require_once \em_misc::getSpecifPath() . 'addons/test2/class/tools/MapManager.class.php';

/**
 * MethodPublicInteractivMap
 *
 * @author  [laura]  <[name]@Medialibs.com>
 *
 * @since 2021-07-06
 */
class methodPublicInteractivMap
{

    // Titre de la méthode de publication
    private $title = '';
    private $mx;
    const APIKEY = '';

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
         $mx = \em_mx::initMx(\em_misc::getSpecifPath() . 'addons/test2/templates/publication_methods/searchAreaResult.html');
         $this->display($mx);
         return \em_mx::write($mx);
     }

    /**
     * Affiche le résultat de la recherche
     *
     * @param  modeliXe $mx              Objet permettant de gérer le template
     * @param  array    $elements        Liste des identifiants des fiches annuaire
     *
     * @return null
     */
     public function display($mx)
     {
         \em_mx::text(
             $mx,
             'results.api',
             '<script src="https://maps.googleapis.com/maps/api/js?v=3.4&libraries=geometry,drawing,weather,visualization&language=fr&key=' . self::APIKEY . '"></script>'
         );
        // Préremplire les champs recherchés pour la dernière fois
        \em_mx::attr($mx, 'areaselected' . $_SESSION['entries']['area'], $_SESSION['entries']['area']);
        \em_mx::attr($mx, 'cityvalue', $_SESSION['entries']['city']);
        $elements = [];
        \em_mx::text($mx, 'results.js', \em_js::getJsTag(' var entryIds = ' . json_encode($elements) . '; var allEntries = [' . implode(',', $elements) . '];'));
        if (!empty($_SESSION['entries']['area'])) {
            $area = $_SESSION['entries']['area'];
        } else {
            $area = 200;
        }
        \em_mx::text($mx, 'areaLoading', 'var areaLoading = ' . $area . ';');
     }
}
