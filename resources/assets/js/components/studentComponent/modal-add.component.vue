<template>
    <transition name="modal-add">
        <div class="modal modal-mask">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title">Modifier les informations de l'etudiant</h4>
                        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                    </div>

                    <div class="modal-body">
                        <slot name="danger-alert"></slot>
                        <slot name="success-alert"></slot>
                        <form method="post">
                            <div class="form-group">
                                <label>Nom de l'etudiant</label>
                                <slot name="firstname"></slot>
                            </div>

                            <div class="form-group">
                                <label>Prenom de l'etudiant</label>
                                <slot name="lastname"></slot>
                            </div>

                            <div class="form-group">
                                <label>Adresse email de l'etudiant</label>
                                <slot name="email"></slot>
                            </div>

                            <div class="form-group">
                                <label>CIN de l'etudiant</label>
                                <slot name="cin"></slot>
                            </div>

                            <div class="form-group">
                                <label>Date de naissance de l'etudiant</label>
                                <slot name="birthdate"></slot>
                            </div>

                            <div class="form-group">
                                <label>Téléphone de l'etudiant</label>
                                <slot name="phone"></slot>
                            </div>

                            <div class="form-group">
                                <label>Classe</label>
                                <slot name="classe"></slot>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <slot name="saveChanges"></slot>
                        <slot name="closeModal"></slot>
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
    .modal-dialog{
        max-height: 710px;
        overflow-y: auto;
    }
</style>