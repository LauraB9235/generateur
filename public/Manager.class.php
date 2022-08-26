<?php

namespace Addon\test2;

/**
 * Classe métier
 *
 * @author  [laura]  <[name]@Medialibs.com>
 *
 * @since 2021-07-06
 */
class Manager
{

   /**
    * Mettre à jour
    *
    * @param      array   $datas      The datas
    * @param      string  $table      The table
    * @param      string  $condition  The condition
    *
    * @return     null
    */
    public static function update($datas, $table, $condition)
    {
        \em_db::exec(createUpdateQuery($datas, $table, $condition));
    }

   /**
    * Recupère le hsCode
    *
    * @param      int  $id     The identifier
    *
    * @return     string
    */
    public static function gethsCode($id, $table, $primary)
    {
        if ($id == 0) {
            return;
        }
        return \em_db::one('SELECT specif_hsCode FROM ' . $table . ' WHERE ' . $primary . ' = ' . $id);
    }

    /**
     * Recupérer les urls des docs
     *
     * @param      int  $orderId       The order identifier
     *
     * @return     string
     */
     public static function getDocumetnsPaths($orderId)
     {
         return \em_db::row('SELECT specif_etiquette_path, specif_cn23_path, specif_delivery_slip FROM cat_commande WHERE id_commande = ' . $orderId);
     }

    /**
     * Recupérer les suivis
     *
     * @param      int  $orderId       The order identifier
     *
     * @return     string
     */
     public static function getPackageTrackingStories($orderId)
     {
         return \em_db::all('SELECT * FROM specif_colissimo_package_tracking WHERE orderId = ' . $orderId . ' ORDER BY 	eventDate DESC');
     }
}
