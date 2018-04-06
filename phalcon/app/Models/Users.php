<?php
namespace Phasty\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Behavior\Timestampable;

class Users extends Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id", type="integer", length=32, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(column="name", type="string", length=255, nullable=false)
     */
    public $name;

    /**
     *
     * @var string
     * @Column(column="email", type="string", length=255, nullable=false)
     */
    public $email;

    /**
     *
     * @var string
     * @Column(column="role", type="string", length=255, nullable=false)
     */
    public $role;

    /**
     *
     * @var string
     * @Column(column="password", type="string", length=255, nullable=false)
     */
    public $password;

    /**
     *
     * @var string
     * @Column(column="profile", type="string", nullable=false)
     */
    public $profile;

    /**
     *
     * @var string
     * @Column(column="created_at", type="string", nullable=false)
     */
    public $created_at;

    /**
     *
     * @var string
     * @Column(column="updated_at", type="string", nullable=false)
     */
    public $updated_at;


    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
        $this->setSource("users");

        $this->addBehavior(
            new Timestampable(
                [
                    'beforeCreate' => [
                        'field'  => 'created_at',
                        'format' => 'Y-m-d H:i:s',
                    ],
                    'beforeUpdate' => [
                        'field'  => 'updated_at',
                        'format' => 'Y-m-d H:i:s',
                    ]
                ]
            )
        );

        $this->addBehavior(
            new Timestampable(
                [
                    'beforeCreate' => [
                        'field'  => 'updated_at',
                        'format' => 'Y-m-d H:i:s',
                    ]
                ]
            )
        );
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'users';
    }

    /**
     * @return array
     */
    public static function allowedFields()
    {
        return [
            'name', 'email', 'role', 'profile'
        ];
    }

    /**
     * @return array
     */
    public static function profileFields(){
        return ['phone', 'address', 'skype'];
    }
}
