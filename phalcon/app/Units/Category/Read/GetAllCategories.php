<?php namespace Phasty\Units\Category\Read;

use League\Pipeline\StageInterface;
use Phalcon\Mvc\User\Plugin;
use ParagonIE\EasyDB\EasyDB;

class GetAllCategories extends Plugin implements StageInterface
{

    /**
     * Process the payload.
     *
     * @param mixed $payload
     *
     * @return mixed
     */
    public function __invoke($payload)
    {
        /* @var EasyDB */
        $edb   = $this->getDI()->get('edb');
        $param = isset($payload->getAs) ?: '';
        $param = $this->request->getQuery('getAs', 'string', $param);

        switch ((string)$param) {
            case "all":
                return $this->all($edb);
                break;
            default:
                return $this->tree($edb);
        }
    }

    public function tree(EasyDB $edb)
    {
        $all = $this->all($edb);
        return $this->buildTree($all);
    }

    public function all(EasyDB $edb)
    {
        return $edb->run('
            SELECT id, name, parent_id, attr FROM categories');
    }

    private function buildTree(array &$elements, $parentId = null) {

        $temp = array();

        foreach ($elements as &$element) {

            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $temp[] = $element;
                unset($element);
            }
        }
        return $temp;
    }
}

