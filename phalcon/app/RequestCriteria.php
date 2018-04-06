<?php namespace Phasty;

use League\Pipeline\StageInterface;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\User\Plugin;

class RequestCriteria extends Plugin implements StageInterface
{

    /**
     * Apply criteria in query repository
     *
     * @param         Builder|Model $model_name
     * @param array $fieldsSearchable
     * @param \stdClass $payload
     *
     * @return mixed
     * @throws HTTPException
     */
    public function apply($model_name, array $fieldsSearchable, $payload)
    {
        $search       = $this->request->getQuery($this->config->searchCriteria->params->search, null, null);
        $searchFields = $this->request->getQuery($this->config->searchCriteria->params->searchFields, null);
        $filter       = $this->request->getQuery($this->config->searchCriteria->params->filter, null, null);
        $orderBy      = $this->request->getQuery($this->config->searchCriteria->params->orderBy, null, null);
        $sortedBy     = $this->request->getQuery($this->config->searchCriteria->params->sortedBy, null, 'ASC');
        $with         = $this->request->getQuery($this->config->searchCriteria->params->with, null, null);
        $searchJoin   = $this->request->getQuery($this->config->searchCriteria->params->searchJoin, null, null);
        $sortedBy     = ! empty($sortedBy) ? strtoupper($sortedBy) : 'ASC';

        $builder = new Query\Builder();
        $builder->from($model_name);

        if ($search && is_array($fieldsSearchable) && count($fieldsSearchable)) {
            $searchFields       = is_array($searchFields) || is_null($searchFields) ? $searchFields : explode(';',
                $searchFields);
            $fields             = $this->parserFieldsSearch($fieldsSearchable, $searchFields);
            $isFirstField       = true;
            $searchData         = $this->parserSearchData($search);
            $search             = $this->parserSearchValue($search);
            $modelForceAndWhere = strtolower($searchJoin) === 'and';

            foreach ($fields as $field => $condition) {
                if (is_numeric($field)) {
                    $field     = $condition;
                    $condition = "=";
                }
                $value     = null;
                $condition = trim(strtolower($condition));
                if (isset($searchData[$field])) {
                    $value = ($condition == "like" || $condition == "ilike") ? "%{$searchData[$field]}%" : $searchData[$field];
                } else {
                    if ( ! is_null($search)) {
                        $value = ($condition == "like" || $condition == "ilike") ? "%{$search}%" : $search;
                    }
                }
                $relation = null;
                if (stripos($field, '.')) {
                    $explode  = explode('.', $field);
                    $field    = array_pop($explode);
                    $relation = implode('.', $explode);
                }

                if ($isFirstField || $modelForceAndWhere) {
                    if ( ! is_null($value)) {
                        if ( ! is_null($relation)) {
                            //todo rewrite for phalcon builder
                            $builder->whereHas($relation, function ($query) use ($field, $condition, $value) {
                                $query->where($field, $condition, $value);
                            });
                        } else {
                            $builder->where("$model_name" . '.' . "$field $condition :firstValue:", [
                                'firstValue' => $value
                            ]);
                        }
                        $isFirstField = false;
                    }
                } else {
                    if ( ! is_null($value)) {
                        if ( ! is_null($relation)) {
                            //todo rewrite for phalcon builder
                            $builder->orWhereHas($relation, function ($query) use ($field, $condition, $value) {
                                $query->where($field, $condition, $value);
                            });
                        } else {
                            $builder->orWhere("$model_name" . '.' . "$field $condition :secondValue:", [
                                'secondValue' => $value
                            ]);
                        }
                    }
                }
            }
        }
        if (isset($orderBy) && ! empty($orderBy)) {
            $split = explode('|', $orderBy);
            //todo rewrite for phalcon builder
            if (count($split) > 1) {
                /*
                 * ex.
                 * products|description -> join products on current_table.product_id = products.id order by description
                 *
                 * products:custom_id|products.description -> join products on current_table.custom_id = products.id order
                 * by products.description (in case both tables have same column name)
                 */

                $sortTable  = $split[0];
                $sortColumn = $split[1];
                $split      = explode(':', $sortTable);
                if (count($split) > 1) {
                    $sortTable = $split[0];
                    $keyName   = $model_name . '.' . $split[1];
                } else {
                    /*
                     * If you do not define which column to use as a joining column on current table, it will
                     * use a singular of a join table appended with _id
                     *
                     * ex.
                     * products -> product_id
                     */
                    $prefix  = str_singular($sortTable);
                    $keyName = $model_name . '.' . $prefix . '_id';
                }
                $builder
                    ->leftJoin($this->getModelClassName($sortTable), "$keyName =  $sortTable" . '.id')
                    ->orderBy("$sortColumn $sortedBy")
                    ->columns($model_name . '.*');
            } else {
                $builder->orderBy("$orderBy $sortedBy");
            }
        }
        if (isset($filter) && ! empty($filter)) {
            if (is_string($filter)) {
                $filter = explode(';', $filter);
            }
            $builder->columns($filter);
        }
        if ($with) {
            $with = explode(';', $with);
            foreach ($with as $key => $tableName) {
                $builder->addFrom($this->getModelClassName($tableName));
            }
        }

        $payload->builder = $builder;

        return $payload;
    }

    /**
     * @param $search
     *
     * @return array
     * @throws HTTPException
     */
    protected function parserSearchData($search)
    {
        $searchData = [];
        if (stripos($search, ':')) {
            $fields = explode(';', $search);
            foreach ($fields as $row) {
                try {
                    list($field, $value) = explode(':', $row);
                    $searchData[$field] = $value;
                } catch (\Exception $e) {
                    throw new HTTPException(
                        'search data not accepted.',
                        400,
                        array(
                            'dev' => $e->getMessage(),
                        )
                    );
                }
            }
        }

        return $searchData;
    }

    /**
     * @param $search
     *
     * @return null
     */
    protected function parserSearchValue($search)
    {
        if (stripos($search, ';') || stripos($search, ':')) {
            $values = explode(';', $search);
            foreach ($values as $value) {
                $s = explode(':', $value);
                if (count($s) == 1) {
                    return $s[0];
                }
            }

            return null;
        }

        return $search;
    }

    protected function parserFieldsSearch(array $fields = [], array $searchFields = null)
    {
        if ( ! is_null($searchFields) && count($searchFields)) {
            $acceptedConditions = $this->config->searchCriteria->acceptedConditions->toArray();
            $originalFields     = $fields;
            $fields             = [];
            foreach ($searchFields as $index => $field) {
                $field_parts    = explode(':', $field);
                $temporaryIndex = array_search($field_parts[0], $originalFields);
                if (count($field_parts) == 2) {
                    if (in_array($field_parts[1], $acceptedConditions)) {
                        unset($originalFields[$temporaryIndex]);
                        $field                  = $field_parts[0];
                        $condition              = $field_parts[1];
                        $originalFields[$field] = $condition;
                        $searchFields[$index]   = $field;
                    }
                }
            }
            foreach ($originalFields as $field => $condition) {
                if (is_numeric($field)) {
                    $field     = $condition;
                    $condition = "=";
                }
                if (in_array($field, $searchFields)) {
                    $fields[$field] = $condition;
                }
            }
            if (count($fields) == 0) {
                throw new HTTPException(
                    'The fields you specified cannot be searched.',
                    400,
                    array(
                        'dev' => 'fields in search criteria not accepted.' . implode(',', $searchFields),
                    )
                );
            }
        }

        return $fields;
    }

    protected function getModelClassName($name)
    {
        $name      = ucfirst($name);
        $className = 'Phasty\Models\\' . $name;

        return $className;
    }

    /**
     * @param \stdClass $payload
     * @return Query
     * @throws HTTPException
     */
    public function __invoke($payload = null)
    {
        if ( ! $payload || ! $payload->model || ! $payload->fieldsSearchable) {
            throw new HTTPException(
                'Bad request criteria',
                400,
                [
                    'dev' => "Payload for request criteria not provided."
                ]);
        }

        return $this->apply($payload->model, $payload->fieldsSearchable, $payload);
    }
}