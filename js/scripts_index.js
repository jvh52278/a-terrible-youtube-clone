let screen_width = window.innerWidth;
// If the screen width is less than 1200px, redirect to mobile page
console.log(`screen width is: ${screen_width}`);
if (screen_width < 1200) {
    window.onload = function () {
        console.log("screen width is smaller than defined desktop resolution");
        document.getElementById("mobile_redirect").submit();
    }
}