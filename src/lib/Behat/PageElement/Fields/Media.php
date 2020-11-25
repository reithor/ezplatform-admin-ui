<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use EzSystems\Behat\Browser\Locator\CSSLocator;
use PHPUnit\Framework\Assert;

class Media extends FieldTypeComponent
{
    public function setValue(array $parameters): void
    {
        $fieldSelector = $this->parentSelector->withDescendant($this->getLocator('fieldInput'));
        $this->getHTMLPage()->find($fieldSelector)->attachFile(
            $this->browser->getRemoteFileUploadPath($parameters['value'])
        );
    }

    public function verifyValueInItemView(array $values): void
    {
        $filename = str_replace('.zip', '', $values['value']);

        Assert::assertContains(
            $filename,
            $this->getHTMLPage()->find($this->parentSelector)->getText(),
            'Media has wrong file name'
        );

        Assert::assertContains(
            $filename,
            $this->getHTMLPage()->find(
                $this->parentSelector->withDescendant($this->getLocator('video'))
            )->getAttribute('src'),
            'Media has wrong source'
        );
    }

    protected function specifyLocators(): array
    {
        return [
            new CSSLocator('fieldInput', 'input[type=file]'),
            new CSSLocator('video', 'video'),
        ];
    }

    public function getFieldTypeIdentifier(): string
    {
        return 'ezmedia';
    }
}
