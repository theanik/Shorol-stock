<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                     <div class="card-header">
                        <h4 class="card-title">Customers<span class="p-1 mb-2 bg-primary text-white">{{total}}</span></h4>
                        <div class="card-tools" style="position: absolute;right: 1rem;top: .5rem,color:#fff;">
                        <button @click="csvExport(csvData)" class="btn btn-primary">Export as CVS</button>
                        <button type="button" class="btn btn-success" @click.prevent="create">
                            Add New Customer
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
                                    <option value="c_name">Name</option>
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
                            <table id="example" class="table table-hover table-bordered table-striped">
                                <thead>
                                    <tr>
                                    <th scope="col">Sl</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Company</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Address</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-show="customers.length" v-for="(customer) in customers" :key="customer.id">
                                        <th scope="row">{{ customer.id}}</th>
                                        <td>{{ customer.c_name }}</td>
                                        <td>{{ customer.company_name.length>16 ? customer.company_name.substring(0,16)+" ..." : customer.company_name }}</td>
                                        <td>{{ customer.email.length >16 ? customer.email.substring(0,16)+" ..." : customer.email }}</td>
                                        <td>{{ customer.phone }}</td>
                                        <td>{{ customer.address.length > 16 ? customer.address.substring(0,16)+" ..." : customer.address }}</td>
                                        <td>
                                            <button type="button" @click.prevent="show(customer)" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" @click.prevent="edit(customer)" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" @click.prevent="destroy(customer)" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-show="!customers.length">
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
                <div class="modal fade" id="customerModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
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
                                <label>Customer Name</label>
                                <input v-model="form.c_name" type="text" name="c_name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('c_name') }">
                                <has-error :form="form" field="c_name"></has-error>
                                </div>

                                <div class="form-group">
                                <label>Company Name</label>
                                <input v-model="form.company_name" type="text" name="company_name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('company_name') }">
                                <has-error :form="form" field="company_name"></has-error>
                                </div>

                                <div class="form-group">
                                <label>Customer Email</label>
                                <input v-model="form.email" type="email" name="email"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                                </div>
                                <div class="form-group">
                                <label>Customer Phone</label>
                                <input v-model="form.phone" type="tel" name="phone"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('phone') }">
                                <has-error :form="form" field="phone"></has-error>
                                </div>
                                <div class="form-group">
                                <label>Customer Address</label>
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
                                        <li class="list-group-item"><strong>Name : </strong> {{ showDetails.c_name }}</li>
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
                queryFiled : "c_name",
                customers:[],
                form : new Form({
                    id : '',
                    c_name : '',
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
            axios.get("api/allcustomer")
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
                    link.setAttribute("download", "customer.csv");
                    link.click();
                    
                },
            getData(){
                this.$Progress.start()
                axios.get('/api/customer?page='+this.pagination.current_page)
                    .then(res=>{
                        //console.log(res.data.meta)
                        this.customers = res.data.data
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
                axios.get('/api/search/customer/'+this.queryFiled+'/'+this.query+'?page='+this.pagination.current_page)
                    .then(res=>{
                        this.customers = res.data.data
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
                $('#customerModalLong').modal('show');
                
            },
            store(){
                this.$Progress.start()
                this.form.busy = true
                this.form.post('/api/customer')
                    .then(res=>{
                        this.getData()
                        $('#customerModalLong').modal('hide');
                        if(this.form.successful){
                            this.$Progress.finish();
                            this.$snotify.success('Cusomer Add Successfully','Success')
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
            edit(customer){
                this.showMode = false
                this.editMode = true
                this.form.reset()
                this.form.clear()
                this.form.fill(customer)
                $('#customerModalLong').modal('show');
            },
            update(){
                this.$Progress.start()
                this.form.busy = true
                this.form.put('/api/customer/'+this.form.id)
                    .then(res=>{
                        this.getData()
                        $('#customerModalLong').modal('hide');
                        if(this.form.successful){
                            this.$Progress.finish();
                            this.$snotify.success('Customer Update Successfully','Success')
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
            destroy(customer){

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
                                    axios.delete('/api/customer/'+customer.id)
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
            show(customer){
                this.showMode = true
                this.editMode = false
                $('#customerModalLong').modal('show');
                //console.log(customer)
                this.showDetails = customer
            }
        },
        
    }

</script>
