<?php namespace Phasty\Units\Category\Write;

use League\Pipeline\StageInterface;
use ParagonIE\EasyDB\EasyDB;
use Phalcon\Mvc\User\Component;
use Phasty\HTTPException;

final class CreateCategory extends Component implements StageInterface
{

    /**
     * @param mixed $payload
     * @return int
     * @throws HTTPException
     */
    public function __invoke($payload)
    {
        /* @var EasyDB */
        $edb = $this->getDI()->get('edb');
        $payload->credentials['id'] = $payload->credentials['id'] ?: null;

        if ($payload->id = $this->create($edb, $payload->credentials)) {
            return $payload->id;
        } else {
            throw new HTTPException(
                'could not create category.',
                400,
                array(
                    'dev' => '',
                )
            );
        }
    }

    private function create(EasyDB $edb, $data): int
    {
        $edb->insert('categories', [
            'parent_id' => $data['id'],
            'name'      => $data['name'],
            'attr'      => json_encode([])
        ]);

        return $this->setCategoryPaths($edb, $edb->getPdo()->lastInsertId(), $data);
    }

    private function setCategoryPaths($edb, $id, $data): int
    {
        $descendant = (int)$id;
        $paretn_id  = (int)$data['id'];

        $ancestor = $paretn_id ? $paretn_id : $descendant;

        $edb->run('
            INSERT INTO paths (ancestor_id, descendant_id, depth)
            SELECT ancestor_id, CAST(? AS INTEGER), depth+1
            FROM paths
            WHERE descendant_id = CAST(? AS INTEGER)
            UNION ALL SELECT ?, ?, 0
        ', $descendant, $ancestor, $descendant, $descendant);

        return $id;
    }
}

