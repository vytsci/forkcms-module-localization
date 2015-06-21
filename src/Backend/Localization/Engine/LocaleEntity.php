<?php

namespace Backend\Modules\Localization\Engine;

use Common\Modules\Entities\AbstractEntity;
use Common\Modules\Entities\Entity;

/**
 * Class LocaleEntity
 * @package Backend\Modules\Localization\Engine
 */
class LocaleEntity extends AbstractEntity
{
    /**
     * @var array
     */
    protected $locale;

    /**
     * @param $table
     * @param $class
     * @return $this
     * @throws \SpoonDatabaseException
     */
    public function loadLocale($table, $class)
    {
        if (!$this->isLoaded()) {
            return $this;
        }

        $records = (array)$this->db->getRecords(
            "SELECT el.* FROM {$table} AS el WHERE el.id = ?",
            array($this->getId())
        );

        if (!empty($records)) {
            foreach ($records as $recordsKey => $recordsValue) {

                /* @var $entityLocale AbstractEntity */
                $entityLocale = new $class();
                $entityLocale->setTable($table);

                foreach ($recordsValue as $recordsValueKey => $recordsValueValue) {
                    $setMethod = 'set' . \SpoonFilter::toCamelCase($recordsValueKey);
                    if (method_exists($entityLocale, $setMethod)) {
                        $entityLocale->$setMethod($recordsValueValue);
                    }
                }

                $this->setLocale($recordsValue['language'], $entityLocale);
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isLoadedLocale()
    {
        return is_array($this->locale) && !empty($this->locale);
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
     * @return null
     * @throws \Exception
     */
    public function getLocale($language)
    {
        if (!isset($this->locale[$language])) {
            throw new \Exception('Locale does not exist');
        }

        return $this->locale[$language];
    }

    /**
     * @param $language
     * @param Entity $locale
     * @return $this
     */
    public function setLocale($language, Entity $locale)
    {
        $this->locale[$language] = $locale;

        return $this;
    }
}
