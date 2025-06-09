<?php

use Livewire\Livewire;

test('language switcher component renders successfully', function () {
    Livewire::test('language-switcher')
        ->assertStatus(200)
        ->assertSeeLivewire('language-switcher');
});

