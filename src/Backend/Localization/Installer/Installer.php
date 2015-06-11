<?php

namespace Backend\Modules\Localization\Installer;

use Backend\Core\Installer\ModuleInstaller;

/**
 * Class Installer
 * @package Backend\Modules\Localization\Installer
 */
class Installer extends ModuleInstaller
{
    /**
     * Install the module
     */
    public function install()
    {
        $this->addModule('Localization');
        $this->setModuleRights(1, 'Localization');
    }
}
