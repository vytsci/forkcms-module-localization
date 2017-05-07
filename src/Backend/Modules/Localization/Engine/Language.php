<?php

namespace Backend\Modules\Localization\Engine;

use Backend\Core\Engine\Language as BL;

use Common\Modules\Localization\Engine\Language as CommonLanguage;

/**
 * Class Language
 * @package Backend\Modules\Localization\Engine*
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
     * @param $code string Langauge code. Code will be converted to lowercase. Example: 'en'
     * @throws \SpoonLocaleException
     */
    function __construct($code)
    {
        parent::__construct($code);

        $this->setTitle(BL::getLabel(strtoupper($this->getCode())));
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
