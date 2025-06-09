<?php

test('app layout component renders without errors', function () {
    $view = $this->blade('<x-app-layout />');

    $view->assertSee('<!DOCTYPE html>', false);
});

