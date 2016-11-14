<?php
/**
 * DadaMovies : Copyright © 2016 Chindit
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
 * First generated : 11/14/2016 at 19:28
 */

declare(strict_types=1);

class Mapper{

    private $allowedValues = [];
    private $rejectedValues = [];

    /**
     * General entry point.  Map any object to it's counterpart
     *
     * @param $object
     * @param string $method Must be a value of MapMethods
     * @param array $allowedValues Only values that should be mapped
     * @param array $rejectedValues Only values that shouldn't be mapped
     * @return mixed
     */
    public function map($object, string $method, array $allowedValues = array(), array $rejectedValues = array()){
        $methodName = 'mapObjectTo'.$method;
        if(!method_exists($this, $methodName))
            return false;
        if(!empty($allowedValues))
            $this->allowedValues = $this->prepareValuesFiltering($allowedValues);
        if(!empty($rejectedValues))
            $this->rejectedValues = $this->prepareValuesFiltering($rejectedValues);
        return $this->$methodName($object);
    }

    /**
     * Map any object to it's JSON counterpart.  Any kind of object is accepted if «is_object()» is true
     *
     * @param $object object Object to map
     * @return string JSON result
     */
    private function mapObjectToJson($object) : string{
        $arrayObject = $this->mapObjectToArray($object);
        return json_encode($arrayObject);
    }

    /**
     * Map the object to an array
     * @param $object
     * @return array
     */
    private function mapObjectToArray($object) : array{
        $result = [];
        if(is_array($object))
            foreach($object as $item)
                $result[] = $this->mapObjectToArray($item);
        else
            return $this->mapObject($object);
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
            $correctValues[] = (strrpos($value, 'get') === 0) ? $value : 'get'.ucfirst($value);
        }
        return $correctValues;
    }

    /**
     * Map an object to an array
     * @param object $object Input object
     * @return array
     */
    private function mapObject($object) : array{
        $methodsList = get_class_methods($object);
        $outputArray = [];
        foreach($methodsList as $method){
            if(strrpos($method, 'get') === 0 && ((!empty($this->allowedValues) && in_array($method, $this->allowedValues)) || (!empty($this->rejectedValues) && !in_array($method, $this->rejectedValues)) || (empty($this->allowedValues) && empty($this->rejectedValues)))){
                $parameterName = strtolower(substr($method, 3));
                if($object->$method() instanceof \DateTimeZone)
                    continue;
                $outputArray[$parameterName] = (is_object($object->$method())) ? $this->mapObject($object->$method()) : $object->$method();
            }
        }
        return $outputArray;
    }
}

/**
 * Class MapMethods
 * Allowed methods to map an object into
 *
 * @package Dada\ApiBundle\Services
 */
abstract class MapMethods{
    const Array = 'Array';
    const JSON = 'Json';
}
