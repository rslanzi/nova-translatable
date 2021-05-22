<?php

namespace Rslanzi\NovaTranslatable\Tests;

use Rslanzi\NovaTranslatable\NovaTranslatable;

class NovaTranslatableTest extends TestCase
{
    /** @test */
    public function it_fails_if_no_passing_a_name_to_it()
    {
        $this->expectException(\ArgumentCountError::class);

        NovaTranslatable::make();
    }

    /** @test */
    public function it_fails_if_first_argument_wasnt_a_string()
    {
        $this->expectException(\TypeError::class);

        NovaTranslatable::make([]);
    }

    /** @test */
    public function it_works_for_a_field_with_a_name()
    {
        $translatable = NovaTranslatable::make('field');

        $expected = [
            "locales" => ['it', 'en'],
            "indexLocale" => "it",
        ];

        $this->assertEquals('text', $translatable->fieldType);
        $this->assertEquals('field', $translatable->attribute);
        $this->assertEquals($expected, $translatable->meta);
    }

    /** @test */
    public function it_works_for_a_sluggable_field()
    {
        $translatable = NovaTranslatable::make('field')
            ->sluggable($slugField = 'slug');

        $expected = [
            "locales" => ['it', 'en'],
            "indexLocale" => "it",
            'sluggable' => true,
            'slug' => $slugField,
        ];

        $this->assertEquals('sluggable', $translatable->fieldType);
        $this->assertEquals($expected, $translatable->meta);
    }

    /** @test */
    public function it_can_override_locales_for_a_field()
    {
        $translatable = NovaTranslatable::make('field')
            ->locales(['fr', 'nl']);

        $expected = [
            "locales" => ['fr', 'nl'],
            "indexLocale" => "it",
        ];

        $this->assertEquals('text', $translatable->fieldType);
        $this->assertEquals($expected, $translatable->meta);
    }

    /** @test */
    public function it_can_override_indexLocale_for_a_field()
    {
        $translatable = NovaTranslatable::make('field')
            ->locales(['fr', 'nl'])
            ->indexLocale('fr');

        $expected = [
            "locales" => ['fr', 'nl'],
            "indexLocale" => "fr",
        ];

        $this->assertEquals('text', $translatable->fieldType);
        $this->assertEquals($expected, $translatable->meta);
    }

    /** @test */
    public function it_works_for_a_field_with_code()
    {
        $translatable = NovaTranslatable::make('field')
            ->code();

        $expected = [
            "locales" => ['it', 'en'],
            "indexLocale" => "it",
            'code' => true,
        ];

        $this->assertEquals('code', $translatable->fieldType);
        $this->assertEquals($expected, $translatable->meta);
    }

    /** @test */
    public function it_works_for_a_field_with_code_and_language()
    {
        $translatable = NovaTranslatable::make('field')
            ->code()
            ->language($language = 'php');

        $expected = [
            "locales" => ['it', 'en'],
            "indexLocale" => "it",
            'code' => true,
            'options' => ['mode' => $language],
        ];

        $this->assertEquals('code', $translatable->fieldType);
        $this->assertEquals($expected, $translatable->meta);
    }

    /** @test */
    public function it_skips_language_if_field_isnt_code()
    {
        $translatable = NovaTranslatable::make('field')
            ->language($language = 'php');

        $expected = [
            "locales" => ['it', 'en'],
            "indexLocale" => "it",
        ];

        $this->assertEquals('text', $translatable->fieldType);
        $this->assertEquals($expected, $translatable->meta);
    }

    /** @test */
    public function it_works_for_a_json_field()
    {
        $translatable = NovaTranslatable::make('field')
            ->json();

        $expected = [
            "locales" => ['it', 'en'],
            "indexLocale" => "it",
            'code' => true,
            'mode' => 'application/json',
        ];

        $this->assertEquals('json', $translatable->fieldType);
        $this->assertEquals($expected, $translatable->meta);
    }

    /** @test */
    public function it_works_for_a_trix_field()
    {
        $translatable = NovaTranslatable::make('field')
            ->trix();

        $expected = [
            "locales" => ['it', 'en'],
            "indexLocale" => "it",
            'trix' => true,
        ];

        $this->assertEquals('trix', $translatable->fieldType);
        $this->assertEquals($expected, $translatable->meta);
    }

    /** @test */
    public function it_works_for_a_ckeditor_field()
    {
        $translatable = NovaTranslatable::make('field')
            ->ckeditor();

        $expected = [
            "locales" => ['it', 'en'],
            "indexLocale" => "it",
            'ckeditor' => true,
            'options' => [],
        ];

        $this->assertEquals('ckeditor', $translatable->fieldType);
        $this->assertEquals($expected, $translatable->meta);
    }

    /** @test */
    public function it_works_for_a_field_with_singleLine()
    {
        $translatable = NovaTranslatable::make('field')
            ->singleLine();

        $expected = [
            "locales" => ['it', 'en'],
            "indexLocale" => "it",
            'singleLine' => true,
        ];

        $this->assertEquals('text', $translatable->fieldType);
        $this->assertEquals($expected, $translatable->meta);
    }

    /** @test */
    public function it_works_for_a_field_with_counted()
    {
        $translatable = NovaTranslatable::make('field')
            ->counted();

        $expected = [
            "locales" => ['it', 'en'],
            "indexLocale" => "it",
            'counted' => true,
        ];

        $this->assertEquals('counted', $translatable->fieldType);
        $this->assertEquals($expected, $translatable->meta);
    }

    /** @test */
    public function it_works_for_a_field_with_counted_and_maxChars()
    {
        $translatable = NovaTranslatable::make('field')
            ->counted()
            ->maxChars($characters = 20);

        $expected = [
            "locales" => ['it', 'en'],
            "indexLocale" => "it",
            'counted' => true,
            'maxChars' => $characters,
        ];

        $this->assertEquals('counted', $translatable->fieldType);
        $this->assertEquals($expected, $translatable->meta);
    }

    /** @test */
    public function it_works_for_a_field_with_counted_and_warningAt()
    {
        $translatable = NovaTranslatable::make('field')
            ->counted()
            ->warningAt($characters = 20);

        $expected = [
            "locales" => ['it', 'en'],
            "indexLocale" => "it",
            'counted' => true,
            'warningAt' => $characters,
        ];

        $this->assertEquals('counted', $translatable->fieldType);
        $this->assertEquals($expected, $translatable->meta);
    }

    /** @test */
    public function it_works_for_a_field_with_counted_maxChars_and_warningAt()
    {
        $translatable = NovaTranslatable::make('field')
            ->counted()
            ->warningAt($warningAt = 20)
            ->maxChars($maxChars = 30);

        $expected = [
            "locales" => ['it', 'en'],
            "indexLocale" => "it",
            'counted' => true,
            'warningAt' => $warningAt,
            'maxChars' => $maxChars,
        ];

        $this->assertEquals('counted', $translatable->fieldType);
        $this->assertEquals($expected, $translatable->meta);
    }

    /** @test */
    public function it_works_for_a_singleLine_field_with_counted_maxChars_and_warningAt()
    {
        $translatable = NovaTranslatable::make('field')
            ->singleLine()
            ->counted()
            ->warningAt($warningAt = 20)
            ->maxChars($maxChars = 30);

        $expected = [
            "locales" => ['it', 'en'],
            "indexLocale" => "it",
            'singleLine' => true,
            'counted' => true,
            'warningAt' => $warningAt,
            'maxChars' => $maxChars,
        ];

        $this->assertEquals('counted', $translatable->fieldType);
        $this->assertEquals($expected, $translatable->meta);
    }
}
