<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title><?= Route::currentRouteName(); ?></title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/app.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/colors.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-extended.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/fonts/icomoon.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/fonts/flag-icon-css/css/flag-icon.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/menu/vertical-menu.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/menu/vertical-overlay-menu.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/pace.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/iziToast.min.css') }}">
        <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">

        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <?php
            if(\Session::has('message-success')) { ?>
                <meta name="message-success" content="<?= \Session::get('message-success') ?>">
            <? }

            if(\Session::has('message-error')) { ?>
                <meta name="message-error" content="<?= \Session::get('message-error') ?>">
            <? }

            if(\Session::has('message-warning')) { ?>
                <meta name="message-warning" content="<?= \Session::get('message-warning') ?>">
            <? }
        ?>
    </head>