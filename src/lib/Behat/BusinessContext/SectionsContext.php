<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\BusinessContext;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\SectionPage;
use PHPUnit\Framework\Assert;

class SectionsContext implements Context
{
    /**
     * @var SectionPage
     */
    private $sectionPage;

    public function __construct(SectionPage $sectionPage)
    {
        $this->sectionPage = $sectionPage;
    }

    /**
     * @Then content items list in section :sectionName contains items
     */
    public function sectionContainsProperContentItems(string $sectionName, TableNode $contentItems): void
    {
        $this->sectionPage->setExpectedSectionName($sectionName);

        foreach ($contentItems->getHash() as $contentItem) {
            $this->sectionPage->verifyContentItem($contentItem['Name'], $contentItem['Content Type'], $contentItem['Path']);
        }
    }

    /**
     * @Then There's empty :sectionName on list
     */
    public function goToSectionsAndVerifySectionIsEmpty(string $sectionName): void
    {
        Assert::assertEquals(0, $this->sectionPage->getAssignedContentCount($sectionName));
    }
}
