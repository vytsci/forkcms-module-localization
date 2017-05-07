<?php

namespace Frontend\Modules\Localization\Engine;

use Frontend\Core\Engine\Template as FrontendTemplate;
use Frontend\Core\Engine\Language as FL;

/**
 * Class Helper
 * @package Frontend\Modules\Localization\Engine
 */
class Helper
{
    /**
     * @param \SpoonTemplate $tpl
     */
    public static function mapTemplateModifiers(\SpoonTemplate $tpl)
    {
        $tpl->mapModifier(
            'stringtotranslation',
            array('Frontend\\Modules\\Localization\\Engine\\Helper', 'stringToTranslation')
        );
    }

    /**
     * @param $var
     * @param string $type
     * @param string $separator
     * @return mixed
     */
    public static function stringToTranslation($var, $type = 'lbl', $separator = '_')
    {
        if (in_array($type, array('lbl', 'msg', 'err'))) {
            return FL::$type(\SpoonFilter::toCamelCase($var, $separator, false));
        }

        return $var;
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
        $tpl = new FrontendTemplate();
        $tpl->assign('language', $language->getCode());
        $tpl->assign('fields', $fields);
        $tpl->assign('errors', $errors);

        return $tpl->getContent(FRONTEND_MODULES_PATH.'/Localization/Layout/Templates/Seo.tpl');
    }
}
