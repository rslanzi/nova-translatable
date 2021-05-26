<?php

namespace Rslanzi\NovaTranslatable\Tests;

use Laravel\Nova\Resource;
use Rslanzi\NovaTranslatable\Tests\TestModel as Test;
use Rslanzi\NovaTranslatable\Tests\TestModelTranslation as TestTranslation;

class SlugControllerTest extends TestCase
{
    /** @var Test */
    protected $testModel;

    public function setUp(): void
    {
        parent::setUp();

        $this->createDummyData();
    }

    /** @test */
    public function it_return_a_correct_slug_response_without_options()
    {
        $data = [
            'value' => 'test title',
        ];

        $this
            ->postJson('nova-vendor/rslanzi/nova-translatable/slug/generate', $data)
            ->assertSuccessful()
            ->assertJson(['slug' => 'test-title']);
    }

    /** @test */
    public function it_return_a_correct_slug_response_with_custom_separator()
    {
        $data = [
            'value' => 'test title',
            'options' => [
                'separator' => '_',
            ],
        ];

        $this
            ->postJson('nova-vendor/rslanzi/nova-translatable/slug/generate', $data)
            ->assertSuccessful()
            ->assertJson(['slug' => 'test_title']);
    }

    /** @test */
    public function it_return_a_correct_slug_response_with_maximum_lenght()
    {
        $data = [
            'value' => 'test title',
            'options' => [
                'maximumLength' => 8,
            ],
        ];

        $this
            ->postJson('nova-vendor/rslanzi/nova-translatable/slug/generate', $data)
            ->assertSuccessful()
            ->assertJson(['slug' => 'test-tit']);
    }

    /** @test */
    public function it_return_a_correct_slug_response_with_unique_slug()
    {
        $this->withoutExceptionHandling();

        $data = [
            'model' => '\Rslanzi\NovaTranslatable\Tests\TestModel',
            'options' => [
                'generateUniqueSlugs' => true,
            ],
            'attribute' => 'slug',
            'value' => 'title',
        ];

        $this
            ->postJson('nova-vendor/rslanzi/nova-translatable/slug/generate', $data)
            ->assertSuccessful()
            ->assertJson(['slug' => 'title']);
    }

    protected function createDummyData(): void
    {
        $this->testModel = Test::create(
            [
                'name' => 'name',
            ]
        );

        TestTranslation::create(
            [
                'test_id' => $this->testModel->id,
                'locale' => 'it',
                'title' => 'titolo',
                'slug' => 'titolo',
            ]
        );
        TestTranslation::create(
            [
                'test_id' => $this->testModel->id,
                'locale' => 'en',
                'title' => 'title',
                'slug' => 'title',
            ]
        );
    }
}

abstract class TestResource extends Resource
{
}
