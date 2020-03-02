<?php
/**
 * @package    Resources
 * @author     Rupert Brasil Lustosa <rupertlustosa@gmail.com>
 * @date       09/12/2019 10:25:33
 */

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => (string)$this->name,
            'email' => (string)$this->email,
            'image' => (string)(($this->image) ? asset("images/{$this->image}") : null),
            'document_number' => (string)$this->document_number,
            'gender' => (string)$this->gender,
            'birth' => (string)$this->birth,
            'phone1' => (string)$this->phone1,
            'phone2' => (string)$this->phone2,
            'cashback_available' => $this->cashback_available,
            'indication_code' => $this->indication_code,
            'address' => new AddressCollection(Address::whereUserId($this->id)->get()),
        ];
    }
}
