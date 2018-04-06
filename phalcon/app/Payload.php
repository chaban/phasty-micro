<?php namespace Phasty;

class Payload
{
    // @param array $data
    public function create(array $data)
    {
        if(! $data || ! is_array($data) || empty($data)){
            throw new HTTPException(
                'Wrong data',
                500,
                [
                    'dev'          => "Data for payload not provided."
                ]);
        }

        $payload = new \stdClass();

        foreach ($data as $key => $value){
            $payload->$key = $value;
        }
        return $payload;
    }
}