<?php

/**
 * Doctrine inflector has static methods for inflecting text
 * 
 * The methods in these classes are from several different sources collected
 * across several different php projects and several different authors. The 
 * original author names and emails are not known
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision: 3189 $
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @author      Jonathan H. Wage <jonwage@gmail.com>
 */
class Inflector
{
    /**
     * Convert word in to the format for a Doctrine table name. Converts 'ModelName' to 'model_name'
     *
     * @param  string $word  Word to tableize
     * @return string $word  Tableized word
     */
    public static function tableize($word)
    {
        return strtolower(preg_replace('~(?<=\\w)([A-Z])~', '_$1', $word));
    }

    /**
     * Convert a word in to the format for a Doctrine class name. Converts 'table_name' to 'TableName'
     *
     * @param string  $word  Word to classify
     * @return string $word  Classified word
     */
    public static function classify($word)
    {
        return str_replace(" ", "", ucwords(strtr($word, "_-", "  ")));
    }

    /**
     * Camelize a word. This uses the classify() method and turns the first character to lowercase
     *
     * @param string $word
     * @return string $word
     */
    public static function camelize($word)
    {
        return lcfirst(self::classify($word));
    }
}