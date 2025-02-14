<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $questionTitle->name }}</title>
    <script type="text/javascript" id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-wDpOeR/0Ck8EeAEn5tpQWillDZlP++sz7KRGM2fvlrXjHqr8N6nWdWBvSWbIUKOJ" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 210mm; /* A4 width */
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .logo {
            height: 50px;
            margin-bottom: 20px;
        }

        .title {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        ol {
            margin-top: 0;
            padding-left: 20px;
        }

        ol li {
            margin-bottom: 10px;
        }

        @media print {
            .container {
                border: none;
                box-shadow: none;
            }

            .new-page {
                page-break-before: always;
            }

            .print-button {
                display: none; /* Hide the print button when printing */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <button class="btn btn-danger print-button" onclick="window.print()">Save ke PDF</button>
    </div>
    <div class="container print-area">
        @if($setting)
            <img src="{{ $setting->app_url }}/storage/upload_files/settings/{{ $setting->logo }}" alt="Logo" class="logo">
        @endif
        <div class="title">
            <h3>{{ $questionTitle->name }}</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pertanyaan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($questions as $index => $question)
                    <tr>
                        <td>{{ ++$index }}</td>
                        <td>
                            {!! $question->question !!}
                            <ol type="A">
                                @for ($i = 1; $i <= $questionTitle->total_choices; $i++)
                                    <li>
                                        @if($questionTitle->assessment_type == 4)
                                            <span class="badge badge-danger">point {{ $question['point_' . $i] }}</span> {!! $question['option_' . $i] !!}
                                        @else
                                            {!! $question['option_' . $i] !!}
                                        @endif
                                    </li>
                                @endfor
                            </ol>
                            @if($questionTitle->assessment_type != 4)
                                <b>
                                    Jawaban :
                                    @for ($i = 0; $i < count($question->answer); $i++)
                                        {{ chr(64 + $question->answer[$i]) }}
                                        @if ($i < count($question->answer) - 1),@endif
                                    @endfor
                                </b><br>
                            @endif
                            <div class="badge badge-primary">{{ $question->valueCategory ? $question->valueCategory->name : '' }}</div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
