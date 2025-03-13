<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel,WithHeadingRow
{
    use importable;



    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $class_id = self::getClassId($row['class']);

        return new Student([
            'name'  => $row['name'],
            'email' => $row['email'],
            'class_id'    => $class_id,
            'section_id' =>self::getSectionId($class_id,$row['section'])
            ]);
    }

    public static function getClassId(string $class)
        {
            return Classes::where('name',$class)->first()->id;
        }

    public static function getSectionId(int $class_id,string $section)
        {
            return Section::where('name',$section)
                ->where('class_id',$class_id)
                ->first()->id;
        }
}
