<?php
namespace ma3obblu\gii\generators\helpers;

use yii\base\Model;
use yii\helpers\Inflector;

/**
 * Class GeneratorHelper
 * @package ma3obblu\gii\generators\helpers
 *
 * различные генераторы данных
 */
class GeneratorHelper
{
    /**
     * возвращает имя класса без namespace
     * @param string $className
     * @return string
     */
    public static function getClassNameWithoutNamespace(string $className) : string
    {
        if ($pos = strrpos($className, '\\')) return substr($className, $pos + 1);
        return $pos;
    }

    /**
     * implode, который каждый элемент массива оборачивает в одинарные кавычки
     * @param string $glue
     * @param array $array
     * @return string
     */
    public static function implodeWithQuotes(string $glue = ', ', array $array)
    {
        $result = "";
        foreach ($array as $item){
            $result .= "'" . $item . "'" . $glue;
        }
        return mb_substr($result, 0, mb_strlen($result) - 2);
    }

    /****************************************************************************************
     * FORM GENERATOR
     ***************************************************************************************/

    /**
     * генерит phpdoc для аттрибутов класса
     * @param Model $entity
     * @param bool $needId
     * @return string
     */
    public static function generatePhpDocForClassAttributes(Model $entity, bool $needId = false) : string
    {
        $attributes = array_keys($entity->getAttributes());
        $result = "";
        foreach ($attributes as $attribute){
            if ($needId === false && $attribute === 'id') continue;
            $result .= " * @property " . GeneratorHelper::getAttributeType($attribute, $entity->rules()) . " $" . $attribute . "\n";
        }
        return $result;
    }

    /**
     * генерации объявления переменных из атрибутов модели
     * @param Model $entity
     * @param bool $needId
     * @return string
     */
    public static function generateClassParams(Model $entity, bool $needId = false) : string
    {
        $result = "";
        $attributes = array_keys($entity->getAttributes());
        foreach ($attributes as $attribute){
            if ($needId === false && $attribute === 'id') continue;
            $result .= "    public $" . $attribute . ";\n";
        }
        return $result;
    }

    /**
     * конвертации rules модели в строку для вывода в шаблоне формы
     * @param Model $entity
     * @param bool $needId
     * @return string
     */
    public static function convertRules(Model $entity, bool $needId = false) : string
    {
        $result = "";
        foreach ($entity->rules() as $rule) {
            $string = "";
            foreach ($rule as $key => $value) {
                if ($key === 0) {
                    if (is_array($value)){
                        if ($needId === false && in_array('id', $value)) {
                            unset($value[array_search('id', $value)]);
                        }
                        $string = "            [[" . self::implodeWithQuotes(', ', $value) . "], ";
                    } else {
                        if ($needId === false && $value === 'id') continue;
                        $string = "            [['" . $value . "'], ";
                    }
                } elseif ($key === 1) {
                    $string .= "'" . $value . "', ";
                } else {
                    if (is_array($value)) {
                        $string .= "'" . $key . "' => [" . self::implodeWithQuotes(', ', $value) . "], ";
                    } else {
                        $string .= "'" . $key . "' => " . $value;
                    }
                }
            }
            if (mb_substr($string, -2, 2) == ', '){
                $string = mb_substr($string, 0, mb_strlen($string) - 2);
            }
            $string .= "],";
            $result .= $string . "\n";
        }
        return $result;
    }

    /**
     * получает тип атрибута из rules. если в rules тип не указан вернет пустую строку
     * @param string $attribute
     * @param array $rules
     * @return string
     */
    public static function getAttributeType(string $attribute, array $rules) : string
    {
        $types = ['integer', 'string', 'boolean', 'double', 'number'];
        foreach ($rules as $rule){
            foreach ($rule as $key => $value){
                if ($key === 0) {
                    if (is_array($value)){
                        if (in_array($attribute, $value)){
                            if (in_array($rule[1], $types)) return $rule[1];
                            continue;
                        }
                    } else {
                        if ($attribute === $value){
                            if (in_array($rule[1], $types)) return $rule[1];
                            continue;
                        }
                    }
                }
            }
        }
        return '//TODO';
    }

    /**
     * генерирует описание атрибутов модели из самой модели
     * @param Model $entity
     * @param bool $needId
     * @return string
     */
    public static function generateAttributeLabels(Model $entity, bool $needId = false) : string
    {
        $attributes = array_keys($entity->getAttributes());
        $result = "";
        foreach ($attributes as $attribute){
            if ($needId === false && $attribute === 'id') continue;
            $result .= "            '" . $attribute . "' => \Yii::t('app', '" . $entity->getAttributeLabel($attribute) . "'),\n";
        }
        return $result;
    }

    /**
     * генерит конструктор формы
     * @param Model $entity
     * @param boolean $getAttributes
     * @param boolean $needId
     * @return string
     */
    public static function generateFormConstructor(Model $entity, bool $getAttributes = false, bool $needId = false)
    {
        $className = self::getClassNameWithoutNamespace(get_class($entity));
        $formClass = $className . "Form";
        $paramName = Inflector::camel2id($className, '_');
        $result = "    /**\n";
        $result .= "     * " . $formClass . " constructor.\n";
        $result .= "     * @param array " . "$" . "config\n";
        $result .= "     * @param " . $className . "|null $" . $paramName . "\n";
        $result .= "     */";
        $result .= "\n    public function __construct(array " . "$" . "config = [], " . $className . " $" . $paramName . " = null)";
        $result .= "\n    {";
        $result .= "\n        if (!is_null($". $paramName . ")){";
        if ($getAttributes === false) {
            $attributes = array_keys($entity->getAttributes());
            foreach ($attributes as $attribute){
                if ($needId === false && $attribute === 'id') continue;
                $result .= "\n            $" . "this->" . $attribute . " = $" . $paramName . "->" . $attribute . ";";
            }
        } else {
            $result .= "\n            $" . "this->setAttributes($" . $paramName . "->getAttributes());";
        }
        $result .= "\n        }";
        $result .= "\n        parent::__construct($" . "config);";
        $result .= "\n    }\n";
        return $result;
    }

    /****************************************************************************************
     * ENTITY GENERATOR
     ***************************************************************************************/

    /**
     * генерирует строки переноса значений атрибутов из формы в сущность
     * @param array $properties
     * @param bool $needId
     * @return string
     */
    public static function generateGetFromForm(array $properties, bool $needId = false) : string
    {
        $result = "";
        foreach ($properties as $property => $data){
            if ($needId === false && $property === 'id') continue;
            $result .= "            $" . "this->" . $property . " = $" . "this->form->" . $property . ";\n";
        }
        return $result;
    }

    /**
     * генерация attributeLabels в моделях
     * @param array $labels
     * @return string
     */
    public static function generateLabels(array $labels) : string
    {
        $result = "";
        foreach ($labels as $name => $label){
            $result .= "            '$name' => " . "\Yii::t('app', '" . addslashes($name) . "')" . ",\n";
        }
        return $result;
    }

    /**
     * массив в строку (для фикстур)
     * @param array $array
     * @return string
     */
    public static function array2string(array $array) : string
    {
        $log_a = "    [\n";
        foreach ($array as $key => $value) {
            if(is_array($value)) {
                $log_a .= "        '".$key."' => [". self::array2string($value). "], \n";
            } elseif (is_null($value)){
                $log_a .= "        '".$key."' => null,\n";
            } elseif ($value === ''){
                $log_a .= "        '".$key."' => '',\n";
            } elseif (preg_match('^[а-яА-ЯёЁ]+$^', $value)
                || preg_match('/[^A-Za-z0-9]/', $value)
                || preg_match('/[A-Za-z]/', $value)){
                $log_a .= "        '".$key."' => '$value',\n";
            } else{
                $log_a .= "        '".$key."' => ".$value.",\n";
            }
        }
        return $log_a . "    ],\n";
    }

    /**
     * генерирует название из заданной реляции для фикстур
     * @param string $relation
     * @return string
     */
    public static function generateFixtureRelationName(string $relation) : string
    {
        if (strripos($relation, '->')){
            return Inflector::camel2id(mb_substr($relation, strripos($relation, '->') + 2));
        }
        return Inflector::camel2id($relation);
    }
}