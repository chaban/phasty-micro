<?php namespace Phasty\Units\Category\Write;

use League\Pipeline\StageInterface;
use Phalcon\Mvc\User\Plugin;
use Phasty\HTTPException;
use Phasty\Models\Categories;

class DeleteCategory extends Plugin implements StageInterface
{

    /**
     * @param mixed $payload
     * @return boolean
     * @throws HTTPException
     */
    public function __invoke($payload)
    {
        /* @var EasyDB */
        $edb = $this->getDI()->get('edb');
        try {
            $this->deleteCategoryPaths($edb, $payload);
            $category = Categories::findFirst($payload->id);
            return $category->delete();
        } catch (\Exception $e) {
            throw new HTTPException(
                'could not delete category.',
                400,
                array(
                    'dev' => $e->getMessage(),
                )
            );
        }
    }

    private function deleteCategoryPaths($edb, $payload) :void
    {
        $edb->run('
            DELETE FROM paths
            WHERE descendant_id IN (
                SELECT descendant_id FROM (
                    SELECT descendant_id FROM paths
                    WHERE ancestor_id = CAST(? AS INTEGER)
                ) as tmptable
            )
        ', (int)$payload->id);
    }
}

