function showToast(type, title, message) {
  var toast = document.getElementById("toast");

  // Add message content
  toast.innerHTML = `<strong>${title}</strong>: ${message}`;

  // Add class based on the type ('success' or 'error')
  toast.className = "toast " + type + " show";

  // Remove toast after 4 seconds
  setTimeout(function () {
    toast.className = toast.className.replace("show", ""); // Hide the toast
  }, 4000);
}
