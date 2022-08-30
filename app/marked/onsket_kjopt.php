    
<style type="text/css">
    .cat {
        display: none;
    }
</style>

<div class="content">
    <h4>Ønskes kjøpt</h4>
    <p class="description">
        Dersom det er noe du ønsker å kjøpe kan du legge ut kjøpsordre her. Dersom en bruker har tingen du ønsker å kjøpe vil brukeren kunne selge deg det for den gitte prisen du setter.
    </p>
    
    <select name="cat_dropdown" id="cat_dropdown" style="height: 30px;">
        <option value="">Velg kategori</option>
        <?php for($i = 0; $i < count($category); $i++){ ?>
            <option value="<?php echo $i; ?>"><?php echo $category[$i]; ?></option>
        <?php } ?>
    </select>
    
    <?php for($i = 0; $i < count($category); $i++){ ?>
        <?php if($i == 0){ ?>
            <div class="cat <?php echo $i; ?>">
                <table>
                    <tr>
                        <th>Velg bil</th>
                        <th>Velg by</th>
                        <th>Antall</th>
                        <th>Pris</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td>
                            <select name="car_dropdown" style="height: 30px;">
                            <option value="">Velg bil</option>
                            <?php for($j = 0; $j <= 18; $j++){ ?>
                            <option value="<?php echo $j; ?>"><?php echo car($j); ?></option>
                            <?php } ?>
                            </select>
                        </td>
                        <td>
                            <select name="city_dropdown" style="height: 30px;">
                            <option value="">Velg by</option>
                            <?php for($j = 0; $j <= 4; $j++){ ?>
                            <option value="<?php echo $j; ?>"><?php echo city_name($j); ?></option>
                            <?php } ?>
                            </select>
                        </td>
                        <td><input type="text" placeholder="Antall"></td>
                        <td><input type="text" placeholder="Pris"></td>
                        <td><input type="submit" value="Legg ut kjøpsordre"></td>
                    </tr>
                </table>
            </div>
        <?php } ?>
    <?php } ?>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $("#cat_dropdown").change(function () {
            var inputVal = $(this).val();
            var eleBox = $("." + inputVal);
            $(".cat").hide();
            $(eleBox).show();
        });
    });
</script>
