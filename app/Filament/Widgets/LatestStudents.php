<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestStudents extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int  $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Student::query()
                ->latest()
                ->limit(10)
            )
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->searchable()->sortable(),
                TextColumn::make('class.name')->badge()->color('success')->searchable(),
                TextColumn::make('section.name')->badge()->color('info')->searchable(),
            ]);
    }
}
