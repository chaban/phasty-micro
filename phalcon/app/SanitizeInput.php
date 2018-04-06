<?php namespace Phasty;

class SanitizeInput
{
    private $data;

    /**
     * SanitizeInput constructor.
     * @param array|null $data
     * @throws HTTPException
     */
    public function __construct(array $data = null)
    {
        if(!$data){
            throw new HTTPException(
                'Request data not provided for sanitizer',
                403,
                array(
                    'dev'          => 'There is not data to sanitize',
                    'internalCode' => 'NF1000',
                )
            );
        }
        $this->data = $data;
    }

    /**
     * Sanitizes the data of a request. This is a superior version of php built-in array_filter() as it preserves
     * FALSE and NULL values as well.
     *
     * @param array $fields a list of fields to be checked in the Dot-Notation (e.g., ['data.name', 'data.description'])
     *
     * @return array an array containing the values if the field was present in the request and the intersection array
     */
    public function sanitize(array $fields)
    {
        $search = [];
        foreach ($fields as $field) {
            // create a multidimensional array based on $fields
            // which was submitted as DOT notation (e.g., data.name)
            $this->array_set($search, $field, true);
        }
        // check, if the keys exist in both arrays
        $data = $this->recursiveArrayIntersectKey($this->data, $search);
        return $data;
    }
    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $value
     * @return array
     */
    private function array_set(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }
        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (! isset($array[$key]) || ! is_array($array[$key])) {
                $array[$key] = [];
            }
            $array = &$array[$key];
        }
        $array[array_shift($keys)] = $value;
        return $array;
    }
    /**
     * Recursively intersects 2 arrays based on their keys.
     *
     * @param array $a first array (that keeps the values)
     * @param array $b second array to be compared with
     *
     * @return array an array containing all keys that are present in $a and $b. Only values from $a are returned
     */
    private function recursiveArrayIntersectKey(array $a, array $b)
    {
        $a = array_intersect_key($a, $b);
        foreach ($a as $key => &$value) {
            if (is_array($value) && is_array($b[$key])) {
                $value = $this->recursiveArrayIntersectKey($value, $b[$key]);
            }
        }
        return $a;
    }

}