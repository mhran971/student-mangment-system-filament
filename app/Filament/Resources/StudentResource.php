<?php

namespace App\Filament\Resources;

use App\Exports\StudentsExport;
use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Student;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\LookupRef\Selection;
use Illuminate\Database\Eloquent\Builder;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;
    protected static ?string $navigationGroup = "Academic Mangement"  ;
    protected static ?string $activeNavigationIcon = "heroicon-o-academic-cap";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function getNavigationBadge(): ?string
    {
        return Student::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               TextInput::make('name')->required()->autofocus(),
               TextInput::make('email')->required()->unique(),
               select::make('class_id')
                    ->relationship(name: 'class', titleAttribute: 'name')
                    ->live(),
               select::make('section_id')
                    ->label('Section')
                    ->options(function (Get $get) {
                        $ClassId = $get('class_id');

                        if ($ClassId) {
                            return Section::where('class_id', $ClassId)->get()->pluck('name', 'id');
                        }
                    })
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->searchable()->sortable(),
                TextColumn::make('class.name')->badge()->color('success')->searchable(),
                TextColumn::make('section.name')->badge()->color('info')->searchable(),

            ])
            ->filters([
                Filter::make('class-section-filter')
                ->form([
                    Select::make('class_id')
                    ->label('Filter By class')
                    ->placeholder('Select a class')
                    ->options(
                        Classes::pluck('name','id')->toArray())
                ,
                    Select::make('section_id')
                        ->label('Filter By Section')
                        ->placeholder('select a Section')
                        ->options(function (Get $get){

                            $classId = $get('class_id');
                            if($classId){
                                return Section::where('class_id',$classId)->pluck('name','id');
                            }
                        }),
                        ])
                ->query(function (Builder $query,array $data): Builder {
                 return $query->when($data['class_id'],function ($query) use ($data){
                     return $query->where('class_id',$data['class_id']);
                 })->when($data['section_id'],function ($query) use ($data){
                     return $query->where('section_id',$data['section_id']);
                 });
                }),
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('downloadPdf')
                ->button()->color('warning')
                ->url(function (Student $student){
                    return route('student.invoice.generate',$student);
                }),
                Tables\Actions\Action::make('QrCode')
                ->button()->color('success')
                ->url(function (Student $record){
                    return static::getUrl('qrcode',['record'=>$record]);
                }),

                ])
    ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('export')
                        ->label('Export to Excel')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (Collection $records) {
                            return Excel::download(new StudentsExport($records), 'Students.xlsx');
                        })
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
            'qrcode' => Pages\GenerateQrCode::route('/{record}/qrcode'),

        ];
    }
}
