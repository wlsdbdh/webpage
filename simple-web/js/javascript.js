function clock() {
  let timetext = document.getElementById("current-time");
  let today = new Date();
  let H = today.getHours();
  let M = today.getMinutes();

  timetext.innerHTML = H + ":" + M
}

clock();
setInterval(clock, 1000);
