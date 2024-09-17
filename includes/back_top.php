<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Back to Top Button</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
#backToTop {
    position: fixed;
    bottom: 20px;
    left: 20px;
    background-color: #6610f2;
    color: #fff;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 18px;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.5s ease, visibility 0.5s ease;
    border-radius: 50px;
}

#backToTop.show {
    opacity: 1;
    visibility: visible;
}

#backToTop:hover {
    background-color: #6610f2;
    opacity: 0.8;
}


</style>
<body>
    <!-- <div style="height: 1500px;"> -->
        <!-- Content to simulate a long page -->
        <!-- Scroll down to see the button. -->
    <!-- </div> -->

    <button class="bg-primary" id="backToTop" title="Back to Top">&#8679;</button>

    <script src="script.js"></script>
</body>
<script>
// Get the button
let backToTopButton = document.getElementById("backToTop");

// Show the button when the user scrolls down 100px
window.onscroll = function() {
    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        backToTopButton.classList.add("show");
    } else {
        backToTopButton.classList.remove("show");
    }
};

// Scroll to the top slowly when the button is clicked
backToTopButton.onclick = function() {
    slowScrollToTop();
};

function slowScrollToTop() {
    const scrollDuration = 1000; // Duration of the scroll (in milliseconds)
    const scrollStep = -window.scrollY / (scrollDuration / 15);
    
    const scrollInterval = setInterval(function() {
        if (window.scrollY != 0) {
            window.scrollBy(0, scrollStep);
        } else {
            clearInterval(scrollInterval);
        }
    }, 15);
}


</script>
</html>
