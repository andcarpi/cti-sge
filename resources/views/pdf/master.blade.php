<html lang="pt-br">
<head>
    <title>@yield('title') - SGE</title>

    @yield('css')
    @stack('css')

    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/dist/css/bootstrap.pdf.min.css') }}">

    <style type="text/css">
        html, body {
            font-size: 11pt;
        }

        h1, h2, h3, h4, h5, h6 {
            color: #222222;
            font-weight: bold;
        }

        body, h1, h2, h3, h4, h5, h6 {
            font-family: sans-serif;
        }

        @page {
            margin: 3.3cm 1.2cm 1.0cm 1.2cm;
        }

        header {
            position: fixed;
            top: -85px;
            font-size: 9pt;
        }

        footer {
            position: fixed;
            bottom: 25px;
        }

        footer .page-number:after {
            content: counter(page);
        }

        .page-break {
            page-break-after: always;
        }

        .table > thead > tr > th,
        .table > tbody > tr > th,
        .table > tfoot > tr > th,
        .table > thead > tr > td,
        .table > tbody > tr > td,
        .table > tfoot > tr > td {
            padding: 6px;
        }
    </style>

    <style type="text/css">
        .header-content {
            display: inline;
        }

        .header-content p {
            margin: 1px;
        }
    </style>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>

<header>
    <div>
        @if($img ?? true)
            <div class="header-content" style="float: left">
                <img src="{{ asset('img/cti.png') }}" style="width: 2.39cm" alt="">
            </div>

            <div class="header-content" style="float: right">
                <img src="{{ asset('img/unesp.png') }}" style="width: 4.02cm" alt="">
            </div>

            <div>
                <div class="header-content" style="text-align: center">
                    <p><b>UNIVERSIDADE ESTADUAL PAULISTA</b></p>
                    <p>COLÉGIO TÉCNICO INDUSTRIAL</p>
                    <p>“PROF. ISAAC PORTAL ROLDÁN”</p>
                </div>
            </div>
        @else
            <div class="header-content" style="text-align: center">
                <p><b>UNIVERSIDADE ESTADUAL PAULISTA "JÚLIO DE MESQUITA FILHO"</b></p>
                <p><b>CÂMPUS DE BAURU</b></p>
                <p>COLÉGIO TÉCNICO INDUSTRIAL “PROF. ISAAC PORTAL ROLDÁN”</p>
            </div>
        @endif
    </div>
</header>

<footer>
    @if($page_number ?? true))
        <div>
            <div class="pull-right page-number"></div>
        </div>
    @endif

    @yield('footer')
</footer>

<main>
    <div>

        @yield('content')

    </div>
</main>

</body>
</html>
