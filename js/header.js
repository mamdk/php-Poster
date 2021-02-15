const ul = document.querySelector("#ul-nav");
let path = window.location.pathname.split("/");
let path_num = path.length;
path = path[path_num - 1].split(".")[0];

if (path === "") path = "index";

for (let li of ul.children) {
    li.children[0].classList.remove("active");
    if (li.getAttribute("data-name") == path) {
        li.children[0].classList.add("active");
    }
}
