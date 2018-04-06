<?php
namespace Phasty\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Behavior\Timestampable;

class Products extends Model
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
     * @var integer
     * @Column(column="category_id", type="integer", length=32, nullable=false)
     */
    public $category_id;

    /**
     *
     * @var string
     * @Column(column="name", type="string", length=255, nullable=false)
     */
    public $name;

    /**
     *
     * @var string
     * @Column(column="desc", type="string", nullable=true)
     */
    public $desc;

    /**
     *
     * @var integer
     * @Column(column="price", type="integer", length=32, nullable=false)
     */
    public $price;

    /**
     *
     * @var string
     * @Column(column="attr", type="string", nullable=false)
     */
    public $attr;

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
        $this->setSource("products");

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
        return 'products';
    }

    /**
     * @return array
     */
    public static function allowedFields()
    {
        return [
            'name', 'category_id', 'desc', 'attr', 'price'
        ];
    }

}
