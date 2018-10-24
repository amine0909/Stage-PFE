<template>
    <div>
        <div class="col-6">
            <div class="form-group">
               <label>Rechercher par nom de le cin :</label>
               <input type="text" name="searchName" class="form-control" v-model="cin">
               <br>
               <select class="form-control" v-model="session">
                  <option disabled value="">Choisir une année universitaire</option>
                  <option v-for="(item,index) in sessions" :key="index">
                    {{ item }}
                  </option>

               </select>
               <button class="btn btn-primary" id="searchBtn" @click="doSearch">Rechercher</button>
               <button class="btn btn-primary" id="resetSearch">Réinitialiser</button>
            </div>
            <button class="btn btn-primary" id="addStudent" @click="openModalAdd">Ajouter un etudiant</button>
        </div><br>
        <table class="table">
            <thead>
                <th>Nom de l'etudiant</th>
                <th>Numero de cin</th>
                <th>Date de naissance</th>
                <th>Numéro de telephone </th>
                <th>Adresse email</th>
                <th>Session</th>
            </thead>

            <tbody>
                <tr v-for="(item,index) in studentFiltred" :key="index">
                    <td>{{ item[1].firstname}} {{ item.lastname}} </td>
                    <td>{{ item[1].cin}}</td>
                    <td> {{item[1].birthdate }} </td>
                    <td>{{ item[1].phone}}</td>
                    <td>{{ item[1].email}}</td>
                    <td>
                        <!-- <a href="#" @click="openModalUpdate">
                            <icon name="edit" scale="2"></icon>
                        </a> -->

                        {{ item[0]}}
                    </td>
                </tr>
            </tbody>
        </table>

        <modalUpdate v-if="modalUpdateShow===true">

        </modalUpdate>

        <modalAdd v-if="modalAddShow==true">
            <div class="alert alert-danger" slot="danger-alert" v-if="errors.length!=0">
               <p v-for="(err,index) in errors" :key="index"> {{ err }} </p>
            </div>

            <div class="alert alert-success" slot="success-alert" v-if="success==true">
                <p>L'etudiant a été bien ajouté..!!!</p>
            </div>
            <div slot="firstname">
                <input type="text" class="form-control" v-model="student.firstname" />
            </div>

            <div slot="lastname">
                <input type="text" class="form-control" v-model="student.lastname"/>
            </div>

            <div slot="email">
                <input type="email" class="form-control" v-model="student.email"/>
            </div>

            <div slot="cin">
                <input type="text" class="form-control" v-model="student.cin"/>
            </div>

            <div slot="birthdate">
                <input type="date" class="form-control" v-model="student.birthdate"/>
            </div>

            <div slot="phone">
                <input type="text" class="form-control" v-model="student.phone"/>
            </div>

            <div slot="classe">
                <select class="form-control" v-model="student.classe_id">
                    <option v-for="(classe,index) in classes" :key="index" :value=classe.id > {{ classe.name }} </option>
                </select>
            </div>

            <button class="btn btn-success" slot="saveChanges" @click="saveChanges">Enregistrer</button>
            <button class="btn btn-secondary" slot="closeModal" @click="closeModalAdd">Fermer</button>

        </modalAdd>

    </div>




</template>



<script>
    import 'vue-awesome/icons'
    import Icon from 'vue-awesome/components/Icon'
    import modalUpdate from './modal-update.component.vue'
    import modalAdd from './modal-add.component.vue'
    export default{
        components: {
            Icon,
            modalUpdate,
            modalAdd

        },
        created(){
            this.getAllStudents()

        },
        data() {
            return {
                listStudents: [],
                studentFiltred: [],
                cin: '',
                modalUpdateShow: false,
                modalAddShow: false,
                student: {
                    firstname: '',
                    lastname: '',
                    email: '',
                    cin: '',
                    birthdate: '',
                    phone: '',
                    classe_id: ''
                },
                errors: [],
                success: false,
                classes: [],
                sessions: [],
                session: ''
            }
        },
        methods: {
            getAllStudents(){
                axios.get("http://localhost/stage-pfe/public/api/getAllStudents")
                    .then(res => {
                        let i=0;
                        while(i<res.data.data.length){

                            // push to students table
                            this.listStudents.push([res.data.data[i].session,res.data.data[i].student_record])

                            //push session to the sessions table
                            this.sessions.push(res.data.data[i].session)
                            i++
                        }
                        //this.listStudents = res.data.data['student_record']
                        this.studentFiltred = this.listStudents
                    })
                    .catch(err => console.log(err))
            },
            doSearch(){
                let i=0;

                if(this.session == '' && this.cin == ''){
                    this.studentFiltred = this.listStudents
                }

                if(this.session != ''){
                    this.studentFiltred = (this.listStudents.filter(st => st[0] == this.session))
                }

                if(this.cin != ''){
                    let i=0
                    let temp =  []
                    while(i<this.studentFiltred.length){
                        let str = this.studentFiltred[i][1].cin.toString()
                        if(str.startsWith(this.cin)){
                            temp.push(this.studentFiltred[i])
                        }

                        i++
                    }
                    this.studentFiltred = temp
                }else if(this.cin != '' && this.session != ''){
                    this.studentFiltred = (this.listStudents.filter(st => st[0] == this.session))
                }else if(this.cin != '' && this.session == ''){
                    this.studentFiltred = this.listStudents
                }

            },

            openModalUpdate() {
                this.modalUpdateShow = true
            },
            openModalAdd(){
              this.modalAddShow = true

              //get All classes
              axios.get("http://localhost/stage-pfe/public/api/getAllGroups")
                .then(res => {

                    let i=0
                    for(i;i<res.data.data.length;i++){
                        this.classes.push(res.data.data[i])
                    }

                })
                .catch(err => {
                    console.log(err)
                })
            },

            closeModalUpdate(){
                this.modalUpdateShow = false
            },
            closeModalAdd(){
                this.modalAddShow = false
            },

            saveChanges(){
                this.errors = []
                axios.post('http://localhost/stage-pfe/public/api/addStudent', this.student)
                    .then(res => {
                        this.errors = []
                        this.success = true

                       //close modal after 2 sec
                       setTimeout(this.closeModalAdd, 2000)
                    })
                    .catch(err => {
                        if(err.response.status == 422){
                            if(err.response.data.errors.firstname!= null && err.response.data.errors.firstname.length != 0){
                                this.fetchErros(err.response.data.errors.firstname)
                            }

                            if(err.response.data.errors.lastname!= null && err.response.data.errors.lastname.length != 0){
                                this.fetchErros(err.response.data.errors.lastname)
                            }

                            if(err.response.data.errors.cin!= null && err.response.data.errors.cin.length != 0){
                                this.fetchErros(err.response.data.errors.cin)
                            }

                            if(err.response.data.errors.dob!= null && err.response.data.errors.dob.length != 0){
                                this.fetchErros(err.response.data.errors.dob)
                            }

                            if(err.response.data.errors.phone!= null && err.response.data.errors.phone.length != 0){
                                this.fetchErros(err.response.data.errors.phone)
                            }

                            if(err.response.data.errors.email!= null && err.response.data.errors.email.length != 0){
                                this.fetchErros(err.response.data.errors.email)
                            }

                            if(err.response.data.errors.classe!= null && err.response.data.errors.classe.length != 0){
                                this.fetchErros(err.response.data.errors.classe)
                            }


                        }
                    })
            },
            fetchErros(errors){
                let i=0;
                for(i;i<errors.length;i++){
                    this.errors.push(errors[i])
                }
            }

        },


    }
</script>


<style>
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

    #searchBtn, #resetSearch  {
        margin-top: 10px;
        border-radius: 0%;
        background-color: #45C48B;
        border: none
    }

    #resetSearch{
        background-color: #F01A30
    }
</style>
