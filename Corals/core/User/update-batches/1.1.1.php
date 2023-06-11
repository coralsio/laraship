<?php

$filesystem = new \Illuminate\Filesystem\Filesystem();

if ($filesystem->exists(public_path('/assets/themes/admin/images/avatars'))
    && !$filesystem->exists(public_path('/assets/corals/images/avatars'))) {
    $filesystem->move(public_path('/assets/themes/admin/images/avatars'), public_path('/assets/corals/images/avatars'));
}