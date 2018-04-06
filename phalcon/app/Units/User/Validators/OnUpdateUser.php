<?php namespace Phasty\Units\User\Validators;

use League\Pipeline\StageInterface;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phasty\HTTPException;
use Phasty\Models\Users;
use Phasty\SanitizeInput;

class OnUpdateUser extends Validation implements StageInterface
{

    public function initialize()
    {
        $this->add('name', new PresenceOf([
            'message' => 'The name is required'
        ]));

        $this->add('email', new PresenceOf([
            'message' => 'The email is required'
        ]));

        $this->add('email', new Email([
            'message' => 'The email is not valid'
        ]));

        $this->add('role', new PresenceOf([
            'message' => 'The role is required'
        ]));

        $this->add('profile', new PresenceOf([
            'message' => 'The profile is required'
        ]));

        $this->setFilters('name', ['trim']);
        $this->setFilters('email', ['trim']);
        $this->setFilters('role', ['trim']);

    }

    /**
     * @param \stdClass $payload
     * @return mixed $payload
     * @throws HTTPException
     */
    public function __invoke($payload)
    {
        $data = (array) $this->getDI()->get('store')->requestBody;
        if ( ! is_array($data) || empty($data)) {
            throw new HTTPException(
                'empty request.',
                202,
                [
                    'dev' => 'there is no data in requestBody',
                ]
            );
        }
        $credentials            = (new SanitizeInput($data))->sanitize(Users::allowedFields());
        $credentials['profile'] = (new SanitizeInput($credentials['profile']))->sanitize(Users::profileFields());

        $messages = $this->validate($credentials);

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

        $credentials['name']    = $this->getValue('name');
        $credentials['email']   = $this->getValue('email');
        $credentials['role']    = $this->getValue('role');
        $credentials['profile'] = json_encode($credentials['profile']);

        $payload->credentials   = $credentials;

        return $payload;
    }
}