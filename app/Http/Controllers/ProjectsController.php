<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Resources\ProjectsResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectsController extends Controller
{
    public function index()
    {
        return ProjectsResource::collection(
            Project::where('user_id', Auth::user()->id)->get()
//            Project::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $request->validated($request->all());

        $project = Project::create([
            'user_id' => Auth::user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
        ]);

        return new ProjectsResource($project);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return $this->error('', 'Project not found', 404);
        }

        if (Auth::user()->id !== $project->user_id) {
            return $this->error('', 'Not authorized for this request', 403);
        }

        return new ProjectsResource($project);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        if (Auth::user()->id !== $project->user_id) {
            return $this->error('', 'Not authorized for this request', 403);
        }

        $project->update($request->all());

        return new ProjectsResource($project);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        return $this->isNotAuthorized($project) ? $this->isNotAuthorized($project) : $project->delete();
    }

    public function isNotAuthorized( $project)
    {
        if (Auth::user()->id !== $project->user_id) {
            return $this->error('', 'Not authorized for this request', 403);
        }
    }
}
