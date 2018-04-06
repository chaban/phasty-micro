<?php


use Phinx\Migration\AbstractMigration;

class CategoryMigration extends AbstractMigration
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
        $table = $this->table('categories', ['signed' => false]);
        $table->addColumn('name', 'string')
            ->addColumn('attr', 'jsonb', ['default' => json_encode([])])
            ->addColumn('parent_id', 'integer', ['signed' => false, 'null' => true])
            ->create();
        $table->addForeignKey('parent_id', 'categories', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])->save();
        $table = $this->table('paths', ['signed' => false]);
        $table->addColumn('ancestor_id', 'integer', ['signed' => false])
            ->addColumn('descendant_id', 'integer', ['signed' => false])
            ->addColumn('depth', 'integer', ['signed' => false])
            ->create();
    }
}
