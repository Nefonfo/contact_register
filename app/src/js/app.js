import 'calendar/src/js/calendar';
import Swiper from 'swiper/bundle';
import moment from 'moment';
import Swal from 'sweetalert2'

const validation = (name, date, phone) => {
    let result = '';
    
    const name_regex = /^[A-Za-z ]{2,80}$/;
    const phone_regex = /^[0-9]{8,12}$/;

    if(!name_regex.test(name)) {
        result = 'El nombre debe contener solo letras de 2-80 caracteres'
    } else if(!phone_regex.test(phone)) {
        result = 'El teléfono debe ser numérico de 8-12 elementos';
    } else if(!moment(date, 'DD-MM-YYYY').isValid()) {
        result = 'La fecha es invalida';
    }

    return result;
}

const post_data = (validate_result, post_obj, success_title) => {
    if(validate_result === '') {
        Swal.fire({
            title: '¿Estas seguro?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si',
            showLoaderOnConfirm: true,
            preConfirm: () => {
              return fetch('/backend', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(post_obj)
                })
                .then(response => {
                  if (!response.ok) {
                    throw new Error(response.statusText)
                  }
                  return response.json()
                })
                .catch(error => {
                  Swal.showValidationMessage(
                    `Error en el servidor: ${error}`
                  )
                })
            },
            backdrop: true,
            allowOutsideClick: () => !Swal.isLoading()
          }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Actualizado',
                    success_title,
                    'success'
                ).then(() => {
                    location.reload();
                });
            }
        });
    } else {
        console.log(validate_result);
        Swal.fire('Error', validate_result, 'error');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new Swiper('.swiper-container',{
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,
        observer: true, 
        observeParents: true
    });

    const button_modal_add = document.querySelector('#button_add');
    const modal_add = document.querySelector('#modal_add');

    button_modal_add.addEventListener('click', () => {modal_add.classList.remove('hidden')});

    modal_add.addEventListener('click', (e) => {
        if(e.target === modal_add.childNodes[1]) {
            modal_add.classList.add('hidden');
        }
    });

    document.querySelectorAll('form[data-register-id]').forEach( element => {
        const id = element.dataset.registerId;

        const data_name = document.querySelector(`#name${id}`);
        const data_birthday = document.querySelector(`#birthday${id}`);
        const data_phone = document.querySelector(`#phone${id}`);

        element.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const type = e.submitter.dataset.formAction;
            
            if(type === 'update') {
                const validate_result = validation(data_name.value, data_birthday.value, data_phone.value);
                post_data(validate_result, {
                    id: id,
                    name: data_name,
                    birthday: data_birthday,
                    phone: data_phone,
                    type: type
                }, 'Actualizado Correctamente');
            } else if(type === 'delete') {
                post_data('', {
                    id: id,
                    type: type
                }, 'Eliminado Correctamente');
            }
            
        });

    });

    const create_name = document.querySelector("#add_name");
    const create_birthday = document.querySelector("#add_birthday");
    const create_phone = document.querySelector("#add_phone");

    document.querySelector('#modal_add').addEventListener('submit', (e) => {
        e.preventDefault();

        const validate_result = validation(create_name.value, create_birthday.value, create_phone.value);

        post_data(validate_result, {
            name: create_name,
            birthday: create_birthday,
            phone: create_phone,
            type: 'create'
        }, 'Creado Exitosamente');
    });

});