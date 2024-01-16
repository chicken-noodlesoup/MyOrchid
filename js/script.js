

const body = document.querySelector("body");
const darkLight = document.querySelector("#darkLight");
const sidebar = document.querySelector(".sidebar");
const submenuItems = document.querySelectorAll(".submenu_item");
const sidebarOpen = document.querySelector("#sidebarOpen");
const sidebarClose = document.querySelector(".collapse_sidebar");
const sidebarExpand = document.querySelector(".expand_sidebar");
const mainContent = document.querySelector("#main");
sidebarOpen.addEventListener("click", () => {
  sidebar.classList.toggle("close");
  if (sidebar.classList.contains("close")) {
    document.getElementById("main").style.marginLeft = "90px";
  } else {
    document.getElementById("main").style.marginLeft = "270px";
  }
});

sidebarClose.addEventListener("click", () => {
  sidebar.classList.add("close", "hoverable");
  document.getElementById("main").style.marginLeft = "90px";
});

sidebarExpand.addEventListener("click", () => {
  sidebar.classList.remove("close", "hoverable");
  document.getElementById("main").style.marginLeft = "270px";
});

sidebar.addEventListener("mouseenter", () => {
  if (sidebar.classList.contains("hoverable")) {
    sidebar.classList.remove("close");
    document.getElementById("main").style.marginLeft = "270px";
  }
});
sidebar.addEventListener("mouseleave", () => {
  if (sidebar.classList.contains("hoverable")) {
    sidebar.classList.add("close");
    document.getElementById("main").style.marginLeft = "90px";
  }
});

darkLight.addEventListener("click", () => {
  body.classList.toggle("dark");
  if (body.classList.contains("dark")) {
    document.setI;
    darkLight.classList.replace("bx-sun", "bx-moon");
  } else {
    darkLight.classList.replace("bx-moon", "bx-sun");
  }
});

submenuItems.forEach((item, index) => {
  item.addEventListener("click", () => {
    item.classList.toggle("show_submenu");
    submenuItems.forEach((item2, index2) => {
      if (index !== index2) {
        item2.classList.remove("show_submenu");
      }
    });
  });
});

if (window.innerWidth < 768) {
  sidebar.classList.add("close");
} else {
  sidebar.classList.remove("close");
}


$(document).ready(function() {
  $('#example').DataTable();
});