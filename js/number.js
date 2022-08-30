function number_space(id) {
  $(id).on("keyup", function () {
    this.value = this.value.replace(/ /g, "");
    var number = this.value;
    this.value = number.replace(
      /\B(?=(\d{3})+(?!\d))/g,
      " "
    );
  });
}

function timer(seconds, id) {
  var timeleft = seconds;
  var downloadTimer = setInterval(function () {
    timeleft--;
    document
      .querySelectorAll(`#${id}`)
      .forEach((e) => {
        e.textContent = timeleft + "s";
      });
    if (timeleft <= 0) {
      document
        .querySelectorAll(`#${id}`)
        .forEach((e) => {
          e.innerHTML =
            '<span class="ready">Klar!</span>';
        });
    }
  }, 1000);
}

function timer_icon(seconds, id) {
  var timeleft = seconds;
  const div = document.getElementById(id);

  var downloadTimer = setInterval(function () {
    timeleft--;
    div.style.backgroundColor =
      "var(--not-ready-color)";
    if (timeleft <= 0) {
      div.style.backgroundColor =
        "var(--ready-color)";
      div.style.color = "black";
    }
  }, 1000);
}

function timeleft(timeleft_sec, elementID) {
  var timeleft = timeleft_sec;
  var downloadTimer = setInterval(function () {
    timeleft--;

    let element =
      document.getElementById(elementID);
    if (!element) {
      clearInterval(downloadTimer);
      return;
    }

    element.textContent = convertTime(
      timeleft,
      true
    );
    if (timeleft <= 0) {
      element.textContent = convertTime(0, true);
      location.href = currentPage;
      clearInterval(downloadTimer);
    }
  }, 1000);
}

function convertTime(seconds) {
  var minutes = 0;
  while (seconds > 59) {
    minutes++;
    seconds -= 60;
  }
  return `${minutes} minutter og ${seconds} sekunder`;
}

function wrapText(elementID, openTag, closeTag) {
  var textArea =
    document.getElementById(elementID);

  if (
    typeof textArea.selectionStart != "undefined"
  ) {
    var begin = textArea.value.substr(
      0,
      textArea.selectionStart
    );
    var selection = textArea.value.substr(
      textArea.selectionStart,
      textArea.selectionEnd -
        textArea.selectionStart
    );
    var end = textArea.value.substr(
      textArea.selectionEnd
    );
    textArea.value =
      begin +
      openTag +
      selection +
      closeTag +
      end;
  }
}

function startTime() {
  var today = new Date();
  var h = today.getHours();
  var m = today.getMinutes();
  var s = today.getSeconds();
  h = checkTime(h);
  m = checkTime(m);
  s = checkTime(s);
  document.getElementById("clock").innerHTML =
    h + ":" + m + ":" + s;
  var t = setTimeout(startTime, 1000);
}
function checkTime(i) {
  if (i < 10) {
    i = "0" + i;
  }
  return i;
}

function startDate() {
  var date = new Date();
  var day = ("0" + date.getDate()).slice(-2);
  var days = [
    "Søndag",
    "Mandag",
    "Tirsdag",
    "Onsdag",
    "Torsdag",
    "Fredag",
    "Lørdag",
  ];
  var today = date.getDay();
  var months = [
    "Januar",
    "Februar",
    "Mars",
    "April",
    "Mai",
    "Juni",
    "Juli",
    "August",
    "September",
    "Oktober",
    "November",
    "Desember",
  ];
  var month_today = date.getMonth();
  document.getElementById("date").innerHTML =
    days[today] +
    " " +
    day +
    ". " +
    months[month_today];
}

function timer_seconds(seconds, id) {
  var timeleft = seconds;
  var downloadTimer = setInterval(function () {
    timeleft--;
    document
      .querySelectorAll(`#${id}`)
      .forEach((e) => {
        e.textContent = timeleft + "s";
      });
    if (timeleft <= 0) {
      document
        .querySelectorAll(`#${id}`)
        .forEach((e) => {
          e.innerHTML =
            '<span class="right_sided_msg">Klar!</span>';
        });
    }
  }, 1000);
}
