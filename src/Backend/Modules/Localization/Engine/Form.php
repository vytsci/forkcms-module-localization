<?php

namespace Backend\Modules\Localization\Engine;

use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Header as BackendHeader;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Form as BackendForm;

/**
 * Class Form
 * @package Backend\Modules\Websites\Engine
 */
class Form extends BackendForm
{

    /**
     * @var BackendHeader
     */
    protected $header;

    /**
     * @var Locale
     */
    private $locale;

    /**
     * @var array
     */
    private $fields;

    /**
     * @param Locale $locale
     * @param null $name
     * @param null $action
     * @param string $method
     * @param bool $useToken
     * @param bool $useGlobalError
     */
    public function __construct(
        Locale $locale,
        $name = null,
        $action = null,
        $method = 'post',
        $useToken = true,
        $useGlobalError = true
    ) {
        $this->locale = $locale;

        if (BackendModel::getContainer()->has('header')) {
            $this->header = BackendModel::getContainer()->get('header');
        }

        parent::__construct($name, $action, $method, $useToken, $useGlobalError);
    }

    /**
     * @param object $object
     * @throws \SpoonFormException
     */
    public function add($object)
    {
        parent::add($object);

        $language = $this->locale->currentLanguage();

        if (empty($language)) {
            return;
        }

        $prefix = $language->getCode().'_';

        if (0 === strpos($object->getName(), $prefix)) {
            $key = substr_replace($object->getName(), '', 0, strlen($prefix));
            $this->fields[$language->getCode()][$key] = $object;
        }
    }

    /**
     * @param string $name
     * @param bool $checked
     * @param string $class
     * @param string $classError
     * @return null|\SpoonFormElement
     * @throws \SpoonFormException
     */
    public function addCheckbox($name, $checked = false, $class = null, $classError = null)
    {
        $name = $this->getFieldName($name, $this->locale->currentLanguage());

        return parent::addCheckbox($name, $checked, $class, $classError);
    }

    /**
     * @param string $name
     * @param array $values
     * @param null $selected
     * @param bool $multipleSelection
     * @param null $class
     * @param null $classError
     * @return \SpoonFormDropdown
     */
    public function addDropdown(
        $name,
        array $values = null,
        $selected = null,
        $multipleSelection = false,
        $class = null,
        $classError = null
    ) {
        $name = $this->getFieldName($name, $this->locale->currentLanguage());

        return parent::addDropdown($name, $values, $selected, $multipleSelection, $class, $classError);
    }

    /**
     * @param string $name
     * @param null $value
     * @param null $class
     * @param null $classError
     * @param bool $HTML
     * @return \SpoonFormTextarea
     */
    public function addEditor($name, $value = null, $class = null, $classError = null, $HTML = true)
    {
        $name = $this->getFieldName($name, $this->locale->currentLanguage());
        $value = ($value !== null) ? (string)$value : null;
        $class = 'inputEditor '.(string)$class;
        $classError = 'inputEditorError '.(string)$classError;
        $HTML = (bool)$HTML;

        if ($this->header instanceof BackendHeader) {
            $this->header->addJS('ckeditor/ckeditor.js', 'Core', false);
            $this->header->addJS('ckeditor/adapters/jquery.js', 'Core', false);
            $this->header->addJS('ckfinder/ckfinder.js', 'Core', false);

            if (is_file(FRONTEND_CACHE_PATH.'/Navigation/editor_link_list_'.BL::getWorkingLanguage().'.js')) {
                $timestamp = @filemtime(
                    FRONTEND_CACHE_PATH.'/Navigation/editor_link_list_'.BL::getWorkingLanguage().'.js'
                );
                $this->header->addJS(
                    '/src/Frontend/Cache/Navigation/editor_link_list_'.BL::getWorkingLanguage().'.js?m='.$timestamp,
                    null,
                    false,
                    true,
                    false
                );
            }
        }

        return parent::addTextarea($name, $value, $class, $classError, $HTML);
    }

    /**
     * @param string $name
     * @param null $value
     * @return null|\SpoonFormElement
     * @throws \SpoonFormException
     */
    public function addHidden($name, $value = null)
    {
        $name = $this->getFieldName($name, $this->locale->currentLanguage());

        return parent::addHidden($name, $value);
    }

    /**
     * @param string $name
     * @param array $values
     * @param null $checked
     * @param null $class
     * @param null $classError
     * @return \SpoonFormRadiobutton
     */
    public function addRadiobutton($name, array $values, $checked = null, $class = null, $classError = null)
    {
        $name = $this->getFieldName($name, $this->locale->currentLanguage());

        return parent::addRadiobutton($name, $values, $checked, $class, $classError);
    }

    /**
     * @param string $name
     * @param null $value
     * @param int $maxLength
     * @param null $class
     * @param null $classError
     * @param bool $HTML
     * @return \SpoonFormText
     */
    public function addText($name, $value = null, $maxLength = 255, $class = null, $classError = null, $HTML = false)
    {
        $name = $this->getFieldName($name, $this->locale->currentLanguage());

        return parent::addText($name, $value, $maxLength, $class, $classError, $HTML);
    }

    /**
     * @param string $name
     * @param null $value
     * @param string $class
     * @param string $classError
     * @param bool $HTML
     * @return null|\SpoonFormElement
     * @throws \SpoonFormException
     */
    public function addTextarea(
        $name,
        $value = null,
        $class = 'inputTextarea',
        $classError = 'inputTextareaError',
        $HTML = false
    ) {
        $name = $this->getFieldName($name, $this->locale->currentLanguage());

        return parent::addTextarea($name, $value, $class, $classError);
    }

    /**
     * @param string $name
     * @param Language|null $language
     * @return \SpoonFormElement
     * @throws \SpoonFormException
     */
    public function getField($name, Language $language = null)
    {
        return parent::getField($this->getFieldName($name, $language));
    }

    /**
     * @param $name
     * @param Language $language
     * @return string
     */
    public function getFieldName($name, Language $language = null)
    {
        if (!empty($language) && !empty($name)) {
            return $language->getCode().'_'.$name;
        }

        return (string)$name;
    }

    /**
     * @return Locale
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Parse the form
     *
     * @param \SpoonTemplate $tpl The template instance wherein the form will be parsed.
     */
    public function parse($tpl)
    {
        if ($this->header instanceof BackendHeader) {
            $this->header->addJS('jquery/jquery.meta.js', 'Localization', true);
            $this->header->addJS('Meta.js', 'Localization', true);
        }

        $tpl->assign('form', $this);
        $this->parseLocalization($tpl);

        parent::parse($tpl);
    }

    /**
     * If SpoonTemplate would not be so fucked up this method wont be necessary
     *
     * @param \SpoonTemplate $tpl
     * @throws \SpoonTemplateException
     */
    public function parseLocalization(\SpoonTemplate $tpl)
    {
        $formLanguages = array();

        while ($language = $this->locale->loopLanguage()) {
            $formLanguage = array(
                'code' => $language->getCode(),
                'title' => $language->getTitle(),
                'fields' => array(),
                'errors' => array(),
                'seo' => false,
            );
            $fields = array();
            $errors = array();
            if (isset($this->fields[$language->getCode()])) {
                foreach ($this->fields[$language->getCode()] as $fieldKey => $fieldValue) {
                    $fields[$fieldKey] = $fieldValue->parse();
                    if (method_exists($fieldValue, 'getErrors')) {
                        $errors[$fieldKey] = $fieldValue->getErrors();
                    }
                }
                $formLanguage['fields'] = $fields;
                $formLanguage['errors'] = $errors;
                if ($language->hasMeta()) {
                    $formLanguage['seo'] = Helper::parseSeoForm($fields, $errors, $language);
                }
            }
            $formLanguages[$language->getCode()] = $formLanguage;
            $this->locale->nextLanguage();
        }

        $tpl->assign('formLocalization', $formLanguages);
    }
}
