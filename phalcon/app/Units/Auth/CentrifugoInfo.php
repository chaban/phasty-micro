<?php namespace Phasty\Units\Auth;
use Centrifugo\Centrifugo;
use League\Pipeline\StageInterface;
use Phalcon\Mvc\User\Plugin;

class CentrifugoInfo extends Plugin implements StageInterface
{
    /**
     * @param \stdClass $payload
     * @return object
     * @throws HTTPException
     * @throws \Exception
     */
    public function __invoke($payload)
    {
        /** @var Centrifugo $cent */
        $cent = $this->getDI()->get('cent');
        $timestamp = (new \DateTime())->getTimestamp();
        $temp = (array)$payload->result;
        $temp['data']['centrifugo_timestamp'] = $timestamp;
        $temp['data']['centrifugo_info'] = '';
        $temp['data']['centrifugo_token'] = $cent->generateClientToken((string)$this->auth->user()->id, $timestamp);
        $payload->result = $temp;
        return $payload;
    }
}