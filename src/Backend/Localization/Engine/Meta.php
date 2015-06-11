<?php

namespace Backend\Modules\Localization\Engine;

use Common\Uri as CommonUri;

use Backend\Core\Engine\Exception;
use Backend\Core\Engine\Meta as BackendMeta;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Language as BL;

/**
 * Class Meta
 * @package Backend\Modules\Localization\Engine
 */
class Meta extends BackendMeta
{
    /**
     * The form instance
     *
     * @var Form
     */
    protected $frm;

    /**
     * Language object.
     *
     * @var Language $language
     */
    private $language;

    /**
     * @param Language $language
     * @param Form $form
     * @param null $metaId
     * @param string $baseFieldName
     * @param bool $custom
     * @throws Exception
     * @throws \Backend\Core\Engine\Exception
     */
    public function __construct(Language $language, Form $form, $metaId = null, $baseFieldName = 'title', $custom = false)
    {
        // check if URL is available from the reference
        if (!BackendModel::getContainer()->has('url')) {
            throw new Exception('URL should be available in the reference.');
        }

        // set language instance
        $this->language = $language;

        // get BackendURL instance
        $this->URL = BackendModel::getContainer()->get('url');

        // should we use meta-custom
        $this->custom = (bool)$custom;

        // set form instance
        $this->frm = $form;

        // set base field name
        $this->baseFieldName = $this->frm->getFieldName($baseFieldName, $this->language);

        // metaId was specified, so we should load the item
        if ($metaId !== null) {
            $this->loadMeta($metaId);
        }

        // set default callback
        $this->setUrlCallback(
            'Backend\\Modules\\' . $this->URL->getModule() . '\\Engine\\Model',
            'getURL'
        );

        // load the form
        $this->loadForm();
    }

    /**
     * Generate an url, using the predefined callback.
     *
     * @param string $URL The base-url to start from.
     * @return string
     * @throws Exception When the function does not exist
     */
    public function generateURL($URL)
    {
        // validate (check if the function exists)
        if (!is_callable(array($this->callback['class'], $this->callback['method']))) {
            throw new Exception('The callback-method doesn\'t exist.');
        }

        // build parameters for use in the callback
        $parameters[] = CommonUri::getUrl($URL);

        // add parameters set by user
        if (!empty($this->callback['parameters'])) {
            foreach ($this->callback['parameters'] as $parameter) {
                $parameters[] = $parameter;
            }
        }

        // get the real url
        return call_user_func_array(array($this->callback['class'], $this->callback['method']), $parameters);
    }

    /**
     * Get the current value for the meta-description;
     *
     * @return mixed
     */
    public function getDescription()
    {
        // not set so return null
        if (!isset($this->data['description'])) {
            return null;
        }

        // return value
        return $this->data['description'];
    }

    /**
     * Should the description overwrite the default
     *
     * @return null|boolean
     */
    public function getDescriptionOverwrite()
    {
        // not set so return null
        if (!isset($this->data['description_overwrite'])) {
            return null;
        }

        // return value
        return ($this->data['description_overwrite'] == 'Y');
    }

    /**
     * Get the current value for the metaId;
     *
     * @return null|integer
     */
    public function getId()
    {
        // not set so return null
        if (!isset($this->data['id'])) {
            return null;
        }

        // return value
        return (int)$this->data['id'];
    }

    /**
     * Get the current value for the meta-keywords;
     *
     * @return mixed
     */
    public function getKeywords()
    {
        // not set so return null
        if (!isset($this->data['keywords'])) {
            return null;
        }

        // return value
        return $this->data['keywords'];
    }

    /**
     * Should the keywords overwrite the default
     *
     * @return null|boolean
     */
    public function getKeywordsOverwrite()
    {
        // not set so return null
        if (!isset($this->data['keywords_overwrite'])) {
            return null;
        }

        // return value
        return ($this->data['keywords_overwrite'] == 'Y');
    }

    /**
     * Get the current value for the page title;
     *
     * @return mixed
     */
    public function getTitle()
    {
        // not set so return null
        if (!isset($this->data['title'])) {
            return null;
        }

        // return value
        return $this->data['title'];
    }

    /**
     * Should the title overwrite the default
     *
     * @return null|boolean
     */
    public function getTitleOverwrite()
    {
        // not set so return null
        if (!isset($this->data['title_overwrite'])) {
            return null;
        }

        // return value
        return ($this->data['title_overwrite'] == 'Y');
    }

    /**
     * Return the current value for an URL
     *
     * @return null|string
     */
    public function getURL()
    {
        // not set so return null
        if (!isset($this->data['url'])) {
            return null;
        }

        // return value
        return urldecode($this->data['url']);
    }

    /**
     * Should the URL overwrite the default
     *
     * @return null|boolean
     */
    public function getURLOverwrite()
    {
        // not set so return null
        if (!isset($this->data['url_overwrite'])) {
            return null;
        }

        // return value
        return ($this->data['url_overwrite'] == 'Y');
    }

    /**
     * Add all element into the form
     */
    protected function loadForm()
    {
        // is the form submitted?
        if ($this->frm->isSubmitted()) {
            /**
             * If the fields are disabled we don't have any values in the post.
             * When an error occurs in the other fields of the form the meta-fields would be cleared
             * therefore we alter the POST so it contains the initial values.
             */
            if (!isset($_POST[$this->frm->getFieldName('page_title', $this->language)])) {
                $_POST[$this->frm->getFieldName('page_title', $this->language)] =
                    (isset($this->data['title'])) ? $this->data['title'] : null;
            }
            if (!isset($_POST[$this->frm->getFieldName('meta_description', $this->language)])) {
                $_POST[$this->frm->getFieldName('meta_description', $this->language)] =
                    (isset($this->data['description'])) ? $this->data['description'] : null;
            }
            if (!isset($_POST[$this->frm->getFieldName('meta_keywords', $this->language)])) {
                $_POST[$this->frm->getFieldName('meta_keywords', $this->language)] =
                    (isset($this->data['keywords'])) ? $this->data['keywords'] : null;
            }
            if (!isset($_POST[$this->frm->getFieldName('url', $this->language)])) {
                $_POST[$this->frm->getFieldName('url', $this->language)] =
                    (isset($this->data['url'])) ? $this->data['url'] : null;
            }
            if ($this->custom && !isset($_POST['meta_custom'])) {
                $_POST['meta_custom'] = (isset($this->data['custom'])) ? $this->data['custom'] : null;
            }
            if (!isset($_POST[$this->frm->getFieldName('seo_index', $this->language)])) {
                $_POST[$this->frm->getFieldName('seo_index', $this->language)] =
                    (isset($this->data['data']['seo_index'])) ?
                        $this->data['data']['seo_index'] :
                        'none';
            }
            if (!isset($_POST[$this->frm->getFieldName('seo_follow', $this->language)])) {
                $_POST[$this->frm->getFieldName('seo_follow', $this->language)] =
                    (isset($this->data['data']['seo_follow'])) ?
                        $this->data['data']['seo_follow'] :
                        'none';
            }
        }

        // prepare base field
        $this->frm->getField($this->baseFieldName)->setAttributes(array(
            'data-meta-base-field' => '1',
            'data-meta-language' => $this->language->getCode()
        ));

        // add page title elements into the form
        $this->frm->addCheckbox(
            'page_title_overwrite',
            (isset($this->data['title_overwrite']) && $this->data['title_overwrite'] == 'Y')
        );
        $this->frm->addText(
            'page_title',
            (isset($this->data['title'])) ? $this->data['title'] : null
        );

        // add meta description elements into the form
        $this->frm->addCheckbox(
            'meta_description_overwrite',
            (isset($this->data['description_overwrite']) && $this->data['description_overwrite'] == 'Y')
        );
        $this->frm->addText(
            'meta_description',
            (isset($this->data['description'])) ? $this->data['description'] : null
        );

        // add meta keywords elements into the form
        $this->frm->addCheckbox(
            'meta_keywords_overwrite',
            (isset($this->data['keywords_overwrite']) && $this->data['keywords_overwrite'] == 'Y')
        );
        $this->frm->addText(
            'meta_keywords', (isset($this->data['keywords'])) ? $this->data['keywords'] : null
        );

        // add URL elements into the form
        $this->frm->addCheckbox(
            'url_overwrite',
            (isset($this->data['url_overwrite']) && $this->data['url_overwrite'] == 'Y')
        );
        $this->frm->addText(
            'url', (isset($this->data['url'])) ? urldecode($this->data['url']) : null
        );

        // advanced SEO
        $indexValues = array(
            array('value' => 'none', 'label' => BL::getLabel('None')),
            array('value' => 'index', 'label' => 'index'),
            array('value' => 'noindex', 'label' => 'noindex')
        );
        $this->frm->addRadiobutton(
            'seo_index',
            $indexValues,
            (isset($this->data['data']['seo_index'])) ? $this->data['data']['seo_index'] : 'none'
        );
        $followValues = array(
            array('value' => 'none', 'label' => BL::getLabel('None')),
            array('value' => 'follow', 'label' => 'follow'),
            array('value' => 'nofollow', 'label' => 'nofollow')
        );
        $this->frm->addRadiobutton(
            'seo_follow',
            $followValues,
            (isset($this->data['data']['seo_follow'])) ? $this->data['data']['seo_follow'] : 'none'
        );

        // should we add the meta-custom field
        if ($this->custom) {
            // add meta custom element into the form
            $this->frm->addTextarea(
                'meta_custom', (isset($this->data['custom'])) ? $this->data['custom'] : null
            );
        }

        $this->frm->addHidden('meta_id', $this->id);
        $this->frm->addHidden('base_field_name', $this->baseFieldName);
        $this->frm->addHidden('custom', $this->custom);
        $this->frm->addHidden('class_name', $this->callback['class']);
        $this->frm->addHidden('method_name', $this->callback['method']);
        $this->frm->addHidden('parameters', \SpoonFilter::htmlspecialchars(serialize($this->callback['parameters'])));
    }

    /**
     * Load a specific meta-record
     *
     * @param int $id The id of the record to load.
     * @throws Exception If no meta-record exists with the provided id
     */
    protected function loadMeta($id)
    {
        $this->id = (int)$id;

        // get item
        $this->data = (array)BackendModel::getContainer()->get('database')->getRecord(
            'SELECT *
             FROM meta AS m
             WHERE m.id = ?',
            array($this->id)
        );

        // validate meta-record
        if (empty($this->data)) {
            throw new Exception('Meta-record doesn\'t exist.');
        }

        // unserialize data
        if (isset($this->data['data'])) {
            $this->data['data'] = @unserialize($this->data['data']);
        }
    }

    /**
     * Saves the meta object
     *
     * @param bool $update Should we update the record or insert a new one.
     * @throws Exception If no meta id was provided.
     * @return int
     */
    public function save($update = false)
    {
        $update = (bool)$update;

        // get meta keywords
        if ($this->frm->getField('meta_keywords_overwrite', $this->language)->isChecked()) {
            $keywords = $this->frm->getField('meta_keywords', $this->language)->getValue();
        } else {
            $keywords = $this->frm->getField($this->baseFieldName, $this->language)->getValue();
        }

        // get meta description
        if ($this->frm->getField('meta_description_overwrite', $this->language)->isChecked()) {
            $description = $this->frm->getField('meta_description', $this->language)->getValue();
        } else {
            $description = $this->frm->getField($this->baseFieldName, $this->language)->getValue();
        }

        // get page title
        if ($this->frm->getField('page_title_overwrite', $this->language)->isChecked()) {
            $title = $this->frm->getField('page_title', $this->language)->getValue();
        } else {
            $title = $this->frm->getField($this->baseFieldName, $this->language)->getValue();
        }

        // get URL
        if ($this->frm->getField('url_overwrite', $this->language)->isChecked()) {
            $URL = \SpoonFilter::htmlspecialcharsDecode(
                $this->frm->getField('url', $this->language)->getValue()
            );
        } else {
            $URL = \SpoonFilter::htmlspecialcharsDecode($this->frm->getField(
                $this->baseFieldName, $this->language)->getValue()
            );
        }

        // get the real URL
        $URL = $this->generateURL($URL);

        // get meta custom
        if ($this->custom && $this->frm->getField('meta_custom', $this->language)->isFilled()) {
            $custom = $this->frm->getField('meta_custom', $this->language)->getValue(true);
        } else {
            $custom = null;
        }

        // build meta
        $meta['keywords'] = $keywords;
        $meta['keywords_overwrite'] = $this->frm->getField(
            'meta_keywords_overwrite',
            $this->language
        )->isChecked() ? 'Y' : 'N';
        $meta['description'] = $description;
        $meta['description_overwrite'] = $this->frm->getField(
            'meta_description_overwrite',
            $this->language
        )->isChecked() ? 'Y' : 'N';
        $meta['title'] = $title;
        $meta['title_overwrite'] = $this->frm->getField(
            'page_title_overwrite',
            $this->language
        )->isChecked() ? 'Y' : 'N';
        $meta['url'] = $URL;
        $meta['url_overwrite'] = $this->frm->getField(
            'url_overwrite', $this->language
        )->isChecked() ? 'Y' : 'N';
        $meta['custom'] = $custom;
        $meta['data'] = null;
        if ($this->frm->getField('seo_index', $this->language)->getValue() != 'none') {
            $meta['data']['seo_index'] =
                $this->frm->getField('seo_index', $this->language)->getValue();
        }
        if ($this->frm->getField('seo_follow', $this->language)->getValue() != 'none') {
            $meta['data']['seo_follow'] =
                $this->frm->getField('seo_follow', $this->language)->getValue();
        }
        if (isset($meta['data'])) {
            $meta['data'] = serialize($meta['data']);
        }

        $db = BackendModel::getContainer()->get('database');

        if ($update) {
            if ($this->id === null) {
                throw new Exception('No metaID specified.');
            }
            $db->update('meta', $meta, 'id = ?', array($this->id));

            return $this->id;
        } else {
            $id = (int)$db->insert('meta', $meta);

            return $id;
        }
    }

    /**
     * Set the callback to calculate an unique URL
     * REMARK: this method has to be public and static
     * REMARK: if you specify arguments they will be appended
     *
     * @param string $className Name of the class to use.
     * @param string $methodName Name of the method to use.
     * @param array $parameters Parameters to parse, they will be passed after ours.
     */
    public function setURLCallback($className, $methodName, $parameters = array())
    {
        $className = (string)$className;
        $methodName = (string)$methodName;
        $parameters = (array)$parameters;

        // store in property
        $this->callback = array('class' => $className, 'method' => $methodName, 'parameters' => $parameters);

        // re-load the form
        $this->loadForm();
    }

    /**
     * Validates the form
     * It checks if there is a value when a checkbox is checked
     */
    public function validate()
    {
        // page title overwrite is checked
        if ($this->frm->getField('page_title_overwrite', $this->language)->isChecked()) {
            $this->frm->getField('page_title', $this->language)
                ->isFilled(BL::err('FieldIsRequired'));
        }

        // meta description overwrite is checked
        if ($this->frm->getField('meta_description_overwrite', $this->language)->isChecked()) {
            $this->frm->getField('meta_description', $this->language)
                ->isFilled(BL::err('FieldIsRequired'));
        }

        // meta keywords overwrite is checked
        if ($this->frm->getField('meta_keywords_overwrite', $this->language)->isChecked()) {
            $this->frm->getField('meta_keywords', $this->language)
                ->isFilled(BL::err('FieldIsRequired'));
        }

        // URL overwrite is checked
        if ($this->frm->getField('url_overwrite', $this->language)->isChecked()) {
            $this->frm->getField('url', $this->language)
                ->isFilled(BL::err('FieldIsRequired'));
            $URL = \SpoonFilter::htmlspecialcharsDecode(
                $this->frm->getField('url', $this->language)->getValue()
            );
            $generatedUrl = $this->generateURL($URL);

            // check if urls are different
            if (CommonUri::getUrl($URL) != $generatedUrl) {
                $this->frm->getField('url', $this->language)->addError(
                    BL::err('URLAlreadyExists')
                );
            }
        }

        // if the form was submitted correctly the data array should be populated
        if ($this->frm->isCorrect()) {
            // get meta keywords
            if ($this->frm->getField('meta_keywords_overwrite', $this->language)->isChecked()) {
                $keywords = $this->frm->getField('meta_keywords', $this->language)->getValue();
            } else {
                $keywords = $this->frm->getField($this->baseFieldName, $this->language)->getValue();
            }

            // get meta description
            if ($this->frm->getField('meta_description_overwrite', $this->language)->isChecked()) {
                $description = $this->frm->getField('meta_description', $this->language)->getValue();
            } else {
                $description = $this->frm->getField($this->baseFieldName, $this->language)->getValue();
            }

            // get page title
            if ($this->frm->getField('page_title_overwrite', $this->language)->isChecked()) {
                $title = $this->frm->getField('page_title', $this->language)->getValue();
            } else {
                $title = $this->frm->getField($this->baseFieldName, $this->language)->getValue();
            }

            // get URL
            if ($this->frm->getField('url_overwrite', $this->language)->isChecked()) {
                $URL = \SpoonFilter::htmlspecialcharsDecode(
                    $this->frm->getField('url', $this->language)->getValue()
                );
            } else {
                $URL = \SpoonFilter::htmlspecialcharsDecode(
                    $this->frm->getField($this->baseFieldName, $this->language)->getValue()
                );
            }

            // get the real URL
            $URL = $this->generateURL($URL);

            // get meta custom
            if ($this->custom && $this->frm->getField('meta_custom', $this->language)->isFilled()) {
                $custom = $this->frm->getField('meta_custom', $this->language)->getValue();
            } else {
                $custom = null;
            }

            // set data
            $this->data['keywords'] = $keywords;
            $this->data['keywords_overwrite'] = $this->frm->getField(
                'meta_keywords_overwrite',
                $this->language
            )->isChecked() ? 'Y' : 'N';
            $this->data['description'] = $description;
            $this->data['description_overwrite'] = $this->frm->getField(
                'meta_description_overwrite',
                $this->language
            )->isChecked() ? 'Y' : 'N';
            $this->data['title'] = $title;
            $this->data['title_overwrite'] = $this->frm->getField(
                'page_title_overwrite',
                $this->language
            )->isChecked() ? 'Y' : 'N';
            $this->data['url'] = $URL;
            $this->data['url_overwrite'] = $this->frm->getField(
                'url_overwrite',
                $this->language
            )->isChecked() ? 'Y' : 'N';
            $this->data['custom'] = $custom;
            if ($this->frm->getField('seo_index', $this->language)->getValue() == 'none') {
                unset($this->data['data']['seo_index']);
            } else {
                $this->data['data']['seo_index'] =
                    $this->frm->getField('seo_index', $this->language)->getValue();
            }
            if ($this->frm->getField('seo_follow', $this->language)->getValue() == 'none') {
                unset($this->data['data']['seo_follow']);
            } else {
                $this->data['data']['seo_follow'] =
                    $this->frm->getField('seo_follow', $this->language)->getValue();
            }
        }
    }
}
