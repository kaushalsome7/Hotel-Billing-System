<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('dob');                              // Date of birth
            $table->string('aadhar_number', 14);             // XXXX XXXX XXXX
            $table->string('aadhar_image_path');             // Stored image path
            $table->string('photo_path')->nullable();         // Webcam capture
            $table->string('check_in_purpose');
            $table->date('check_in_date');
            $table->integer('number_of_days');
            // Room is nullable — customer may use only restaurant (per SRS)
            $table->foreignId('room_id')
                  ->nullable()
                  ->constrained('rooms')
                  ->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};