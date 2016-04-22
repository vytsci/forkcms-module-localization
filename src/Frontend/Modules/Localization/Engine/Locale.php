<?php

namespace Frontend\Modules\Localization\Engine;

use Frontend\Core\Engine\Language as FL;

use Common\Modules\Localization\Engine\Locale as CommonLocale;

/**
 * Class Locale
 * @package Frontend\Modules\Localization\Engine
 */
class Locale extends CommonLocale
{

    /**
     * Sets up given languages. If $languages array is empty all active languages will be loaded.
     *
     * @param array $languages Array of languages to set up. Example: array('en', 'ru', 'lt')
     */
    function __construct($languages = array())
    {
        if (empty($languages) || !is_array($languages)) {
            $languages = FL::getActiveLanguages();
        }

        foreach ($languages as $languagesValue) {
            $this->setLanguage(new Language($languagesValue));
        }

        if (!$this->existsLanguage(FRONTEND_LANGUAGE)) {
            $this->setLanguage(FRONTEND_LANGUAGE);
        }

        $this->resetLanguage();
    }
}
