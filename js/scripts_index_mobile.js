let screen_width = window.innerWidth;
// If the screen width is greater then 1200px, redirect to desktop page
console.log(`screen width is: ${screen_width}`);
if (screen_width > 1200) {
    window.onload = function () {
        console.log("screen width is equal to or greater than defined desktop resolution");
        document.getElementById("desktop_redirect").submit();
    }
}