<?php
namespace Phasty\Models;

use Phalcon\Mvc\Model;

class Categories extends Model
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
     * @Column(column="attr", type="string", nullable=false)
     */
    public $attr;

    /**
     *
     * @var integer
     * @Column(column="parent_id", type="integer", length=32, nullable=true)
     */
    public $parent_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
        $this->setSource("categories");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'categories';
    }

    public static function allowedFields() :array
    {
        return [
            'id', 'name', 'attr'
        ];
    }

}
