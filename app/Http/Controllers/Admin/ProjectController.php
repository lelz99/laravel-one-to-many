<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $project = new Project();
        return view('admin.projects.create', compact('project'));
    }

    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated();

        $project = new Project();

        $project->fill($data);
        
        $project->slug = Str::slug($project->title);
        $project->is_published = Arr::exists($data, 'is_published');

        if(Arr::exists($data, 'preview_project')){
           $img_url = Storage::putfile('project_images', $data['preview_project']);
           $project->preview_project = $img_url;
        };

        $project->save();

        return to_route('admin.projects.show', $project);
    }

    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $data = $request->validated();

        $project->fill($data);

        $project->slug = Str::slug($project->title);
        $project->is_published = Arr::exists($data, 'is_published');

        if(Arr::exists($data, 'preview_project')){
            if($project->preview_project) Storage::delete($project->preview_project);

            $img_url = Storage::putfile('project_images', $data['preview_project']);
            $project->preview_project = $img_url;
        };

        $project->save();

        return to_route('admin.projects.show', $project)->with('message', "{$project->title} modificato con successo");
    }

    public function destroy(Project $project)
    {
        if($project->preview_project) Storage::delete($project->preview_project); // spostare successivamente in drop

        $project->delete();
        return to_route('admin.projects.index')->with('message', "{$project->title} eliminato con successo");
    }
}
