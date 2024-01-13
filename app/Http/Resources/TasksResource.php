<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TasksResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string)$this->id,
            'attributes' => [
                'title' => $this->title,
                'description' => $this->description,
                'priority' => $this->priority,
                'status' => $this->status,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
            'relationships' => [
                'user' => [
                    'id' => (string)$this->user->id,
                    'username' => $this->user->username,
                    'email' => $this->user->email,
                ],
                'project' => [
                    'id' => (string)$this->project->id,
                    'title' => $this->project->title,
                    'description' => $this->project->description,
                    'priority' => $this->project->priority,
                    'status' => $this->project->status,
                    'created_at' => $this->project->created_at,
                    'updated_at' => $this->project->updated_at,
                ],
            ]
        ];
    }
}
