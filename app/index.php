<?php 
include('database.php');
use Web\App\Database;

function get_users() {
    $database = Database\Database::get_instance();
    $cnx = $database->get_connection();
    $query = 'SELECT * FROM Agenda';
    $pstm = $cnx->prepare($query);
    $pstm->execute([]);
    $users = $pstm->fetchAll(PDO::FETCH_OBJ);
    $cnx = NULL;
    return $users;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script defer="defer" src="./js/runtime~main.js"></script>
    <script defer="defer" src="./js/main.js"></script>
    <title>Proyecto</title>
</head>
<body class="bg-gray-100">
    <!-- NAVBAR -->
    <div class="w-full bg-purple-800 shadow-xl px-2 py-4 flex justify-center items-center sticky top-0 z-50">
        <h1 class="text-white text-xl md:text-3xl text-center font-bold">Registro de Contactos</h1>
    </div>
    <!-- NAVBAR ENDS -->
    <!-- CARROUSEL CONTAINER -->
    <div class="z-0 w-full mt-6 md:mt-20 mb-20 md:mb-6 flex flex-wrapper justify-center items-center">
        <div class="w-full swiper-container z-0" style="margin: 0 !important">
            <div class="swiper-wrapper z-0">
                <?php
                    $users = get_users();
                    foreach($users as $index => $user) {
                        echo '
                            <!-- CARD -->
                            <div class="swiper-slide w-full flex flex-wrap justify-center items-center">
                                <form class="w-4/5 md:w-2/5 p-6 md:py-12 md:px-5 bg-white shadow-lg rounded-lg" data-register-id="'.($user->id).'">
                                    <div class="w-full mb-2 flex flex-wrap justify-center">
                                        <h1 class="text-2xl font-light text-purple-800">Registro '.($index+1).' de '.sizeof($users).'</h1>
                                    </div>
                                    <div class="w-full mb-4 flex flex-wrap flex-col">
                                        <label class="mb-1 text-purple-600 text-sm font-base">Nombre</label>
                                        <input value="'.($user->name).'" id="name'.($user->id).'" class="flex-1 appearance-none border border-transparent w-full py-1 px-2 bg-white text-gray-700 placeholder-gray-400 shadow-md rounded-lg text-base ring-2 ring-gray-400 focus:outline-none focus:ring-purple-600 focus:border-transparent" type="text">
                                    </div>
                                    <div class="w-full mb-4 flex flex-wrap flex-col">
                                        <label class="mb-1 text-purple-600 text-sm font-base">Cumpleaños</label>
                                        <input value="'.date('d-m-Y', strtotime($user->birthday)).'" id="birthday'.($user->id).'" class="flex-1 appearance-none border border-transparent w-full py-1 px-2 bg-white text-gray-700 placeholder-gray-400 shadow-md rounded-lg text-base ring-2 ring-gray-400 focus:outline-none focus:ring-purple-600 focus:border-transparent" type="text" data-date-component="birthday" data-date-color="purple" data-date-text-color="white">
                                    </div>
                                    <div class="w-full mb-4 flex flex-wrap flex-col">
                                        <label class="mb-1 text-purple-600 text-sm font-base">Telefono</label>
                                        <input value="'.($user->phone).'" id="phone'.($user->id).'" class="flex-1 appearance-none border border-transparent w-full py-1 px-2 bg-white text-gray-700 placeholder-gray-400 shadow-md rounded-lg text-base ring-2 ring-gray-400 focus:outline-none focus:ring-purple-600 focus:border-transparent" type="number">
                                    </div>
                                    <div class="w-full mt-8 flex flex-wrap justify-center items-center">
                                        <button data-form-action="delete" type="submit" class="flex flex-wrap justify-center items-center w-full mb-2 md:mb-0 md:w-2/5 mx-1 px-4 py-2 rounded-lg shadow-md hover:shadow-lg bg-red-400 hover:bg-red-600 text-white transition-colors duration-300 ease-in-out">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle h-5 w-5"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                            &nbsp;Eliminar
                                        </button>
                                        <button data-form-action="update" type="submit" class="flex flex-wrap justify-center items-center w-full mb-2 md:mb-0 md:w-2/5 mx-1 px-4 py-2 rounded-lg shadow-md hover:shadow-lg bg-green-400 hover:bg-green-600 text-white transition-colors duration-300 ease-in-out">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save h-5 w-5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                                            &nbsp;Actualizar
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <!-- CARD ENDS -->
                        ';
                    }
                ?>
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
    <!-- CARROUSEL CONTAINER ENDS -->
    <!-- ADD BUTTON -->
    <button id="button_add" class="z-40 fixed flex justify-center items-center bottom-5 right-5 h-12 w-12 bg-green-400 shadow-xl hover:bg-green-600 transition-colors duration-300 ease-in-out rounded-full text-white">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus fill-current"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
    </button>
    <!-- ADD BUTTON ENDS-->
    <form id="modal_add" class="hidden z-50 fixed w-full h-full top-0 left-0 flex items-center justify-center" style="overflow-x: hidden; overflow-y: visible !important;">
        <div class="z-50 modal-overlay cursor-pointer absolute w-full h-full bg-gray-900 opacity-50"></div>
        <div class="z-50 w-4/5 md:w-2/5 p-6 md:py-12 md:px-5 bg-white shadow-lg rounded-lg">
            <div class="w-full mb-2 flex flex-wrap justify-center">
                <h1 class="text-2xl font-light text-purple-800">Registro 1 de 8</h1>
            </div>
            <div class="w-full mb-4 flex flex-wrap flex-col">
                <label class="mb-1 text-purple-600 text-sm font-base">Nombre</label>
                <input id="add_name" class="flex-1 appearance-none border border-transparent w-full py-1 px-2 bg-white text-gray-700 placeholder-gray-400 shadow-md rounded-lg text-base ring-2 ring-gray-400 focus:outline-none focus:ring-purple-600 focus:border-transparent" type="text">
            </div>
            <div class="w-full mb-4 flex flex-wrap flex-col">
                <label class="mb-1 text-purple-600 text-sm font-base">Cumpleaños</label>
                <input id="add_birthday" class="flex-1 appearance-none border border-transparent w-full py-1 px-2 bg-white text-gray-700 placeholder-gray-400 shadow-md rounded-lg text-base ring-2 ring-gray-400 focus:outline-none focus:ring-purple-600 focus:border-transparent" type="text" data-date-component="birthday" data-date-color="purple" data-date-text-color="white">
            </div>
            <div class="w-full mb-4 flex flex-wrap flex-col">
                <label class="mb-1 text-purple-600 text-sm font-base">Telefono</label>
                <input id="add_phone" class="flex-1 appearance-none border border-transparent w-full py-1 px-2 bg-white text-gray-700 placeholder-gray-400 shadow-md rounded-lg text-base ring-2 ring-gray-400 focus:outline-none focus:ring-purple-600 focus:border-transparent" type="number">
            </div>
            <div class="w-full mt-8 flex flex-wrap justify-center items-center">
                <button type="submit" class="flex flex-wrap justify-center items-center w-full mb-2 md:mb-0 md:w-2/5 mx-1 px-4 py-2 rounded-lg shadow-md hover:shadow-lg bg-green-400 hover:bg-green-600 text-white transition-colors duration-300 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save h-5 w-5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    &nbsp;Agregar
                </button>
            </div>
        </div>
    </form>
</body>
</html>
