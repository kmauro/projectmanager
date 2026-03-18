<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('imports', function (Blueprint $table){
            $table->id();
            $table->string('file_path');
            $table->string('status');
            $table->integer('processed_rows')->nullable(true);
            $table->integer('total_rows')->nullable(true);
            $table->string('error_message')->nullable(true);
            $table->timestamp('created_at')->nullable(true);
        });
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('institution');
        });
        //REVISAR CUALES SON OBLIGATORIOS Y CUALES SON OPCIONALES
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string('IP_address')->nullable(true);
            $table->string('user')->nullable(true);
            $table->string('full_name')->nullable(true);
            $table->string('email')->nullable(true);
            $table->string('phone')->nullable(true);
            $table->string('cuil')->nullable(true);
            $table->foreignId('institution_id')->nullable(true)->constrained('institutions')->onDelete('cascade');
            $table->string('company_name')->nullable(true);
            $table->string('company_CUIT')->nullable(true);
        });
        Schema::create('trl_levels', function (Blueprint $table) {
            $table->id();
            $table->string('trl_level');
        });
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('area');
        });
        Schema::create('provinces', function (Blueprint $table){
            $table->id();
            $table->string('province');
        });
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->integer('SID')->nullable(true);
            $table->string('title')->nullable(true);
            $table->dateTime('time_sent')->nullable(true);
            $table->dateTime('time_completed')->nullable(true);
            $table->dateTime('time_modified')->nullable(true);
            $table->foreignId('applicant_id')->constrained('applicants')->onDelete('cascade');
            $table->date('start_date')->nullable(true);
            $table->date('end_date')->nullable(true);
            $table->longText('objective')->nullable(true);
            $table->longText('innovation_value')->nullable(true);
            $table->foreignId('trl_level_id')->nullable(true)->constrained('trl_levels')->onDelete('cascade');
            $table->foreignId('area_id')->nullable(true)->constrained('areas')->onDelete('cascade');
            $table->string('located_entity')->nullable(true);
            $table->foreignId('province_id')->nullable(true)->constrained('provinces')->onDelete('cascade');
            $table->string('EBCT_amount')->nullable(true);
        });
        Schema::create('techs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('predefined')->default(true);
        });
        Schema::create('projects_techs', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('tech_id')->constrained('techs')->onDelete('cascade');
            
        });
        Schema::create('needs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('parent_need_id')->nullable(true)->constrained('needs')->onDelete('cascade');
        });
        Schema::create('projects_needs', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('need_id')->constrained('needs')->onDelete('cascade');
            $table->string('other_equipment')->nullable(true);
            $table->string('other_need')->nullable(true);
            $table->string('countries')->nullable(true);
        });
        Schema::create('financial_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
        });
        Schema::create('projects_financial_sources', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('source_id')->constrained('financial_sources')->onDelete('cascade');
            $table->string('other_source')->nullable(true);
            $table->float('percentage');
            $table->integer('amount');
        });
        Schema::create('sectors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('parent_sector_id')->nullable(true)->constrained('sectors')->onDelete('cascade');
        });
        Schema::create('projects_sectors', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('sector_id')->constrained('sectors')->onDelete('cascade');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('imports');
        Schema::dropIfExists('institutions');
        Schema::dropIfExists('applicants');
        Schema::dropIfExists('trl_levels');
        Schema::dropIfExists('areas');
        Schema::dropIfExists('provinces');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('techs');
        Schema::dropIfExists('projects_techs');
        Schema::dropIfExists('needs');
        Schema::dropIfExists('projects_needs');
        Schema::dropIfExists('financial_sources');
        Schema::dropIfExists('projects_financial_sources');
        Schema::dropIfExists('sectors');
        Schema::dropIfExists('projects_sectors');
    }
};
