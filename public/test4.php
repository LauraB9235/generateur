<?php
namespace Addon\test2;
/**
* Action du BO "test4"
*
* @author [Name] <[name]@medialibs.com>
*
* @since 2021-07-06
*/

class test4
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
