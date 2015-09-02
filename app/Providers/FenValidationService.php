<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FenValidationService extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */

    private $validationStuff = [
        'legalSquareChars' => ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'],
        'legalSquareNums'  => ['1', '2', '3', '4', '5', '6', '7', '8']
    ];

    public function boot()
    {
      $this->app['validator']->extend('checkfen', function ($attribute, $value, $parameters) {
        return $this->fenValidation($value);
      });
      $this->app['validator']->extend('checksolution', function ($attribute, $value, $parameters) {
        return $this->solutionValidation($value);
      });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    


    }

    private function solutionValidation($ratkaisu) {

        $ruudut = explode("-", $ratkaisu);

        if (count($ruudut) !== 2) return false;
        if (!in_array(substr($ruudut[0], 0, 1), $this->validationStuff['legalSquareChars'])) return false;
        if (!in_array(substr($ruudut[0], 1, 1), $this->validationStuff['legalSquareNums'])) return false;
        if (!in_array(substr($ruudut[1], 0, 1), $this->validationStuff['legalSquareChars'])) return false;
        if (!in_array(substr($ruudut[1], 1, 1), $this->validationStuff['legalSquareNums'])) return false;
        return true;
    }

    private function fenValidation($fen) {

        $osat = explode(" ", $fen);

        if (count($osat) !== 6) return false;
        if ($osat[1] !== 'b' && $osat[1] !== 'w') return false;
        if (substr_count($osat[0], '/') !== 7) return false;
        return true;
    }

}
