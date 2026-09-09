<?php

use App\Domains\Projects\Models\Applicant;
use App\Domains\Projects\Models\Area;
use App\Domains\Projects\Models\Project;
use App\Domains\Projects\Models\Province;
use App\Domains\Projects\Models\TRLLevel;
use App\Domains\Users\Models\Role;
use App\Domains\Users\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    Role::query()->create(['role_name' => 'Administrador']);
    Role::query()->create(['role_name' => 'Usuario']);

    $this->actingAs(User::factory()->create());
});

it('requires authentication for project pages', function () {
    auth()->logout();

    $this->get(route('projects.index'))
        ->assertRedirectToRoute('login');
});

it('lists paginated projects and filters them by title', function () {
    $applicant = Applicant::factory()->create();
    $area = Area::factory()->create();
    $province = Province::factory()->create();
    $trlLevel = TRLLevel::factory()->create();

    Project::factory()
        ->count(12)
        ->for($applicant)
        ->for($area)
        ->for($province)
        ->for($trlLevel, 'trlLevel')
        ->create();

    Project::factory()
        ->for($applicant)
        ->for($area)
        ->for($province)
        ->for($trlLevel, 'trlLevel')
        ->create(['title' => 'Plataforma solar federal']);

    $this->get(route('projects.index', [
        'search' => 'solar',
        'field' => 'title',
        'per_page' => 10,
    ]))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Projects/index')
            ->where('filters.search', 'solar')
            ->where('filters.field', 'title')
            ->where('projects.total', 1)
            ->has('projects.data', 1)
            ->where('projects.data.0.title', 'Plataforma solar federal')
        );
});

it('renders a project with its lookup data', function () {
    $project = Project::factory()->create();

    $this->get(route('projects.show', $project))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Projects/View')
            ->where('project.id', $project->id)
            ->has('project.applicant')
            ->has('project.area')
            ->has('project.province')
            ->has('project.trl_level')
            ->has('project.techs')
            ->has('project.needs')
            ->has('project.funding_sources')
            ->has('project.sectors')
            ->has('trlLevels')
            ->has('areas')
            ->has('provinces')
        );
});

it('updates a project with validated data', function () {
    $project = Project::factory()->create();
    $area = Area::factory()->create();
    $province = Province::factory()->create();
    $trlLevel = TRLLevel::factory()->create();

    $response = $this->put(route('projects.update', $project), [
        'title' => 'Proyecto actualizado',
        'start_date' => '2026-01-10',
        'end_date' => '2026-07-10',
        'objective' => 'Objetivo actualizado',
        'innovation_value' => 'Valor actualizado',
        'trl_level_id' => $trlLevel->id,
        'area_id' => $area->id,
        'located_entity' => 'Centro de innovación',
        'province_id' => $province->id,
        'EBCT_amount' => '1500000',
        'countries' => 'Argentina, Uruguay',
    ]);

    $response
        ->assertRedirectToRoute('projects.show', $project)
        ->assertSessionHas('success');

    expect($project->refresh())
        ->title->toBe('Proyecto actualizado')
        ->area_id->toBe($area->id)
        ->province_id->toBe($province->id)
        ->trl_level_id->toBe($trlLevel->id)
        ->countries->toBe('Argentina, Uruguay');
});

it('rejects a project end date before its start date', function () {
    $project = Project::factory()->create();

    $this->from(route('projects.show', $project))
        ->put(route('projects.update', $project), [
            'title' => 'Proyecto inválido',
            'start_date' => '2026-07-10',
            'end_date' => '2026-01-10',
        ])
        ->assertRedirect(route('projects.show', $project))
        ->assertSessionHasErrors('end_date');
});

it('deletes a project and redirects to the project list', function () {
    $project = Project::factory()->create();

    $this->delete(route('projects.destroy', $project))
        ->assertRedirectToRoute('projects.index')
        ->assertSessionHas('success');

    $this->assertModelMissing($project);
});
