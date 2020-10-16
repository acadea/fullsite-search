<?php

namespace Acadea\FullSite;

use Acadea\FullSite\Controller\SiteSearchController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;

class FullSiteSearch
{
    public static function parseModelNameFromFile(SplFileInfo $file)
    {
        $filename = $file->getRelativePathname();

        // assume model name is equal to file name
        /* making sure it is a php file*/
        if (substr($filename, -4) !== '.php') {
            return null;
        }
        // removing .php
        return substr($filename, 0, -4);
    }

    public static function filterSearchableModel(?string $classname)
    {
        if ($classname === null) {
            return false;
        }

        // using reflection class to obtain class info dynamically
        $reflection = new \ReflectionClass(self::modelNamespacePrefix() . $classname);

        // making sure the class extended eloquent model
        $isModel = $reflection->isSubclassOf(Model::class);

        // making sure the model implemented the searchable trait
        $searchable = $reflection->hasMethod('search');

        // filter model that has the searchable trait and not in exclude array
        return $isModel && $searchable && ! in_array($reflection->getName(), config('fullsite-search.exclude'), true);
    }

    private static function isTesting()
    {
        return App::environment('testing');
    }

    /** Creating a callback function to set search attributes to the model
     *
     * @param $model
     * @param $keyword
     * @param $classname
     */
    public static function createMatchAttribute(Model $model, array $fields, $keyword)
    {
        // only extracting the relevant fields from our model
        $fieldsData = $model->only($fields);

        // joining the fields together
        $serializedValues = collect($fieldsData)->join(' ');

        // finding the position of match
        $searchPos = strpos(strtolower($serializedValues), strtolower($keyword));

        // Our goal here:
        // After finding the match position, we also want to include the surrounding text, so our user would
        // have a better search experience.
        //
        // We append or prepend `...` if there are more text before / after our match + neighbouring text
        // including the found terms
        if ($searchPos !== false) {
            $buffer = config('fullsite-search.buffer');

            // the buffer number dictates how many neighbouring characters to display
            $start = $searchPos - $buffer;

            // we don't want to go below 0 as the starting position
            $start = $start < 0 ? 0 : $start;

            // multiply 2 buffer to cover the text before and after the match
            $length = strlen($keyword) + 2 * $buffer;

            // getting the match and neighbouring text
            $sliced = substr($serializedValues, $start, $length);

            // adding prefix and postfix dots

            // if start position is negative, there is no need to prepend `...`
            $shouldAddPrefix = $start > 0;
            // if end position went over the total length, there is no need to append `...`
            $shouldAddPostfix = ($start + $length) < strlen($serializedValues) ;

            $sliced = $shouldAddPrefix ? '...' . $sliced : $sliced;
            $sliced = $shouldAddPostfix ? $sliced . '...' : $sliced;
        }

        return $sliced ?? substr($serializedValues, 0, 20) . '...';
    }

    public static function search(string $keyword)
    {
        // getting all the model files from the model folder
        if (self::isTesting()) {
            $files = File::allFiles(__DIR__ . '/../tests/Models');
        } else {
            $files = File::allFiles(app()->basePath() . '/app/' . config('fullsite-search.model_path'));
        }

        // to get all the model classes
        return collect($files)
            ->map([self::class, 'parseModelNameFromFile'])
            ->filter([self::class, 'filterSearchableModel'])
            ->map(function ($classname) use ($keyword) {
                // for each class, call the search function
                $model = app(self::modelNamespacePrefix() . $classname);

                /**
                 * Our goal here: to add these 3 attributes to each of our search result:
                 * a. `match` -- the match found in our model records
                 * b. `model` -- the related model name
                 * c. `view_link` -- the URL for the user to navigate in the frontend to view the resource
                 */

                // to create the `match` attribute, we need to join the value of all the searchable fields in
                // our model, ie all the fields defined in our 'toSearchableArray' model method

                // We make use of the SEARCHABLE_FIELDS constant in our model
                // we dont want id in the match, so we filter it out.
                $fields = array_filter($model::SEARCHABLE_FIELDS, fn ($field) => $field !== 'id');
                return $model::search($keyword)->get()->map(function (Model $modelRecord) use ($keyword, $fields, $classname) {

                    // use $slice as the match, otherwise if undefined we use the first 20 character of serialisedValues
                    $modelRecord->setAttribute('match', self::createMatchAttribute($modelRecord, $fields, $keyword));
                    // setting the model name
                    $modelRecord->setAttribute('model', $classname);
                    // setting the resource link
                    $modelRecord->setAttribute('view_link', self::resolveModelViewLink($modelRecord));

                    return $modelRecord;
                });
            })->flatten(1);
    }

    /** A helper function to generate the model namespace
     * @return string
     */
    private static function modelNamespacePrefix()
    {
        if (self::isTesting()) {
            return (new \ReflectionClass(self::class))->getNamespaceName().'\\Tests\\Models\\';
        }

        return app()->getNamespace() . config('fullsite-search.model_path') . '\\';
    }

    /** Helper function to retrieve resource URL
     * @param Model $model
     * @return string|string[]
     */
    public static function resolveModelViewLink(Model $model)
    {
        // Here we list down all the alternative model-link mappings
        // if we dont have a record here, will default to /{model-name}/{model_id}
        $mapping = config('fullsite-search.view_mapping');

        // getting the Fully Qualified Class Name of model
        $modelClass = get_class($model);

        // converting model name to kebab case
        $modelName = Str::plural(Arr::last(explode('\\', $modelClass)));
        $modelName = Str::kebab(Str::camel($modelName));

        // attempt to get from $mapping. We assume every entry has an `{id}` for us to replace
        if (Arr::has($mapping, $modelClass)) {
            $replace = [
                '{id}' => $model->id,
                '{ id }' => $model->id,
            ];

            return URL::to(str_replace(
                array_keys($replace),
                array_values($replace),
                $mapping[$modelClass]
            ));
        }
        // assume /{model-name}/{model_id}
        return URL::to('/' . strtolower($modelName) . '/' . $model->id);
    }

    /**
     * Register the api routes
     */
    public static function routes()
    {
        Route::get(config('fullsite-search.api.url'), [SiteSearchController::class, 'search']);
    }
}
