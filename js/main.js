/*=============== SEARCH ===============*/

/*=============== LOGIN ===============*/
const loginButton = document.getElementById("login-button");
const loginClose = document.getElementById("login-close");
const loginContent = document.getElementById("login-content");

if (loginButton) {
  loginButton.addEventListener("click", () => {
    loginContent.classList.add("show-login");
  });
}

if (loginClose) {
  loginClose.addEventListener("click", () => {
    loginContent.classList.remove("show-login");
  });
}

/*=============== DIRECT KE SIGNUP ===============*/

/*=============== REGISTER ===============*/
const registerButton = document.getElementById("register-button");
const registerClose = document.getElementById("register-close");
const registerContent = document.getElementById("register-content");

if (registerButton) {
  registerButton.addEventListener("click", () => {
    registerContent.classList.add("show-register");
  });
}

if (registerClose) {
  registerClose.addEventListener("click", () => {
    registerContent.classList.remove("show-register");
  });
}

/*=============== HOME SWIPER ===============*/
let swiper = new Swiper(".home__swiper", {
  loop: true,
  spaceBetween: -24,
  grabCursor: true,
  slidesPerView: "auto",
  centeredSlides: true,

  autoplay: {
    delay: 3000,
    disableOnInteraction: false,
  },
  breakpoints: {
    1220: {
      spaceBetween: -32,
    },
  },
});

/*=============== Buku Terbaru ===============*/
let swiperFeatured = new Swiper(".featured__swiper", {
  loop: true,
  spaceBetween: 16,
  grabCursor: true,
  slidesPerView: "auto",
  centeredSlides: "true",

  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },

  breakpoints: {
    1150: {
      slidesPerView: 5,
      centeredSlides: false,
    },
  },
});

/*=============== TESTIMONIAL SWIPER ===============*/
let swiperTestimonial = new Swiper(".testimonial__swiper", {
  loop: true,
  spaceBetween: 16,
  grabCursor: true,
  slidesPerView: "auto",
  centeredSlides: true,

  breakpoints: {
    1150: {
      slidesPerView: 3,
      centeredSlides: false,
    },
  },
});

/*=============== TESTIMONIAL SWIPER ===============*/
let swiperTeam = new Swiper(".team__swiper", {
  loop: true,
  spaceBetween: 16,
  grabCursor: true,
  slidesPerView: "auto",
  centeredSlides: true,

  autoplay: {
    delay: 5000,
    disableOnInteraction: false,
  },
  breakpoints: {
    1150: {
      slidesPerView: 3,
      centeredSlides: false,
    },
  },
});

/*=============== SHOW SCROLL UP ===============*/
const scrollHeader = () => {
  const header = document.getElementById("header");
  const scrollUp = document.getElementById("scroll-up");
  // Memeriksa posisi scroll
  window.scrollY > 350
    ? header.classList.add("show-scroll")
    : header.classList.remove("show-scroll");
};

window.addEventListener("scroll", scrollHeader);

/*=============== DARK LIGHT THEME ===============*/

const themeButton = document.getElementById("theme-button");

const darkTheme = "dark-theme";
const iconTheme = "ri-sun-line";

const selectedTheme = localStorage.getItem("selected-theme");
const selectedIcon = localStorage.getItem("selected-icon");

const getCurrentTheme = () =>
  document.body.classList.contains(darkTheme) ? "dark" : "light";

const getCurrentIcon = () =>
  themeButton.classList.contains(iconTheme) ? "ri-moon-line" : "ri-sun-line";

if (selectedTheme) {
  document.body.classList[selectedTheme === "dark" ? "add" : "remove"](
    darkTheme
  );
  themeButton.classList[selectedIcon === "ri-moon-line" ? "add" : "remove"](
    iconTheme
  );
}

themeButton.addEventListener("click", () => {
  document.body.classList.toggle(darkTheme);

  themeButton.classList.toggle(iconTheme);

  localStorage.setItem("selected-theme", getCurrentTheme());
  localStorage.setItem("selected-icon", getCurrentIcon());
});

/*=============== SCROLL REVEAL ANIMATION ===============*/

/*=============== PDF VIEWER===============*/
const zoomButton = document.getElementById("zoom");
const input = document.getElementById("inputFile");
const openFile = document.getElementById("openPDF");
const currentPage = document.getElementById("current_page");
const viewer = document.querySelector(".pdf-viewer");
let currentPDF = {};

function resetCurrentPDF() {
  currentPDF = {
    file: null,
    countOfPages: 0,
    currentPage: 1,
    zoom: 2,
  };
}

openFile.addEventListener("click", () => {
  input.click();
});

input.addEventListener("change", (event) => {
  const inputFile = event.target.files[0];
  if (inputFile.type == "application/pdf") {
    const reader = new FileReader();
    reader.readAsDataURL(inputFile);
    reader.onload = () => {
      loadPDF(reader.result);
      zoomButton.disabled = false;
    };
  } else {
    alert("The file you are trying to open is not a pdf file!");
  }
});

zoomButton.addEventListener("input", () => {
  if (currentPDF.file) {
    document.getElementById("zoomValue").innerHTML = zoomButton.value + "%";
    currentPDF.zoom = parseInt(zoomButton.value) / 100;
    renderCurrentPage();
  }
});

document.getElementById("next").addEventListener("click", () => {
  const isValidPage = currentPDF.currentPage < currentPDF.countOfPages;
  if (isValidPage) {
    currentPDF.currentPage += 1;
    renderCurrentPage();
  }
});

document.getElementById("previous").addEventListener("click", () => {
  const isValidPage = currentPDF.currentPage - 1 > 0;
  if (isValidPage) {
    currentPDF.currentPage -= 1;
    renderCurrentPage();
  }
});

function loadPDF(data) {
  const pdfFile = pdfjsLib.getDocument(data);
  resetCurrentPDF();
  pdfFile.promise.then((doc) => {
    currentPDF.file = doc;
    currentPDF.countOfPages = doc.numPages;
    viewer.classList.remove("hidden");
    document.querySelector("main h3").classList.add("hidden");
    renderCurrentPage();
  });
}

function renderCurrentPage() {
  currentPDF.file.getPage(currentPDF.currentPage).then((page) => {
    var context = viewer.getContext("2d");
    var viewport = page.getViewport({ scale: currentPDF.zoom });
    viewer.height = viewport.height;
    viewer.width = viewport.width;

    var renderContext = {
      canvasContext: context,
      viewport: viewport,
    };
    page.render(renderContext);
  });
  currentPage.innerHTML =
    currentPDF.currentPage + " of " + currentPDF.countOfPages;
}
