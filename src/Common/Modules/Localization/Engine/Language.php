<?php

namespace Common\Modules\Localization\Engine;

/**
 * Class Language
 * @package Common\Modules\Localization\Engine
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
     * Sets all necessary variables.
     *
     * @param $code Language code. Code will be converted to lowercase. Example: 'en'
     * @throws \SpoonLocaleException
     */
    function __construct($code)
    {
        $this->setCode(strtolower($code));
        $this->setTitle(strtoupper($this->getCode()));
    }

    /**
     * Gets language code.
     *
     * @return string
     */
    public function getCode()
    {
        return strtolower($this->code);
    }

    /**
     * Set language code. Example: 'en'
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = strtolower($code);
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
}
