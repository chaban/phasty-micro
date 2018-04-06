<?php namespace Phasty\Units\Category\Read;

use League\Pipeline\StageInterface;
use Phasty\HTTPException;
use Phasty\Models\Categories;
use Phasty\Units\Category\CategoriesPresenter;
use Phasty\Phractal;

class ShowCategory implements StageInterface
{

    /**
     * @param \stdClass $payload
     * @return object
     * @throws HTTPException
     * @throws \Exception
     */
    public function __invoke($payload)
    {
        /**
         * @param $model Users
         */
        $category = Categories::findFirst($payload->id);

        if(!$category){
            throw new HTTPException(
                'Model not found.',
                400,
                array(
                    'dev' => 'Could not find category model with id ' . $payload->id,
                )
            );
        }

        return (new Phractal(new CategoriesPresenter()))->transform($category);
    }

}

