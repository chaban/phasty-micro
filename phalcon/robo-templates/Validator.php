<?php namespace Phasty\Units\{upperName}\Validators;

use League\Pipeline\StageInterface;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phasty\HTTPException;
use Phasty\Models\{upperPlural};
use Phasty\SanitizeInput;

class On{action}{upperName} extends Validation implements StageInterface
{

    public function initialize()
    {

    }

    /**
     * @param \stdClass $payload
     * @return mixed $payload
     * @throws HTTPException
     */
    public function __invoke($payload)
    {
        $data = (array)$this->getDI()->get('store')->requestBody;

        if ( ! is_array($data) || empty($data)) {
            throw new HTTPException(
                'empty request.',
                202,
                [
                    'dev' => 'there is no data in requestBody',
                ]
            );
        }

        $data = (new SanitizeInput($data))->sanitize({upperPlural}::allowedFields());

        $messages = $this->validate($data);

        $output = '';
        if (count($messages)) {
            foreach ($messages as $key => $message) {
                $output .= '  ' . $message . ';  ';
            }

            return new HTTPException(
                'validation failed with message: ' . $output,
                202,
                [
                    'dev' => '',
                ]
            );
        }

        $credentials             = [];

        $payload->credentials = $credentials;

        return $payload;
    }
}

