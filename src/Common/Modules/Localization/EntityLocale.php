<?php

namespace Common\Modules\Localization;

use Common\Modules\Entities\Entity as CommonEntity;

/**
 * Class EntityLocale
 * @package Common\Modules\Localization
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
}
