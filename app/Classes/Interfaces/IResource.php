<?php

namespace App\Classes\Interfaces;

/**
 * Interface with the factory methods for the resources
 */
interface IResource{
    public function saveResource($data, $returnResource);
    public function saveTranslation($data, $TranslatedInstace);
    public function deleteResource();
    public static function getList(Array $filterOptions, $displayField='name', $translationFile=null);
    public static function get($id);
    public static function dropdownOptions($valueField, $textField);
}

