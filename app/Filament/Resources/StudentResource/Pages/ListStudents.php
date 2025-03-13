<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Imports\StudentsImport;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('importStudents')
              ->label('Import Students')
              ->color('danger')
              ->icon('heroicon-o-document-arrow-down')
              ->form([
                  FileUpload::make('attachment'),
              ])
              ->action(function (array $data){
                    $file = public_path('storage/'.$data['attachment']);

                    Excel::import(new StudentsImport,$file);

                    Notification::make()
                        ->title('Student Imported')
//                        ->message('The student have imported successfully.')
                        ->success()
                        ->send();
              })
        ];
    }
}
