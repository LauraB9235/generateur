<?php
namespace Addon\test2;
/**
* Action du BO "test"
*
* @author [Name] <[name]@medialibs.com>
*
* @since 2021-06-27
*/

class test
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
