import axios from 'axios';

function getCircle(loc, radius) {
    var R = 6371; // earth's mean radius in km
    var lat = (loc.latitude * Math.PI) / 180; //rad
    var lon = (loc.longitude * Math.PI) / 180; //rad
    var d = parseFloat(radius) / R;  // d = angular distance covered on earth's surface
    var locs = [];
    for (let x = 0; x <= 360; x++) {
        const p = {};
        const brng = x * Math.PI / 180; //rad
        p.latitude = Math.asin(Math.sin(lat) * Math.cos(d) + Math.cos(lat) * Math.sin(d) * Math.cos(brng));
        p.longitude = ((lon + Math.atan2(Math.sin(brng) * Math.sin(d) * Math.cos(lat), Math.cos(d) - Math.sin(lat) * Math.sin(p.latitude))) * 180) / Math.PI;
        p.latitude = (p.latitude * 180) / Math.PI;
        locs.push(p);
    }
    return locs;
}

function getAddressesPoints(currentCoords, radius) {
    const {latitude, longitude} = currentCoords;
    axios
        .get(`localhost:8000/api/apartments/latitude=${latitude}&longitude=${longitude}&radius=${radius}`)
        .then(res => {
            const data = res.data.data;
            const dataForReturn = data.map(el => {
                return {
                    latitude: el.address.latitude,
                    longitude: el.address.longitude
                };
            });
            return dataForReturn;
        });
}

//let map = {};

export default function getMap() {
    navigator.geolocation.getCurrentPosition(location => {
        const {latitude, longitude} = location.coords;
        const map = new Microsoft.Maps.Map('#map', {
            center: new Microsoft.Maps.Location(latitude, longitude),
            zoom: 12
        });
        const center = map.getCenter();
        const pin = new Microsoft.Maps.Pushpin(center, {
            title: 'Vị trí của bạn',
            subTitle: 'Vị trí hiện tại của bạn',
            color: 'green'
        });

        // const points = getAddressesPoints(location.coords, 15);
        // console.log(points);
        // points.forEach(el => {
        //     const pin = new Microsoft.Maps.Pushpin(center, {
        //         icon: '/photo/pin.svg',
        //         anchor: new Microsoft.Maps.Point(el.latitude, el.longitude)
        //     });
        //     map.entities.push(pin);
        // });

        const circleShape = getCircle({latitude, longitude}, 15);
        const circle = new Microsoft.Maps.Polygon(circleShape, {
            fillColor: 'rgba(0, 255, 0, 0.5)',
            strokeColor: '#eb8f8f',
            strokeThickness: 2
        })

        map.entities.push(pin);
        map.entities.push(circle);

    });
}
