<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                     <div class="card-header">
                        <h4 class="card-title">Supplier <span class="p-1 mb-2 bg-primary text-white">{{total}}</span></h4>
                        <div class="card-tools" style="position: absolute;right: 1rem;top: .5rem,color:#fff;">
                        <button @click="csvExport(csvData)" class="btn btn-primary">Export as CVS</button>
                        <button type="button" class="btn btn-success" @click.prevent="create">
                            Add New
                            <i class="fas fa-plus"></i>
                        </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- search box -->
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-2">
                                <strong>Search By :</strong>
                                </div>
                                <div class="col-md-3">
                                <select v-model="queryFiled" class="form-control" id="fileds">
                                    <option value="name">Name</option>
                                    <option value="company_name">Company</option>
                                    <option value="email">Enail</option>
                                    <option value="phone">Phone</option>
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
                                    <th scope="col">Sl</th>
                                    <th scope="col">Supplier Name</th>
                                    <th scope="col">Company</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Address</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-show="suppliers.length" v-for="(supplier) in suppliers" :key="supplier.id">
                                        <th scope="row">{{ supplier.id}}</th>
                                        <td>{{ supplier.name.length>16 ? supplier.name.substring(0,16)+"..." : supplier.name}}</td>
                                        <td>{{ supplier.company_name.length>16 ? supplier.company_name.substring(0,16)+" ..." : supplier.company_name }}</td>
                                        <td>{{ supplier.email.length >16 ? supplier.email.substring(0,16)+" ..." : supplier.email }}</td>
                                        <td>{{ supplier.phone }}</td>
                                        <td>{{ supplier.address.length > 16 ? supplier.address.substring(0,16)+" ..." : supplier.address }}</td>
                                        <td>
                                            <button type="button" @click.prevent="show(supplier)" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" @click.prevent="edit(supplier)" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" @click.prevent="destroy(supplier)" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-show="!suppliers.length">
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
                    </div>
                </div>
            </div>

            <!-- Modal -->
                <div class="modal fade" id="supplierModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">

                            <h5 v-if="showMode == true" class="modal-title" id="exampleModalLongTitle">Details for <strong>{{ showDetails.name }}</strong> </h5>
                            <h5 v-else class="modal-title" id="exampleModalLongTitle">{{ editMode ? "Edit" : "Add New"}}  supplier</h5>
                            
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                         <form v-if="showMode == false" @submit.prevent=" editMode ? update() : store()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                          <alert-error :form="form" message="There were some problems with your input."></alert-error>
                                <div class="form-group">
                                <label>supplier Name</label>
                                <input v-model="form.name" type="text" name="name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                                </div>

                                <div class="form-group">
                                <label>Company Name</label>
                                <input v-model="form.company_name" type="text" name="company_name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('company_name') }">
                                <has-error :form="form" field="company_name"></has-error>
                                </div>

                                <div class="form-group">
                                <label>supplier Email</label>
                                <input v-model="form.email" type="email" name="email"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                                </div>
                                <div class="form-group">
                                <label>supplier Phone</label>
                                <input v-model="form.phone" type="tel" name="phone"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('phone') }">
                                <has-error :form="form" field="phone"></has-error>
                                </div>
                                <div class="form-group">
                                <label>supplier Address</label>
                                    <textarea v-model="form.address" name="address" rows="3" class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('address') }"></textarea>
                                <has-error :form="form" field="address"></has-error>
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
                                        <li class="list-group-item"> <strong>Company Name : </strong> {{ showDetails.company_name }}</li>
                                        <li class="list-group-item"><strong>Email : </strong> {{ showDetails.email }}</li>
                                        <li class="list-group-item"><strong>Phone : </strong> {{ showDetails.phone }}</li>
                                        <li class="list-group-item"><strong>Address : </strong> {{ showDetails.address }}</li>
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
                editMode : false,
                showMode : false,
                showDetails : [],
                query : "",
                queryFiled : "name",
                suppliers:[],
                form : new Form({
                    id : '',
                    name : '',
                    company_name : '',
                    email : '',
                    phone : '',
                    address : ''
                }),
                pagination:{
                    current_page : 1,
                },
                total : null,
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
            axios.get("api/allsupplier")
                    .then(res=>{
                       this.exportdata = res.data.data
                       //console.log(res.data.data)
                    })
        },
        methods:{
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
                    link.setAttribute("download", "supplier.csv");
                    link.click();
                    
                },
            getData(){
                this.$Progress.start()
                axios.get('/api/supplier?page='+this.pagination.current_page)
                    .then(res=>{
                        //console.log(res.data.meta)
                        this.suppliers = res.data.data
                        this.pagination = res.data.meta
                        this.total = res.data.meta.total
                        this.$Progress.finish()


                    })
                    .catch(e=>{
                        console.log(e)
                        this.$Progress.fail()
                    })
            },
            searchData(){
                this.$Progress.start()
                axios.get('/api/search/supplier/'+this.queryFiled+'/'+this.query+'?page='+this.pagination.current_page)
                    .then(res=>{
                        this.suppliers = res.data.data
                        this.pagination = res.data.meta
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
            create(){
                this.editMode = false
                this.showMode = false
                this.form.reset()
                this.form.clear()
                $('#supplierModalLong').modal('show');
                
            },
            store(){
                this.$Progress.start()
                this.form.busy = true
                this.form.post('/api/supplier')
                    .then(res=>{
                        this.getData()
                        $('#supplierModalLong').modal('hide');
                        if(this.form.successful){
                            this.$Progress.finish();
                            this.$snotify.success('Supplier Add Successfully','Success')
                        }else{
                            this.$Progress.fail()
                            this.$snotify.error('Something wend wrong. Try aging','Error')
                        }
                    })
                    .catch(e=>{
                        this.$Progress.fail()
                        console.log(e)
                    })
            },
            edit(supplier){
                this.showMode = false
                this.editMode = true
                this.form.reset()
                this.form.clear()
                this.form.fill(supplier)
                $('#supplierModalLong').modal('show');
            },
            update(){
                this.$Progress.start()
                this.form.busy = true
                this.form.put('/api/supplier/'+this.form.id)
                    .then(res=>{
                        this.getData()
                        $('#supplierModalLong').modal('hide');
                        if(this.form.successful){
                            this.$Progress.finish();
                            this.$snotify.success('Supplier Update Successfully','Success')
                        }else{
                            this.$Progress.fail()
                            this.$snotify.error('Something wend wrong. Try aging','Error')
                        }
                    })
                    .catch(e=>{
                        this.$Progress.fail()
                        console.log(e)
                    })
            },
            destroy(supplier){

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
                                    //console.log(supplier)
                                    this.$Progress.start()
                                    axios.delete('/api/supplier/'+supplier.id)
                                    .then(res=>{
                                        this.getData()
                                            this.$Progress.finish();
                                            this.$snotify.success('Cusomer Delete Successfully','Success')
                                       
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
            },
            show(supplier){
                this.showMode = true
                this.editMode = false
                $('#supplierModalLong').modal('show');
                //console.log(supplier)
                this.showDetails = supplier
            }
        },
        
    }

</script>
