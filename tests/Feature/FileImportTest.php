<?php

use App\Domains\Files\Services\ProjectImportService;
use App\Domains\Users\Models\Role;
use App\Domains\Users\Models\User;
use Illuminate\Http\UploadedFile;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\mock;

beforeEach(function () {
    Role::query()->create(['role_name' => 'Administrador']);
    Role::query()->create(['role_name' => 'Usuario']);

    $this->actingAs(User::factory()->create());
});

it('renders the project import page', function () {
    $this->get(route('projects.create'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Projects/addProjectsFile')
            ->where('title', 'Agregar Proyectos')
        );
});

it('rejects unsupported upload formats', function () {
    $this->from(route('projects.create'))
        ->post(route('projects.store'), [
            'file' => UploadedFile::fake()->create('projects.pdf', 10, 'application/pdf'),
        ])
        ->assertRedirect(route('projects.create'))
        ->assertSessionHasErrors('file');
});

it('passes a valid CSV to the import service', function () {
    mock(ProjectImportService::class)
        ->shouldReceive('processUpload')
        ->once()
        ->withArgs(fn (UploadedFile $file) => $file->getClientOriginalName() === 'projects.csv');

    $this->from(route('projects.create'))
        ->post(route('projects.store'), [
            'file' => UploadedFile::fake()->create('projects.csv', 10, 'text/csv'),
        ])
        ->assertRedirect(route('projects.create'))
        ->assertSessionHas('success');
});
