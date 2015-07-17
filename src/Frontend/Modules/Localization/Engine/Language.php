<?php

namespace Frontend\Modules\Localization\Engine;

use Frontend\Core\Engine\Language as FL;

use Common\Modules\Localization\Language as CommonLanguage;

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
     * Setups Meta object. Parameters are same as core Meta.
     *
     * @param $form
     * @param null $metaId
     * @param string $baseFieldName
     * @param bool $custom
     * @return $this
     */
    public function setMeta($form, $metaId = null, $baseFieldName = 'title', $custom = false)
    {
        $this->meta = new Meta($this, $form, $metaId, $baseFieldName, $custom);

        return $this;
    }
}
