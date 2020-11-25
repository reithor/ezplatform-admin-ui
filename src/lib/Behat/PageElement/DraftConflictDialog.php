<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement;

use EzSystems\Behat\Browser\Component\Component;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use PHPUnit\Framework\Assert;

class DraftConflictDialog extends Component
{
    public function createNewDraft(): void
    {
        $this->getHTMLPage()->find($this->getLocator('addDraft'))->click();
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertTrue($this->getHTMLPage()->find($this->getLocator('dialog'))->isVisible());
    }

    protected function specifyLocators(): array
    {
        return [
            new VisibleCSSLocator('dialog', '.ez-modal--version-draft-conflict.show'),
            new VisibleCSSLocator('addDraft', '.ez-btn--add-draft'),
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
