<?php

namespace App\Imports;

use App\Models\Blog;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BlogsImport implements ToModel, WithHeadingRow, WithValidation
{

    use Importable;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Blog([
            'title' => $row['title'],
            'content' => $row['content'],
            'status' => $row['status'] ?? 'pending',
            'created_by' => $row['created_by'], // make sure this user exists
        ]);
    }

    public function rules(): array
    {
        return [
            '*.title' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {},
            ],
            '*.content' => 'required|string',
            '*.status' => 'in:pending,published,rejected',
            '*.created_by' => 'required|exists:users,id',
        ];
    }
}
