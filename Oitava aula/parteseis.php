    <html>
        <body>
            <table border ="1">
                <tr>
                    <th>Disciplina</th>
                    <th>Faltas</th>
                    <th>Média</th>
                </tr>
                <?php
                    $boletim = array( 
                    array("Disciplina" => "Matematica", "Faltas" => "5", "Média" => "8.5"),
                    array("Disciplina" => "Português", "Faltas" => "2", "Média" => "6"),
                    array("Disciplina" => "Geografia", "Faltas" => "10", "Média"=> "6"),
                    array("Disciplina" => "Educação física", "Faltas" => "2", "Média" => "8")
                    );

                    foreach($boletim as $tabela) {
                        echo "<tr>";
                            echo "<td>".$tabela["Disciplina"]. "</td>";
                            echo "<td>".$tabela["Faltas"]. "</td>";
                            echo "<td>".$tabela["Média"]. "</td>";
                        echo "</tr>";
                    }
                ?>
            </table>
        </body>
    </html>