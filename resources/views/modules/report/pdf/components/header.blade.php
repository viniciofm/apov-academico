<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">

    <style type="text/css">
    </style>
</head>

<body>
<div id="header">
    <table>
        <tr>
            <td>
                <img src="{{ asset($currentUser->instituicao->logomarca) }}" class="logoProfile" style="margin: 10px 20px 10px 20px; max-width: 120px" />
            </td>
            @if(!empty($currentUser->instituicao->logomarca_secundaria))
                <td style="width: 100%; text-align: right; padding: 0;">
                    <img src="{{ asset($currentUser->instituicao->logomarca_secundaria) }}" class="logoProfile" style="margin: 10px 20px 10px 20px; max-width: 120px" />
                </td>
            @else
                <td style="width: 100%; text-align: right; padding: 0;">{{ $currentUser->instituicao->nome }}</td>
            @endif
        </tr>
    </table>
    <br>
</div>
</body>
</html>
