<?php

namespace Frontend\Modules\Localization\Engine;

use Frontend\Core\Engine\Language as FL;

use Common\Modules\Localization\Engine\Language as CommonLanguage;

/**
 * Class Language
 * @package Frontend\Modules\Localization\Engine
 */
class Language extends CommonLanguage
{

    /**
     * Meta object. Only if required.
     *
     * @var Meta
     */
    private $meta;

    /**
     * Sets all necessary variables.
     *
     * @param $code Language code. Code will be converted to lowercase. Example: 'en'
     * @throws \SpoonLocaleException
     */
    function __construct($code)
    {
        $this->setCode(strtolower($code));
        $this->setTitle(FL::getLabel(strtoupper($this->getCode())));
    }

    /**
     * Checks if language has meta set
     *
     * @return bool
     */
    public function hasMeta()
    {
        return (bool)isset($this->meta);
    }

    /**
     * Gets meta object.
     *
     * @return Meta
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @param $module
     * @param $form
     * @param null $metaId
     * @param string $baseFieldName
     * @return $this
     */
    public function setMeta(Form $form, $module, $metaId = null, $baseFieldName = 'title')
    {
        $this->meta = new Meta($this, $form, $module, $metaId, $baseFieldName);

        return $this;
    }
}
