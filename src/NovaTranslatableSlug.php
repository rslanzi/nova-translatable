<?php

namespace Rslanzi\NovaTranslatable;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Element;
use Laravel\Nova\Http\Requests\NovaRequest;

class NovaTranslatableSlug extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-translatable-slug-field';
    
    protected $options = [];

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|null  $attribute
     * @param  mixed|null  $resolveCallback
     * @return void
     */
    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $locales = array_map(function ($value) {
            return __($value);
        }, config('translatable.locales'));

        $this->withMeta([
            'locales' => $locales,
            'indexLocale' => app()->getLocale()
        ]);
    }

    /**
     * Resolve the given attribute from the given resource.
     *
     * @param  mixed  $resource
     * @param  string  $attribute
     * @return mixed
     */
    protected function resolveAttribute($resource, $attribute)
    {
        $results = [];
        if ( class_exists('\Spatie\Translatable\TranslatableServiceProvider') ) {
            $results = $resource->getTranslations($attribute);
        } elseif ( class_exists('\Astrotomic\Translatable\TranslatableServiceProvider') ) {
            $translations = $resource->translations()
                ->get([config('translatable.locale_key'), $attribute])
                ->toArray();
            foreach ( $translations as $translation ) {
                $results[$translation[config('translatable.locale_key')]] = $translation[$attribute];
            }
        }
        return $results;
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  string  $requestAttribute
     * @param  object  $model
     * @param  string  $attribute
     * @return void
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ( class_exists('\Spatie\Translatable\TranslatableServiceProvider') ) {
            parent::fillAttributeFromRequest($request, $requestAttribute, $model, $attribute);
        } elseif ( class_exists('\Astrotomic\Translatable\TranslatableServiceProvider') ) {
            if ( is_array($request[$requestAttribute]) ) {
                foreach ( $request[$requestAttribute] as $lang => $value ) {
                    $model->translateOrNew($lang)->{$attribute} = $value;
                }
        }
        }
    }

    /**
     * Set the locales to display / edit.
     *
     * @param  array  $locales
     * @return $this
     */
    public function locales(array $locales)
    {
        return $this->withMeta(['locales' => $locales]);
    }

    public function slugModel(string $model): Element
    {
        return $this->withMeta(['model' => $model]);
    }
    
    public function slugUnique(): Element
	{
		return $this->setOption('generateUniqueSlugs', true);
	}

	public function slugMaxLength(int $length): Element
	{
		return $this->setOption('maximumLength', $length);
	}

	public function slugSeparator(string $separator): Element
	{
		return $this->setOption('slugSeparator', $separator);
	}

	public function slugLanguage(string $language): Element
	{
		return $this->setOption('slugLanguage', $language);
	}
	
	protected function setOption(string $name, string $value): Element
	{
		$this->options[$name] = $value;
		return $this->withMeta(['options' => $this->options]);
	}

    /**
     * Use Counted.
     */
    public function counted()
    {
        return $this->withMeta(['counted' => true]);
    }

    public function maxChars(int $characters)
    {
        return $this->withMeta(['maxChars' => $characters]);
    }

    public function warningAt(int $characters)
    {
        return $this->withMeta(['warningAt' => $characters]);
    }
}
