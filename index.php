<?php
$resultado = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numeroSiri = $_POST['numero_siri'];

    $endpoint = 'https://www.datos.gov.co/resource/iaeu-rcn6.json?numero_siri=' . $numeroSiri;
    $data = file_get_contents($endpoint);

    if ($data !== false) {
        $dataArray = json_decode($data, true);

        if ($dataArray !== null && !empty($dataArray)) {
            $resultado .= '<table>';
            $resultado .= 
            '<tr>
                <th>Identificacion</th>
                <th>Primer Apellido</th>
                <th>Primer Nombre</th>
                <th>Sancion</th>
                <th></th>
            </tr>';

            foreach ($dataArray as $item) {
                $resultado .= '<tr>';
                $resultado .= '<td>' . $item['numero_identificacion'] . '</td>';
                $resultado .= '<td>' . $item['primer_apellido'] . '</td>';
                $resultado .= '<td>' . $item['primer_nombre'] . '</td>';
                $resultado .= '<td>' . $item['sanciones'] . '</td>'; 
                $resultado .= '</tr>';
            }

            $resultado .= '</table>';
        } else {
            $resultado = 'No se encontraron datos para el número de Siri proporcionado.';
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
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Consulta de Antecedentes Judiciales o Penales</h1>
    <form method="post">
        <label for="numero_siri">Número de Siri:</label>
        <input type="text" id="numero_siri" name="numero_siri" placeholder="Ingresa el número de Siri" required>
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
