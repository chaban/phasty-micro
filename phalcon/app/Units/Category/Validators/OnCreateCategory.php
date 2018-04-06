<?php namespace Phasty\Units\Category\Validators;

use League\Pipeline\StageInterface;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phasty\HTTPException;
use Phasty\Models\Categories;
use Phasty\SanitizeInput;

class OnCreateCategory extends Validation implements StageInterface
{

    public function initialize()
    {
        $this->add('name', new PresenceOf([
            'message' => 'The name is required'
        ]));

        $this->add('id', new PresenceOf([
            'message' => 'The id is required'
        ]));

        $this->add("id", new Validation\Validator\Numericality([
            "message" => ":field is not numeric"
        ]));

        $this->setFilters('name', ['trim']);
        $this->setFilters('id', ['trim']);
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

        $data = (new SanitizeInput($data))->sanitize(Categories::allowedFields());

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
        $credentials['name'] = $this->getValue('name');
        $credentials['id'] = $this->getValue('id');
        $payload->credentials = $credentials;

        return $payload;
    }
}

