if (document.getElementById('title'))
  document.getElementById('title').innerHTML = images["images"][0].replace("./img/","");
if (document.getElementById('sep'))
  document.getElementById('sep').innerHTML = "|";

generateHeaderLinks(getDateHeaders());

function goto_photo(seqnum) {

  var dateTime = get_dateTime(images, seqnum);

  document.getElementById('content_port').innerHTML = generateContent(seqnum);
  document.getElementById('date').innerHTML = dateTime;
  document.getElementById('imgNum').innerHTML = (seqnum + 1) + "/" + (images["images"].length);
  document.getElementById('title').innerHTML = images["images"][seqnum].replace("./img/","");
  document.getElementById('location').href = generateMapsURL(seqnum);
  document.getElementById('description').innerHTML = images["metadata"][seqnum]["UserComment"] ?? "No Description";

  document.getElementById('backButton').setAttribute('href', 'index.php#' + seqnum);

  generateMapsURL(seqnum);
}

function generateMapsURL(seqnum) {

  // https://www.google.com/maps/search/?api=1&query=43.14692%2C-80.26552 

  //console.log(images["metadata"][seqnum]);

  var lat_deg = parseFloat(images["metadata"][seqnum]["GPSLatitude"][0]);
  var lat_min = parseFloat(images["metadata"][seqnum]["GPSLatitude"][1]);
  var lat_sec = parseFloat(images["metadata"][seqnum]["GPSLatitude"][2]) / 10000;
  if (images["metadata"][seqnum]["GPSLatitudeRef"] == "S") {
    lat_deg *= -1;
    lat_min *= -1;
    lat_sec *= -1;
  }

  var long_deg = parseFloat(images["metadata"][seqnum]["GPSLongitude"][0]);
  var long_min = parseFloat(images["metadata"][seqnum]["GPSLongitude"][1]);
  var long_sec = parseFloat(images["metadata"][seqnum]["GPSLongitude"][2]) / 10000;
  if (images["metadata"][seqnum]["GPSLongitudeRef"] == "W") {
    long_deg *= -1;
    long_min *= -1;
    long_sec *= -1;
  }

  var lat_dec = lat_deg + (lat_min/60) + (lat_sec/3600);
  var long_dec = long_deg + (long_min/60) + (long_sec/3600);

  //console.log("Deg: " + lat_deg + " Min: " + lat_min + " Sec: " + lat_sec + " Dec: " + lat_dec);

  return "https://www.google.com/maps/search/?api=1&query=" + lat_dec + "%2C" + long_dec; 

}

function basename(path) {
   return path.split('/').reverse()[0];
}

function generateContent(seqnum) {

  if (basename(images["images"][seqnum]).includes(".mp4")) {

    var html = '<video loading="lazy" class="photo" id="mainImage" preload="auto" controls><source src="' + images["images"][seqnum] + '" /></video>';
    
  } else {

    var html = '<img class="photo" id="mainImage" src="' + images["images"][seqnum] + '" />';

  }

  return html;

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
  console.log(dateTime);

  dateTime = dateTime.replace(/([0-9]{4}):([0-9]{2}):([0-9]{2}) /, "$1-$2-$3T");
  dateTime = dateTime + "Z";
  dateTime = new Date(dateTime);

  var day = dateTime.getDate();
  var month = dateTime.getMonth() + 1;
  var year = dateTime.getFullYear();
  var hour = dateTime.getHours() >= 10 ? dateTime.getHours() + 4: '0' + (dateTime.getHours() + 4);
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

  if (html) {
    document.getElementById("navbar").innerHTML = html;
  }

}






