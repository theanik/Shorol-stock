<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                     <div class="card-header">
                        <h4 class="card-title">Product <span class="p-1 mb-2 bg-primary text-white">{{totalProduct}}</span></h4> 
                        <div class="card-tools" style="position: absolute;right: 1rem;top: .5rem,color:#fff;">
                        <button @click="csvExport(csvData)" class="btn btn-primary">Export as CVS</button>
                        <button type="button" class="btn btn-success" @click.prevent="create">
                            Add New Product
                            <i class="fas fa-plus"></i>
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
                                    <option value="cat_name">Catgory</option>
                                    <option value="code">Code</option>
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
                                    <th scope="col">Product Id</th>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Product Category</th>
                                    <th scope="col">Product Code</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="product in products" :key="product.id">
                                        <th scope="row">{{ product.id }}</th>
                                        <td>{{ product.name}}</td>
                                        <td>{{ product.cat_name }}</td>
                                        <td>{{ product.code }}</td>
                                        <td>
                                            <button type="button" @click.prevent="show(product)" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" @click.prevent="edit(product)" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" @click.prevent="destroy(product)" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-show="!products.length">
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

                <!-- Modal -->
                <div class="modal fade" id="ModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">

                            <h5 v-if="showMode == true" class="modal-title text-info" id="exampleModalLongTitle">Details for <strong>{{ showDetails.name }}</strong> </h5>
                            <h5 v-else class="modal-title text-success" id="exampleModalLongTitle">{{ editMode ? "Edit" : "Add New"}}  Product</h5>
                            
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                         <form v-if="showMode == false" @submit.prevent=" editMode ? update() : store()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                          <alert-error :form="form" message="There were some problems with your input."></alert-error>
                                <div class="form-group">
                                <label>Product Name</label>
                                <input v-model="form.name" type="text" name="name" placeholder="Enter Product Name" 
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                                </div>

                                <div class="form-group">
                                <label>Product Category</label>
                                <select v-model="form.cat_name" name="cat_name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('cat_name') }" placeholder="Select Category">
                                    <option v-for="category in categories" :key="category.id" v-bind:value="category.name">{{ category.name}}</option>
                                </select>
                                <has-error :form="form" field="cat_name"></has-error>
                                </div>
                                <div class="form-group">
                                <label>Product Description</label>
                                    <textarea v-model="form.description" name="description" rows="3" class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('description') } " placeholder="Enter Product Description" ></textarea>
                                <has-error :form="form" field="description"></has-error>
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
                                        <li class="list-group-item"><strong>Prpduct Name : </strong> {{ showDetails.name }}</li>
                                        <li class="list-group-item"> <strong>Category  Name : </strong> {{ showDetails.cat_name }}</li>
                                        <li class="list-group-item"><strong>Product Code : </strong> {{ showDetails.code }}</li>
                                        <li class="list-group-item"><strong>Product Description : </strong> {{ showDetails.description }}</li>
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
    </div>
</template>

<script>
    export default {
        data(){
            return{
                
                editMode : false,
                showMode : false,
                categories : [],
                products : [],
                query : "",
                queryFiled : "name",
                form : new Form({
                    id : '',
                    name : '',
                    cat_name : '',
                    code : '',
                    description : '',
                    created_at : '',
                }),
                pagination:{
                    current_page : 1,
                },
                showDetails : [],
                totalProduct : null,
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
                //id: item.id, //item.address.city,
                //name: 'компания'//item.company.name
            }));
            }
        },
        mounted() {
            console.log('Component mounted.')
            this.getCat()
            this.getData()
            axios.get("api/allproduct")
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
                    link.setAttribute("download", "product.csv");
                    link.click();
                    
                },
            getData(){
                this.$Progress.start()
                axios.get('/api/product?page='+this.pagination.current_page)
                    .then(res=>{
                        //console.log(res.data.meta)
                        this.products = res.data.data
                        this.pagination = res.data.meta
                        this.totalProduct = res.data.meta.total
                        this.$Progress.finish()


                    })
                    .catch(e=>{
                        console.log(e)
                        this.$Progress.fail()
                    })
            },
            searchData(){
                this.$Progress.start()
                axios.get('/api/search/product/'+this.queryFiled+'/'+this.query+'?page='+this.pagination.current_page)
                    .then(res=>{
                        //console.log(res.data.data)
                        this.products = res.data.data
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
            getCat(){
                axios.get('/api/category')
                    .then(res=>{
                        this.categories = res.data.data
                    })
                    .catch(e=>{
                        console.log(e)
                    })
            },
             create(){
                this.editMode = false
                this.showMode = false
                this.form.reset()
                this.form.clear()
                $('#ModalLong').modal('show');
            },
            store(){
                this.$Progress.start()
                this.form.busy = true
                this.form.post('/api/product')
                    .then(res=>{
                        this.getData()
                        $('#ModalLong').modal('hide');
                        if(this.form.successful){
                            this.$Progress.finish();
                            this.$snotify.success('Product Add Successfully','Success')
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
            edit(product){
                this.showMode = false
                this.editMode = true
                this.form.reset()
                this.form.clear()
                this.form.fill(product)
                $('#ModalLong').modal('show');
            },
            update(){
                this.$Progress.start()
                this.form.busy = true
                this.form.put('/api/product/'+this.form.id)
                    .then(res=>{
                        this.getData()
                        $('#ModalLong').modal('hide');
                        if(this.form.successful){
                            this.$Progress.finish();
                            this.$snotify.success('Cusomer Update Successfully','Success')
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
            destroy(product){

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
                                    //console.log(product)
                                    this.$Progress.start()
                                    axios.delete('/api/product/'+product.id)
                                    .then(res=>{
                                        this.getData()
                                            this.$Progress.finish();
                                            this.$snotify.success('Product Delete Successfully','Success')
                                       
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
            show(product){
                this.showMode = true
                this.editMode = false
                $('#ModalLong').modal('show');
                //console.log(product)
                this.showDetails = product
            },
            
            
        }
    }
</script>
