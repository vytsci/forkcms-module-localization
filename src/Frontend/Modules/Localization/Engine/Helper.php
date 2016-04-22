<?php

namespace Frontend\Modules\Localization\Engine;

use Frontend\Core\Engine\Template as FrontendTemplate;

/**
 * Class Helper
 * @package Frontend\Modules\Localization\Engine
 */
class Helper
{

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
