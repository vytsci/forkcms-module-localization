<?php

namespace Common\Modules\Localization\Engine;

use Common\Modules\Entities\Engine\Entity as CommonEntity;

/**
 * Class EntityLocale
 * @package Common\Modules\Localization\Engine
 */
class EntityLocale extends CommonEntity
{

    /**
     * @var array
     */
    protected $_primary = array('id', 'language');

    /**
     * @var string
     */
    protected $language;

    /**
     * @var bool
     */
    protected $default = false;

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param $language
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDefault()
    {
        return (bool)$this->default;
    }

    /**
     * @param $default
     * @return $this
     */
    public function setDefault($default)
    {
        $this->default = (bool)$default;

        return $this;
    }
}
