<?php namespace Phasty\Units\{upperName};

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use Phasty\HTTPException;

/**
 * Class {upperPlural}Transformer
 * @package namespace Phasty\Units\{upperName};
 */
class {upperPlural}Transformer extends TransformerAbstract
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
                'dev' => '{upperName} model is empty'
            )
        );
    }
}
