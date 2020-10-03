<?php

namespace Tests\Feature\Organizations;

use App\Models\Country;
use App\Models\Type;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateOrganizationTest extends TestCase
{

    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_organization_success()
    {

        $response = $this->postJson('/api/register',[
            'organization_title' => $this->faker->company,
            'organization_title_ar' => $this->faker->company,
            'organization_city' => $this->faker->city,
            'organization_city_ar' => $this->faker->city,
            'organization_address' => $this->faker->address,
            'organization_address_ar' =>  $this->faker->address,
            'organization_phone_number' =>  $this->faker->phoneNumber,
            'organization_description' => $this->faker->sentence,
            'organization_description_ar' => $this->faker->sentence,
            'organization_vat' => $this->faker->numberBetween(1,50),
            'organization_cr' =>  $this->faker->randomNumber,
            'organization_country_id' =>Country::inRandomOrder()->first()->id,
            'organization_business_type' => Type::inRandomOrder()->first()->id,
            'organization_type' => $this->faker->randomElement(["individual","government","corporation",'establishment']),
            'name' => $this->faker->name,
            'name_ar' => $this->faker->name,
            'phone_number' => $this->faker->phoneNumber,
            'email' =>  $this->faker->companyEmail,
            'password' => 'password',
            "password_confirmation" => 'password'
        ]);

        $response 
        ->assertSee("dashboard")
        ->assertRedirect()
       ;
    }



    public function test_create_organization_failed_data_required()
    {

        $response = $this->postJson('/api/register');

        $response 
        ->assertSee(
            'organization_title','organization_city','organization_title_ar','organization_city_ar','organization_address',
            'organization_address_ar','organization_phone_number','organization_description','organization_description_ar',
            'organization_vat','organization_cr','organization_country_id','organization_business_type','organization_type',
            'name','name_ar','phone_number','email','password'
        )
        ->assertStatus(422);
    }




}
