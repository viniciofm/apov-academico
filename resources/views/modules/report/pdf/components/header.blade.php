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
                <img src="{{ asset($currentUser->instituicao->logomarca) }}" class="logoProfile" style="margin: 10px 20px 10px 20px; max-width: 110px" />
            </td>
            <td style="width: 100%; text-align: right; padding: 0;">{{ $currentUser->instituicao->nome }}</td>
        </tr>
    </table>
    <br>
</div>
</body>
</html>
