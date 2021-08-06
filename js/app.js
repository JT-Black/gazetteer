var map = L.map('backgroundMap').setView([51.505, -0.09], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// modal handling
// document.querySelector('#example-modal-button-2').addEventListener('click', function(e) {
//     var modalTwo = Bulma('[data-modal="clickwindow"]').modal();
//     modalTwo.open();
// });








