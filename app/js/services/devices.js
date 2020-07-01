import { config } from './config.js';

export function getAllDevices() {
    console.log("Getting devices");
    return fetch(config.apis.url + 'devices/', {
        headers: {
            username: config.user.id,
            token: config.user.token
        }
    })
    .then((response) => { return response.json(); })
    .catch(() => { return 'Invalid API'; })
}

export function getDeviceById(id) {
    return fetch(config.apis.url + 'devices/' + id, {
        headers: {
            username: config.user.id,
            token: config.user.token
        }
    })
    .then((response) => { return response.json(); })
    .catch(() => { return 'Invalid API'; })

    // var x = new XMLHttpRequest();
    // x.open("GET", "http://localhost/dashboard2020/api/devices/" + id);
    // x.send();
    // x.onreadystatechange = function () {
    //   //readystate 4= back with response
    //   if (x.readyState == 4 && x.status == 200) {
    //     //parse result to json
    //     var jsonData = JSON.parse(x.responseText);
    //     console.log(jsonData);
    //     //check status
    //     if (jsonData.status == 0) {
    //       showDeviceInfo(jsonData.device);
    //       showChart(jsonData.device);
    //     }
    //   }
    // };
  }