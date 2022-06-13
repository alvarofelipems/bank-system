<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class Checks extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            $this->mergeWhen($this->check, [
                'check' => $this->check ? Storage::disk('s3')->temporaryUrl($this->check, \Carbon\Carbon::now()->addMinutes(5)) : null,
            ]),
            'amount' => $this->amount,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
