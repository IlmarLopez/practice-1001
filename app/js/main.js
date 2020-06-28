function init() {
  console.log("initializing...");
  getDevices();
}
function getDeviceById(id) {
  var x = new XMLHttpRequest();
  x.open("GET", "http://localhost/dashboard2020/api/devices/" + id);
  x.send();
  x.onreadystatechange = function () {
    //readystate 4= back with response
    if (x.readyState == 4 && x.status == 200) {
      //parse result to json
      var jsonData = JSON.parse(x.responseText);
      console.log(jsonData);
      //check status
      if (jsonData.status == 0) {
        showDeviceInfo(jsonData.device);
        showChart(jsonData.device);
      }
    }
  };
}
function getDevices() {
  console.log("Getting devices");
  var x = new XMLHttpRequest();
  x.open("GET", "http://localhost/dashboard2020/api/devices/");
  x.send();
  x.onreadystatechange = function () {
    //readystate 4= back with response
    if (x.readyState == 4 && x.status == 200) {
      //parse result to json
      var jsonData = JSON.parse(x.responseText);
      console.log(jsonData);
      //check status
      if (jsonData.status == 0) {
        showDevices(jsonData.devices);
      }
    }
  };
  //showDevices();
}
function showDevices(devices) {
  console.log(devices);
  //read devices
  devices.forEach((d) => {
    console.log(d);
    var divDevice = document.createElement("div");
    divDevice.className = "device";
    divDevice.setAttribute("onclick", `getDeviceById('${d.id}')`);

    //name
    var divName = document.createElement("div");
    divName.className = "devicename";
    divName.innerHTML = d.name;
    divDevice.appendChild(divName);
    //ICON
    var divIcon = document.createElement("div");
    divIcon.className = "deviceicon";
    var imgIcon = document.createElement("img");
    imgIcon.src = d.type.icon;
    divIcon.appendChild(imgIcon);
    divDevice.appendChild(divIcon);
    //devicetype
    var divType = document.createElement("div");
    divType.className = "devicetype";
    divType.innerHTML = d.type.description;
    divDevice.appendChild(divType);
    //deviceip
    var divIp = document.createElement("div");
    divIp.className = "deviceip";
    divIp.innerHTML = d.ipAddress;
    divDevice.appendChild(divIp);
    //add parent
    document.getElementById("devicelist").appendChild(divDevice);
  });
}
function showDeviceInfo(device) {
  console.log(device);
  document.getElementById("devicename").innerHTML = device.name;
  document.getElementById("typeicon").src = device.type.icon;
  document.getElementById("typename").innerHTML = device.type.description;
}

function showChart(device) {
  console.log("Showing chart...");
  var categories = [];
  //values
  var values = [];
  //read readings
  device.readings.forEach((r) => {
    categories.push(r.dateTime);
    values.push(r.value);
  });
  Highcharts.chart("chart", {
    chart: {
      type: "column",
      backgroundColor: "#333",
    },
    title: {
      text: device.type.description,
      style: {
        "font-size": "14pt",
        color: "#FFF",
      },
    },
    yAxis: {
      //eje de las y
      min: device.type.minValue,
      max: device.type.maxValue,
      title: {
        text: device.type.unitOfMeasurement,
      },
      style: {
        "font-size": "10pt",
        color: device.type.chartColor,
      },
    }, //eje de las x
    xAxis: {
      categories: categories,
      title: "Hour",
      style: {
        "font-size": "10pt",
        color: "#AAA",
      },
    },
    series: [
      {
        data: values,
        color: device.type.chartColor,
      },
    ],
    legend: {
      enabled: false,
    },
  });
}
