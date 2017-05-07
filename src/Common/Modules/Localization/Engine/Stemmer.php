<?php

namespace Common\Modules\Localization\Engine;

/**
 * Class Stemmer
 * @package Common\Modules\Localization\Engine
 */
class Stemmer
{

    /**
     * @todo should be parsed from file or smth
     * @var array
     */
    private $languagesRules = array(
        'en' => array(),
        'lt' => array(
            //nominative singular
            'as',
            'is',
            'ys',
            'a',
            'e',
            'is',
            'us',
            'ius',
            //nominative plural
            'ai',
            'iai',
            'os',
            'es',
            'ys',
            'ai',
            //genitive singular
            'o',
            'io',
            'io',
            'os',
            'es',
            'ies',
            'aus',
            'iaus',
            //genitive plural
            'u',
            'iu',
            //dative singular
            'ui',
            'iui',
            'iui',
            'ai',
            'ei',
            'iai',
            'ui',
            'iui',
            //dative plural
            'ams',
            'iams',
            'oms',
            'ems',
            'ims',
            'ams',
            //accusative singular
            'a',
            'i',
            'i',
            'a',
            'e',
            'i',
            'u',
            'iu',
            //accusative plural
            'us',
            'ius',
            'ius',
            'as',
            'es',
            'is',
            'us',
            'ius',
            //instrumental singular
            'u',
            'iu',
            'iu',
            'a',
            'e',
            'imi',
            'umi',
            'iumi',
            //instrumental plural
            'ais',
            'iais',
            'omis',
            'emis',
            'imis',
            'ais',
            //locative singular
            'e',
            'yje',
            'oje',
            'eje',
            'uje',
            'iuje',
            //locative plural
            'ais',
            'iais',
            'omis',
            'emis',
            'imis',
            'ais',
            //vocative singular
            'e',
            'i',
            'y',
            'a',
            'e',
            'ie',
            'au',
            //vocative plural
            'ai',
            'iai',
            'iai',
            'os',
            'es',
            'ys',
            //custom
            'ias',
            'auti',
            'aus',
        ),
    );

    /**
     * @var
     */
    private $rules;

    /**
     * @var
     */
    private $words;

    /**
     * @var
     */
    private $stems;

    /**
     * @param string $language
     */
    public function __construct($language = 'en')
    {
        if (isset($this->languagesRules[$language])) {
            $this->rules = $this->languagesRules[$language];
        }
    }

    /**
     * @todo implement this functionality
     *
     * @param $string
     * @return mixed
     */
    public function execute($string)
    {
        $this->prepareWords($string);
        $this->prepareStems();
    }

    /**
     * @param $string
     */
    public function prepareWords($string)
    {
        $string = transliterator_transliterate('Any-Latin; Latin-ASCII;', $string);
        $string = preg_replace('/[^a-zA-Z0-9]/', ' ', $string);

        $this->words = array_filter(explode(' ', $string));
    }

    /**
     *
     */
    public function prepareStems()
    {
        foreach ($this->words as $word) {
            $this->stems[$word] = $this->stem($word);
        }
    }

    /**
     * @param $word
     * @return mixed
     */
    public function stem($word)
    {
        $stem = preg_replace('/('.implode('|', $this->rules).')$/', '', $word);

        return $stem;
    }

    /**
     * @return mixed
     */
    public function getStems()
    {
        return $this->stems;
    }

    /**
     * @return mixed
     */
    public function getWords()
    {
        return $this->words;
    }

    /**
     * @param $fields
     * @return string
     */
    public function getMatchQuery($fields)
    {
        $query = null;

        if (is_array($fields) && is_array($this->stems)) {
            $query .= 'MATCH ('.implode(', ', $fields).') AGAINST (\'';

            $strings = array();
            foreach ($this->words as $word) {
                $strings[] = isset($this->stems[$word]) ? $this->stems[$word].'*' : $word;
            }

            $query .= implode(' ', $strings).'\' IN BOOLEAN MODE)';
        }

        return $query;
    }
}
