<?php
namespace Addon\test2;

/**
 * Gestion de la balise MX "test5"
 *
 * @author  [Laura]  <[name]@Medialibs.com>
 *
 * @since 2021-06-26
 */
 function getSpecifTagTest6($value)
 {
     $objectName = 'Addon\\test2\\mxTest6';
     \em_misc::loadPHP(__DIR__ . '/test6.class.php', $objectName);
     $obj = new $objectName();
     preg_match_all('/([a-z]*)="([^"]*)"/', $value[2], $matches, PREG_SET_ORDER);
     foreach ($matches as $params) {
         $obj->setTagParams($params[1], $params[2]);
     }
     $obj->setDevPath($path);
     return $obj->start();
 }
