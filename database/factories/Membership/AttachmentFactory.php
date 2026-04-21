<?php

namespace Database\Factories\Membership;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Membership\Attachment;
use App\Models\Membership\Member;

class AttachmentFactory extends Factory
{
    protected $model = Attachment::class;

    public function definition(): array
    {
        return [
            'member_id' => Member::factory(),
            'type' => fake()->randomElement(['National ID', 'Contract', 'Birth Certificate']),
            'file_path' => 'attachments/' . fake()->uuid() . '.pdf',
        ];
    }
}
