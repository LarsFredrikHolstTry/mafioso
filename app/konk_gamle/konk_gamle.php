<div class="col-8 single">
    <div class="content">
        <img class="action_image" src="img/action/actions/konk.png">
        <p class="description" style="text-align: center;">Under vil du se vinnere av forrige konkurranser</p>
        <table>
            <tr>
                <th>Konkurranse</th>
                <th>Dato
                <th>Vinnere</th>
            </tr>
            <?php

            $query = $pdo->prepare('SELECT * FROM konk WHERE KONK_to <= :time ORDER BY KONK_TO DESC');
            $query->execute(array(':time' => time()));

            foreach ($query as $row) {

            ?>
                <tr>
                    <td><?php echo $row['KONK_title']; ?></td>
                    <td><?php echo 'Fra ' . date_to_text($row['KONK_from']) . ' til ' . date_to_text($row['KONK_to']); ?></td>
                    <td><?php

                        if ($row['KONK_winners']) {

                            $row['KONK_winners'] = json_decode($row['KONK_winners'], true);
                            $i = 0;
                            foreach ($row['KONK_winners'] as $value) {
                                $i++;
                                echo $i . '. plass: ' . ACC_username($value, $pdo) . '<br> ';
                            }
                        }
                        ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>