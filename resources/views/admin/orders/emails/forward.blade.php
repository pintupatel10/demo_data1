<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Grayline</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>

<body>
This is an unclassified message received by the system.

@if ($original->textHtml || $original->textPlain)
    <br />
    <br />
    <blockquote type="cite">
        <div>
            <div>
                On {{ $original->date }}, {{ $original->fromName }} &lt;<a href="mailto:{{ $original->fromAddress }}"/>{{ $original->fromAddress }}</a>&gt; wrote:
            </div>

            <div>
                @if ($original->textHtml)
                    {!! quoted_printable_decode($original->textHtml) !!}
                @else
                    {!! nl2br(quoted_printable_decode($original->textPlain)) !!}
                @endif
            </div>

        </div>
    </blockquote>
@endif
</body>
</html>