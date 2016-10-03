<?php
/**
 * Copyright © 2016 Chindit
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * First generated : 10/03/2016 at 18:41
 */

declare(strict_types=1);

/**
 * Class JsonObjectMapper
 */
class JsonObjectMapper{
    /**
     * Map any object to it's JSON counterpart.  Any kind of object is accepted if «is_object()» is true
     *
     * @param $object object Object to map
     * @param array $allowedValues array List of allowed values.  If omitted, all
     * @param array $rejectedValues array List of rejected values.  If omitted, none
     * @return string JSON result
     */
    public function mapObjectToJson($object, array $allowedValues = array(), array $rejectedValues = array()) : string{
        if(!is_object($object))
            return '';
        if(!empty($allowedValues))
            $allowedValues = $this->prepareValuesFiltering($allowedValues);
        if(!empty($rejectedValues))
            $rejectedValues = $this->prepareValuesFiltering($rejectedValues);
        $methodsList = get_class_methods($object);
        $json = [];
        foreach($methodsList as $method){
            if(strrpos($method, 'get') === 0 && ((!empty($allowedValues) && in_array($method, $allowedValues)) || (!empty($rejectedValues) && !in_array($method, $rejectedValues)) || (empty($allowedValues) && empty($rejectedValues)))){
                $parameterName = strtolower(substr($method, 3));
                $json[$parameterName] = (is_object($object->$method())) ? $this->mapObjectToJson($object->$method(), $allowedValues, $rejectedValues) : $object->$method();
            }
        }
        return json_encode($json);
    }

    /**
     * Map an array of objects.  Meta-function of «mapObjectToJson»
     *
     * @param array $objects Objects to map
     * @return array List of JSON mapped objects
     */
    public function mapObjectsToJson(array $objects, array $allowedValues = array(), array $rejectedValues = array()) : array{
        $result = [];
        foreach($objects as $object)
            $result[] = $this->mapObjectToJson($object, $allowedValues, $rejectedValues);
        return $result;
    }

    /**
     * Set filters to the standard method names
     *
     * @param array $values Array of string.  Name of methods
     * @return array Array of string.  Standardized method names
     */
    private function prepareValuesFiltering(array $values) : array{
        $correctValues = [];
        foreach($values as $value){
            $correctValues[] = (!strrpos($value, 'get')) ? $value : 'get'.ucfirst($value);
        }
        return $correctValues;
    }
}
