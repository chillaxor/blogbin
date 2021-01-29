<?php

namespace App\Nova;

use App\Category;
use Laravel\Nova\Panel;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use DigitalCreative\ResourceNavigationTab\CardMode;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use Maatwebsite\LaravelNovaExcel\Actions\ExportToExcel;
use DigitalCreative\ResourceNavigationTab\ResourceNavigationTab;
use DigitalCreative\ResourceNavigationTab\HasResourceNavigationTabTrait;

class Blog extends Resource
{
    // use HasResourceNavigationTabTrait;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Blog::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'title',
        'author'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        // return [
        //     ResourceNavigationTab::make([ 'label' => 'Profile' ]), // show all the available cards by default
        //     ResourceNavigationTab::make([
        //         'label' => 'Activities',
        //         'cardMode' => CardMode::KEEP_ALL | CardMode::EXCLUDE_ALL, // show all or remove all cards when this tab is active
        //     ]),
        //     ResourceNavigationTab::make([
        //         'label' => 'Preferences',
        //         'cardMode' => CardMode::ONLY | CardMode::EXCEPT ,// show or remove only the selected cards
        //         'cards' => [
        //            ClientPerformanceCard::class,
        //            ClientProfileCard::class,
        //         ]
        //     ]),
        // ];
        return [
        ResourceNavigationTab::make([
            'label' => 'Information',
            'behaveAsPanel' => true,
            'fields' => [
                ID::make()->sortable(),
                Text::make('title')->displayUsing(function ($value) {
                    return \Illuminate\Support\Str::limit($value,10);
                }),
                Image::make('album_url')->disk('public'),
                Image::make('album_thumbnail')->disk('public'),
                Text::make('author'),
                Text::make('excerpt')->displayUsing(function ($value) {
                    return \Illuminate\Support\Str::limit($value,10);
                }),
                Text::make('tag')->displayUsing(function ($value) {
                    return \Illuminate\Support\Str::limit($value,10);
                }),
                Select::make('category_id', 'category_id')
                ->options(Category::pluck('category_name', 'id')->toArray())
                    ->displayUsing(function ($name) {
                        return Category::pluck('category_name', 'id')->toArray()[$name] ??'';
                    }),
                Select::make('users_id', 'users_id')
                ->options(\App\User::pluck('name', 'id')->toArray())
                    ->displayUsing(function ($name) {
                        return \App\User::pluck('name', 'id')->toArray()[$name] ??'';
                }),
                // Boolean::make('其他选项', 'has_other_options')->sortable()->rules('boolean'),
                // Number::make('最少选择', 'at_least_selection')->sortable()->rules('numeric'),
                // KeyValue::make('题干', 'album_url')->rules('json', function ($attr, $value, $fail) use ($request) {
                //     if ($request->subject_type != 3 && empty(json_decode($value, true))) return $fail('请设置题干');
                // }),
                Date::make('created_at'),
                // new Panel('Information', $this->addressFields()),
                Trix::make('content'),
            ]
        ]),
        ResourceNavigationTab::make([ 'label' => 'Activities' ]),
        ResourceNavigationTab::make([ 'label' => 'Social Interactions' ]),
        ResourceNavigationTab::make([ 'label' => 'Settings' ]),
    ];

        // return [
        //     ID::make()->sortable(),
        //     Text::make('title')->displayUsing(function ($value) {
        //         return \Illuminate\Support\Str::limit($value,10);
        //     }),
        //     Image::make('album_url')->disk('public'),
        //     Image::make('album_thumbnail')->disk('public'),
        //     Text::make('author'),
        //     Text::make('excerpt')->displayUsing(function ($value) {
        //         return \Illuminate\Support\Str::limit($value,10);
        //     }),
        //     Text::make('tag')->displayUsing(function ($value) {
        //         return \Illuminate\Support\Str::limit($value,10);
        //     }),
        //     Select::make('category_id', 'category_id')
        //     ->options(Category::pluck('category_name', 'id')->toArray())
        //         ->displayUsing(function ($name) {
        //             return Category::pluck('category_name', 'id')->toArray()[$name] ??'';
        //         }),
        //     Select::make('users_id', 'users_id')
        //     ->options(\App\User::pluck('name', 'id')->toArray())
        //         ->displayUsing(function ($name) {
        //             return \App\User::pluck('name', 'id')->toArray()[$name] ??'';
        //     }),
        //     // Boolean::make('其他选项', 'has_other_options')->sortable()->rules('boolean'),
        //     // Number::make('最少选择', 'at_least_selection')->sortable()->rules('numeric'),
        //     // KeyValue::make('题干', 'album_url')->rules('json', function ($attr, $value, $fail) use ($request) {
        //     //     if ($request->subject_type != 3 && empty(json_decode($value, true))) return $fail('请设置题干');
        //     // }),
        //     Date::make('created_at'),
        //     Trix::make('content'),
        //     // new Panel('Information', $this->addressFields()),
        // ];
    }
   /**
     * 重载默认排序方法
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param array $orderings
     * @return \Illuminate\Database\Query\Builder
     */
    protected static function applyOrderings($query, array $orderings)
    {
        return $query->orderBy('id');
    }

    /**
     * 重载获取列表的查询
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        // return $query->select(
        //     'user_poll_answer.id',
        //     'user_poll_answer.user_answer_index',
        //     'user_poll_answer.other_options',
        //     'poll_subject.subject_index',
        //     'poll_subject.title',
        //     'poll_subject.subject_type'
        // )->join('poll_subject', 'poll_subject.id', '=', 'user_poll_answer.poll_subject_id')->orderBy(DB::raw('subject_index'), 'asc');
    }
     /**
     * 重载获取详情的查询
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function detailQuery(NovaRequest $request, $query)
    {
        // return  parent::detailQuery($request, $query)->select(
        //     'user_poll_answer.id',
        //     'user_poll_answer.user_answer',
        //     'user_poll_answer.user_answer_index',
        //     'user_poll_answer.other_options',
        //     'poll_subject.subject_index',
        //     'poll_subject.title',
        //     'poll_subject.subject_type',
        //     'poll_subject.subject_body'
        // )->join('poll_subject', 'poll_subject.id', '=', 'user_poll_answer.poll_subject_id');
    }
    public function addressFields()
    {
        return [
            Trix::make('content'),
        ];
    }
    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function cards(Request $request)
    {
        return [  ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            // new ExportToExcel(),
            new DownloadExcel(),
            // new \App\Nova\Actions\Blog,
            //导出
            // (new \Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel)->withName('下载报表')->onSuccess(function ($request, $response) {
            //     $file_name =  $response->getFile()->getFilename() . '.xlsx';
            //     return \Laravel\Nova\Actions\Action::download(
            //         \Illuminate\Support\Facades\URL::temporarySignedRoute('laravel-nova-excel.download', now()->addMinutes(1), [
            //             'path'     => encrypt($response->getFile()->getPathname()),
            //             'filename' => $file_name,
            //             '_url'     => 'maatwebsite/laravel-nova-excel/download'
            //         ]),
            //         $file_name
            //     );
            // })
        ];
    }
}
