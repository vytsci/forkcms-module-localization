# Fork CMS Localization
## Introduction
Module was created to serve as translator of data. Sometimes Fork CMS default language functionality does not work in way we need to.
So i decided to create module which will be used in place where we need single ID records.
For example restaurant menu, with default functionality we need to create separate record from every item within menu.
With this module you will be able to develop module where item will be translated into several languages at its creation or editing stage.

## Requirements
* Core: Fork CMS 3.9.0
* Module: Entities (https://github.com/vytenizs/forkcms-module-entities)

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

### Action files
#### Execute
First of all you need to initialize localization object within our action.

```
$this->locale = new BackendLocalizationLocale();
```

#### Load form
Later where your form is loaded you have to put code that loops through each active language and sets form related data.

```
while ($language = $this->locale->loopLanguage()) {
    $this->frm->addText(
        'title', null, null, 'form-control title', 'form-control danger title'
    );
    $this->frm->addEditor(
        'introduction', null, 'form-control introduction', 'form-control danger introduction'
    );
    $this->frm->addEditor(
        'text', null, 'form-control introduction', 'form-control danger introduction'
    );
    $language->setMeta($this->frm);
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
At we end we want to save our data, localization will go through languages again and will collect form data.

```
while ($language = $this->locale->loopLanguage()) {
    $recordLocale = new EntityArticleLocale();
    $recordLocale
        ->setTable(BackendArticlesModel::TBL_ARTICLES_LOCALE)
        ->setId($this->record->getId())
        ->setLanguage($language->getCode())
        ->setTitle($this->frm->getField('title', $language->getCode())->getValue())
        ->setIntroduction($this->frm->getField('introduction', $language->getCode())->getValue())
        ->setText($this->frm->getField('text', $language->getCode())->getValue())
        ->insert();
    $this->record->setLocale($language->getCode(), $recordLocale);
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

