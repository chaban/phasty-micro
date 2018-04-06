<?php


use Phalcon\Security;
use Phinx\Seed\AbstractSeed;
use Faker\Factory as FakerFactory;

class AUserSeeder extends AbstractSeed
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
        $sec  = new Security();
        $data = [
            [
                'name'     => 'admin',
                'email'    => 'admin@example.com',
                'role'     => 'admin',
                //'password' => '$2y$08$STRNUkRETmhIRDlVQ3d1M.OTjUfR1rbFHemH/Rpi.NUfQucbE644i',
                'password' => $sec->hash('admin'),
                'profile'  => json_encode(['phone' => 1234567, 'address' => 'Some address']),
                'created_at' => $faker->dateTime('now')->format('Y-m-d H:i:s'),
                'updated_at' => $faker->dateTime('now')->format('Y-m-d H:i:s')
            ],
            [
                'name'     => 'user',
                'email'    => 'user@example.com',
                'role'     => 'user',
                //'password' => '$2y$08$T0JPRThSZzc1YWNEV1k0e.XAN15YtwApI9eboR3nTOssXjvdGgQAu',
                'password' => $sec->hash('password'),
                'profile'  => json_encode(['phone' => 7654321, 'address' => 'User not a bomg']),
                'created_at' => $faker->dateTime('now')->format('Y-m-d H:i:s'),
                'updated_at' => $faker->dateTime('now')->format('Y-m-d H:i:s')
            ]
        ];

        $entity = $this->table('users');
        $entity->insert($data)->save();
    }
}
