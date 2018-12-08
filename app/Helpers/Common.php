<?php
/**
 * Created by PhpStorm.
 * User: Mahendran T
 * Date: 07-10-2018
 * Time: 12:34 PM
 */
class Common
{
    public static function ordinalValue($value)
    {
        $formatter = new NumberFormatter('en_US', NumberFormatter::SPELLOUT);
        $formatter->setTextAttribute(NumberFormatter::DEFAULT_RULESET,"%spellout-ordinal");
        return $formatter->format($value);
    }
}
