# Fork CMS Localization
## Introduction
Module was created to serve as translator of data. Sometimes Fork CMS default language functionality does not work in way we need to.
So i decided to create module which will be used in place where we need single ID records.
For example restaurant menu, with default functionality we need to create separate record from every item within menu.
With this module you will be able to develop module where item will be translated into several languages at its creation or editing stage.

## Requirements
* Core: Fork CMS 3.9.0
* Module: Entities (https://github.com/vytenizs/forkcms-module-entities)

## Usage examples
### Action files
#### Initialization
First of all you need to initialize localization object within your action.

```
$this->locale = new BackendLocalizationLocale();
```

#### Form building
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

#### Parse template variables
We will use some custom variables and modifiers for our localization module, so we need to parse them,
this should be put inside parse method of your action.

```
$this->locale->parse($this->tpl);
```

#### Retrieving and saving data
At we end we want to save our data, last part will go through languages again and collect form data.

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
