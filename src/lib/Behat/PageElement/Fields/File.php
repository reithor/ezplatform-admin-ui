<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Fields;

use EzSystems\Behat\Browser\Selector\CSSSelector;
use PHPUnit\Framework\Assert;

class File extends FieldTypeComponent
{
    public function setValue(array $parameters): void
    {
        $fieldSelector = CSSSelector::combine("%s %s", $this->parentSelector, $this->getSelector('fieldInput'));
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
            'Image has wrong file name'
        );

        $fileFieldSelector = CSSSelector::combine("%s %s", $this->parentSelector, $this->getSelector('file'));

        Assert::assertContains(
            $filename,
            $this->getHTMLPage()->find($fileFieldSelector)->getAttribute('href'),
            'File has wrong href'
        );
    }

    public function specifySelectors(): array
    {
        return [
            new CSSSelector('fieldInput', 'input[type=file]'),
            new CSSSelector('file', '.ezbinaryfile-field a'),
        ];
    }

    public function getFieldTypeIdentifier(): string
    {
        return 'ezbinaryfile';
    }
}
