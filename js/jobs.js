const nameRegex = /^[A-Za-z\s]+$/

const emailRegex = /^[\w.-]+@[A-Za-z-]+(\.[A-Za-z0-9-]+){0,3}\.[A-Za-z]{2,3}$/
/*
    [\w.-]+ matches user name contains word characters including hyphen and period
    [A-Za-z-]+ matches domain name 2-4 address extension (gmail, yahoo, t-online)
    (\.[A-Za-z0-9-]+){0,3} matches subdomain (optional) that can have up to 3 repetition
    \.[A-Za-z]{2,3} matches the final extension that must have 2-3 characters
    eg:
    user@gmail.com
    us-er@yahoo.com
    u-se.r@t-online.com
    user@e.ntu.edu.sg

    invalid:
    user@example
    user@example..com
    user@exam!ple.com
    user@example.comm
    user @example.com
    user#example.com
    userexample.com
*/

function handle_name_validation()  {
    let error_name = document.getElementById("error-name")

    let name = document.forms["jobs-form"]["name"].value

    if (!name || name.trim().length === 0) {
        error_name.innerText = "Name field is required!"
        error_name.classList.add("error")
        return 
    }

    if (!nameRegex.test(name)) {
        error_name.innerText = "Invalid Name format (required field)"
        error_name.classList.add("error")
        return
    }

    error_name.innerText = ''
    error_name.classList.remove("error")
    return
}

function handle_email_validation() {
    let error_email = document.getElementById("error-email")

    let email = document.forms["jobs-form"]["email"].value

    if (!emailRegex.test(email)) {
        error_email.innerText = "Invalid Email Format"
        error_email.classList.add("error")
        return
    }

    error_email.innerText = ''
    error_email.classList.remove("error")
    return
}

function handle_sDate_validation() {
    let sDate = document.forms["jobs-form"]["sDate"].value

    let error_sDate = document.getElementById("error-sDate")

    if (!sDate) {
        error_sDate.innerText = "Date cannot be empty"
        error_sDate.classList.add("error")
        return
    }

    // Get the start date value from the input
    const startDate = new Date(sDate);

    // Get today's date
    const today = new Date();

    // Set the time of today's date to midnight (00:00:00) to exclude the time part
    today.setHours(0, 0, 0, 0);
    startDate.setHours(0, 0, 0, 0);
    
    // Check if the start date is today or a past date
    if (startDate <= today) {
        error_sDate.innerText = "The start date cannot be today or in the past."
        error_sDate.classList.add("error")
        return
    }

    error_sDate.innerText = ''
    error_sDate.classList.remove("error")
    return
}

function handle_experience_validation() {
    let error_experience = document.getElementById("error-experience")

    let experience = document.forms["jobs-form"]["experience"].value

    if (!experience) {
        error_experience.innerText = "Experience is a required field"
        error_experience.classList.add("error")
        return
    }

    error_experience.innerText = ''
    error_experience.classList.remove("error")
    return
}


function handle_jobs_validation() {
    let error_name = document.getElementById("error-name")
    let error_email = document.getElementById("error-email")
    let error_sDate = document.getElementById("error-sDate")
    let error_experience = document.getElementById("error-experience")

    handle_name_validation()
    handle_email_validation()
    handle_sDate_validation()
    handle_experience_validation()

    if(error_name.innerText !== '' || error_email.innerText !== '' || error_sDate.innerText !== '' || error_experience.innerText !== '') {
        return false // Error Prevent form submission
    }


    return true // Submit form
}