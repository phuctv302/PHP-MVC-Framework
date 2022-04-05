// SHOW AND HIDE EDIT-FORM (CLICK EDIT BTN AND EDIT ACCOUNT NAVBAR)
// DOM
const formEdit = document.querySelector('.form-edit');
const layout = document.querySelector('.layout');
const showFormBtns = document.querySelectorAll('.show-form');
const hideFormBtns = document.querySelectorAll('.close-form');

// show edit form and add background layout
const showForm = () => {
    formEdit.classList.remove('hidden');
    layout.classList.remove('hidden');
}

// close form function
const closeForm = () => {
    formEdit.classList.add('hidden');
    layout.classList.add('hidden');
}

// event listener for showing form
showFormBtns.forEach(btn => {
    btn.addEventListener('click', showForm)
})

// event listener for hiding form
hideFormBtns.forEach(btn => {
    btn.addEventListener('click', closeForm)
})

// SHOW AND HIDE ALERT
const hideAlert = () => {
    const alert = document.querySelector('.alert');
    if (alert) alert.parentElement.removeChild(alert);
}

// type is either: success or error
const showAlert = (type, msg, time = 3) => {
    hideAlert();

    const markup = `<div class="alert alert--${type}"></div>`;
    document.querySelector('body').insertAdjacentHTML('afterbegin', markup);

    window.setTimeout(hideAlert, time * 1000);
}

// format message: remove " at the beginning and ending
const formatMessage = (message) => {
    return message.substring(1, message.length-1) || false;
}

// UPLOAD IMAGE ONE CLICK
$('.user-detail__image').on('click', function(){
    $('#profile-image-upload').click();
})
$('#profile-image-upload').on('change', function(){
    $('#profile-image-form').submit();
})
