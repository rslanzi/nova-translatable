<?php

namespace Rslanzi\NovaTranslatable\Tests;

use Rslanzi\NovaTranslatable\NovaTranslatableSlug;

class NovaTranslatableSlugTest extends TestCase
{
    /** @test */
    public function it_fails_if_no_passing_a_name_to_it()
    {
        $this->expectException(\ArgumentCountError::class);

        NovaTranslatableSlug::make();
    }

    /** @test */
    public function it_fails_if_first_argument_wasnt_a_string()
    {
        $this->expectException(\TypeError::class);

        NovaTranslatableSlug::make([]);
    }

    /** @test */
    public function it_works_for_a_field_with_a_name()
    {
        $translatable = NovaTranslatableSlug::make('field');

        $expected = [
            'locales' => ['it', 'en'],
            'indexLocale' => 'it',
        ];

        $this->assertEquals('nova-translatable-slug-field', $translatable->component);
        $this->assertEquals($expected, $translatable->meta);
    }

    /** @test */
    public function it_can_override_locales_for_a_field()
    {
        $translatable = NovaTranslatableSlug::make('field')
            ->locales(['fr', 'nl']);

        $expected = [
            'locales' => ['fr', 'nl'],
            'indexLocale' => 'it',
        ];

        $this->assertEquals($expected, $translatable->meta);
    }

    /** @test */
    public function it_works_with_unique_option()
    {
        $model = (new TestModel);
        $translatable = NovaTranslatableSlug::make('field')
            ->unique($model);

        $expected = [
            'locales' => ['it', 'en'],
            'indexLocale' => 'it',
            'model' => $model,
            'options' => [
                'generateUniqueSlugs' => true,
            ],
        ];

        $this->assertEquals($expected, $translatable->meta);
    }

    /** @test */
    public function it_works_with_maxLength_option()
    {
        $translatable = NovaTranslatableSlug::make('field')
            ->maxLength($length = 50);

        $expected = [
            'locales' => ['it', 'en'],
            'indexLocale' => 'it',
            'options' => [
                'maximumLength' => $length,
            ],
        ];

        $this->assertEquals($expected, $translatable->meta);
    }

    /** @test */
    public function it_works_with_separator_option()
    {
        $translatable = NovaTranslatableSlug::make('field')
            ->separator($separator = '-');

        $expected = [
            'locales' => ['it', 'en'],
            'indexLocale' => 'it',
            'options' => [
                'separator' => $separator,
            ],
        ];

        $this->assertEquals($expected, $translatable->meta);
    }

    /** @test */
    public function it_works_with_language_option()
    {
        $translatable = NovaTranslatableSlug::make('field')
            ->language($language = 'en');

        $expected = [
            'locales' => ['it', 'en'],
            'indexLocale' => 'it',
            'options' => [
                'language' => $language,
            ],
        ];

        $this->assertEquals($expected, $translatable->meta);
    }

    /** @test */
    public function it_works_for_a_field_with_counted()
    {
        $translatable = NovaTranslatableSlug::make('field')
            ->counted();

        $expected = [
            'locales' => ['it', 'en'],
            'indexLocale' => 'it',
            'counted' => true,
        ];

        $this->assertEquals($expected, $translatable->meta);
    }

    /** @test */
    public function it_works_for_a_field_with_counted_and_maxChars()
    {
        $translatable = NovaTranslatableSlug::make('field')
            ->counted()
            ->maxChars($characters = 20);

        $expected = [
            'locales' => ['it', 'en'],
            'indexLocale' => 'it',
            'counted' => true,
            'maxChars' => $characters,
        ];

        $this->assertEquals($expected, $translatable->meta);
    }

    /** @test */
    public function it_works_for_a_field_with_counted_and_warningAt()
    {
        $translatable = NovaTranslatableSlug::make('field')
            ->counted()
            ->warningAt($characters = 20);

        $expected = [
            'locales' => ['it', 'en'],
            'indexLocale' => 'it',
            'counted' => true,
            'warningAt' => $characters,
        ];

        $this->assertEquals($expected, $translatable->meta);
    }

    /** @test */
    public function it_works_for_a_field_with_counted_maxChars_and_warningAt()
    {
        $translatable = NovaTranslatableSlug::make('field')
            ->counted()
            ->warningAt($warningAt = 20)
            ->maxChars($maxChars = 30);

        $expected = [
            'locales' => ['it', 'en'],
            'indexLocale' => 'it',
            'counted' => true,
            'warningAt' => $warningAt,
            'maxChars' => $maxChars,
        ];

        $this->assertEquals($expected, $translatable->meta);
    }
}
