<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use EzSystems\Behat\Browser\Page\Browser;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\RightMenu;
use Traversable;

class UserUpdatePage extends ContentUpdateItemPage
{
    public function __construct(Browser $browser, RightMenu $rightMenu, Traversable $fieldTypeComponents)
    {
        parent::__construct($browser, $rightMenu, $fieldTypeComponents);
        $this->locators->replace(
            new VisibleCSSLocator(
                'formElement',
                '[name=ezplatform_content_forms_user_create],[name=ezplatform_content_forms_user_update]'
            )
        );
    }
}
