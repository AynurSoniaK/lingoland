<?php

namespace App\Data;

class SearchData
{
    /**
     * @var string
     */
    public $q = '';

    /**
     * @var Language[] 
     */
    public $languages = [];

    /**
     * @var Languagelearned[] 
     */
    public $languageslearned = [];

    /**
     * @var genre[] 
     */
    public $gender = [];

    /**
    * @var null/integer
     */
    public $ageMin;

    /**
    * @var null/integer
     */
    public $ageMax;
}
