<?php

namespace Backend\Modules\Localization\Engine;

use Backend\Core\Engine\Language as BL;

use Common\Modules\Localization\Locale as CommonLocale;

/**
 * Class Locale
 * @package Backend\Modules\Localization\Engine
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
            $languages = BL::getActiveLanguages();
        }

        foreach ($languages as $languagesValue) {
            $this->setLanguage(new Language($languagesValue));
        }

        if (!$this->existsLanguage(BL::getWorkingLanguage())) {
            $this->setLanguage(new Language(BL::getWorkingLanguage()));
        }

        $this->resetLanguage();
    }
}
