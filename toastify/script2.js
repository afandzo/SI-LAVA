var bgColors = ["linear-gradient(to right, #00b09b, #96c93d)", "linear-gradient(to right, #ff5f6d, #ffc371)"],
  i = 0;

// Toastify({
//   text: "Hi",
//   duration: 4500,
//   newWindow: true,
//   gravity: "top",
//   position: "left",
// }).showToast();

setTimeout(function () {
  Toastify({
    text: "Username / Password Salah!",
    gravity: "top",
    duration: 3000,
    position: "center",
    style: {
      background: "#0f3443",
    },
  }).showToast();
}, 500);

// Options for the toast
// var options = {
//   text: "Happy toasting!",
//   duration: 2500,
//   callback: function () {
//     console.log("Toast hidden");
//     Toastify.reposition();
//   },
//   close: true,
//   style: {
//     background: "linear-gradient(to right, #00b09b, #96c93d)",
//   },
// };

// // Initializing the toast
// var myToast = Toastify(options);

// // Toast after delay
// setTimeout(function () {
//   myToast.showToast();
// }, 4500);

// setTimeout(function () {
//   Toastify({
//     text: "Highly customizable",
//     gravity: "bottom",
//     position: "left",
//     close: true,
//     style: {
//       background: "linear-gradient(to right, #ff5f6d, #ffc371)",
//     },
//   }).showToast();
// }, 3000);

// // Displaying toast on manual action `Try`
// document.getElementById("new-toast").addEventListener("click", function () {
//   Toastify({
//     text: "I am a toast",
//     duration: 3000,
//     close: i % 3 ? true : false,
//     style: {
//       background: bgColors[i % 2],
//     },
//   }).showToast();
//   i++;
// });
