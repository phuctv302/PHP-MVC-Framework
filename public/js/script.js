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