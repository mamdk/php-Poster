let imgCaptcha = document.querySelector("#imgCaptcha");
let btnCaptcha = document.querySelector("#btnCaptcha");

btnCaptcha.onclick = () => {
    imgCaptcha.src = "../captcha/captcha.php?" + Date.now();
}


