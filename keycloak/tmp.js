document.querySelector("#kc-form-buttons").addEventListener("click", (event) => {
  event.preventDefault();
  console.log(99999);
  setTimeout(() => { console.log(555555); }, 3000);
  return false
});

