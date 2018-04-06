<?php namespace Phasty\Plugs;

use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use Phalcon\Mvc\User\Plugin;

class SetUpStore extends Plugin implements MiddlewareInterface
{

    public function beforeHandleRoute()
    {
        $this->store->currentUser = null;
        $this->store->requestBody = $this->getInput();
    }

    /**
     * Calls the middleware
     *
     * @param Micro $app
     *
     * @returns bool
     */
    public function call(Micro $app)
    {
        return true;
    }

    private function getInput() :array
    {
        $temp = $this->request->getJsonRawBody(true);
        if (is_array($temp) && ! empty($temp)) {
            return $temp;
        }
        parse_str($this->request->getRawBody(), $temp);

        return $temp;
    }
}