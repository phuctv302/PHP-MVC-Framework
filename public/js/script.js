// SHOW AND HIDE EDIT-FORM (CLICK EDIT BTN AND EDIT ACCOUNT NAVBAR)
// DOM
const formEdit = $('.form-edit');
const layout = $('.layout');

// show edit form and add background layout
const showForm = () => {
    formEdit.removeClass('hidden');
    layout.removeClass('hidden');
}

// close form function
const closeForm = () => {
    formEdit.addClass('hidden');
    layout.addClass('hidden');
}

// event listener for showing form
for (let i = 0; i < $('.show-form').length; i++){
   $('.show-form').on('click', showForm)
}

// event listener for hiding form
for (let i = 0; i < $('.close-form').length; i++){
    $('.close-form').on('click', closeForm)
}

// SHOW AND HIDE ALERT
const hideAlert = () => {
    const alert = $('.alert');
    if (alert) alert.remove();
}

// type is either: success or error
const showAlert = (type, msg, time = 3) => {
    hideAlert();

    const markup = `<div class="alert alert--${type}">${msg}</div>`;
    $('body').prepend(markup);

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
