# Fork CMS Localization
## Introduction
Module was created to serve as translator of data. Sometimes Fork CMS default language functionality does not work in way we need to.
So i decided to create module which will be used in place where we need single ID records.
For example restaurant menu, with default functionality we need to create separate record from every item within menu.
With this module you will be able to develop module where item will be translated into several languages at its creation or editing stage.

## Requirements
* Core: Fork CMS 3.9.0
* Module: [Entities](https://github.com/vytenizs/forkcms-module-entities)

## Usage
### Database
Every table should have its own locale table.
For example:
* articles
* articles_locale
* articles_categories
* articles_categories_locale

Each locale table should have primary key consisted of fields `id(int 11)` and `language(varchar 5)`.
Where `id(int 11)` is record id and `language(varchar 5)` is language code.

Example tables:

articles
* id(int 11)
* category_id(int 11)
* hidden(tinyint 1)

articles_locale
* id(int 11)
* language(varchar 5)
* title(varchar 255)
* introduction(text)
* text(text)

### Action files
#### Namespaces
First of all we need to load proper namespaces

```
use Backend\Modules\Localization\Engine\Form as BackendLocalizationForm;
use Backend\Modules\Localization\Engine\Locale as BackendLocalizationLocale;
```

#### Execute
Later you need to initialize localization object within our action.

```
$this->locale = new BackendLocalizationLocale();
```

#### Load form
Later where your form is loaded you have to put code that loops through each active language and sets form related data.

Initialize localization form

```
$this->frm = new BackendLocalizationForm($this->locale, 'addCategory');
```

Create localized fields into your form

```
while ($language = $this->locale->loopLanguage()) {
    $this->frm->addText('title');
    $this->frm->addEditor('text');
    $this->frm->addEditor('introduction');
    $language->setMeta($this->frm);
    $language->getMeta()->setUrlCallback(
        'Backend\\Modules\\' . $this->URL->getModule() . '\\Engine\\Model',
        'getURLForCategory'
    );
    $this->locale->nextLanguage();
}
```

#### Parse
We will use some custom variables and modifiers for our localization module, so we need to parse them,
this should be put inside parse method of your action.

```
$this->locale->parse($this->tpl);
```

#### Validate form
At we end we want to check and save our data.

##### Check data

```
while ($language = $this->locale->loopLanguage()) {
    $this->frm->getField('title', $language)->isFilled(BL::err('TitleIsRequired'));
    $language->getMeta()->validate();
    $this->locale->nextLanguage();
}
```

##### Collect and save data

```
while ($language = $this->locale->loopLanguage()) {
    $categoryLocale = new CategoryLocale();
    $categoryLocale
        ->setId($this->record->getId())
        ->setLanguage($language->getCode())
        ->setTitle($this->frm->getField('title', $language)->getValue())
        ->setIntroduction($this->frm->getField('introduction', $language)->getValue())
        ->save();
    $this->record->setLocale($categoryLocale, $language->getCode());
    $language->getMeta()->save();
    $this->locale->nextLanguage();
}
```

### Template files
Localization is easy to implement within templates. It contains simple `$formLocalization` array which have been parsed
through `$this->locale->parse($this->tpl);` method.

```
{iteration:formLocalization}
  {* Language code (en, ru, lt) *}
  {$formLocalization.code}
  {* Language code (english, russian, lithuanian) *}
  {$formLocalization.title}
  {* If meta was set, there will be seo variable available which loads SEO fields *}
  {option:formLocalization.seo}
    {$formLocalization.seo}
  {option:formLocalization.seo}
  {* Fields array with all fields (title, introduction, text) *}
  {$formLocalization.fields.title}
  {* Errors array with all fields errors (title, introduction, text) *}
  {$formLocalization.errors.title}
{/iteration:formLocalization}
```

## Issues
If you are having any issues, please create issue at [Github](https://github.com/vytenizs/forkcms-module-localization/issues).
Or contact me directly. Thank you.

## Contacts

* e-mail: info@vytsci.lt
* skype: vytenizs
