<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement;

use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Selector\CSSSelector;
use PHPUnit\Framework\Assert;

class DraftConflictDialog extends Component
{
    public function createNewDraft(): void
    {
        $this->getHTMLPage()->find($this->getSelector('addDraft'))->click();
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertTrue($this->getHTMLPage()->find($this->getSelector('dialog'))->isVisible());
    }

    protected function specifySelectors(): array
    {
        return [
            new CSSSelector('dialog', '.ez-modal--version-draft-conflict.show'),
            new CSSSelector('addDraft', '.ez-btn--add-draft'),
        ];
    }

    public function getTableCellValue(string $header, ?string $secondHeader = null): string
    {

//        $this->fields['listElement'] = $this->fields['list'] . ' tbody td:nth-child(1)';
//        $this->fields['editButton'] = $this->fields['list'] . ' tr:nth-child(%s) .ez-icon-edit';
//
        $columnPosition = $this->context->getElementPositionByText(
            $header,
            $this->fields['horizontalHeaders']
        );
        $rowPosition = $this->context->getElementPositionByText(
            $secondHeader,
            $this->fields['listElement']
        );

        return $this->getCellValue($rowPosition, $columnPosition);
    }

    public function edit(string $draftName): void
    {
        $this->clickEditButtonByElementLocator($draftName, $this->fields['listElement']);
    }
}
