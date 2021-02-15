const links = document.querySelectorAll(".links");
const page = document.querySelector("#page").value;

function getPlace(index, i) {
    return ((i - index + 1) * 50) + 45;
}

for (let i = 0; i < links.length; i++) {
    links[i].style.left = getPlace(page, i) + "px";
    links[i].classList.remove("active");
    if (i == page) {
        links[i].classList.add("active");
    }
}