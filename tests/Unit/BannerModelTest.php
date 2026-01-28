<?php

namespace Tests\Unit;

use App\Models\Banner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BannerModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test banner can be created
     */
    public function test_banner_can_be_created(): void
    {
        $banner = Banner::create([
            'title' => 'Test Banner',
            'image' => 'banners/test.jpg',
            'image_mobile' => 'banners/test-mobile.jpg',
        ]);

        $this->assertDatabaseHas('banners', [
            'title' => 'Test Banner',
            'image' => 'banners/test.jpg',
        ]);
    }

    /**
     * Test banner is_active is cast to boolean
     */
    public function test_is_active_is_cast_to_boolean(): void
    {
        $banner = Banner::create([
            'title' => 'Test Banner 2',
            'image' => 'banners/test2.jpg',
            'image_mobile' => 'banners/test2-mobile.jpg',
            'is_active' => 1,
        ]);

        $this->assertIsBool($banner->is_active);
        $this->assertTrue($banner->is_active);
    }

    /**
     * Test banner has fillable attributes
     */
    public function test_banner_has_fillable_attributes(): void
    {
        $banner = new Banner();
        
        $expected = [
            'image',
            'image_mobile',
            'title',
            'subtitle',
            'is_active',
        ];

        $this->assertEquals($expected, $banner->getFillable());
    }
}
