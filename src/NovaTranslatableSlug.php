<?php

namespace Rslanzi\NovaTranslatable;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class NovaTranslatableSlug extends Text
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
     * @param string      $name
     * @param string|callable|null $attribute
     * @param mixed|null  $resolveCallback
     *
     * @return void
     */
    public function __construct(string $name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $locales = array_map(function (string $value) {
            return __($value);
        }, config('translatable.locales'));

        $this->withMeta([
            'locales' => $locales,
            'indexLocale' => app()->getLocale(),
        ]);
    }

    /**
     * Resolve the given attribute from the given resource.
     *
     * @param mixed  $resource
     * @param string $attribute
     *
     * @return mixed
     */
    protected function resolveAttribute($resource, $attribute)
    {
        $results = [];

        if (class_exists('\Spatie\Translatable\TranslatableServiceProvider')) {
            return $resource->getTranslations($attribute);
        }

        if (class_exists('\Astrotomic\Translatable\TranslatableServiceProvider')) {
            $translations = $resource->translations()
                ->get([config('translatable.locale_key'), $attribute])
                ->toArray();
            foreach ($translations as $translation) {
                $results[$translation[config('translatable.locale_key')]] = $translation[$attribute];
            }
        }

        return $results;
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param string                                  $requestAttribute
     * @param object                                  $model
     * @param string                                  $attribute
     *
     * @return void
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute): void
    {
        if (class_exists('\Spatie\Translatable\TranslatableServiceProvider')) {
            parent::fillAttributeFromRequest($request, $requestAttribute, $model, $attribute);
        } elseif (class_exists('\Astrotomic\Translatable\TranslatableServiceProvider')) {
            if (is_array($request[$requestAttribute])) {
                foreach ($request[$requestAttribute] as $lang => $value) {
                    $model->translateOrNew($lang)->{$attribute} = $value;
                }
            }
        }
    }

    /**
     * Set the locales to display / edit.
     *
     * @param array $locales
     *
     * @return $this
     */
    public function locales(array $locales): self
    {
        return $this->withMeta(['locales' => $locales]);
    }

    /**
     * Set slug value unique.
     *
     * @return $this
     */
    public function unique(string $model): self
    {
        $this->model($model);

        return $this->setOption('generateUniqueSlugs', true);
    }

    /**
     * With unique, set the related model for the unicity.
     *
     * @param string $model
     *
     * @return $this
     */
    protected function model(string $model): self
    {
        return $this->withMeta(['model' => $model]);
    }

    /**
     * Max slug length.
     *
     * @param int $length
     *
     * @return $this
     */
    public function maxLength(int $length): self
    {
        return $this->setOption('maximumLength', $length);
    }

    /**
     * Character used to replace space.
     *
     * @param string $separator
     *
     * @return $this
     */
    public function separator(string $separator): self
    {
        return $this->setOption('separator', $separator);
    }

    /**
     * Set slug language.
     *
     * @param string $language
     *
     * @return $this
     */
    public function language(string $language): self
    {
        return $this->setOption('language', $language);
    }

    /**
     * Use Counted.
     */
    public function counted(): self
    {
        return $this->withMeta(['counted' => true]);
    }

    /**
     * With counted, indicate the maximum threshold length.
     *
     * @param int $characters
     *
     * @return NovaTranslatableSlug
     */
    public function maxChars(int $characters): self
    {
        return $this->withMeta(['maxChars' => $characters]);
    }

    /**
     * With counted, indicate the warning threshold length.
     *
     * @param int $characters
     *
     * @return NovaTranslatableSlug
     */
    public function warningAt(int $characters): self
    {
        return $this->withMeta(['warningAt' => $characters]);
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return self
     */
    protected function setOption(string $name, mixed $value): self
    {
        $this->options[$name] = $value;

        return $this->withMeta(['options' => $this->options]);
    }
}
