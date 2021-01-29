<?php
/*
 * @Author: jinzhi
 * @email: <chenxinbin@linghit.com>
 * @Date: 2021-01-07 12:16:45
 * @Description: Description
 */

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\Boolean;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class Blog extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = '修改发布状态';
    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $models->each->release($fields->poll_status);
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Text::make('发布问卷', 'author'),
            Text::make('发布问卷', 'excerpt'),
            Text::make('发布问卷', 'tag'),
        ];
    }
}
