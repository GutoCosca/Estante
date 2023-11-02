<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        date_default_timezone_set('America/Sao_Paulo');
        $data = date('1974-08-29');
        echo $data."<br>";
        $data2 = date('d-m-Y', strtotime($data));
        echo $data2."<br>";
    ?>
    <form action="">
        <fieldset>teste
            <input type="radio" name="arqmorto" id="sim" value="1">
            <label for="arqmorto">Sim</label>
            <input type="radio" name="arqmorto" id="nao" value="0" placeholder="0">
            <label for="arqmorto">Não</label>
        </fieldset>
    </form>
</body>
</html>