var metricSelect = document.createElement("select");
metricSelect.setAttribute("name", "metric_id[]");
//for(var i = 0; i < metrics.length; i++) {
for(metric in metrics) {
  metricSelect.appendChild(document.createElement("option"));
  metricSelect.lastChild.setAttribute("value", metric);
  metricSelect.lastChild.appendChild(document.createTextNode(metrics[metric]));
}

function removePoints(event) {
  var row = event.target;
  while(row.nodeName.toLowerCase() != "tr" && row.parentNode) {
    row = row.parentNode;
  }
  row.parentNode.removeChild(row);
}

function addPoints(event) {
  document.getElementById('addpoints').className = 'enabled';
  var form = event.target.form;
  var index = form['userid'].selectedIndex;
  
  var row = document.createElement("tr");
  for(var i = 1; i <= 5; i++) {
    var cell = document.createElement("td");
    switch(i) {
    case 1:
      cell.appendChild(document.createElement("input"));
      cell.lastChild.setAttribute("type", "button");
      cell.lastChild.setAttribute("value", "Remove");
      cell.lastChild.addEventListener("click", removePoints, false);
      break;
    case 2:
      cell.appendChild(document.createElement("input"));
      cell.lastChild.setAttribute("type", "hidden");
      cell.lastChild.setAttribute("name", "user_id[]");
      cell.lastChild.setAttribute("value", form['userid'][index].value);
      cell.appendChild(document.createTextNode(form['userid'][index].text));
      break;
    case 3:
      cell.appendChild(metricSelect.cloneNode(true));
      break;
    case 4:
      cell.appendChild(document.createElement("input"));
      cell.lastChild.setAttribute("type", "text");
      cell.lastChild.setAttribute("name", "points[]");
      cell.lastChild.setAttribute("value", "1");
      cell.lastChild.addEventListener("change", function(event) { alert(event); }, false);
      break;
    case 5:
      cell.appendChild(document.createElement("input"));
      cell.lastChild.setAttribute("type", "text");
      cell.lastChild.setAttribute("name", "description[]");
      cell.lastChild.setAttribute("value", "");
      break;
    }
    row.appendChild(cell);
  }
  document.getElementById("points").appendChild(row);
}
