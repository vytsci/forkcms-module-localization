<?php

namespace Backend\Modules\Localization\Engine;

use Common\Uri as CommonUri;

use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Template as BackendTemplate;

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
        return isset($record['locale'][$language][$name])?$record['locale'][$language][$name]:'';
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
            array('Backend\\Modules\\Localization\\Engine\\Helper', 'parseLocaleValue')
        );
    }

    /**
     * SpoonTemplate is so crappy it can't parse variables into included file so we need to compile this manually
     *
     * @param $fields
     * @param $errors
     * @param Language $language
     * @return string
     * @throws \SpoonTemplateException
     */
    public static function parseSeoForm($fields, $errors, Language $language)
    {
        $tpl = new BackendTemplate();
        $tpl->assign('language', $language->getCode());
        $tpl->assign('fields', $fields);
        $tpl->assign('errors', $errors);
        return $tpl->getContent(BACKEND_MODULES_PATH . '/Localization/Layout/Templates/Seo.tpl');
    }
}
