<?php

namespace Common\Modules\Localization;

use Common\Modules\Entities\AbstractEntity;
use Common\Modules\Entities\Entity as CommonEntity;
use Common\Modules\Entities\Helper as CommonEntitiesHelper;

/**
 * Class Entity
 * @package Common\Modules\Localization
 */
class Entity extends CommonEntity
{
    /**
     * @var string
     */
    protected $_locale;

    /**
     * @var string
     */
    protected $_language;

    /**
     * @var array
     */
    protected $locale = array();

    /**
     * @param array $languages
     * @param array $parameters
     */
    function __construct($parameters = array(), $languages = array())
    {
        parent::__construct($parameters);

        $this->loadLocale($languages);
    }

    /**
     * @param array $languages
     * @return $this
     * @throws \Exception
     */
    public function loadLocale($languages = array())
    {
        if (!empty($languages)) {
            $this->addRelation('locale');

            foreach ($languages as $language) {
                if (isset($this->_locale)) {
                    /* @var $locale CommonEntity */
                    $locale = new $this->_locale(array($this->getId(), $language));
                    $this->setLocale($locale, $language);
                }
            }
            if (count($languages) == 1) {
                $this->lockLocaleLanguage(reset($languages));
            }
        }

        return $this;
    }

    /**
     * @param $language
     * @return $this
     */
    public function lockLocaleLanguage($language)
    {
        $this->_language = $language;

        return $this;
    }

    /**
     * @return $this
     */
    public function unlockLocaleLanguage()
    {
        $this->_language = null;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLoadedLocale()
    {
        return !empty($this->_language);
    }

    /**
     * @param $language
     * @return bool
     */
    public function existsLocale($language)
    {
        return isset($this->locale[$language]);
    }

    /**
     * @param $language
     * @return Entity
     * @throws \Exception
     */
    public function getLocale($language = null)
    {
        if (is_null($language) && isset($this->_language)) {
            $language = $this->_language;
        }

        if (!isset($this->locale[$language])) {
            throw new \Exception('Locale does not exist');
        }

        return $this->locale[$language];
    }

    /**
     * @param CommonEntity $locale
     * @param null $language
     * @return CommonEntity
     * @throws \Exception
     */
    public function setLocale(CommonEntity $locale, $language = null)
    {
        if (is_null($language) && isset($this->_language)) {
            $language = $this->_language;
        }

        if (empty($language)) {
            throw new \Exception('Language cannot be empty');
        }

        $this->locale[$language] = $locale;

        return $this->getLocale($language);
    }

    /**
     * @param bool|false $onlyColumns
     * @return array
     * @throws \Exception
     */
    public function toArray($onlyColumns = false)
    {
        $result = array();

        foreach ($this->getVariables(!$onlyColumns) as $variablesKey => &$variablesValue) {
            $variablesKey = CommonEntitiesHelper::toSnakeCase($variablesKey);
            if ($variablesKey === 'locale' && isset($this->_language)) {
                $result[$variablesKey] = $this->getLocale()->toArray();
                continue;
            }
            if (is_array($variablesValue)) {
                foreach ($variablesValue as $variablesValueKey => &$variablesValueValue) {
                    $variablesValueKey = CommonEntitiesHelper::toSnakeCase($variablesValueKey);
                    if ($variablesValueValue instanceof AbstractEntity) {
                        $result[$variablesKey][$variablesValueKey] = $variablesValueValue->toArray();
                    }
                }
                continue;
            }
            $result[$variablesKey] = $variablesValue;
        }

        return $result;
    }
}
