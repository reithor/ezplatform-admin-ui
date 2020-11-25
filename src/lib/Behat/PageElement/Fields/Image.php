<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use EzSystems\Behat\Browser\Locator\CSSLocator;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use PHPUnit\Framework\Assert;

class Image extends FieldTypeComponent
{
    public function setValue(array $parameters): void
    {
        $fieldSelector = $this->getLocator('fieldInput')->withParent($this->parentLocator);

        $this->getHTMLPage()->find($fieldSelector)->attachFile(
            $this->browser->getRemoteFileUploadPath($parameters['value'])
        );
    }

    public function verifyValueInItemView(array $values): void
    {
        $filename = str_replace('.zip', '', $values['value']);

        Assert::assertContains(
            $filename,
            $this->getHTMLPage()->find($this->parentLocator)->getText(),
            'Image has wrong file name'
        );

        $fileFieldSelector = $this->parentLocator->withDescendant($this->getLocator('image'));

        Assert::assertContains(
            $filename,
            $this->getHTMLPage()->find($fileFieldSelector)->getAttribute('src'),
            'Image has wrong source'
        );
    }

    public function specifyLocators(): array
    {
        return [
            new CSSLocator('fieldInput', 'input[type=file]'),
            new VisibleCSSLocator('image', '.ez-field-preview__image-wrapper .ez-field-preview__image img'),
        ];
    }

    public function getFieldTypeIdentifier(): string
    {
        return 'ezimage';
    }
}
