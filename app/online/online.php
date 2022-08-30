<?php

if (isset($_POST['search'])) {
  if (isset($_POST['username'])) {
    $username = $_POST['username'];

    $query = $pdo->prepare("SELECT COUNT(ACC_username) as amount, ACC_username, ACC_id FROM accounts WHERE ACC_username LIKE ?");
    $query->execute(array('%' . $username . '%'));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    $amount = $row['amount'];

    if (strlen($username) < 3) {
      echo feedback("Skriv minst 3 tegn", "error");
    } elseif ($amount == 0) {
      echo feedback("Ingen brukere funnet med kombinasjonen '" . $username . "'", "blue");
    } elseif ($amount == 1) {
      header("Location: ?side=profil&id=" . $row['ACC_id'] . "");
    } elseif ($amount >= 2) {
      header("Location: ?side=online&sok=" . $username . "");
    }
  }
}

if (isset($_GET['sok'])) {
?>
  <div class="col-8 single">
    <div class="content">
      <?php echo '<center>Søkeresulater med "' . $_GET['sok'] . '"</center>'; ?>
      <table id="datatable_player_search" data-order="[[3]]" style="margin-top: 20px;">
        <thead>
          <tr>
            <th data-orderable="false" style="width: 50px;"></th>
            <th>Bruker</th>
            <th>Rank</th>
            <th>Sist aktiv</th>
            <th>Registrert</th>
          </tr>
        </thead>
        <tbody>
          <?php

          $query = $pdo->prepare('SELECT * FROM accounts WHERE ACC_username LIKE :user');
          $query->execute(array(':user' => '%' . $_GET['sok'] . '%'));
          foreach ($query as $row) {
            $exp = AS_session_row($row['ACC_id'], 'AS_rank', $pdo);
          ?>
            <tr class="cursor_hover clickable-row" data-href="?side=profil&id=<?php echo $row['ACC_id']; ?>">
              <td><img style="max-height: 50px; max-width: 50px;" src="<?php echo AS_session_row($row['ACC_id'], 'AS_avatar', $pdo); ?>"></td>
              <td><span style="color: <?php echo role_colors($row['ACC_type']); ?>"><?php echo $row['ACC_username']; ?></span></td>
              <td data-order="<?php echo rank_index(rank_list($exp)); ?>"><?php

                  if (AS_session_row($row['ACC_id'], 'AS_rank', $pdo) >= 15) {
                    echo '<span style="color: #DD6E0F">' . rank_list($exp) . '</span>';
                  } else {
                    echo rank_list(AS_session_row($row['ACC_id'], 'AS_rank', $pdo));
                  }

                  ?></td>
              <td data-order="<?php echo $row['ACC_last_active'] ?>"><?php echo date_to_text($row['ACC_last_active']); ?></td>
              <td data-order="<?php echo $row['ACC_register_date'] ?>"><?php echo date_to_text($row['ACC_register_date']); ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
<?php

}

?>
<div class="col-8 single">
  <div class="content">
    <h3 style="text-align: center;"><?php echo players_online($pdo) ?> spiller<?php if (players_online($pdo) > 1) { ?>e<?php } ?> pålogget</h3>
    <div class="col-6 single" style="padding: 10px 0px;">
      <form method="post">
        <p class="description" style="margin-bottom: 5px;">Søk etter spiller</p>
        <div class="autocomplete">
          <input id="myInput" type="text" name="username" placeholder="Brukernavn">
        </div>
        <input type="submit" name="search" value="Søk etter spiller">
      </form>
    </div>
    <table id="datatable_online_players" data-order="[[3]]" style="margin-top: 20px;">
      <thead>
        <tr>
          <th data-orderable="false" style="width: 50px;"></th>
          <th>Bruker</th>
          <th>Rank</th>
          <th>Sist aktiv</th>
          <th>Registrert</th>
        </tr>
      </thead>
      <tbody>
        <?php

        $i = 0;
        $differanse = time() - 1800;
        $sql = "SELECT * FROM accounts WHERE ACC_last_active > '$differanse' AND ACC_type NOT IN (4, 5) ORDER BY ACC_username";
        $stmt = $pdo->query($sql);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $rank = AS_session_row($row['ACC_id'], 'AS_rank', $pdo);
        ?>
          <tr class="cursor_hover clickable-row" data-href="?side=profil&id=<?php echo $row['ACC_id']; ?>">
            <td><img style="max-height: 50px; max-width: 50px;" src="<?php echo AS_session_row($row['ACC_id'], 'AS_avatar', $pdo); ?>"></td>
            <td><span style="color: <?php echo role_colors($row['ACC_type']); ?>"><?php echo $row['ACC_username']; ?></span></td>
            <td data-order="<?php echo rank_index(rank_list($rank)); ?>"><?php

                if (AS_session_row($row['ACC_id'], 'AS_rank', $pdo) >= 15) {
                  echo '<span style="color: #DD6E0F">' . rank_list($rank) . '</span>';
                } else {
                  echo rank_list(AS_session_row($row['ACC_id'], 'AS_rank', $pdo));
                }

                ?></td>
            <td data-order="<?php echo $row['ACC_last_active']; ?>"><?php echo date_to_text($row['ACC_last_active']); ?></td>
            <td data-order="<?php echo $row['ACC_register_date']; ?>"><?php echo date_to_text($row['ACC_register_date']); ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<style>
  .autocomplete {
    position: relative;
    display: inline-block;
  }

  input[type=text] {
    width: 100%;
  }

  .autocomplete-items {
    position: absolute;
    border-bottom: none;
    border-top: none;
    z-index: 99;
    top: 100%;
    left: 0;
    right: 0;
  }

  .autocomplete-items div {
    padding: 10px;
    cursor: pointer;
    background-color: var(--container-color);
  }

  .autocomplete-items div:hover {
    background-color: var(--main-bg-color);
  }

  .autocomplete-active {
    color: var(--container-color);
  }
</style>

<script type="text/javascript">
  jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
      $(".clickable-row").unbind("click"); //Fjern click-event-listener for alle .clickable-row's
      window.location = $(this).data("href");
    });
  });

  function autocomplete(inp, arr) {
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) {
        return false;
      }
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
          b.addEventListener("click", function(e) {
            /*insert the value for the autocomplete text field:*/
            inp.value = this.getElementsByTagName("input")[0].value;
            /*close the list of autocompleted values,
            (or any other open lists of autocompleted values:*/
            closeAllLists();
          });
          a.appendChild(b);
        }
      }
    });
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
    });

    function addActive(x) {
      /*a function to classify an item as "active":*/
      if (!x) return false;
      /*start by removing the "active" class on all items:*/
      removeActive(x);
      if (currentFocus >= x.length) currentFocus = 0;
      if (currentFocus < 0) currentFocus = (x.length - 1);
      /*add class "autocomplete-active":*/
      x[currentFocus].classList.add("autocomplete-active");
    }

    function removeActive(x) {
      /*a function to remove the "active" class from all autocomplete items:*/
      for (var i = 0; i < x.length; i++) {
        x[i].classList.remove("autocomplete-active");
      }
    }

    function closeAllLists(elmnt) {
      /*close all autocomplete lists in the document,
      except the one passed as an argument:*/
      var x = document.getElementsByClassName("autocomplete-items");
      for (var i = 0; i < x.length; i++) {
        if (elmnt != x[i] && elmnt != inp) {
          x[i].parentNode.removeChild(x[i]);
        }
      }
    }
    /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function(e) {
      closeAllLists(e.target);
    });
  }

  /*An array containing all the country names in the world:*/
  <?php ?>

  var users = new Array();

  <?php

  $sql = "SELECT ACC_username FROM accounts";
  $stmt = $pdo->query($sql);

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

  ?>
    users.push('<?php echo $row['ACC_username']; ?>');
  <?php } ?>

  autocomplete(document.getElementById("myInput"), users);
</script>
<script>
$(document).ready(function() {
    $('#datatable_player_search, #datatable_online_players').DataTable();
} );
</script>
<script type="text/javascript" src="includes/datatable/datatables.min.js"></script>