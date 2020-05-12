<?php

namespace Capitalc\AdvancedNovaMediaLibrary\Fields;

class Images extends \Ebess\AdvancedNovaMediaLibrary\Fields\Media
{
    public $component = 'parentimage';

    public function resolve($resource, $attribute = null)
    {
        if ($this->meta['extraAttributes']['model']) {
            $resource = $this->meta['extraAttributes']['model'];
        }
        return parent::resolve($resource, $attribute = null);
    }
}
