<?php

class Search extends Controller
{

    public function index($searchedWord)
    {
        $this->view('search', [
            'searchedWord' => $searchedWord
        ]);
    }

}