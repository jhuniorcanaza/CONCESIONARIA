document.addEventListener("DOMContentLoaded", function () {
    const initSlider = () => {
        const slides = document.querySelectorAll(".hero-slide");
        let currentIndex = 0; // Track the current slide
        const totalSlides = slides.length;

        function moveToSlide(n) {
            slides.forEach((slide, index) => {
                slide.style.transform = `translateX(${-100 * n}%)`;
                if (n === index) {
                    slide.classList.add("active");
                } else {
                    slide.classList.remove("active");
                }
            });
            currentIndex = n;
        }

        // Function to go to the next slide
        function nextSlide() {
            if (currentIndex === totalSlides - 1) {
                moveToSlide(0); // Go to the first slide if we're at the last
            } else {
                moveToSlide(currentIndex + 1);
            }
        }

        // Function to go to the previous slide
        function prevSlide() {
            if (currentIndex === 0) {
                moveToSlide(totalSlides - 1); // Go to the last slide if we're at the first
            } else {
                moveToSlide(currentIndex - 1);
            }
        }

        // Example usage with buttons
        // Assuming you have buttons with classes `.next` and `.prev` for navigation
        const carouselNextButton = document.querySelector(".hero-slide-next");
        if (carouselNextButton) {
            carouselNextButton.addEventListener("click", nextSlide);
        }
        const carouselPrevButton = document.querySelector(".hero-slide-prev");
        if (carouselPrevButton) {
            carouselPrevButton.addEventListener("click", prevSlide);
        }

        // Initialize the slider
        moveToSlide(0);
    };

    const initImagePicker = () => {
        const fileInput = document.querySelector("#carFormImageUpload");
        const imagePreview = document.querySelector("#imagePreviews");
        if (!fileInput) {
            return;
        }
        fileInput.onchange = (ev) => {
            imagePreview.innerHTML = "";
            const files = ev.target.files;
            for (let file of files) {
                readFile(file).then((url) => {
                    const img = createImage(url);
                    imagePreview.append(img);
                });
            }
        };

        function readFile(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = (ev) => {
                    resolve(ev.target.result);
                };
                reader.onerror = (ev) => {
                    reject(ev);
                };
                reader.readAsDataURL(file);
            });
        }

        function createImage(url) {
            const a = document.createElement("a");
            a.classList.add("car-form-image-preview");
            a.innerHTML = `
        <img src="${url}" />
      `;
            return a;
        }
    };

    const initMobileNavbar = () => {
        const btnToggle = document.querySelector(".btn-navbar-toggle");

        btnToggle.onclick = () => {
            document.body.classList.toggle("navbar-opened");
        };
    };

    const imageCarousel = () => {
        const carousel = document.querySelector(".car-images-carousel");
        if (!carousel) {
            return;
        }
        const thumbnails = document.querySelectorAll(
            ".car-image-thumbnails img"
        );
        const activeImage = document.getElementById("activeImage");
        const prevButton = document.getElementById("prevButton");
        const nextButton = document.getElementById("nextButton");

        let currentIndex = 0;

        // Initialize active thumbnail class
        thumbnails.forEach((thumbnail, index) => {
            if (thumbnail.src === activeImage.src) {
                thumbnail.classList.add("active-thumbnail");
                currentIndex = index;
            }
        });

        // Function to update the active image and thumbnail
        const updateActiveImage = (index) => {
            activeImage.src = thumbnails[index].src;
            thumbnails.forEach((thumbnail) =>
                thumbnail.classList.remove("active-thumbnail")
            );
            thumbnails[index].classList.add("active-thumbnail");
        };

        // Add click event listeners to thumbnails
        thumbnails.forEach((thumbnail, index) => {
            thumbnail.addEventListener("click", () => {
                currentIndex = index;
                updateActiveImage(currentIndex);
            });
        });

        // Add click event listener to the previous button
        if (prevButton) {
            prevButton.addEventListener("click", () => {
                currentIndex =
                    (currentIndex - 1 + thumbnails.length) % thumbnails.length;
                updateActiveImage(currentIndex);
            });
        }

        // Add click event listener to the next button
        if (nextButton) {
            nextButton.addEventListener("click", () => {
                currentIndex = (currentIndex + 1) % thumbnails.length;
                updateActiveImage(currentIndex);
            });
        }
    };

    const initMobileFilters = () => {
        const filterButton = document.querySelector(".show-filters-button");
        const sidebar = document.querySelector(".search-cars-sidebar");
        const closeButton = document.querySelector(".close-filters-button");

        if (!filterButton) return;

        // console.log(filterButton.classList)
        filterButton.addEventListener("click", () => {
            if (sidebar.classList.contains("opened")) {
                sidebar.classList.remove("opened");
            } else {
                sidebar.classList.add("opened");
            }
        });

        if (closeButton) {
            closeButton.addEventListener("click", () => {
                sidebar.classList.remove("opened");
            });
        }
    };

    const initCascadingDropdown = (parentSelector, childSelector) => {
        const parentDropdown = document.querySelector(parentSelector);
        const childDropdown = document.querySelector(childSelector);

        if (!parentDropdown || !childDropdown) return;

        hideModelOptions(parentDropdown.value);

        parentDropdown.addEventListener("change", (ev) => {
            hideModelOptions(ev.target.value);
            childDropdown.value = "";
        });

        function hideModelOptions(parentValue) {
            const models = childDropdown.querySelectorAll("option");
            models.forEach((model) => {
                if (
                    model.dataset.parent === parentValue ||
                    model.value === ""
                ) {
                    model.style.display = "block";
                } else {
                    model.style.display = "none";
                }
            });
        }
    };

    const initSortingDropdown = () => {
        const sortingDropdown = document.querySelector(".sort-dropdown");
        if (!sortingDropdown) return;

        // Init sorting dropdown with the current value
        const url = new URL(window.location.href);
        const sortValue = url.searchParams.get("sort");
        if (sortValue) {
            sortingDropdown.value = sortValue;
        }

        sortingDropdown.addEventListener("change", (ev) => {
            const url = new URL(window.location.href);
            url.searchParams.set("sort", ev.target.value);
            window.location.href = url.toString();
        });
    };

    initSlider();
    initImagePicker();
    initMobileNavbar();
    imageCarousel();
    initMobileFilters();
    initCascadingDropdown("#makerSelect", "#modelSelect");
    initCascadingDropdown("#stateSelect", "#citySelect");
    initSortingDropdown();

    ScrollReveal().reveal(".hero-slide.active .hero-slider-title", {
        delay: 200,
        reset: true,
    });
    ScrollReveal().reveal(".hero-slide.active .hero-slider-content", {
        delay: 200,
        origin: "bottom",
        distance: "50%",
    });

    const searchCarForm = document.getElementById("searchCarForm");
    if (searchCarForm) {
        searchCarForm.addEventListener("submit", function (event) {
            event.preventDefault();
            let form = event.target;
            let url = new URL(form.action, window.location.origin);
            let params = new URLSearchParams(new FormData(form));

            let currentParams = new URLSearchParams(window.location.search);
            let sortValue = currentParams.get("sort");
            if (sortValue) {
                params.set("sort", sortValue);
            }

            let keysToDelete = [];
            for (let [key, value] of params.entries()) {
                if (!value.trim()) {
                    keysToDelete.push(key);
                }
            }
            keysToDelete.forEach((key) => params.delete(key));

            window.location.href = url.pathname + "?" + params.toString();
        });
    }

    const resetSearchForm = document.getElementById("resetSearchForm");
    if (resetSearchForm) {
        resetSearchForm.addEventListener("click", function () {
            let form = document.getElementById("searchCarForm");

            // form.reset();
            form.querySelectorAll("input, select, textarea").forEach(
                (field) => {
                    if (field.type === "checkbox" || field.type === "radio") {
                        field.checked = false;
                    } else {
                        field.value = "";
                    }
                }
            );

            let params = new URLSearchParams(window.location.search);
            for (let key of params.keys()) {
                params.delete(key);
            }

            history.replaceState(null, "", window.location.pathname);
        });
    }

    const resetCarForm = document.getElementById("resetCarForm");
    if (resetCarForm) {
        resetCarForm.addEventListener("click", function () {
            document.getElementById("carForm").reset();
            document.getElementById("imagePreviews").innerHTML = "";
        });
    }

    document.querySelectorAll(".btn-heart").forEach((button) => {
        button.addEventListener("click", function () {
            let carId = this.dataset.carId;

            fetch(`/favorite/toggle/${carId}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    "Content-Type": "application/json",
                },
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.status === "added") {
                    this.classList.add("active");
                } else {
                    this.classList.remove("active");
                }
            })
            .catch((error) => console.error("Ошибка:", error));
        });
    });

    const phoneLink = document.getElementById("carPhone");
    let phoneLoaded = false;
    if (phoneLink) {
        phoneLink.addEventListener("click", function (event) {

          event.preventDefault();
          
          if (phoneLoaded) return;

            fetch(phoneLink.href, {
              method: "POST",
              headers: {
                  "X-CSRF-TOKEN": document.querySelector(
                      'meta[name="csrf-token"]'
                  ).content,
                  "Content-Type": "application/json",
              },
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.phone) {
                    const phoneSpan = phoneLink.querySelector(".phone");
                    phoneSpan.textContent = data.phone;

                    const viewText = phoneLink.querySelector(
                        ".car-details-phone-view"
                    );
                    if (viewText) {
                        viewText.style.display = "none";
                    }
                    phoneLoaded = true;
                }
            })
            .catch((error) =>
                console.error("Getting phone number error:", error)
            );
        });
    }
});
