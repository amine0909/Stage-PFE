<template>
    <div>
        <div class="col-6">
            <div class="form-group">
               <label>Rechercher par nom de la  classe :</label>
               <input type="text" name="searchName" class="form-control" v-model="searchClasse">
               <button class="btn btn-primary" id="searchBtn" v-on:click="doSearch">Rechercher</button>
               <button class="btn btn-primary" id="resetSearch" v-on:click="resetSearch">Réinitialiser</button>
            </div>
        </div>
        
        <div class="col-12">
            <button class="btn btn-primary" id="addGroup" v-on:click="showModal()">Ajouter une classe</button>
        </div><br>

        <table class="table">
            <thead>
                <th>Nom de la classe</th>
                <th>Date de creation </th>
                <th>Liste des etudiants</th>
                <th>Action</th>
            </thead>
            <tbody>
                <tr v-for="(g,index) in groupsFiltred" id="g.id" :key="index">
                    <td>{{ g.name}}</td>
                    <td>{{ g.created_at}}</td>
                    <td><a href="#" data-toggle="modal" data-target="#myModal" v-on:click="fetchStudentForGroup(g.id)">Liste des etudiants</a></td>
                    <td>
                        <a href="#" @click="showUpdateModal(g.id, g.name, g.stream, g.created_at)">
                            <icon name="edit" scale="2"></icon>
                        </a>                         
                        <!-- <button @click="showUpdateModal(g.id, g.name, g.stream, g.created_at)">show</button> -->
                    </td>
                </tr>
            </tbody>

        </table>

        <!-- modal liste etudiant-->
        <div class="modal fade" tabindex="-1" role="dialog" id="myModal" v-if="listStudentsForGroup">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Modal title</h4>
                    </div>
                    <div class="modal-body">
                        <div class="" v-if="listStudentsForGroup.length==0">
                            <h5>Aucun etudiant dans cette classe</h5>
                        </div>
                        <div v-else>
                            <table class="table">
                                <thead>
                                <th>Nom de l'etudiant</th>
                                <th>Classe</th>
                                <th>Date d'inscription</th>
                                </thead>
                                <tbody>
                                <tr v-for="(item,index) in listStudentsForGroup" :key="index">
                                    <td>{{ item.student_record.firstname}} {{ item.student_record.lastname}}</td>
                                    <td>{{ item.group_record.name}}</td>
                                    <td>{{ item.created_at }}</td>
                                </tr>
                                </tbody>

                            </table>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal update group-->
        <modalUpdateGroup v-if="isShownModalUpdate==true">
            <div slot="nom_classe">
                <input type="text" class="form-control" v-model="name" v-on:change="checkStream"/>
            </div>

            <div slot="stream_classe">
               <input type="text" class="form-control" v-model="stream" disabled/>
            </div>

            <div slot="check_stream">
                <span class="error_group text-danger" v-if="stream == null">
                    Aucune groupe, il faut saisir une classe correct
                </span>
            </div>


             <button type="button" slot="enregistrer_event"  class="btn btn-primary" v-on:click="save_Changes">Enregistrer</button>
             <button type="button" slot="fermer_modal"  class="btn btn-secondary" v-on:click="closeModal">Fermer</button>  
        </modalUpdateGroup>







        <!-- MODAL ADD GROUP -->
        <modalAddGroup v-if="isShownModalAdd==true">
            <div slot="nom_classe">
                <input type="text" class="form-control" v-model="name" v-on:change="checkStream"/>
            </div>

            <div slot="stream_classe">
               <input type="text" class="form-control" v-model="stream" disabled/>
            </div>

            <div slot="check_stream">
                <span class="error_group text-danger" v-if="stream == null">
                    Aucune groupe, il faut saisir une classe correct
                </span>
            </div>


             <button type="button" slot="enregistrer_event"  class="btn btn-primary" v-on:click="addGroup">Enregistrer</button>
             <button type="button" slot="fermer_modal"  class="btn btn-secondary" v-on:click="closeModal">Fermer</button>  
        </modalAddGroup>  
        
    </div>
</template>







<script>
    import 'vue-awesome/icons'
    import Icon from 'vue-awesome/components/Icon'
    import modalAddGroup from './modal-add-group.component.vue';
    import modalUpdateGroup from './modal-update-group.component.vue';
    export default {
        components: {
            Icon,
            modalAddGroup,
            modalUpdateGroup
        },
        data(){
            return {
                group: {
                    id: '',
                    name: '',
                    created_at: '',
                    stream: ''
                },
                groups :[],
                groupsFiltred: [],
                listStudentsForGroup: [],
                classPossible: [
                    'TI',
                    'DSI',
                    'RSI',
                    'SEM',
                    'EX',
                ],
                current: '',
                id :'',
                name: '' ,
                stream : '',
                created_at : '',
                searchClasse: '',
                modalShown: true,
                isShownModalAdd: false,
                isShownModalUpdate: false
            }
        },
        methods: {
            getAllGroups() {
                axios.get("http://localhost/stage-pfe/public/api/getAllGroups")
                    .then(response => {
                       // this.groups = (response.data.data);
                       this.groupsFiltred = (response.data.data)
                       this.groups = this.groupsFiltred

                    })
                    .catch(err=>console.log(err))

            },
            fetchStudentForGroup(idGroup){
                axios.get("http://localhost/stage-pfe/public/api/fetchStudentForGroup", {params: {id_group: idGroup}})
                    .then(response => {
                        this.listStudentsForGroup = (response.data.data)
                    })
            },
            fetchGroupInformation(id,name,stream,created_at){

                this.id = id;
                this.name = name;
                this.stream = stream;
                this.created_at = created_at;
                
            },
            checkStream(){

                // in case when user type 'EX'
                if(this.name.length>2){
                    this.current = this.name.substring(0, this.name.length-2).toUpperCase();
                    this.stream = this.classPossible.filter(this.check)[0]
                }else{
                    this.current = this.group.name.substring(0, this.name.length).toUpperCase();
                    this.stream = this.classPossible.filter(this.check)[0]
                }

            },
            check(el){
               return el === this.current;
            },
            save_Changes(){
                if(this.stream != null){
                    // POST now
                    axios.put("http://localhost/stage-pfe/public/api/saveUpdateGroup", {
                        name: this.name,
                        stream: this.stream,
                        id_group: this.id
                    })
                        .then(response => {

                            if(response.data.data === "true"){
                                let c = this.groupsFiltred;
                                for(let i=0;i<this.groupsFiltred.length;i++){
                                    if(this.groupsFiltred[i].id === this.id){
                                        this.$set(this.groupsFiltred, i,{
                                            id: this.id,
                                            name: this.name,
                                            created_at: this.created_at,
                                            stream: this.stream
                                        });
                                       
                                        break;
                                    }
                                }
                                this.closeModal()
                            }
                        })
                        .catch(error => console.log(error))
                }
            },
            doSearch(){
                let i=0;
                this.groupsFiltred = [];
                while(i<this.groups.length){
                    if(this.groups[i].name.toLowerCase().includes(this.searchClasse.toLowerCase())){
                        this.groupsFiltred.push(this.groups[i])
                    }else if(this.searchClasse.toLowerCase() == ''){
                        this.resetSearch()
                    }
                    i++
                }
            },
            resetSearch() {
                let i=0;
                this.groupsFiltred = [];
                while(i<this.groups.length){
                   this.groupsFiltred.push(this.groups[i])
                    i++
                }
                this.searchClasse = ''
            },
            addGroup(){
                if(this.stream!=''){
                    axios.post("http://localhost/stage-pfe/public/api/add_group", 
                    {
                        name: this.name,
                        stream: this.stream
                    })
                    .then(response =>{
                        console.log(response.data)
                        this.groupsFiltred.splice(0,0,response.data.data)
                        this.closeModal()
                        //location.reload();
                    })
                    .catch(error=> console.log(error))
                }
                
            },
            showModal(){
                this.isShownModalAdd = true;
            },
            showUpdateModal(id,name,stream,created_at){
                this.fetchGroupInformation(id,name,stream,created_at)
                this.isShownModalUpdate = true;
            },
            closeModal(){
                this.isShownModalAdd = false;
                this.isShownModalUpdate = false
            }
        },
        created(){
            this.getAllGroups();
        }
    }
</script>


<style scoped>
    table {
        border: 2px solid #42b983;
        border-radius: 3px;
        background-color: #fff;
        font-size: 1.1em;
    }

    th {
        background-color: #42b983;
        color: rgba(255,255,255,0.66);
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    td {
        background-color: #f9f9f9;
    }

    th, td {
        min-width: 120px;
        padding: 10px 20px;
    }

    #trash{
        margin-left: 1em;
    }

    .modal-dialog{
        width: 800px;
    }

    #searchBtn, #resetSearch, #addGroup    {
        margin-top: 10px;
        border-radius: 0%;
        background-color: #45C48B;
        border: none
    }

    #resetSearch{
        background-color: #F01A30
    }


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

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container {
  width: 300px;
  margin: 0px auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
  transition: all .3s ease;
  font-family: Helvetica, Arial, sans-serif;
}

.modal-header h3 {
  margin-top: 0;
  color: #42b983;
}

.modal-body {
  margin: 20px 0;
}

.modal-default-button {
  float: right;
}

</style>