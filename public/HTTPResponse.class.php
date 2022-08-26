<?php
namespace Addon\test2;


/**
 * API Rest
 *
 * @author  [laura]  <[name]@Medialibs.com>
 *
 * @since 2021-07-06
 */
class HTTPResponse
{

    protected $statutMessage = array(
        200 => 'OK',
        401 => 'Unauthorized',
        403 => 'Forbidden',

    );

    protected $codesMessage = array(
        '0001' => 'Authentification failed',
        '0002' => 'Acces Forbidden',
    );

   /**
    * Construction d'une reponse
    *
    * @param  int $statut               [description]
    * @param  int $code                 [description]
    * @param  string $supplementMessage [description]
    *
    * @return array                    [description]
    */
    public function response($statut, $code = null, $supplementMessage = null)
    {
        $message = $statut . ' ' . $this->statutMessage[$statut];
        if (!is_null($supplementMessage)) {
            $message = $supplementMessage;
        }
        if (!is_null($code)) {
            $message .= ' ' . $this->codesMessage[$code];
        }
        return array("code" => $code, "message" => $message);
    }
}
