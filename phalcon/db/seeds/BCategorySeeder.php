<?php


use Phinx\Seed\AbstractSeed;
use Faker\Factory as FakerFactory;

class BCategorySeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $faker = FakerFactory::create();
        foreach (range(1, 10) as $key => $value) {
            $data = [
                'name' => $faker->sentence(2, true),
                'attr' => json_encode([['name' => $faker->sentence(2, true), 'suffix' => $faker->word],
                                       ['name' => $faker->sentence(2, true), 'suffix' => $faker->word]])
            ];
            $entity = $this->table('categories');
            $entity->insert($data)->save();

            $id = $this->getAdapter()->getConnection()->lastInsertId();
            $this->setCategoryPaths($id);
        }
    }

    private function setCategoryPaths($id)
    {
        $descendant = intval($id);

        $ancestor = $descendant;

        $statement = $this->getAdapter()->getConnection()->prepare('
            INSERT INTO paths (ancestor_id, descendant_id, depth)
            SELECT ancestor_id, CAST(:des AS INTEGER), depth+1
            FROM paths
            WHERE descendant_id = :anc
            UNION ALL SELECT :des, :des, 0
        ');
        $statement->execute([':des' => intval($descendant), ':anc' => intval($ancestor)]);
    }
}
