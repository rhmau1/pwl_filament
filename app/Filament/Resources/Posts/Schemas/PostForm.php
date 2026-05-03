<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Models\Category;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TagsInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make("Post Detail")
                        ->description("Enter the post details below.")
                        ->icon("heroicon-o-document-text")
                        ->schema([
                            TextInput::make("title")
                                // ->rules('required'| 'min:5'| 'max:255'),
                                ->required()
                                ->minLength(5)
                                ->maxLength(255),                            
                        TextInput::make("slug")->required()
                        ->minLength(3)
                        ->unique()
                        ->validationMessages([
                            'unique' => 'Slug harus unik dan tidak boleh sama.'
                        ]),
                            Select::make("category_id")
                                ->required()
                                ->relationship("category", "name")
                                // ->preload()
                                ->options(Category::all()->pluck("name", "id"))
                                ->searchable()
                                ->validationMessages([
                                    'required' => 'category harus dipilih'
                                ]),
                            ColorPicker::make("color"),
                        ])->columns(2),
                ])->columnSpan(2),
                Group::make([
                    Section::make("Image Upload")
                        ->icon("heroicon-o-photo")
                        ->schema([
                            FileUpload::make("image")
                                ->required()
                                ->disk("public")
                                ->directory("post"),
                        ]),
                    Section::make("Meta")
                        ->icon("heroicon-o-tag")
                        ->schema([
                            // TagsInput::make("tags"),
                            Select::make('tags')
                                ->relationship('tags','name')
                                ->multiple(),
                            Checkbox::make('published'),
                            DatePicker::make('published_at'),
                        ]),
                ])->columnSpan(1),
            ])->columns(3);
    }
}
