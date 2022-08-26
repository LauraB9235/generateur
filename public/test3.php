<?php
namespace Addon\test1;
/**
* Action du BO "test3"
*
* @author [Name] <[name]@medialibs.com>
*
* @since 2021-07-06
*/

class test3
{
    /**
     * Point d'entrée de l'action
     *
     * @return null
     */
    public function start()
    {
        \em_output::echoAndExit('Hello world');
    }
}
