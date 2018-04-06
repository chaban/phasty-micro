<?php namespace Phasty\Units\Category;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use Phasty\HTTPException;

/**
 * Class CategoriesTransformer
 * @package namespace Phasty\Units\Category;
 */
class CategoriesTransformer extends TransformerAbstract
{

    /**
     * Transform the entity
     * @param  $model
     *
     * @return array
     * @throws HTTPException
     */
    public function transform($model)
    {
        if (is_array($model)) {
            return $this->asArray($model);
        }

        return [
            'id'             => $model->id ?: $this->alert(),
            'name'             => $model->name ?: null,
            'attr'             => $model->attr ?: null,

            'created_at'     => isset($model->created_at) ? Carbon::createFromFormat('Y-m-d H:i:s',
                $model->created_at)->diffForHumans() : null,
            'updated_at'     => isset($model->updated_at) ? Carbon::createFromFormat('Y-m-d H:i:s',
                $model->updated_at)->diffForHumans() : null,
        ];
    }

    private function asArray($array)
    {
        return [
            'id'             => $array['id'] ?: $this->alert(),
            'name'           => $array['name'] ?: null,
            'attr'           => $array['attr'] ?: null,

            'created_at'     => isset($array['created_at']) ? Carbon::createFromFormat('Y-m-d H:i:s',
                $array['created_at'])->diffForHumans() : null,
            'updated_at'     => isset($array['updated_at']) ? Carbon::createFromFormat('Y-m-d H:i:s',
                $array['updated_at'])->diffForHumans() : null,
        ];
    }

    private function alert()
    {
        throw new HTTPException(
            'Empty model',
            400,
            array(
                'dev' => 'Category model is empty'
            )
        );
    }
}
