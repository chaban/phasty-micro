<?php namespace Phasty\Units\Product\Validators;

use League\Pipeline\StageInterface;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phasty\HTTPException;
use Phasty\Models\Products;
use Phasty\SanitizeInput;

class OnUpdateProduct extends Validation implements StageInterface
{

    public function initialize()
    {
        $this->add('name', new PresenceOf([
            'message' => 'The name is required'
        ]));

        $this->add('price', new PresenceOf([
            'message' => 'The price is required'
        ]));

        $this->add('price', new Validation\Validator\Numericality([
            'message' => 'The price must be number'
        ]));

        $this->add('category_id', new PresenceOf([
            'message' => 'The category_id is required'
        ]));

        $this->add('category_id', new Validation\Validator\Numericality([
            'message' => 'The category_id must be number'
        ]));

        $this->add('attr', new PresenceOf([
            'message' => 'The role is required'
        ]));

        $this->setFilters('name', ['trim']);
        $this->setFilters('price', ['trim']);
        $this->setFilters('category_id', ['trim']);
        $this->setFilters('desc', ['trim', 'string']);
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

        $data = (new SanitizeInput($data))->sanitize(Products::allowedFields());

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
        $credentials['category_id'] = $this->getValue('category_id');
        $credentials['price'] = $this->getValue('price');
        $credentials['attr'] = json_encode($this->getValue('attr'));
        $credentials['desc'] = $this->getValue('desc');
        $payload->credentials = $credentials;

        return $payload;
    }
}

