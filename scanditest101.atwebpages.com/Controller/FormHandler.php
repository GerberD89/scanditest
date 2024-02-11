<?php
class FormHandler
{
    public static function generateDropdownAndContainers()
    {
        $databaseConnection = DatabaseConnection::getInstance();
        $pdo = $databaseConnection->getInstance();
        
        $productTypes = DatabaseQueryHandler::fetchProductTypes($pdo);
        $tableNames = DatabaseQueryHandler::fetchTableNames($pdo);
        $requiredDescriptions = DatabaseQueryHandler::fetchRequiredDescriptions($pdo);

        // Generate HTML for dropdown
        $optionsHtml = '<option value="">Typeswitcher</option>';
        for ($i = 0; $i < count($productTypes); $i++) {
            $productType = $productTypes[$i];
            $tableName = $tableNames[$i];
            $optionsHtml .= "<option value='$tableName' id='$productType'>" . ucwords($productType) . "</option>";
        }

        // Generate HTML for required descriptions
        $scriptHtml = '<script>';
        $scriptHtml .= 'var productTypes = ' . json_encode($productTypes) . ';';
        $scriptHtml .= 'var tableNames = ' . json_encode($tableNames) . ';';
        $scriptHtml .= 'var requiredDescriptions = ' . json_encode($requiredDescriptions) . ';';
        $scriptHtml .= '</script>';

        // HTML for the dynamic form fields container
        $dynamicFormFieldsContainerHtml = '<div id="dynamicFormFieldsContainer"></div>';

        // HTML for the required description container
        $requiredDescriptionContainerHtml = '<div class="form-group row m-5">';
        $requiredDescriptionContainerHtml .= '<div class="col-sm-3 col-form-label" id="requiredDescriptionContainer">';
        $requiredDescriptionContainerHtml .= $scriptHtml;
        $requiredDescriptionContainerHtml .= '</div>';
        $requiredDescriptionContainerHtml .= '</div>';

        return [
            'optionsHtml' => $optionsHtml,
            'dynamicFormFieldsContainerHtml' => $dynamicFormFieldsContainerHtml,
            'requiredDescriptionContainerHtml' => $requiredDescriptionContainerHtml,
        ];
    }
}






