<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class RestServiceProvider extends ServiceProvider
{
    // Error codes for json return (EC = Error code)
    const EC_NOT_FOUND = 10;
    const EC_PARAMETERS_REQUIRED = 11;
    const EC_INTERNAL_ERROR = 12;
    // Key used in array
    const ARRAY_KEY_ERRORS = 'errors';

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Generate one line of error
     * @param int $intCode
     * @param string $strDescription
     * @return array
     */
    public static function generateErrorLine($intCode, $strDescription)
    {
        return [
            'code' => $intCode,
            'description' => $strDescription,
        ];
    }

    /**
     * Generate error result when a required parameter is missing
     * @param array $arrParametersName
     * @return array
     */
    public static function generateAtLeastOneParametersRequiredError($arrParametersName)
    {
        return [
            self::ARRAY_KEY_ERRORS => self::generateErrorLine(
                self::EC_PARAMETERS_REQUIRED,
                'At least on parameters is required ('.implode(', ', $arrParametersName).')'
            ),
        ];
    }

    /**
     * Generate error result when a required parameter is missing
     * @param array $arrParametersName
     * @return array
     */
    public static function generateParametersRequiredError($arrParametersName)
    {
        return [
            self::ARRAY_KEY_ERRORS => self::generateErrorLine(
                self::EC_PARAMETERS_REQUIRED,
                'Parameters are required ('.implode(', ', $arrParametersName).')'
            ),
        ];
    }

    /**
     * Generate error result when the provided id is not found in the database
     * @param string $strEntityName
     * @return array
     */
    public static function generateEntityNotFound($strEntityName)
    {
        return [
            self::ARRAY_KEY_ERRORS => self::generateErrorLine(
                self::EC_NOT_FOUND,
                "$strEntityName is not found"
            ),
        ];
    }

    /**
     * Generate error result when the error is not usual
     * @param string $strDescription
     * @param integer $intCode
     * @return array
     */
    public static function generateCustomError($strDescription, $intCode = self::EC_INTERNAL_ERROR)
    {
        return [
            self::ARRAY_KEY_ERRORS => self::generateErrorLine(
                $intCode,
                $strDescription
            ),
        ];
    }



    /**
     * Determine if the request contains a non-empty value for all of the given inputs.
     *
     * @param  Request $objRequest
     * @param  string|array  $keys
     * @return bool
     */
    public static function allFilled(Request $objRequest, $keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        foreach ($keys as $key) {
            if (!$objRequest->filled($key)) {
                return false;
            }
        }

        return true;
    }
}
