<?php namespace Phasty\Units\User;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use Phasty\HTTPException;
use Phasty\Models\Users;

/**
 * Class UsersTransformer
 * @package namespace Phasty\Units\User;
 */
class UsersTransformer extends TransformerAbstract
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
            'name'           => $model->name ?: null,
            'email'          => $model->email ?: null,
            'role'           => $model->role ?: null,
            'profile'        => $model->profile ?: null,
            'profile_fields' => Users::profileFields(),
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
            'email'          => $array['email'] ?: null,
            'role'           => $array['role'] ?: null,
            'profile'        => $array['profile'] ?: null,
            'profile_fields' => Users::profileFields(),
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
                'dev' => 'User model is empty'
            )
        );
    }
}
