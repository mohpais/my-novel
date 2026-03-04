<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="/favicon.ico" />
        <title>My Novel Management System</title>
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

        <style>
            [v-cloak] > * {
                display: none;
            }
            #body {
                margin: 0;
            }
            #loading__container {
                min-height: 100vh;
                display: flex;
                align-items: center;
                text-align: center;
                color: #fff;
                /* background: #10b981; */
                background: #16a34a;
                text-transform: uppercase;
                font-family: Arial, sans-serif;
                font-size: 10px;
                letter-spacing: 2px;
            }
            .preloader {
                margin: 0 auto;
            }
            .lds-ellipsis {
                display: inline-block;
                position: relative;
                width: 80px;
                height: 80px;
            }
            .lds-ellipsis div {
                position: absolute;
                top: 33px;
                width: 13px;
                height: 13px;
                border-radius: 50%;
                background: #fff;
                animation-timing-function: cubic-bezier(0, 1, 1, 0);
            }
            .lds-ellipsis div:nth-child(1) {
                left: 8px;
                animation: lds-ellipsis1 0.6s infinite;
            }
            .lds-ellipsis div:nth-child(2) {
                left: 8px;
                animation: lds-ellipsis2 0.6s infinite;
            }
            .lds-ellipsis div:nth-child(3) {
                left: 32px;
                animation: lds-ellipsis2 0.6s infinite;
            }
            .lds-ellipsis div:nth-child(4) {
                left: 56px;
                animation: lds-ellipsis3 0.6s infinite;
            }
            @keyframes lds-ellipsis1 {
                0% {
                    transform: scale(0);
                }
                100% {
                    transform: scale(1);
                }
            }
            @keyframes lds-ellipsis3 {
                0% {
                    transform: scale(1);
                }
                100% {
                    transform: scale(0);
                }
            }
            @keyframes lds-ellipsis2 {
                0% {
                    transform: translate(0, 0);
                }
                100% {
                    transform: translate(24px, 0);
                }
            }
        </style>

        <!-- Scripts -->
        @vite([
          'resources/css/font.css',
          'resources/css/satoshi.css',
          'resources/css/main.css',
          'resources/js/main.js'
        ])
    </head>
    <body id="body" class="dark:bg-gray-900">
        <div id="app" v-cloak>
            <div id="loading__container">
                <div class="preloader">
                <div class="lds-ellipsis">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>
    </body>
</html>
