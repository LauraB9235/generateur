<?php
namespace Addon\test2;

/**
 * Gestion de la balise MX ""
 *
 * @author  [laura]  <[name]@Medialibs.com>
 *
 * @since 2021-07-06
 */
 function getSpecifTag($value)
 {
     $objectName = 'Addon\\test2\\mx';
     \em_misc::loadPHP(__DIR__ . '/.class.php', $objectName);
     $obj = new $objectName();
     preg_match_all('/([a-z]*)="([^"]*)"/', $value[2], $matches, PREG_SET_ORDER);
     foreach ($matches as $params) {
         $obj->setTagParams($params[1], $params[2]);
     }
     $obj->setDevPath($path);
     return $obj->start();
 }
