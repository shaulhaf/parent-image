<?php

namespace Capitalc\AdvancedNovaMediaLibrary\Fields;

class Images extends Media
{
    public $component = 'parentimage';

    protected $defaultValidatorRules = ['image'];

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->croppable();
    }

    
    public function resolve($resource, $attribute = null)
    {
        if ($this->meta['extraAttributes']['model']) {
            $parent = $this->meta['extraAttributes']['model'];

            $collectionName = $attribute ?? $this->attribute;

            $this->value = $parent->getMedia($collectionName)
                ->map(function (\Spatie\MediaLibrary\Models\Media $media) {
                    return array_merge($this->serializeMedia($media), ['__media_urls__' => $this->getConversionUrls($media)]);
                });

            // $this->value = array_values(array_map(function ($item) {
            //     return $item;
            // }, $this->value));

            if ($collectionName) {
                $this->checkCollectionIsMultiple($parent, $collectionName);
            }
        } else {
            parent::resolve($resource, $attribute = null);
        }
    }

    /**
     * Do we deprecate this for SingleMediaRules?
     * @param $singleImageRules
     * @return Images
     */
    public function singleImageRules($singleImageRules): self
    {
        $this->singleMediaRules = $singleImageRules;

        return $this;
    }

	public function croppable(bool $croppable = true): self
	{
		return $this->withMeta(compact('croppable'));
	}

	public function croppingConfigs(array $configs): self
	{
		return $this->withMeta(['croppingConfigs' => $configs]);
	}
}
