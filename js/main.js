if (document.getElementById('title'))
  document.getElementById('title').innerHTML = images["images"][0].replace("./img/","");
if (document.getElementById('sep'))
  document.getElementById('sep').innerHTML = "|";

generateHeaderLinks(getDateHeaders());

function goto_photo(seqnum) {

  var dateTime = get_dateTime(images, seqnum);

  document.getElementById('mainImage').src = images["images"][seqnum];
  document.getElementById('date').innerHTML = dateTime;
  document.getElementById('imgNum').innerHTML = (seqnum + 1) + "/" + (images["images"].length);
  document.getElementById('title').innerHTML = images["images"][seqnum].replace("./img/","");

  document.getElementById('backButton').setAttribute('href', 'index.php#' + seqnum);

}

function back_photo() {

  if (!(seqnum == 0)) {

    seqnum--;
    goto_photo(seqnum);

  } else {

    seqnum = images["images"].length - 1;
    goto_photo(seqnum);

  }

}

function forward_photo() {

  if (!(seqnum == images["images"].length - 1)) {

    seqnum++;
    goto_photo(seqnum);

  } else {

    seqnum = 0;
    goto_photo(seqnum);

  } 

} 

function get_dateTime(images, seqnum) {

  var dateTime = images["metadata"][seqnum]["DateTimeOriginal"];
  dateTime = dateTime.replace(/([0-9]{4}):([0-9]{2}):([0-9]{2}) /, "$1-$2-$3T");
  dateTime = dateTime + "Z";
  dateTime = new Date(dateTime);

  var day = dateTime.getDate();
  var month = dateTime.getMonth();
  var year = dateTime.getFullYear();
  var hour = dateTime.getHours() >= 10 ? dateTime.getHours(): '0' + dateTime.getHours();
  var min = dateTime.getMinutes() >= 10 ? dateTime.getMinutes(): '0' + dateTime.getMinutes();

  dateTime = day + "-" + month + "-" + year + "   " + hour + ":" + min;

  return dateTime;

}

function get_date(images, seqnum) {

  console.log(date);
  var date = images["metadata"][seqnum]["DateTimeOriginal"];
  date = date.replace(/([0-9]{4}):([0-9]{2}):([0-9]{2}) /, "$1-$2-$3T");
  date = date.replace(/T.*/);
  console.log(date);

  date = new Date(date);

  var day = dateTime.getDate();
  var month = dateTime.getMonth();
  var year = dateTime.getFullYear();

  formatted_date = day + "-" + month + "-" + year;
  datePair = {"formatted": formatted_date, "unformatted": date.getTime()};

  return datePair;

}

function post(url, data) {

  fetch(url, {
    method: "POST",
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify(data)
  }).then(res => {
    console.log("Request Response: ", res);
  });

}

function redirect(url, data) {

  var parsedData = JSON.parse(data);
  var params = ""; 

  for (var key in parsedData) {

    var value = parsedData[key];
    params += key + "=" + value + "&";

  }

  params = params.substring(0, params.length -1);
  var newURL = url + "?" + params;

  document.location.href = newURL;

}

function getDateHeaders() {

  var headers = document.querySelectorAll('.dateHeader');
  console.log(headers);
  
  return headers;

}

function generateHeaderLinks(headers) {

  var html = "";

  for(var header of headers) {

    console.log(header);
    html += "<a href='#" + header.id + "' />" + header.innerHTML + "</a><br />";

  }

  document.getElementById("navbar").innerHTML = html;

}






