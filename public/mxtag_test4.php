<?php
namespace Addon\test2;

/**
 * Gestion de la balise MX "test3"
 *
 * @author  [Laura]  <[name]@Medialibs.com>
 *
 * @since 2021-06-24
 */
 function getSpecifTagTest4($value)
 {
     $objectName = 'Addon\\test2\\mxTest4';
     \em_misc::loadPHP(__DIR__ . '/test4.class.php', $objectName);
     $obj = new $objectName();
     preg_match_all('/([a-z]*)="([^"]*)"/', $value[2], $matches, PREG_SET_ORDER);
     foreach ($matches as $params) {
         $obj->setTagParams($params[1], $params[2]);
     }
     $obj->setDevPath($path);
     return $obj->start();
 }
