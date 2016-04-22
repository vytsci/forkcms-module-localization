<?php

namespace Common\Modules\Localization\Engine;

use Common\Core\Form;

/**
 * Class Helper
 * @package Backend\Modules\Localization\Engine
 */
class Helper
{

    /**
     * @param $record
     * @param $name
     * @param $language
     * @return string
     */
    public static function parseLocaleValue($record, $name, $language)
    {
        if (isset($record['locale'][$name])) {
            return $record['locale'][$name];
        }

        if (isset($record['locale'][$language][$name])) {
            return $record['locale'][$language][$name];
        }

        return '';
    }

    /**
     * @param Form $frm
     * @param $name
     * @param $language
     * @return string|void
     * @throws \SpoonFormException
     */
    public static function parseField(Form $frm, $name, $language)
    {
        $name = $frm->getFieldName($name, $frm->getLocale()->getLanguage($language));

        if (empty($name)) {
            return '';
        }

        $field = $frm->getField($name);

        return $field->parse();
    }

    /**
     * @param Form $frm
     * @param $name
     * @param $language
     * @return string|void
     * @throws \SpoonFormException
     */
    public static function parseFieldErrors(Form $frm, $name, $language)
    {
        $name = $frm->getFieldName($name, $frm->getLocale()->getLanguage($language));

        if (empty($name)) {
            return '';
        }

        $field = $frm->getField($name);

        return $field->getErrors();
    }

    /**
     * @param \SpoonTemplate $tpl
     */
    public static function mapTemplateModifiers(\SpoonTemplate $tpl)
    {
        $tpl->mapModifier(
            'parselocalevalue',
            array('Common\\Modules\\Localization\\Engine\\Helper', 'parseLocaleValue')
        );
    }
}
