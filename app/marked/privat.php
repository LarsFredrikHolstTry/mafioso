<table id="table_id" class="display" style="padding-top: 10px;">
    <thead>
        <tr>
            <th>Beskrivelse</th>
            <th>Pris</th>
            <th>Trekkes tilbake</th>
            <th style="width: 15%;">Handling</th>
        </tr>
    </thead>
    <tbody>
        <?php

        $query = $pdo->prepare('SELECT * FROM marked WHERE MARKED_private = :priv OR MARKED_seller = :seller');
        $query->execute(array(':priv' => $_SESSION['ID'], ':seller' => $_SESSION['ID']));
        foreach ($query as $row) {

            $info = null;

            if ($row['MARKED_cat'] == 0) { ?>
                <tr>
                    <td>
                        <?php
                        $jsonobj = $row['MARKED_info'];

                        $obj = json_decode($jsonobj);

                        echo number($obj->car_amount);
                        echo ' stk ';
                        echo car($obj->car_id);
                        echo ' i ';
                        echo city_name($obj->car_city);
                        ?>
                        <?php

                        if (is_numeric($row['MARKED_private'])) {
                            echo 'fra ' . ACC_username($row['MARKED_seller'], $pdo) . ' til ' . ACC_username($row['MARKED_private'], $pdo);
                        }

                        ?>
                    </td>
                    <td><?php echo number($row['MARKED_price']); ?> kr
                    </td>
                    <td><?= date_to_text($row['MARKED_date']) ?></td>
                    <td>
                        <?php

                        if ($row['MARKED_seller'] == $_SESSION['ID']) {
                            echo '<a class="a_as_button" href="?side=marked&remove=' . $row['MARKED_id'] . '">Fjern</a>';
                        } else {
                            echo '<a class="a_as_button" href="?side=marked&buy=' . $row['MARKED_id'] . '">Kjøp</a>';
                        }

                        ?>
                    </td>
                </tr>
            <?php } elseif ($row['MARKED_cat'] == 1) { ?>
                <tr>
                    <td>
                        <?php
                        $jsonobj = $row['MARKED_info'];

                        $obj = json_decode($jsonobj);

                        echo thing($obj->thing_type) . ' ';
                        echo number($obj->amount);
                        echo ' stk ';
                        ?>
                        <?php

                        if (is_numeric($row['MARKED_private'])) {
                            echo 'fra ' . ACC_username($row['MARKED_seller'], $pdo) . ' til ' . ACC_username($row['MARKED_private'], $pdo);
                        }

                        ?>
                    </td>
                    <td><?php echo number($row['MARKED_price']); ?> kr
                    </td>
                    <td><?= date_to_text($row['MARKED_date']) ?></td>
                    <td>
                        <?php

                        if ($row['MARKED_seller'] == $_SESSION['ID']) {
                            echo '<a class="a_as_button" href="?side=marked&remove=' . $row['MARKED_id'] . '">Fjern</a>';
                        } else {
                            echo '<a class="a_as_button" href="?side=marked&buy=' . $row['MARKED_id'] . '">Kjøp</a>';
                        }

                        ?>
                    </td>
                </tr>
            <?php } elseif ($row['MARKED_cat'] == 2) { ?>
                <tr>
                    <td><?php echo number($row['MARKED_info']); ?> poeng
                        <?php

                        if (is_numeric($row['MARKED_private'])) {
                            echo 'fra ' . ACC_username($row['MARKED_seller'], $pdo) . ' til ' . ACC_username($row['MARKED_private'], $pdo);
                        }

                        ?>
                    </td>
                    <td><?php echo number($row['MARKED_price']); ?> kr
                        <br>
                        <p class="description" style="margin: 0;">Pris pr poeng: <?php echo number(($row['MARKED_price'] / $row['MARKED_info'])); ?> kr</p>
                    </td>
                    <td><?= date_to_text($row['MARKED_date']) ?></td>
                    <td>
                        <?php

                        if ($row['MARKED_seller'] == $_SESSION['ID']) {
                            echo '<a class="a_as_button" href="?side=marked&remove=' . $row['MARKED_id'] . '">Fjern</a>';
                        } else {
                            echo '<a class="a_as_button" href="?side=marked&buy=' . $row['MARKED_id'] . '">Kjøp</a>';
                        }

                        ?>
                    </td>
                </tr>
            <?php } elseif ($row['MARKED_cat'] == 3) { ?>
                <tr>
                    <td><?php echo number($row['MARKED_info']); ?> kuler
                        <?php

                        if (is_numeric($row['MARKED_private'])) {
                            echo 'fra ' . ACC_username($row['MARKED_seller'], $pdo) . ' til ' . ACC_username($row['MARKED_private'], $pdo);
                        }

                        ?>
                    </td>
                    <td><?php echo number($row['MARKED_price']); ?> kr
                        <br>
                        <p class="description" style="margin: 0;">Pris pr kule: <?php echo number(($row['MARKED_price'] / $row['MARKED_info'])); ?> kr</p>
                    </td>
                    <td><?= date_to_text($row['MARKED_date']) ?></td>
                    <td>
                        <?php

                        if ($row['MARKED_seller'] == $_SESSION['ID']) {
                            echo '<a class="a_as_button" href="?side=marked&remove=' . $row['MARKED_id'] . '">Fjern</a>';
                        } else {
                            echo '<a class="a_as_button" href="?side=marked&buy=' . $row['MARKED_id'] . '">Kjøp</a>';
                        }

                        ?>
                    </td>
                </tr>
            <?php } elseif ($row['MARKED_cat'] == 4) { ?>
                <tr>
                    <td><img style="height: 30px;" src="<?php echo charm($row['MARKED_info']); ?>">
                        <?php

                        if (is_numeric($row['MARKED_private'])) {
                            echo 'fra ' . ACC_username($row['MARKED_seller'], $pdo) . ' til ' . ACC_username($row['MARKED_private'], $pdo);
                        }

                        ?>
                    </td>
                    <td><?php echo number($row['MARKED_price']); ?> kr
                    </td>
                    <td><?= date_to_text($row['MARKED_date']) ?></td>
                    <td>
                        <?php

                        if ($row['MARKED_seller'] == $_SESSION['ID']) {
                            echo '<a class="a_as_button" href="?side=marked&remove=' . $row['MARKED_id'] . '">Fjern</a>';
                        } else {
                            echo '<a class="a_as_button" href="?side=marked&buy=' . $row['MARKED_id'] . '">Kjøp</a>';
                        }

                        ?>
                    </td>
                </tr>
            <?php } elseif ($row['MARKED_cat'] == 5) { ?>
                <tr>
                    <td>
                        <?php

                        $jsonobj = $row['MARKED_info'];

                        $obj = json_decode($jsonobj);

                        echo $firma_type[($obj->type)];
                        echo ' i ';
                        echo city_name($obj->city);
                        ?>
                        <?php

                        if (is_numeric($row['MARKED_private'])) {
                            echo 'fra ' . ACC_username($row['MARKED_seller'], $pdo) . ' til ' . ACC_username($row['MARKED_private'], $pdo);
                        }

                        ?>
                    </td>
                    <td><?php echo number($row['MARKED_price']); ?> kr
                    </td>
                    <td><?= date_to_text($row['MARKED_date']) ?></td>
                    <td>
                        <?php

                        if ($row['MARKED_seller'] == $_SESSION['ID']) {
                            echo '<a class="a_as_button" href="?side=marked&remove=' . $row['MARKED_id'] . '">Fjern</a>';
                        } else {
                            echo '<a class="a_as_button" href="?side=marked&buy=' . $row['MARKED_id'] . '">Kjøp</a>';
                        }

                        ?>
                    </td>
                </tr>
            <?php } elseif ($row['MARKED_cat'] == 6) { ?>
                <tr>
                    <td><?php echo number($row['MARKED_info']); ?> g cannabis
                        <?php

                        if ($row['MARKED_private'] != null) {
                            'fra ' . ACC_username($row['MARKED_seller'], $pdo) . ' til ' . ACC_username($row['MARKED_private'], $pdo);
                        }

                        ?>
                    </td>
                    <td><?php echo number($row['MARKED_price']); ?> kr
                    </td>
                    <td><?= date_to_text($row['MARKED_date']) ?></td>
                    <td>
                        <?php

                        if ($row['MARKED_seller'] == $_SESSION['ID']) {
                            echo '<a class="a_as_button" href="?side=marked&remove=' . $row['MARKED_id'] . '">Fjern</a>';
                        } else {
                            echo '<a class="a_as_button" href="?side=marked&buy=' . $row['MARKED_id'] . '">Kjøp</a>';
                        }

                        ?>
                    </td>
                </tr>
            <?php } ?>

        <?php } ?>
    </tbody>
</table>