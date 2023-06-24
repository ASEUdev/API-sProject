<?php
$resultado = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $placa = $_POST['placa'];

    $endpoint = 'https://www.datos.gov.co/resource/x8g3-nn2c.json?placa=' . $placa;
    $data = file_get_contents($endpoint);

    if ($data !== false) {
        $dataArray = json_decode($data, true);
        if ($dataArray !== null && !empty($dataArray)) {
            $fmt = numfmt_create('es_CO', NumberFormatter::CURRENCY);
            $resultado .= '<table>';
            $resultado .= 
            '<tr>
                <th>Fecha</th>
                <th>Valor</th>
                <th>Ciudad</th>
                <th>Pago</th>
            </tr>';

            foreach ($dataArray as $item) {
                $valor = numfmt_format_currency($fmt, (int)$item['valor_multa'], 'COP');
                $resultado .= '<tr>';
                $resultado .= '<td>' . $item['fecha_multa'] . '</td>';
                $resultado .= '<td>' . $valor . '</td>';
                $resultado .= '<td>' . $item['ciudad'] . '</td>';
                $resultado .= '<td>' . $item['pagado_si_no'] . '</td>'; 
                $resultado .= '</tr>';
            }

            $resultado .= '</table>';
        } else {
            $resultado = 'No se encontraron datos para la placa proporcionada.';
        }
    } else {
        $resultado = 'No se pudieron obtener los datos del endpoint.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Consulta de Antecedentes</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    
</head>
<body>
    <h1>Consulta de Antecedentes Judiciales o Penales</h1>
    <form method="post">
        <label for="placa">Placa:</label>
        <input type="text" id="placa" name="placa" placeholder="Ingresa la placa" required>
        <br />
        <button type="submit">Consultar</button>
    </form>

    <div id="result">
        <?php if (!empty($resultado)): ?>
            <?php echo $resultado; ?>
        <?php endif; ?>
    </div>
</body>
</html>
