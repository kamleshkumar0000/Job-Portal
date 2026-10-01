// ================= JOB SEARCH =================

function searchJobs() {

    let job =
        document.getElementById("jobSearch").value.trim();

    let location =
        document.getElementById("locationSearch").value.trim();

    let result =
        document.getElementById("searchResult");


    if (job === "" && location === "") {

        result.innerHTML =
            "Please enter a job title or location.";

        return;
    }


    if (job !== "" && location !== "") {

        result.innerHTML =
            "Searching for " +
            job +
            " jobs in " +
            location +
            "...";

    }

    else if (job !== "") {

        result.innerHTML =
            "Searching for " +
            job +
            " jobs...";

    }

    else {

        result.innerHTML =
            "Searching for jobs in " +
            location +
            "...";
    }

}


// ================= APPLY JOB =================

function applyJob(jobName) {

    alert(
        "You selected: " +
        jobName +
        "\n\nPlease Login or Register to apply."
    );

}


// ================= LOGIN =================

function openLogin() {

    document.getElementById("loginModal")
        .style.display = "flex";

}

function closeLogin() {
    document.getElementById("loginModal")
        .style.display = "none";
}

function closeLogin() {

    document.getElementById("loginModal")
        .style.display = "none";

}


function loginUser() {

    alert("Login button clicked!");

    closeLogin();

}


// ================= REGISTER =================

function openRegister() {

    document.getElementById("registerModal")
        .style.display = "flex";

}


function closeRegister() {

    document.getElementById("registerModal")
        .style.display = "none";

}


function registerUser() {

    alert("Registration button clicked!");

    closeRegister();

}


// ================= CONTACT =================

function sendMessage(event) {

    event.preventDefault();

    let name =
        document.getElementById("name").value;

    alert(
        "Thank you " +
        name +
        "! Your message has been sent."
    );

    document
        .querySelector(".contact-section form")
        .reset();

}


// ================= CLOSE MODALS =================

window.onclick = function(event) {

    let loginModal =
        document.getElementById("loginModal");

    let registerModal =
        document.getElementById("registerModal");


    if (event.target === loginModal) {

        loginModal.style.display = "none";

    }


    if (event.target === registerModal) {

        registerModal.style.display = "none";

    }

};