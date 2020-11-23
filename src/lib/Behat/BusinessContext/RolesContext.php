<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\BusinessContext;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\UniversalDiscoveryWidget;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\RolePage;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\RoleUpdatePage;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Exception;

class RolesContext implements Context
{
    private $tabMapping = [
        'policy' => 'Policies',
        'assignment' => 'Assignments',
    ];

    private $fields = [
        'newPolicySelectList' => 'policy_create_policy',
        'newPolicyAssignmentLimitation' => 'role_assignment_create_sections',
    ];
    /**
     * @var RolePage
     */
    private $rolePage;
    /**
     * @var RoleUpdatePage
     */
    private $roleUpdatePage;
    /**
     * @var UniversalDiscoveryWidget
     */
    private $universalDiscoveryWidget;

    public function __construct(RolePage $rolePage, RoleUpdatePage $roleUpdatePage, UniversalDiscoveryWidget $universalDiscoveryWidget)
    {
        $this->rolePage = $rolePage;
        $this->roleUpdatePage = $roleUpdatePage;
        $this->universalDiscoveryWidget = $universalDiscoveryWidget;
    }

    /**
     * @When I start assigning users and groups to :roleName from role page
     */
    public function iStartAssigningTo(string $roleName): void
    {
        $this->rolePage->setExpectedRoleName($roleName);
        $this->rolePage->goToTab($this->tabMapping['assignment']);
        throw new \Exception('tutaj tez cos trzeba przeorac...');
        $this->rolePage->adminLists[$this->tabMapping['assignment']]->clickAssignButton();
    }

    /**
     * @When I select limitation :itemPath for :tabName through UDW
     * @When I select :kind limitation :itemPath for :tabName through UDW
     */
    public function iSelectSubtreeLimitationThroughUDW(string $itemPath, string $tabName, ?string $kind = null): void
    {
        $buttonNo = $kind === 'subtree' ? 1 : 0;
        $buttonLabel = $tabName === 'assignment' ? 'Select Subtree' : 'Select Locations';

        if ('assignment' === $tabName) {
            $this->roleUpdatePage->fillFieldWithValue('Subtree', 'true');
        }

        $this->roleUpdatePage->clickButton($buttonLabel, $buttonNo);
        $this->universalDiscoveryWidget->verifyIsLoaded();
        $this->universalDiscoveryWidget->selectContent($itemPath);
        $this->universalDiscoveryWidget->confirm();
    }

    /**
     * @When I delete :itemType from :roleName role
     */
    public function iDeleteManyFromRole(string $itemType, string $roleName, TableNode $settings): void
    {
        $this->rolePage->setExpectedRoleName($roleName);
        $this->rolePage->goToTab($this->tabMapping[$itemType]);

        throw new \Exception('tutaj znowu cos dziwnego');
        $adminList = $rolePage->adminLists[$this->tabMapping[$itemType]];

        $elements = $settings->getHash();
        foreach ($elements as $element) {
            $adminList->table->selectListElement($element['item']);
        }

        // tak by to mialo wygladac...
        foreach ($settings->getHash() as $elements){
            $this->rolePage->selectElement(['Name' => $element['item']]);
        }

        $this->rolePage->deleteSelectedItems();
    }

    /**
     * @Then there is a policy :moduleAndFunction with :limitation limitation on the :roleName policies list
     */
    public function thereIsAPolicy(string $moduleAndFunction, string $limitation, string $roleName): void
    {
        $this->rolePage->setExpectedRoleName($roleName);
        Assert::assertTrue($this->rolePage->isRoleWithLimitationPresent($this->tabMapping['policy'], $moduleAndFunction, $limitation));
    }

    /**
     * @Then there is no policy :moduleAndFunction with :limitation limitation on the :roleName policies list
     */
    public function thereIsNoPolicy(string $moduleAndFunction, string $limitation, string $roleName): void
    {
        $this->rolePage->setExpectedRoleName($roleName);
        Assert::assertFalse($this->rolePage->isRoleWithLimitationPresent($this->tabMapping['policy'], $moduleAndFunction, $limitation));
    }

    /**
     * @Then there is an assignment :limitation for :userOrGroup on the :roleName assignments list
     */
    public function thereIsAnAssignment(string $limitation, string $userOrGroup, string $roleName): void
    {
        $this->rolePage->setExpectedRoleName($roleName);
        $this->rolePage->goToTab($this->tabMapping['assignment']);

        throw new \Exception('zaorac...');
        $adminList = $rolePage->adminLists[$this->tabMapping['assignment']];
        $actualAssignmentList = $adminList->table->getTableHash();
        $assignmentExists = false;
        foreach ($actualAssignmentList as $policy) {
            if ($policy['User/Group'] === $userOrGroup && (strpos($policy['Limitation'], $limitation) !== false)) {
                $assignmentExists = true;
            }
        }

        if (!$assignmentExists) {
            throw new Exception(sprintf('Assignment to "%s" with Limitation "%s" not found.', $userOrGroup, $limitation));
        }
    }

    /**
     * @Then there are policies on the :roleName policies list
     */
    public function thereArePolicies(string $roleName, TableNode $settings): void
    {
        $policies = $settings->getHash();
        foreach ($policies as $policy) {
            $this->thereIsAPolicy($policy['policy'], $policy['limitation'], $roleName);
        }
    }

    /**
     * @Then there are assignments on the :roleName assignments list
     */
    public function thereAreAssignments(string $roleName, TableNode $settings): void
    {
        $policies = $settings->getHash();
        foreach ($policies as $policy) {
            $this->thereIsAnAssignment($policy['limitation'], $policy['user/group'], $roleName);
        }
    }

    /**
     * @When I select policy :policyName
     */
    public function iSelectPolicy(string $policyName): void
    {
        $this->browserContext->selectOption($this->fields['newPolicySelectList'], $policyName);
    }

    /**
     * @When I select :limitationName from Sections as role assignment limitation
     */
    public function iSelectSectionLimitation(string $limitationName): void
    {
        throw new \Exception('refactor me...');
        // PageObjectFactory::createPage($this->browserContext, AdminUpdateItemPage::PAGE_NAME)
        //     ->adminUpdateForm->fillFieldWithValue('Sections', 'true');
        // $this->browserContext->selectOption($this->fields['newPolicyAssignmentLimitation'], $limitationName);
    }

    /**
     * @When I assign :itemType to role
     */
    public function iAssignToRole(string $itemType, TableNode $items): void
    {
        $this->roleUpdatePage->assign($items->getHash(), $itemType);
    }

    /**
     * @When I select limitation for :selectName
     */
    public function iSelectOptionsFrom(string $selectName, TableNode $options): void
    {
        $values = [];

        foreach ($options->getHash() as $option) {
            $values[] = $option['option'];
        }

        $this->roleUpdatePage->selectLimitationValues($selectName, $values);
    }
}
