<?php

namespace Rslanzi\NovaTranslatable;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Expandable;
use Laravel\Nova\Element;

class NovaTranslatable extends Field
{
    use Expandable;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-translatable';

    /**
     * The available locales.
     *
     * @var array
     */
    protected $locales = [];

    /**
     * Indicates the field kind
     *
     * @var string
     */
    public $fieldType = 'text';

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

        $this->locales = array_map(function ($value) {
            return __($value);
        }, config('translatable.locales'));
        
        $this->withMeta([
            'locales' => $this->locales,
            'indexLocale' => app()->getLocale()
        ]);
    }

    /**
     * Set configuration options for the CKEditor editor instance.
     *
     * @param  array $options
     * @return $this
     */
    public function options($options)
    {
        $currentOptions = $this->meta['options'] ?? [];

        return $this->withMeta([
            'options' => array_merge($currentOptions, $options),
        ]);
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'shouldShow' => $this->shouldBeExpanded(),
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
        }

        if ( class_exists('\Astrotomic\Translatable\TranslatableServiceProvider') ) {
            $translations = $resource->translations()
                ->get([config('translatable.locale_key'), $attribute]);
            
            foreach ($this->locales as $locale => $label) {
                $value = $translations->where('locale', $locale)->first()->$attribute ?? '';
                if ($this->fieldType == 'json') {
                    $results[$locale] = json_encode(json_decode($value), JSON_PRETTY_PRINT);;
                } else {
                    $results[$locale] = $value;
                }
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
        }

        if ( class_exists('\Astrotomic\Translatable\TranslatableServiceProvider') ) {
            if ( is_array($request[$requestAttribute]) ) {
                foreach ( $request[$requestAttribute] as $lang => $value ) {
                    if ($value) {
                        $model->translateOrNew($lang)->{$attribute} = $value;
                    }
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

    /**
     * Set the locale to display on index.
     *
     * @param  string $locale
     * @return $this
     */
    public function indexLocale($locale)
    {
        return $this->withMeta(['indexLocale' => $locale]);
    }

    /**
     * Set the input field to a single line text field.
     */
    public function singleLine()
    {
        return $this->withMeta(['singleLine' => true]);
    }

    /**
     * Use Code field.
     */
    public function code()
    {
        $this->fieldType = 'code';

        return $this->withMeta(['code' => true]);
    }

    /**
     * Use as JSON field.
     */
    public function json()
    {
        $this->fieldType = 'json';

        return $this->withMeta([
            'code' => true, 
            'mode' => 'application/json'
        ]);
    }

    /**
     * Use Trix Editor.
     */
    public function trix()
    {
        $this->fieldType = 'trix';

        return $this->withMeta(['trix' => true]);
    }

    /**
     * Use CKEditor.
     */
    public function ckeditor()
    {
        $this->fieldType = 'ckeditor';

        return $this->withMeta([
            'ckeditor' => true,
            'options' => config('nova.ckeditor-field.options', [])
        ]);
    }

    /**
     * Use Sluggable.
     */
    public function sluggable($slugField = 'Slug'): Element
    {
        $this->fieldType = 'sluggable';

        return $this->withMeta([
            'sluggable' => true,
            'slug' => $slugField
        ]);
    }

    /**
     * Use Counted.
     */
    public function counted()
    {
        $this->fieldType = 'counted';

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
