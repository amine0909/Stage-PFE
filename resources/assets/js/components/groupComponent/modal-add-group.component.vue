<template>
    <transition name="modal">
        <div class="modal modal-mask" tabindex="-1" role="dialog" id="modal_add_group" ref="modal_add">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter une classe</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label>Nom de la classe</label>
                                <!-- <input type="text" class="form-control" /> -->
                                <slot name="nom_classe"></slot>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Groupe</label>
                                <!-- <input type="text" class="form-control"  disabled/> -->
                                <slot name="stream_classe"></slot>
                                <slot name="check_stream"></slot>
                                <!-- <span class="error_group text-danger">
                                    Aucune groupe, il faut saisir une classe correct
                                </span> -->
                            </div>
                        </form>

                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-primary" >Enregistrer</button> -->
                        <slot name="enregistrer_event"></slot>
                        <slot name="fermer_modal"></slot>
                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button> -->
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