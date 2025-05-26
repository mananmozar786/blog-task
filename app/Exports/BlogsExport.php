<?php

namespace App\Exports;

use App\Models\Blog;
use Maatwebsite\Excel\Concerns\FromCollection;

class BlogsExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Blog::select('id', 'title', 'content', 'status', 'created_by', 'created_at', 'updated_at')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Content',
            'Status',
            'Created By',
            'Created At',
            'Updated At',
        ];
    }
}
