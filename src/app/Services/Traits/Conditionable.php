<?php
declare(strict_types=1);

namespace App\Services\Traits;

use InvalidArgumentException;
use Illuminate\Http\Request;


trait Conditionable
{
    private array $escape_chars = [",", "/"];

    /**
     *
     * @param  string|null  $conditionQuery
     * @return array
     */
    public function conditionQueryToArray($conditionQuery = ''): array
    {
        if (is_null($conditionQuery) || '' === $conditionQuery) {
            return [];
        }

        //$temp = current(csv2array($conditionQuery));
        $temp = explode(",", $conditionQuery);

        $conditions = [];
        foreach ($temp as $val) {
            if (null == $val) {
                continue;
            }
            foreach($this->escape_chars as $char){
                $val = str_replace(urlencode($char), $char, $val);
            }
            $pos = stripos($val, ':');
            if (false === $pos) {
                continue;
            }

            $key   = substr($val, 0, $pos);
            $value = ltrim(stristr($val, ':'), ':');
            if (is_json($value)) {
                $value = json_decode($value);
            }
            if($key == 'clear_condition'){
                return [];
            }

            $conditions[$key] = $value;
        }

        return $conditions;
    }

    /**
     *
     * @param  array|null  $conditionAttributes
     * @return string
     * @throws InvalidArgumentException
     */
    public function conditionsToQuery($conditionAttributes = [], $use_conditions_array = false): string
    {
        if (is_null($conditionAttributes) || 0 == count($conditionAttributes)) {
            return '';
        }

        if (! $this->request instanceof Request) {
            throw new InvalidArgumentException('This service must have $request parameter of the \Illuminate\Http\Request instance.');
        }

        if($this->request->input('clear_condition')){
            return 'clear_condition:1';
        }

        $conditions = [];
        if($use_conditions_array){
            $conditions = $conditionAttributes;
        }else{
            foreach ($conditionAttributes as $conditionAttribute) {
                if ($this->request->filled($conditionAttribute)) {
                    $conditions[$conditionAttribute] = $this->request->input($conditionAttribute);
                }
            }
        }

        $conditions = array_filter($conditions, function ($value) {
            if (is_null($value)) {
                return false;
            } elseif (is_string($value) && '' === trim($value)) {
                return false;
            /*
            } elseif (is_array($value) && 0 == count(array_filter($value))) {
                return false;
            */
            }
            return true;
        });
        if (0 == count($conditions)) {
            return '';
        }

        $conditions = array_map(function ($value, $key) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
            foreach($this->escape_chars as $char){
                $value = str_replace($char, urlencode(urlencode($char)), $value);
            }

            return $key . ':' . $value;
        }, $conditions, array_keys($conditions));

        $conditionQuery = implode(',', $conditions);

        return $conditionQuery;
    }
}
