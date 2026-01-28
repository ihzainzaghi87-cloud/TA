<?php

namespace Tests\Unit;

use App\Helpers\ResponseFormatter;
use Tests\TestCase;

class ResponseFormatterTest extends TestCase
{
    /**
     * Test success response returns correct structure
     */
    public function test_success_response_returns_correct_structure(): void
    {
        $data = ['user' => 'John Doe'];
        $message = 'Operation successful';
        
        $response = ResponseFormatter::success($data, $message);
        
        $this->assertEquals(200, $response->getStatusCode());
        
        $content = json_decode($response->getContent(), true);
        
        $this->assertEquals(200, $content['code']);
        $this->assertEquals('success', $content['status']);
        $this->assertEquals($message, $content['message']);
        $this->assertEquals($data, $content['data']);
    }

    /**
     * Test success response with null data
     */
    public function test_success_response_with_null_data(): void
    {
        $response = ResponseFormatter::success();
        
        $content = json_decode($response->getContent(), true);
        
        $this->assertEquals(200, $content['code']);
        $this->assertEquals('success', $content['status']);
        $this->assertNull($content['message']);
        $this->assertNull($content['data']);
    }

    /**
     * Test error response returns correct structure
     */
    public function test_error_response_returns_correct_structure(): void
    {
        $data = ['error' => 'Invalid input'];
        $message = 'Validation failed';
        $code = 422;
        
        $response = ResponseFormatter::error($data, $message, $code);
        
        $this->assertEquals($code, $response->getStatusCode());
        
        $content = json_decode($response->getContent(), true);
        
        $this->assertEquals($code, $content['code']);
        $this->assertEquals('error', $content['status']);
        $this->assertEquals($message, $content['message']);
        $this->assertEquals($data, $content['data']);
    }

    /**
     * Test error response with default code
     */
    public function test_error_response_with_default_code(): void
    {
        $response = ResponseFormatter::error(null, 'Something went wrong');
        
        $content = json_decode($response->getContent(), true);
        
        $this->assertEquals(400, $content['code']);
        $this->assertEquals('error', $content['status']);
    }

    /**
     * Test error response with different HTTP codes
     */
    public function test_error_response_with_different_codes(): void
    {
        $testCases = [
            ['code' => 400, 'message' => 'Bad Request'],
            ['code' => 401, 'message' => 'Unauthorized'],
            ['code' => 403, 'message' => 'Forbidden'],
            ['code' => 404, 'message' => 'Not Found'],
            ['code' => 500, 'message' => 'Internal Server Error'],
        ];

        foreach ($testCases as $testCase) {
            $response = ResponseFormatter::error(null, $testCase['message'], $testCase['code']);
            $content = json_decode($response->getContent(), true);
            
            $this->assertEquals($testCase['code'], $content['code']);
            $this->assertEquals($testCase['message'], $content['message']);
        }
    }

    /**
     * Test success response returns JSON
     */
    public function test_success_response_returns_json(): void
    {
        $response = ResponseFormatter::success(['test' => 'data']);
        
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
    }

    /**
     * Test error response returns JSON
     */
    public function test_error_response_returns_json(): void
    {
        $response = ResponseFormatter::error(['test' => 'error']);
        
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
    }
}
