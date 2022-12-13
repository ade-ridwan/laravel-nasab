<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TreeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return collect([
            'id' => $this->id,
            'fid' => $this->father_id,
            'mid' => $this->mother_id,
            'gender' => $this->gender == 'L' ? 'male' : 'female',
            'name' => $this->name,
            'url' => 'http://google.com',
            'avatar' => 'https://i.stack.imgur.com/9WpSK.png',
            'pids' => $this->when($this->gender, function () {
                if ($this->gender == 'L')
                    return $this->wifes->pluck('wife_id');
                else
                    return $this->husband->pluck('husband_id');
            }),
        ])->filter()->all();
    }
}
