<?php
namespace Addon\test2;
require_once \em_misc::getClassPath() . '/mods/catalogue/Emajine_Catalog_Product.class.php';

/**
 * Map manager
 *
 * @author  [laura]  <[name]@Medialibs.com>
 *
 * @since 2021-07-06
 */
class MapManager
{
    const GOOGLE_MAPS_API = 'https://maps.googleapis.com/maps/api/geocode/json?sensor=false&address={LOC},+FR&key=';
    const APIKEY = ''; // Clé API
    const REQUEST_TIMEOUT = 10;
    const REQUEST_CONNECTTIMEOUT = 2;
    const DEG_TO_KM = 111.13384;
    const DEFAULT_AREA = 200;
    private static $searchedCoord;
    private static $area;
    public static $positions;

    /**
     * Supprimer les signes avec un `_`
     *
     * @param      string  $line   La ligne
     *
     * @return     string  La ligne sans `_`
     */
     public function deleteFromArray($line)
     {
          if ($line == '_') {
              return;
          }
          return $line;
     }

    /**
     * Retourne les informations relatives à un point de vente
     *
     *
     * @return array
     */
     public static function getData()
     {
         // Récuperation de toutes les informations à afficher sur le map ...

        // Données de retour à titre d'exemple ...
        return [
            [
                'coord' => self::latLngDecode('45.77486|5.801936'), // coord: {lat: 45.77486, lng: 5.801936}
                'latlong' => '45.77486|5.801936',
                'product_name' => 'TEST1',
                'address' => 'ADRESS1',
                'url' => 'url1',
            ],
            [
               'coord' => self::latLngDecode('45.87486|5.801936'),
               'latlong' => '45.87486|5.801936',
               'product_name' => 'TEST2',
               'address' => 'ADRESS2',
               'url' => 'url2',
            ],
            [
               'coord' => self::latLngDecode('45.57486|5.801936'),
               'latlong' => '45.57486|5.801936',
               'product_name' => 'TEST3',
               'address' => 'ADRESS3',
               'url' => 'url3',
            ],
            [
               'coord' => self::latLngDecode('40.57486|5.801936'),
               'latlong' => '40.57486|5.801936',
               'product_name' => 'TEST3',
               'address' => 'ADRESS3',
               'url' => 'url3',
            ]
        ];
    }
   /**
    * Décode Les latitudes et longitudes stockées en BDD
    *
    * @param  string $latLng Coordonnées stockées en BDD
    *
    * @return array
    */
    public static function latLngDecode($latLng)
    {
        $tmp = explode('|', $latLng);
        if (count($tmp) < 2) {
            $tmp = explode(',', $latLng);
        }
        foreach ($tmp as $key => &$value) {
           $value = str_replace(',', '.', $value);
        }
        return array('lat' => (float) $tmp[0], 'lng' => (float) $tmp[1]);
    }

    /**
     * Récupère la distance entre 2 points
     *
     * @param  array $coord1  coordonnées du premier point
     * @param  array $coord2  coordonnées du second point
     *
     * @return float distance entre les deux points en km
     */
     public static function getDistance($coord1, $coord2)
     {
         return rad2deg(
             acos((
                 sin(deg2rad($coord1['lat'])) * sin(deg2rad($coord2['lat'])))
                 + (cos(deg2rad($coord1['lat'])) * cos(deg2rad($coord2['lat'])) * cos(deg2rad($coord1['lng'] - ($coord2['lng']))))
             )
         ) * self::DEG_TO_KM;
     }

     /**
      * Recupère les données avec filtre
      *
      * @param      int     $area           The area
      * @param      array   $searchedCoord  The searched coordinate
      * @param      string  $address        The address
      *
      * @return     array
      */
      public static function search(&$area, &$searchedCoord, $address)
      {
          if (empty($area)) {
              $area = self::DEFAULT_AREA;
          }
          self::$area = $area;
          $cacheKey = md5($area . $address);

          if (!empty($_SESSION['mapPoints'][$cacheKey])) {
              if (!empty($address)) {
                   $searchedCoord = $_SESSION['positions'][md5(urlencode($address) . self::$area)];
              }
              return $_SESSION['mapPoints'][$cacheKey];
          }
          $entries = self::getData();
          if (!empty($address)) {
              $searchedCoord = self::getSearchedCoord($address);
              self::$searchedCoord = $searchedCoord;
              $entries = self::completeDatas($entries, 'filterCoord');
          }
          $entries = array_values(array_filter($entries));
          $_SESSION['mapPoints'][$cacheKey] = $entries;
          return $entries;
      }

     /**
      * Compléter les données
      *
      * @param   array  $entries
      * @param   string $method
      *
      * @return array
      */
      public static function completeDatas($entries, $method)
      {
          $fullEntries = [];
          for ($i = 0; $i < count($entries); $i++) {
              $fullEntries[$i] = self::$method($entries[$i]);
          }
          return $fullEntries;
      }

     /**
      * Callback d'ajout coordonnée et type_id dans une ligne
      *
      * @param array $entry
      *
      * @return array
      */
      private static function filterCoord($entry)
      {
          $elementCoord = self::latLngDecode($entry['latlong']);
          $distance = self::getDistance(self::$searchedCoord, $elementCoord);
          if ($distance > floatval(self::$area)) {
              return;
          }
          return $entry;
      }

     /**
      * Récupère les coordonnées correspondant à un code postal
      *
      * @param  string $state   Code postal
      *
      * @return array
      */
      public static function getSearchedCoord($state)
      {
          if (empty($state)) {
              return;
          }
          $loc = '';
          $condition = '';
          $loc = urlencode($state);
          if (!empty($_SESSION['positions'][md5($loc . self::$area)])) {
              return $_SESSION['positions'][md5($loc . self::$area)];
          }
          $request = curl_init();
          curl_setopt($request, CURLOPT_URL, str_replace('{LOC}', $loc, self::GOOGLE_MAPS_API . self::APIKEY));
          curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($request, CURLOPT_CONNECTTIMEOUT, self::REQUEST_CONNECTTIMEOUT);
          curl_setopt($request, CURLOPT_TIMEOUT, self::REQUEST_TIMEOUT);

          $result = json_decode(curl_exec($request), true);
          if (empty($result) || $result['status'] != 'OK' || empty($result['results'][0])) {
              return;
          }
          $data = $result['results'][0];
          $_SESSION['positions'][md5($loc . self::$area)] = $data['geometry']['location'];
          return $data['geometry']['location'];
      }
}
