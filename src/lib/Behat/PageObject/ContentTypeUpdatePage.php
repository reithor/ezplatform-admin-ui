<?php


namespace EzSystems\EzPlatformAdminUi\Behat\PageObject;

use EzSystems\Behat\API\ContentData\FieldTypeNameConverter;
use EzSystems\Behat\Browser\Element\NodeElement;
use EzSystems\Behat\Browser\Locator\VisibleCSSLocator;
use EzSystems\Behat\Browser\Page\Browser;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\Notification;
use EzSystems\EzPlatformAdminUi\Behat\PageElement\RightMenu;

class ContentTypeUpdatePage extends AdminUpdateItemPage
{
    /**
     * @var Notification
     */
    private $notification;

    public function __construct(Browser $browser, RightMenu $rightMenu, Notification $notification)
    {
        parent::__construct($browser, $rightMenu);
        $this->notification = $notification;
    }

    public function fillFieldDefinitionFieldWithValue(string $fieldName, string $label, string $value)
    {
        $this->expandFieldDefinition($fieldName);

        $this->getFieldDefinition($fieldName)
            ->findAll($this->getLocator('field'))->getByText($label)
            ->find($this->getLocator('fieldInput'))->setValue($value);
    }

    public function expandFieldDefinition(string $fieldName): void
    {
        $fieldDefinition = $this->getFieldDefinition($fieldName);

        if ($fieldDefinition->hasClass($this->getLocator('fieldCollapsed')->getSelector())) {
            $fieldDefinition->find($this->getLocator('fieldDefinitionToggler'))->click();
        }
    }

    public function specifyLocators(): array
    {
        return array_merge(parent::specifyLocators(), [
            new VisibleCSSLocator('fieldTypesList', '#ezplatform_content_forms_contenttype_update_fieldTypeSelection'),
            new VisibleCSSLocator('addFieldDefinition', '#ezplatform_content_forms_contenttype_update_addFieldDefinition'),
            new VisibleCSSLocator('fieldDefinitionContainer', '.ez-card--toggle-group'),
            new VisibleCSSLocator('fieldDefinitionName', '.ez-card--toggle-group .ez-card__header .form-check-label'),
            new VisibleCSSLocator('fieldBody', 'ez-card__body'),
            new VisibleCSSLocator('fieldCollapsed', 'ez-card--collapsed'),
            new VisibleCSSLocator('fieldDefinitionToggler', '.ez-card__body-display-toggler'),
        ]);
    }

    public function addFieldDefinition(string $fieldName)
    {
        $this->getHTMLPage()->find($this->getLocator('fieldTypesList'))->selectOption($fieldName);
        $this->getHTMLPage()->find($this->getLocator('addFieldDefinition'))->click();
        $this->getFieldDefinition($fieldName)->assert()->isVisible();

        $this->notification->verifyIsLoaded();
        $this->notification->verifyAlertSuccess();
        $this->notification->closeAlert();
    }

    private function getFieldDefinition($fieldName): NodeElement
    {
        $fieldTypeIdentifier =  FieldTypeNameConverter::getFieldTypeIdentifierByName($fieldName);

        return $this->getHTMLPage()
            ->findAll($this->getLocator('fieldDefinitionContainer'))
            ->filter(function(NodeElement  $element) use ($fieldTypeIdentifier) {
                return strpos($element->find($this->getLocator('fieldDefinitionName'))->getText(), $fieldTypeIdentifier) !== false;
            })
            ->first();
    }

}
