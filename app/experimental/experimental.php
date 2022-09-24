<?php

$money_amount_from[0] =     0;
$money_amount_to[0] =       1000;
$money_amount_from[1] =     $money_amount_to[0];
$money_amount_to[1] =       10000;
$money_amount_from[2] =     $money_amount_to[1];
$money_amount_to[2] =       100000;
$money_amount_from[3] =     $money_amount_to[2];
$money_amount_to[3] =       500000;
$money_amount_from[4] =     $money_amount_to[3];
$money_amount_to[4] =       10000000;
$money_amount_from[5] =     $money_amount_to[4];
$money_amount_to[5] =       25000000;
$money_amount_from[6] =     $money_amount_to[5];
$money_amount_to[6] =       50000000;
$money_amount_from[7] =     $money_amount_to[6];
$money_amount_to[7] =       100000000;
$money_amount_from[8] =     $money_amount_to[7];
$money_amount_to[8] =       200000000;
$money_amount_from[9] =     $money_amount_to[8];
$money_amount_to[9] =       500000000;
$money_amount_from[10] =    $money_amount_to[9];
$money_amount_to[10] =      1000000000;
$money_amount_from[11] =    $money_amount_to[10];
$money_amount_to[11] =      5000000000;
$money_amount_from[12] =    $money_amount_to[11];
$money_amount_to[12] =      10000000000;
$money_amount_from[13] =    $money_amount_to[12];
$money_amount_to[13] =      25000000000;
$money_amount_from[14] =    $money_amount_to[13];
$money_amount_to[14] =      50000000000;
$money_amount_from[15] =    $money_amount_to[14];
$money_amount_to[15] =      100000000000;
$money_amount_from[16] =    $money_amount_to[15];
$money_amount_to[16] =      INF;

$money_rank[0] = "Taxisjåfør";
$money_rank[1] = "Pusher";
$money_rank[2] = "Arbeider";
$money_rank[3] = "Langer";
$money_rank[4] = "Middel klasse";
$money_rank[5] = "Øvrige klasse";
$money_rank[6] = "Rik";
$money_rank[7] = "Middels rik";
$money_rank[8] = "Millionær";
$money_rank[9] = "Multimillionær";
$money_rank[10] = "Mangemillionær";
$money_rank[11] = "Skipsmegler";
$money_rank[12] = "Aksjemegler";
$money_rank[13] = "Børssjef";
$money_rank[14] = "Hotell-investor";
$money_rank[15] = "Oljesjeik";
$money_rank[16] = "Forretningsmagnat";


?>


<table>
    <tr>
        <th>PENGERANK</th>
        <th>PENGER FRA</th>
        <th>PENGER TIL</th>
    </tr>
    <?php for ($i = 0; $i < count($money_rank); $i++) { ?>
        <tr>
            <td><?= $money_rank[$i] ?></td>
            <td><?= number($money_amount_from[$i]) ?></td>
            <td><?= number($money_amount_to[$i]) ?></td>
        </tr>
    <?php } ?>

</table>