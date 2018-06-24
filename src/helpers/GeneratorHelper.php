<?php
namespace Ma3oBblu\gii\helpers;

use yii\base\Model;
use yii\helpers\Inflector;

/**
 * Class GeneratorHelper
 * @package Ma3oBblu\gii\helpers
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
    public static function implodeWithQuotes(string $glue = ',', array $array)
    {
        $result = "";
        foreach ($array as $item){
            $result .= "'" . $item . "'" . $glue;
        }
        return mb_substr($result, 0, mb_strlen($result) - 1);
    }

    /**
     * генерит phpdoc для аттрибутов класса
     * @param Model $entity
     * @return string
     */
    public static function generatePhpDocForClassAttributes(Model $entity) : string
    {
        $attributes = array_keys($entity->getAttributes());
        $result = "";
        foreach ($attributes as $attribute){
            $result .= "\n* @property " . GeneratorHelper::getAttributeType($attribute, $entity->rules()) . " $" . $attribute . ";";
        }
        return $result;
    }

    /**
     * генерации объявления переменных из атрибутов модели
     * @param Model $entity
     * @return string
     */
    public static function generateClassParams(Model $entity) : string
    {
        $result = "";
        $attributes = array_keys($entity->getAttributes());
        foreach ($attributes as $attribute){
            $result .= "public $" . $attribute . ";\n";
        }
        return $result;
    }

    /**
     * конвертации rules модели в строку для вывода в шаблоне формы
     * @param Model $entity
     * @return string
     */
    public static function convertRules(Model $entity) : string
    {
        $result = "";
        foreach ($entity->rules() as $rule) {
            $string = "";
            foreach ($rule as $key => $value) {
                if ($key === 0) {
                    if (is_array($value)){
                        $string = "[[" . self::implodeWithQuotes(',', $value) . "],";
                    } else {
                        $string = "[['" . $value . "'],";
                    }
                } elseif ($key === 1) {
                    $string .= "'" . $value . "',";
                } else {
                    if (is_array($value)) {
                        $string .= "'" . $key . "' => [" . self::implodeWithQuotes(',', $value) . "],";
                    } else {
                        $string .= "'" . $key . "' => " . $value;
                    }
                }
            }
            if (mb_substr($string, -1, 1) == ','){
                $string = mb_substr($string, 0, mb_strlen($string) - 1);
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
     * @return string
     */
    public static function generateAttributeLabels(Model $entity) : string
    {
        $attributes = array_keys($entity->getAttributes());
        $result = "";
        foreach ($attributes as $attribute){
            $result .= "'" . $attribute . "' => \Yii::t('app', '" . $entity->getAttributeLabel($attribute) . "'),\n";
        }
        return $result;
    }

    /**
     * генерит конструктор формы
     * @param Model $entity
     * @return string
     */
    public static function generateFormConstructor(Model $entity)
    {
        $className = self::getClassNameWithoutNamespace(get_class($entity));
        $formClass = $className . "Form";
        $paramName = Inflector::camel2id($className, '_');
        $result = "/**\n * " . $formClass . " constructor.\n * @param array " . "$" . "config\n * @param " . $className . "|null $" . $paramName . "\n */";
        $result .= "\npublic function __construct(array " . "$" . "config = [], " . $className . " $" . $paramName . " = null)";
        $result .= "\n{";
        $result .= "\n\tif (!is_null($". $paramName . ")){";
        $result .= "\n\t\t$" . "this->setAttributes($" . $paramName . "->getAttributes());";
        $result .= "\n\t}";
        $result .= "\n\tparent::__construct($" . "config);\n}";
        return $result;
    }
}