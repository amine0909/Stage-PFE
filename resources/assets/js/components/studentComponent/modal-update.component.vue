<template>
    <transition name="modal">
        <div class="modal modal-mask" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Modifier les informations de l'etudiant </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                   <div class="form-group">
                        <label>Nom</label>
                        <slot name="firstname"></slot>
                   </div>
                   <div class="form-group">
                        <label>Prenom</label>
                        <slot name="lastname"></slot>
                   </div>
                   <div class="form-group">
                        <label>Adresse email</label>
                        <slot name="email"></slot>
                   </div>
                   <div class="form-group">
                        <label>Nom</label>
                        <slot name="name"></slot>
                   </div>
                   <div class="form-group">
                        <label>Nom</label>
                        <slot name="name"></slot>
                   </div>
                   <div class="form-group">
                        <label>Nom</label>
                        <slot name="name"></slot>
                   </div>
                   

                </div>

                <div class="modal-footer">
                    <slot name="saveChange"></slot>
                    <slot name="closeModal"></slot>
                    <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> -->
                </div>

                </div>
            </div>
        </div>
    </transition>
</template>

<style>

    .modal-mask {
 position: fixed;
 z-index: 9998;
 top: 0;
 left: 0;
 width: 100%;
 height: 100%;
 background-color: rgba(0, 0, 0, .5);
 display: table;
 transition: opacity .3s ease;
}
</style>