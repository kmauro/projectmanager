<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('institution');
        });
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string('IP_address');
            $table->string('user');
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->string('cuil');
            $table->foreignId('institution_id')->constrained('institutions')->onDelete('cascade');
            $table->string('company_name');
            $table->string('company_CUIT');
        });
        Schema::create('trl_levels', function (Blueprint $table) {
            $table->id();
            $table->string('trl');
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
            $table->integer('SID');
            $table->string('title');
            $table->dateTime('time_sent');
            $table->dateTime('time_completed');
            $table->dateTime('time_modified');
            $table->foreignId('applicant_id')->constrained('applicants')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date')->nullable(true);
            $table->longText('objective');
            $table->longText('innovation_value');
            $table->foreignId('trl_level_id')->constrained('trl_levels')->onDelete('cascade');
            $table->foreignId('area_id')->constrained('areas')->onDelete('cascade');
            $table->string('located_entity');
            $table->foreignId('province_id')->constrained('provinces')->onDelete('cascade');
            $table->string('EBCT_amount')->nullable(true);
        });
        Schema::create('techs', function (Blueprint $table) {
            $table->id();
            $table->string('tech');
        });
        Schema::create('project_techs', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('tech_id')->constrained('techs')->onDelete('cascade');
            $table->string('other_tech')->nullable(true);
        });
        Schema::create('needs', function (Blueprint $table) {
            $table->id();
            $table->string('need');
            $table->foreignId('parent_need_id')->nullable(true)->constrained('needs')->onDelete('cascade');
        });
        Schema::create('projects_needs', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('need_id')->constrained('needs')->onDelete('cascade');
            $table->string('other_equipment')->nullable(true);
            $table->string('other_need')->nullable(true);
        });
        Schema::create('financial_sources', function (Blueprint $table) {
            $table->id();
            $table->string('source');
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
            $table->string('sector');
            $table->foreignId('parent_sector_id')->nullable(true)->constrained('sectors')->onDelete('cascade');
        });
        Schema::create('projects_sectors', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('sector_id')->constrained('sectors')->onDelete('cascade');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('institutions');
        Schema::dropIfExists('applicants');
        Schema::dropIfExists('trl_levels');
        Schema::dropIfExists('areas');
        Schema::dropIfExists('provinces');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('techs');
        Schema::dropIfExists('project_techs');
        Schema::dropIfExists('needs');
        Schema::dropIfExists('projects_needs');
        Schema::dropIfExists('financial_sources');
        Schema::dropIfExists('projects_financial_sources');
        Schema::dropIfExists('sectors');
        Schema::dropIfExists('projects_sectors');
    }
};
