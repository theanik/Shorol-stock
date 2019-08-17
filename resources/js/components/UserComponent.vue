<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                     <div class="card-header">
                        <h4 class="card-title">Admin <span class="p-1 mb-2 bg-primary text-white">{{total}}</span></h4>
                        <div class="card-tools" style="position: absolute;right: 1rem;top: .5rem,color:#fff;">
                        <button @click="csvExport(csvData)" class="btn btn-primary">Export as CVS</button>
                        <button type="button" class="btn btn-success" @click.prevent="create">
                            <i class="fas fa-user-plus"></i>
                            Add New Admin
                            
                        </button>
                        </div>
                        </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-2">
                                <strong>Search By :</strong>
                                </div>
                                <div class="col-md-3">
                                <select v-model="queryFiled" class="form-control" id="fileds">
                                    <option value="name">Name</option>
                                    <option value="email">Enail</option>
                                </select>
                                </div>
                                <div class="col-md-5">
                                <input v-model="query" type="text" class="form-control" placeholder="Search">
                                </div>
                                <div class="col-md-2">
                                     <button type="button" class="btn btn-primary" @click.prevent="reload">
                                        Reset Search
                                        <i class="fas fa-sync"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- table  -->
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-striped">
                                <thead>
                                    <tr>
                                    <th scope="col">Admin Id</th>
                                    <th scope="col">Admin Name</th>
                                    <th scope="col">Admin E-mail</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="user in users" :key="user.id">
                                        <th scope="row">{{ user.id }}</th>
                                        <td>{{ user.name }}</td>
                                        <td>{{ user.email }}</td>
                                        
                                        <td>
                                            <button type="button" @click.prevent="show(user)" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                           
                                            <button type="button" @click.prevent="destroy(user)" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="!users.length">
                                        <td colspan="6">
                                            <div class="alert alert-danger" role="alert">Sorry :( No data found.</div>
                                        </td>
                                    </tr>
                                    
                                </tbody>
                                </table>
                                <pagination-component v-if="pagination.last_page > 1"
                                    :pagination="pagination"
                                    :offset="5"
                                    @paginate="query === '' ? getData() : searchData()"></pagination-component>
                        </div>

                        <!-- end table -->
                    </div>
                </div>
            </div>

             <!-- Modal -->
                <div class="modal fade" id="ModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">

                            <h5 v-if="showMode == true" class="modal-title" id="exampleModalLongTitle">Details for <strong>{{ showDetails.name }}</strong> </h5>
                            <h5 v-else class="modal-title" id="exampleModalLongTitle">{{ editMode ? "Edit" : "Add New"}}  Customer</h5>
                            
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                         <form v-if="showMode == false" @submit.prevent=" editMode ? update() : store()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                          <alert-error :form="form" message="There were some problems with your input."></alert-error>
                                <div class="form-group">
                                <label>Admin Name</label>
                                <input v-model="form.name" type="text" name="name" placeholder="Enater Name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                                </div>

                                <div class="form-group">
                                <label>Admin Email</label>
                                <input v-model="form.email" type="email" name="email" placeholder="Enater E-mail"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                                </div>
                                <div class="form-group">
                                <label>Password</label>
                                <input v-model="form.password" type="password" name="password" placeholder="Enater Password"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                <has-error :form="form" field="password"></has-error>
                                </div>
                               

                           
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button :disabled="form.busy" type="submit" class="btn btn-primary">Save</button>
                        </div>
                         </form>

                          <div v-if="showMode ==  true" class="modal-body">

                              <div class="card border-info mb-3">
                                <div class="card-body">

                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Name : </strong> {{ showDetails.name }}</li>
                                        <li class="list-group-item"><strong>Email : </strong> {{ showDetails.email }}</li>
                                        <li class="list-group-item"><strong>Created At : </strong> {{ showDetails.created_at | mydate }}</li>
                                    </ul>
                                </div>
                                </div>
                          </div>
                        </div>
                    </div>
                </div>
            <!-- end modal  -->
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                users : [],
                pagination :{
                    current_page : 1,
                },
                showMode : false,
                editMode : false,
                form : new Form({
                    id : '',
                    name : '',
                    email : '',
                    password : ''
                }),
                total : null,
                showDetails : [],
                query : "",
                queryFiled : "name",
                data :{
                    exportdata : []
                }
            }
        },
        watch:{
            query:function(newQ,old) {
                    if(newQ === ''){
                        this.getData();
                    }else{
                        this.searchData();
                    }
                
            }
        },
         computed: {
            csvData() {
            return this.exportdata.map(item => ({
                ...item,
            }));
            }
        },
        mounted() {
            console.log('Component mounted.')
            this.getData()
            axios.get("api/alluser")
                    .then(res=>{
                       this.exportdata = res.data.data
                       //console.log(res.data.data)
                    })
        },
        methods : {
            csvExport(arrData) {
                    let csvContent = "data:text/csv;charset=utf-8,";
                    csvContent += [
                        Object.keys(arrData[0]).join(";"),
                        ...arrData.map(item => Object.values(item).join(";"))
                    ]
                        .join("\n")
                        .replace(/(^\[)|(\]$)/gm, "");

                    const data = encodeURI(csvContent);
                    const link = document.createElement("a");
                    link.setAttribute("href", data);
                    link.setAttribute("download", "user.csv");
                    link.click();
                    
                },
            getData(){
                this.$Progress.start()
                axios.get('/api/user?page='+this.pagination.current_page)
                    .then(res=>{
                        this.users = res.data.data
                        this.pagination = res.data.meta
                        this.total = res.data.meta.total
                        this.$Progress.finish()
                    })
                    .catch(e=>{
                        console.log(e)
                        this.$Progress.fail()
                    })
                },
                 create(){
                     this.form.reset()
                    this.form.clear()
                    this.editMode = false
                    $('#ModalLong').modal('show');
                },
                store(){
                    this.$Progress.start()
                    this.form.busy = true
                    this.form.post('/api/user')
                        .then(res=>{
                            this.getData()
                            $('#ModalLong').modal('hide');
                            if(this.form.successful){
                            this.$Progress.finish();
                            this.$snotify.success('Admin Add Successfully','Success')
                            }else{
                                this.$Progress.fail()
                                this.$snotify.error('Something wend wrong. Try aging','Error')
                            }
                        })
                        .catch(e=>{
                            console.log(e)
                            this.$Progress.fail()
                        })
                },
                destroy(user){

                this.$snotify.clear()
                this.$snotify.confirm("You will not be able to recover this data!","Are you sure?", {
                        showProgressBar: false,
                        closeOnClick: false,
                        pauseOnHover: true,
                        buttons:[
                            {
                                text : "Yes",
                                action : toast => {
                                    this.$snotify.remove(toast.id)
                                    //console.log(customer)
                                    this.$Progress.start()
                                    axios.delete('/api/user/'+user.id)
                                    .then(res=>{
                                        this.getData()
                                            this.$Progress.finish();
                                            this.$snotify.success('Admin Delete Successfully','Success')
                                       
                                    })
                                    .catch(e=>{
                                        this.$Progress.fail()
                                        console.log(e)
                                    })
                                },
                                bold : true
                            },
                            {
                                text : "No",
                                action : toast => {
                                    this.$snotify.remove(toast.id)
                                },
                                bold : true
                            }
                            
                        ]
                    })
                },searchData(){
                    this.$Progress.start()
                    axios.get('/api/search/user/'+this.queryFiled+'/'+this.query+'?page='+this.pagination.current_page)
                        .then(res=>{
                            this.users = res.data.data
                            this.pagination = res.data.meta
                            this.total = res.data.meta.total
                            this.$Progress.finish()
                        })
                        .catch(e=>{
                            console.log(e)
                            this.$Progress.fail()
                        })
                    },
                    reload(){
                    this.getData()
                    this.query = ''
                    this.queryFiled = 'name'
                    
                    this.$snotify.info('Search reset', {
                        timeout: 1000,
                        showProgressBar: false,
                        closeOnClick: true,
                        pauseOnHover: false
                        });
                    },
                    show(user){
                        this.showMode = true
                        this.editMode = false
                        $('#ModalLong').modal('show');
                        //console.log(customer)
                        this.showDetails = user
                    }
        }
    }
</script>
