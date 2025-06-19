<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Trang Chủ - Samsung</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Owl Carousel CSS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <!-- Bootstrap -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'quicksand';
        }
        x-cloak {
            display: none !important;
        }
    </style>
</head>
<body>
<header class="py-10">
    <div class="container m-auto flex items-center">
        <a href="/" class="w-[20%]">
            <svg class="icon" focusable="false" aria-hidden="true" width="130" height="29" viewBox="0 0 130 29">
                <g transform="translate(-250 -7)">
                    <path d="M0,13.835V12.482H4.609V14.2a1.558,1.558,0,0,0,1.724,1.6A1.509,1.509,0,0,0,8,14.6a2.237,2.237,0,0,0-.03-1.322C7.076,10.976.981,9.931.208,6.333a6.531,6.531,0,0,1-.029-2.4C.654,1.045,3.122,0,6.184,0c2.438,0,5.8.585,5.8,4.458V5.719H7.7V4.612a1.492,1.492,0,0,0-1.605-1.6,1.452,1.452,0,0,0-1.575,1.2,2.468,2.468,0,0,0,.03.922c.5,2.059,7.017,3.167,7.73,6.887a8.481,8.481,0,0,1,.029,2.921C11.892,17.893,9.336,19,6.244,19,3,19,0,17.8,0,13.835Zm55.837-.062V12.421h4.549v1.691a1.533,1.533,0,0,0,1.695,1.6,1.49,1.49,0,0,0,1.665-1.168,2.147,2.147,0,0,0-.029-1.292c-.863-2.274-6.9-3.319-7.671-6.917a6.37,6.37,0,0,1-.03-2.367c.476-2.859,2.944-3.9,5.946-3.9,2.409,0,5.739.615,5.739,4.427v1.23H63.449V4.643a1.485,1.485,0,0,0-1.575-1.6,1.4,1.4,0,0,0-1.546,1.168,2.463,2.463,0,0,0,.029.922C60.832,7.194,67.284,8.27,68,11.959a8.314,8.314,0,0,1,.029,2.89c-.416,2.952-2.943,4.028-6.005,4.028C58.811,18.877,55.837,17.678,55.837,13.773Zm16.293.647A7.18,7.18,0,0,1,72.1,13.25V.523h4.341V13.65a5.023,5.023,0,0,0,.029.677,1.682,1.682,0,0,0,3.271,0,4.852,4.852,0,0,0,.03-.677V.523h4.341V13.25c0,.339-.03.984-.03,1.169-.3,3.319-2.825,4.4-5.976,4.4S72.428,17.739,72.13,14.419Zm35.739-.185a9.539,9.539,0,0,1-.059-1.168V5.6c0-.308.029-.861.059-1.169.386-3.319,2.973-4.365,6.036-4.365,3.033,0,5.708,1.045,6.006,4.365A8.781,8.781,0,0,1,119.94,5.6v.584H115.6V5.2a3.791,3.791,0,0,0-.059-.677,1.777,1.777,0,0,0-3.42,0,3.772,3.772,0,0,0-.059.829v8.117a5.1,5.1,0,0,0,.03.677,1.707,1.707,0,0,0,1.813,1.291,1.633,1.633,0,0,0,1.754-1.291,2.554,2.554,0,0,0,.03-.677V10.883h-1.754V8.3H120v4.765a9.377,9.377,0,0,1-.06,1.168c-.3,3.228-3,4.366-6.036,4.366S108.166,17.462,107.869,14.235Zm-60.5,4.027L47.245,1.845,44.272,18.262H39.931L36.987,1.845l-.118,16.417H32.587L32.943.554h6.988L42.1,14.388,44.272.554h6.987l.386,17.708Zm-22.835,0L22.211,1.845,19.831,18.262H15.194L18.344.554h7.642l3.152,17.708Zm72.665-.184L92.884,3.352l.238,14.726H88.9V.554h6.363l4.044,14.265L99.068.554h4.251V18.078Z" transform="translate(255 12)"></path>
                </g>
            </svg>
        </a>
        <ul class="w-full flex justify-center gap-10">
            <li><a href="#">Ưu Đãi</a></li>
            <li><a href="#">Di Động</a></li>
            <li><a href="#">TV &amp; AV</a></li>
            <li><a href="#">Gia Dụng</a></li>
            <li><a href="#">IT</a></li>
            <li><a href="#">Phụ Kiện</a></li>
            <li><a href="#">SmartThings</a></li>
            <li><a href="#">AI</a></li>
        </ul>
        <div class="w-[20%] flex gap-5 justify-end">
            <a href="/auth/register">
                <svg class="icon w-[20px]" focusable="false" aria-hidden="true" width="96" height="96" viewBox="0 0 96 96">
                    <path d="M72.817 71.324c5.522 0 10 4.478 10 10 0 5.524-4.477 10-10 10s-10-4.476-10-10c0-5.522 4.477-10 10-10zm-34.946 0c5.523 0 10 4.478 10 10 0 5.524-4.477 10-10 10-5.522 0-10-4.476-10-10 0-5.521 4.479-10 10-10zm34.946 5a5.001 5.001 0 000 10 5 5 0 100-10zm-34.946 0a5 5 0 10.001 9.999 5 5 0 00-.001-9.999zM13.674 5c1.62 0 3.11 1.117 3.6 2.648l.054.186 3.208 12.292h70.035c2.126 0 3.61 1.88 3.194 3.914l-.041.18-9.398 36.292c-.405 1.566-1.849 2.747-3.459 2.835l-.194.006H29.57c-1.619 0-3.11-1.118-3.6-2.65l-.054-.185L12.725 10l-11.614.007-.002-5L13.674 5zm74.65 20.126H21.842l8.674 33.226H79.72l8.604-33.226z"></path>
                </svg>
            </a>
            <div class="relative" x-data="{ open: false }" x-on:click="open = ! open">
                <a href="#">
                    <svg class="icon w-[20px]" focusable="false" aria-hidden="true" width="96" height="96" viewBox="0 0 96 96">
                        <path d="M48,51.5c16.521,0,30.5,13.82,30.5,29.555h0V89A3.5,3.5,0,0,1,75,92.5H21A3.5,3.5,0,0,1,17.5,89h0V81.055C17.5,65.32,31.479,51.5,48,51.5Zm0,5c-13.772,0-25.5,11.595-25.5,24.555h0V87.5h51V81.055c0-12.831-11.494-24.323-25.087-24.552h0Zm0-53A20.5,20.5,0,1,1,27.5,24,20.5,20.5,0,0,1,48,3.5Zm0,5A15.5,15.5,0,1,0,63.5,24,15.5,15.5,0,0,0,48,8.5Z" transform="translate(-0.5 0.5)"></path>
                    </svg>
                </a>
                <div id="header-account-menu" class="bg-white border absolute z-10 top-[75px] right-0 w-[200px]" x-show="open" x-cloak>
                    <div class="links text-center">
                        <ul>
                            <li class="py-2.5"><a href="/customer/dashboard/" title="Thông Tin">Thông Tin</a></li>
                            <li class="py-2.5"><a href="/auth/login/" title="Đăng Nhập">Đăng Nhập</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<main>
