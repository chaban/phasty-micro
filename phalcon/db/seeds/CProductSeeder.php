<?php


use Phinx\Seed\AbstractSeed;
use Faker\Factory as FakerFactory;

class CProductSeeder extends AbstractSeed
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
        $con   = $this->getAdapter()->getConnection();
        $count = $this->getCountCategories($con);
        foreach (range(1, 20) as $key => $value) {
            $category = $this->findCategory($faker->numberBetween(1, $count), $con);
            $data  = [
                'category_id' => $category['id'],
                'name'        => $faker->words(rand(1, 2), true),
                'desc'        => $faker->paragraphs($nb = 3, $asText = true),
                'price'       => $faker->numberBetween(200, 900000),
                'attr'        => $this->generateAttributes($category, $faker),
                'created_at' => $faker->dateTime('now')->format('Y-m-d H:i:s'),
                'updated_at' => $faker->dateTime('now')->format('Y-m-d H:i:s')
            ];
            $entity = $this->table('products');
            $entity->insert($data)->save();
        }
    }

    private function getCountCategories($con)
    {
        $sql    = 'SELECT count(*) FROM categories';
        $result = $con->prepare($sql);
        $result->execute();

        return $result->fetchColumn();
    }

    private function findCategory($id, $con)
    {
        $result = $con->prepare("SELECT * FROM categories WHERE id=? LIMIT 1");
        $result->execute([$id]);

        return $result->fetch();
    }

    private function generateAttributes($category, $faker)
    {
        $temp               = [];
        $categoryAttributes = json_decode($category['attr']);
        foreach ($categoryAttributes as $key => $categoryAttribute) {
            $temp[$key]['name']   = $categoryAttribute->name;
            $temp[$key]['value']  = $faker->words(rand(1, 2), true);
            $temp[$key]['suffix'] = $categoryAttribute->suffix;
        }

        return json_encode($temp);
    }
}
