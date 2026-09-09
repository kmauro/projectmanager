<?php

namespace App\Domains\Projects\Controllers;

use App\Domains\Projects\Models\Project;
use App\Domains\Projects\Requests\IndexProjectRequest;
use App\Domains\Projects\Requests\UpdateProjectRequest;
use App\Domains\Projects\Services\ProjectService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class ProjectController extends Controller
{
    public function __construct(private ProjectService $projectService) {}

    public function index(IndexProjectRequest $request): InertiaResponse
    {
        $filters = $request->filters();

        return Inertia::render('Projects/index', [
            'title' => 'Lista de proyectos',
            'projects' => $this->projectService->paginate($filters),
            'filters' => $filters,
        ]);
    }

    public function show(Project $project): InertiaResponse
    {
        return Inertia::render('Projects/View', [
            'title' => 'Detalle del proyecto',
            'project' => $this->projectService->getProject($project),
            'trlLevels' => $this->projectService->getTrlLevels(),
            'provinces' => $this->projectService->getProvinces(),
            'areas' => $this->projectService->getAreas(),
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $this->projectService->update($project, $request->validated());

        return to_route('projects.show', $project)->with('success', 'Proyecto actualizado correctamente.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->projectService->delete($project);

        return to_route('projects.index')->with('success', 'Proyecto eliminado correctamente.');
    }
}
