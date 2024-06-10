<?php

require 'vendor/autoload.php';

use React\EventLoop\Loop;
use React\Promise\Promise;

function calculateSimilarity($targetStudent, $referenceStudent)
{
    // Your similarity calculation logic here
    // Replace this with your actual similarity calculation code
    $similarity = rand(0, 100) / 100; // Placeholder logic, replace this with your calculation

    return $similarity;
}

function calculateSimilarityAsync($targetStudent, $referenceStudents)
{
    $loop = Loop::get();
    $promises = [];

    foreach ($referenceStudents as $referenceStudent) {
        $promise = new Promise(function ($resolve, $reject) use ($targetStudent, $referenceStudent, $loop) {
            $loop->futureTick(function () use ($resolve, $targetStudent, $referenceStudent) {
                $similarity = calculateSimilarity($targetStudent, $referenceStudent);
                $resolve([$referenceStudent->student_no => $similarity]);
            });
        });

        $promises[] = $promise;
    }

    return $promises;
}
