<?php


use Phinx\Migration\AbstractMigration;

class ProductMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('products', ['signed' => false]);
        $table->addColumn('category_id', 'integer', ['signed' => false])
            ->addColumn('name', 'string')
            ->addColumn('desc', 'text', ['null' => true])
            ->addColumn('price', 'integer', ['signed' => false])
            ->addColumn('attr', 'jsonb', ['default' => json_encode([])])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
