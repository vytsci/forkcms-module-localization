<?php

namespace Backend\Modules\Localization\Engine;

use Backend\Core\Engine\Language as BL;

/**
 * Class Language
 * @package Backend\Modules\Localization\Engine
 */
class Language
{
    /**
     * Language code. It comes from outside.
     *
     * @var string $code Language code. Example: 'en'
     */
    private $code;

    /**
     * Language title. It is loaded from within framework.
     *
     * @var string $title Language title
     */
    private $title;

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
        $this->code = strtolower($code);
        $this->title = BL::getLabel(strtoupper($this->code));
    }

    /**
     * Gets language code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set language code. Example: 'en'
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Gets language name.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets language title. Does not change frameworks value. Example: 'English'
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
